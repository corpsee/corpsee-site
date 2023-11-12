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

#[AsCommand(
    name: 'app:update-pull-requests',
    description: 'Import pull requests from Github by API',
)]
class UpdatePullRequestsCommand extends Command
{
    public function __construct(
        private readonly Client $githubClient,
        private readonly PullRequestRepository $pullRequestRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Start: ' . \date('Y-m-d H:i:s'));

        $repositories = $this->githubClient->api('user');
        $paginator = new ResultPager($this->githubClient);
        $events = $paginator->fetchAll($repositories, 'publicEvents', ['corpsee']);

        $io->note("\tpublicEvents: " . \sizeof($events));

        $pullRequests = [];
        foreach ($events as $event) {
            dump($event);
            if ('PullRequestEvent' === $event['type']) {
                $pullRequests[] = $event;
            }
        }

        $io->note("\tPullRequestEvent: " . \sizeof($pullRequests) . "\n");

        $inserted = 0;
        $updated  = 0;
        foreach ($pullRequests as $pullRequest) {
            $repo = \explode('/', $pullRequest['repo']['name']);
            $data = $this->githubClient->api('pull_request')->show($repo[0], $repo[1], $pullRequest['payload']['number']);

            if ('corpsee' !== $data['user']['login']) {
                continue;
            }

            $pullRequestFromStorage = $this->pullRequestRepository->findOneBy([
                'repository' => $pullRequest['repo']['name'],
                'platformId' => $pullRequest['payload']['number'],
            ]);

            if (null === $pullRequestFromStorage) {
                $entity = new PullRequest(
                    \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']),
                    \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at'])
                );
                $entity
                    ->setPlatform(PullRequest::PLATFORM_GITHUB)
                    ->setRepository($pullRequest['repo']['name'])
                    ->setPlatformId($pullRequest['payload']['number'])
                    ->setTitle($data['title'])
                    ->setBody($data['body'])
                    ->setStatus((true === (boolean)$data['merged']) ? 'merged' : $data['state'])
                    ->setCommits((int)$data['commits'])
                    ->setAdditions((int)$data['additions'])
                    ->setDeletions((int)$data['deletions'])
                    ->setFiles((int)$data['changed_files'])
                ;

                $this->pullRequestRepository->save($entity, true);

                $io->note(
                    "\tPull request {$pullRequest['repo']['name']}/{$pullRequest['payload']['number']} inserted"
                );
                $inserted++;
            } else {
                $entity = new PullRequest(
                    \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at']),
                    \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at'])
                );
                $entity
                    ->setPlatform(PullRequest::PLATFORM_GITHUB)
                    ->setRepository($pullRequest['repo']['name'])
                    ->setPlatformId($pullRequest['payload']['number'])
                    ->setTitle($data['title'])
                    ->setBody($data['body'])
                    ->setStatus((true === (boolean)$data['merged']) ? 'merged' : $data['state'])
                    ->setCommits((int)$data['commits'])
                    ->setAdditions((int)$data['additions'])
                    ->setDeletions((int)$data['deletions'])
                    ->setFiles((int)$data['changed_files'])
                ;

                $this->pullRequestRepository->save($entity, true);

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
