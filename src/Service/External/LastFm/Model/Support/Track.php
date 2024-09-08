<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class Track {
  private string $mbid;
  private string $name;
  private string $url;
  private array $streamable;
  private array $albumCovers;
  private ?int $playcount;
  private ?int $duration;
  private ?int $listeners;
  private ?Artist $artist;
  private ?Album $album;
  private ?TrackWiki $wiki;
  /**
   * @var Tag[]
   */
  private array $toptags;

  public function __construct($track) {
    $this->mbid       = $track['mbid'] ?? '';
    $this->name       = $track['name'] ?? '';
    $this->url        = $track['url'] ?? '';
    $this->streamable = is_array($track['streamable']) ? $track['streamable'] : [];
    $this->playcount  = !empty($track['playcount']) ? (int)$track['playcount'] : null;
    $this->duration   = !empty($track['duration']) ? (int)$track['duration'] : null;
    $this->listeners  = !empty($track['listeners']) ? (int)$track['listeners'] : null;

    $this->artist      = !empty($track['artist']) ? (is_string($track['artist']) ? new Artist(['name' => $track['artist']]) : new Artist($track['artist'])) : null;
    $this->album       = !empty($track['album']) ? new Album($track['album']) : null;
    $this->wiki        = !empty($track['wiki']) ? new TrackWiki($track['wiki']) : null;
    $this->toptags     = !empty($track['toptags']['tag']) ? Tag::fromJsonArray($track['toptags']['tag']) : [];
    $this->albumCovers = !empty($track['image']) ? Image::fromJsonArray($track['image']) : [];
  }

  /**
   * @return string
   */
  public function getMbid(): string {
    return $this->mbid;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * @return array
   */
  public function getStreamable(): array {
    return $this->streamable;
  }

  /**
   * @return Image[]
   */
  public function getAlbumCovers(): array {
    return $this->albumCovers;
  }

  /**
   * @return int|null
   */
  public function getPlaycount(): ?int {
    return $this->playcount;
  }

  /**
   * @return int|null
   */
  public function getDuration(): ?int {
    return $this->duration;
  }

  /**
   * @return int|null
   */
  public function getListeners(): ?int {
    return $this->listeners;
  }

  /**
   * @return Artist|null
   */
  public function getArtist(): ?Artist {
    return $this->artist;
  }

  /**
   * @return Album|null
   */
  public function getAlbum(): ?Album {
    return $this->album;
  }

  /**
   * @return TrackWiki|null
   */
  public function getWiki(): ?TrackWiki {
    return $this->wiki;
  }

  /**
   * @return Tag[]
   */
  public function getTopTags(): array {
    return $this->toptags;
  }

  /**
   * @return string[]
   */
  public function getTopTagsAsStringArray(): array {
    return array_map(function (Tag $tag): string {
      return $tag->getName();
    },
        $this->toptags ?? []
    );
  }
}
