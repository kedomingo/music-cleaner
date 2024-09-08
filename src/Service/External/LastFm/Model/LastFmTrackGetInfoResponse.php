<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model;

use MusicCleaner\Service\External\LastFm\Model\Support\LastFmResponse;
use MusicCleaner\Service\External\LastFm\Model\Support\Track;

class LastFmTrackGetInfoResponse extends LastFmResponse {
  private ?Track $track;

  public function __construct($json) {
    parent::__construct($json);

    $this->track = !empty($this->data['track']) ? new Track($this->data['track']) : null;
  }

  /**
   * @return Track|null
   */
  public function getTrack(): ?Track {
    return $this->track;
  }
}

