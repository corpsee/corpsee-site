<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\PullRequest;
use App\Repository\PullRequestRepository;
use Github\Client;
use Github\ResultPager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttplugClient;

#[AsCommand(
    name: 'app:update-pull-requests',
    description: 'Import pull requests from Github by API',
)]
class UpdatePullRequestsCommand extends Command
{
    private readonly Client $githubClient;

    public function __construct(
        private readonly PullRequestRepository $pullRequestRepository,
    ) {
        $this->githubClient = Client::createWithHttpClient(new HttplugClient());

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Start: ' . \date('Y-m-d H:i:s'));

        $githubUser = 'corpsee';

        $repositories = $this->githubClient->api('user');
        $paginator = new ResultPager($this->githubClient);
        $events = $paginator->fetchAll($repositories, 'publicEvents', [$githubUser]);
        $io->note("\tpublicEvents: " . \sizeof($events));

        $pullRequests = [];
        foreach ($events as $event) {
            if ('PullRequestEvent' === $event['type']) {
                $pullRequests[] = $event;
            }
        }

        $io->note("\tPullRequestEvent: " . \sizeof($pullRequests) . "\n");

        $inserted = 0;
        $updated  = 0;
        foreach ($pullRequests as $pullRequest) {
            $repo = \explode('/', $pullRequest['repo']['name']);
            /** @var \Github\Api\PullRequest $pullRequestData */
            $pullRequestData = $this->githubClient->api('pull_request');
            $data = $pullRequestData->show($repo[0], $repo[1], $pullRequest['payload']['number']);

            if ($githubUser !== $data['user']['login']) {
                continue;
            }

            $pullRequestEntity = $this->pullRequestRepository->findOneBy([
                'repository' => $pullRequest['repo']['name'],
                'externalId' => $pullRequest['payload']['number'],
            ]);

            if (null === $pullRequestEntity) {
                $pullRequestEntity = new PullRequest();
                $pullRequestEntity
                    ->setPlatform(PullRequest::PLATFORM_GITHUB)
                    ->setRepository($pullRequest['repo']['name'])
                    ->setExternalId((string)$pullRequest['payload']['number'])
                    ->setTitle($data['title'])
                    ->setBody($data['body'])
                    ->setStatus((true === (boolean)$data['merged']) ? 'merged' : $data['state'])
                    ->setCommits((int)$data['commits'])
                    ->setAdditions((int)$data['additions'])
                    ->setDeletions((int)$data['deletions'])
                    ->setFiles((int)$data['changed_files'])
                    ->setExternalCreatedAt(\DateTimeImmutable::createFromFormat('U', (string)\strtotime($data['created_at'])))
                ;

                $this->pullRequestRepository->save($pullRequestEntity, true);

                $io->note(
                    "\tPull request {$pullRequest['repo']['name']}/{$pullRequest['payload']['number']} inserted"
                );
                $inserted++;
            } else {
                $pullRequestEntity
                    ->setTitle($data['title'])
                    ->setBody($data['body'])
                    ->setStatus((true === (boolean)$data['merged']) ? 'merged' : $data['state'])
                    ->setCommits((int)$data['commits'])
                    ->setAdditions((int)$data['additions'])
                    ->setDeletions((int)$data['deletions'])
                    ->setFiles((int)$data['changed_files'])
                ;

                $this->pullRequestRepository->save($pullRequestEntity, true);

                $io->note(
                    "\tPull request {$pullRequest['repo']['name']}/{$pullRequest['payload']['number']} updated"
                );
                $updated++;
            }
        }

        $io->note("\tInserted: " . $inserted);
        $io->note("\tUpdated: " . $updated);

        $io->note("End\n");

        return Command::SUCCESS;
    }
}
