<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm;

use MusicCleaner\Service\External\LastFm\Model\LastFmTrackSearchResponse;

class TrackSearch extends LastFmApi {

  const API_METHOD = 'track.search';

  protected function getEndpoint(): string {
    return self::API_METHOD;
  }

  public function api(string $track): LastFmTrackSearchResponse {
    $params['track'] = $track;

    $result = $this->get($params);

    return new LastFmTrackSearchResponse($result);
  }
}
