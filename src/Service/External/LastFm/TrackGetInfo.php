<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm;

use MusicCleaner\Service\External\LastFm\Model\LastFmTrackGetInfoResponse;

class TrackGetInfo extends LastFmApi {

  const API_METHOD = 'track.getInfo';

  protected function getEndpoint(): string {
    return self::API_METHOD;
  }

  public function findByMbId(string $mbId): LastFmTrackGetInfoResponse {
    $params['mbid'] = $mbId;

    $result = $this->get($params);

    return new LastFmTrackGetInfoResponse($result);
  }

  public function findByArtistAndTitle(string $artist, string $title): LastFmTrackGetInfoResponse {
    $params['artist'] = $artist;
    $params['track'] = $title;
    $params['autocorrect'] = 1;

    $result = $this->get($params);

    return new LastFmTrackGetInfoResponse($result);
  }
}
