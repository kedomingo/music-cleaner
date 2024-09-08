<?php
declare(strict_types=1);

use MusicCleaner\Service\External\LastFm\Entity\LastFmTrackGetInfoResponse;
use MusicCleaner\Service\External\LastFm\Entity\LastFmTrackSearchResponse;

$s = file_get_contents(__DIR__.'/../Service/External/LastFm/json/track.search.json');

$a = new LastFmTrackSearchResponse($s);

print_r($a);
