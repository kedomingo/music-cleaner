<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class Artist {
  private string $name;
  private ?string $mbid;
  private ?string $url;

  public function __construct($artist) {
    $this->name = $artist['name'] ?? '';
    $this->mbid = $artist['mbid'] ?? null;
    $this->url  = $artist['url'] ?? null;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @return string|null
   */
  public function getMbid(): ?string {
    return $this->mbid;
  }

  /**
   * @return string|null
   */
  public function getUrl(): ?string {
    return $this->url;
  }

}

