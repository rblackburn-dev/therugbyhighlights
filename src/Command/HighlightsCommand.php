<?php

namespace App\Command;

use App\Service\YoutubeService;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Google\Service\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HighlightsCommand extends Command
{
    protected EntityManager $entityManager;
    protected YoutubeService $youtubeService;

    public function __construct(EntityManager $entityManager, YoutubeService $youtubeService)
    {
        $this->entityManager = $entityManager;

        $this->youtubeService = $youtubeService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('getHighlights');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getName());

        $this->youtubeService->getHighlights();

        $io->success('Complete');
        return Command::SUCCESS;
    }
}