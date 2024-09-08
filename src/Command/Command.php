<?php
declare(strict_types=1);

namespace MusicCleaner\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

abstract class Command {
  public const SUCCESS = BaseCommand::SUCCESS;
  public const FAILURE = BaseCommand::FAILURE;
  public const INVALID = BaseCommand::INVALID;
  public abstract function execute(InputInterface $input, OutputInterface $output): int;
}
