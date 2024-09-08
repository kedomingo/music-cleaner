<?php
declare(strict_types=1);

namespace MusicCleaner\Service\Id3;

use getID3;

class Id3Getter {
  private $wrapped;

  public function __construct(getID3 $wrapped) {
    $this->wrapped = $wrapped;
  }

  public function analyze(string $filePath) {
    return $this->wrapped->analyze($filePath);
  }
}
