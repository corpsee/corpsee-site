<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Picture;
use App\Entity\Tag;
use App\Repository\PictureRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-pictures',
    description: 'Import pictures with tags from CSV files into DB',
)]
class ImportPicturesCommand extends Command
{
    public function __construct(
        private readonly PictureRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('picturesFile', InputArgument::OPTIONAL, 'CSV file for import Pictures')
            ->addArgument('tagsFile', InputArgument::OPTIONAL, 'CSV file for import Tags')
            ->addArgument('picturesTagsFile', InputArgument::OPTIONAL, 'CSV file for import Pictures-Tags')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $pFile  = $input->getArgument('picturesFile');
        $tFile  = $input->getArgument('tagsFile');
        $ptFile = $input->getArgument('picturesTagsFile');

        if ($pFile && $tFile && $ptFile) {
            $io->note(sprintf('Start import. You passed CSV files: %s, %s, %s', $pFile, $tFile, $ptFile));
        } else {
            $io->error('No CSV files for import!');

            return Command::FAILURE;
        }

        $tFileHandler = \fopen($tFile, 'r');
        $indexes      = [];
        $tCounter     = 0;
        /** @var Tag[] $tags */
        $tags        = [];
        while ($row = \fgetcsv($tFileHandler)) {
            if (\count($indexes) === 0) {
                $indexes = \array_flip($row);

                continue;
            }

            $tCounter++;
            $data = [];
            foreach ($indexes as $field => $index) {
                $data[$field] = $row[$index];
            }

            $tags[$data['id']] = new Tag(
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['post_date']),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['modify_date'])
            );
            $tags[$data['id']]
                ->setName($data['tag'])
                ;
        }

        $pFileHandler = \fopen($pFile, 'r');
        $indexes      = [];
        $pCounter     = 0;
        /** @var Picture[] $pictures */
        $pictures    = [];
        while ($row = \fgetcsv($pFileHandler)) {
            if (\count($indexes) === 0) {
                $indexes = \array_flip($row);

                continue;
            }

            $pCounter++;
            $data = [];
            foreach ($indexes as $field => $index) {
                $data[$field] = $row[$index];
            }

            $pictures[$data['id']] = new Picture(
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['post_date']),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['modify_date'])
            );
            $pictures[$data['id']]
                ->setTitle($data['title'])
                ->setImage($data['image'] . '.jpg')
                ->setDescription($data['description'])
                ->setDrawnAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['create_date']))
                ;
        }

        $ptFileHandler = \fopen($ptFile, 'r');
        $indexes     = [];
        $ptCounter     = 0;
        while ($row = \fgetcsv($ptFileHandler)) {
            if (\count($indexes) === 0) {
                $indexes = \array_flip($row);

                continue;
            }

            $ptCounter++;
            $data = [];
            foreach ($indexes as $field => $index) {
                $data[$field] = $row[$index];
            }

            ($pictures[(int)$data['picture_id']])
                ->addTag($tags[(int)$data['tag_id']])
                ;
        }

        foreach ($pictures as $picture) {
            $this->repository->save($picture, true);
        }

        $io->note(\sprintf('Saving: %s, %s, %s', $pCounter, $tCounter, $ptCounter));

        $io->success('Success import.');

        return Command::SUCCESS;
    }
}
