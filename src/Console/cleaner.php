<?php
declare(strict_types=1);

use MusicCleaner\Command\Cleaner;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;


$application = (new SingleCommandApplication())
    ->addArgument(Cleaner::DIR_ARGUMENT, InputArgument::REQUIRED, 'The directory to process')
    ->setCode(function (InputInterface $input, OutputInterface $output): int {
      $container = get_container();
      /**
       * @var Cleaner $cleaner
       */
      $cleaner = $container->get(Cleaner::class);

      return $cleaner->execute($input, $output);
    });
$application->run();
