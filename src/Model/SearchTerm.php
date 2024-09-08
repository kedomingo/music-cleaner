<?php
declare(strict_types=1);

namespace MusicCleaner\Model;

class SearchTerm {

  private string $song;
  private string $album;
  private string $artist;

  public function __construct(string $song, string $album, string $artist) {
    $this->song   = $song;
    $this->album  = $album;
    $this->artist = $artist;
  }

  /**
   * @return string
   */
  public function getSong(): string {
    return $this->song;
  }

  /**
   * @return string
   */
  public function getAlbum(): string {
    return $this->album;
  }

  /**
   * @return string
   */
  public function getArtist(): string {
    return $this->artist;
  }

  public function toString(): string {
    $s = '';
    if (!str_contains(strtolower($this->artist), 'unknown')) {
      $s .= $this->artist . ' - ';
    }

    return str_replace(['unknown artist', 'unknown album'], '', strtolower($s . $this->song));
  }
}
