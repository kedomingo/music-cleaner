<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class Album {
  private ?string $artist;
  private ?string $title;
  private ?string $mbid;
  private ?string $url;
  private array $images;

  public function __construct($album) {
    $this->artist = $album['artist'] ?? '';
    $this->title  = $album['title'] ?? '';
    $this->mbid   = $album['mbid'] ?? '';
    $this->url    = $album['url'] ?? '';
    $this->images = $album['image'] ?? [];
  }

  /**
   * @return string|null
   */
  public function getArtist(): ?string {
    return $this->artist;
  }

  /**
   * @return string|null
   */
  public function getTitle(): ?string {
    return $this->title;
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

  /**
   * @return array
   */
  public function getImages(): array {
    return $this->images;
  }
}
