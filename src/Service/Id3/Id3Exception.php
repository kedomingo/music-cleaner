<?php
declare(strict_types=1);

namespace MusicCleaner\Service\Id3;

class Id3Exception extends \Exception {

  public function __construct(string $message) {
    parent::__construct($message);
  }
}
