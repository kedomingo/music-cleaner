<?php
declare(strict_types=1);

namespace MusicCleaner\Command;

use MusicCleaner\Service\MusicCleaner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cleaner extends Command {

  public const DIR_ARGUMENT = 'dir';
  private MusicCleaner $cleanerService;

  /**
   * @param MusicCleaner $cleanerService
   */
  public function __construct(MusicCleaner $cleanerService) {
    $this->cleanerService = $cleanerService;
  }

  public function execute(InputInterface $input, OutputInterface $output): int {

    try {
      $this->cleanerService->clean($input->getArgument(self::DIR_ARGUMENT));

      return Command::SUCCESS;
    } catch (\Throwable $e) {
      echo $e->getMessage();
    }

    return Command::FAILURE;
  }
}
