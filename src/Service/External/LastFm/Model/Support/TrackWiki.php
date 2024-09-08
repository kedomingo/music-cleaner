<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class TrackWiki {
  private string $published;
  private string $summary;
  private string $content;

  public function __construct($wiki) {
    $this->published = $wiki['published'] ?? '';
    $this->summary   = $wiki['summary'] ?? '';
    $this->content   = $wiki['content'] ?? '';
  }
}
