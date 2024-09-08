<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class LastFmResponse
{
  protected array $data;

  public function __construct($json)
  {
    $this->data = json_decode($json, true);
  }
}
