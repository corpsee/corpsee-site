<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\PullRequest;
use App\Repository\PullRequestRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-pull-requests',
    description: 'Import pull requests from CSV file into DB',
)]
class ImportPullRequestsCommand extends Command
{
    public function __construct(
        private readonly PullRequestRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::OPTIONAL, 'CSV file for import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if ($file) {
            $io->note(sprintf('Start import. You passed CSV file: %s', $file));
        } else {
            $io->error('No CSV file for import!');

            return Command::FAILURE;
        }

        $fileHandler = \fopen($file, 'r');
        $indexes     = [];
        $counter     = 0;
        while ($row = \fgetcsv($fileHandler)) {
            if (count($indexes) === 0) {
                $indexes = array_flip($row);

                continue;
            }

            $counter++;
            $data = [];
            foreach ($indexes as $field => $index) {
                $data[$field] = $row[$index];
            }

            $entity = new PullRequest(
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['create_date']),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['create_date'])
            );
            $entity
                ->setPlatform(PullRequest::PLATFORM_GITHUB)
                ->setRepository($data['repository'])
                ->setPlatformId($data['number'])
                ->setTitle($data['title'])
                ->setBody($data['body'])
                ->setStatus($data['status'])
                ->setCommits((int)$data['commits'])
                ->setAdditions((int)$data['additions'])
                ->setDeletions((int)$data['deletions'])
                ->setFiles((int)$data['files'])
                ;

            $this->repository->save($entity, true);
        }

        $io->note('Saving: ' . $counter);

        $io->success('Success import.');

        return Command::SUCCESS;
    }
}
