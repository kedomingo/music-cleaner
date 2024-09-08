<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model;

use MusicCleaner\Service\External\LastFm\Model\Support\LastFmResponse;
use MusicCleaner\Service\External\LastFm\Model\Support\Track;

class LastFmTrackSearchResponse extends LastFmResponse {
  private int $totalResults;
  private int $startIndex;
  private int $itemsPerPage;
  /**
   * @var Track[]
   */
  private array $trackMatches;

  public function __construct($json) {
    parent::__construct($json);
    $response = $this->data['results'];

    $this->totalResults = (int)$response['opensearch:totalResults'];
    $this->startIndex   = (int)$response['opensearch:startIndex'];
    $this->itemsPerPage = (int)$response['opensearch:itemsPerPage'];

    $tracks = [];
    foreach ($response['trackmatches']['track'] as $trackData) {
      $tracks[] = new Track($trackData);
    }
    $this->trackMatches = $tracks;
  }

  public function getBestMatchingTrack(): ?Track {
    $first = null;
    foreach ($this->trackMatches as $track) {
      if ($first === null) {
        $first = $track;
      }
      if (!empty($track->getAlbumCovers())) {
        return $track;
      }
    }

    return $first;
  }
}

