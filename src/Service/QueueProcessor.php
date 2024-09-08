<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

use MusicCleaner\Model\Job;
use MusicCleaner\Model\SearchTerm;
use MusicCleaner\Service\External\LastFm\Model\Support\Track;
use MusicCleaner\Service\External\LastFm\TrackGetInfo;
use MusicCleaner\Service\External\LastFm\TrackSearch;
use MusicCleaner\Service\Id3\Id3TagService;

class QueueProcessor {

  private JobsService $jobsService;
  private SearchTermBuilder $searchTermBuilder;
  private NameProvider $nameProvider;
  private TrackSearch $trackSearchApi;
  private TrackGetInfo $trackGetInfoApi;
  private Id3TagService $id3TagService;

  /**
   * @param JobsService       $jobsService
   * @param SearchTermBuilder $searchTermBuilder
   * @param NameProvider      $nameProvider
   * @param TrackSearch       $trackSearchApi
   * @param TrackGetInfo      $trackGetInfoApi
   */
  public function __construct(
      JobsService       $jobsService,
      SearchTermBuilder $searchTermBuilder,
      NameProvider      $nameProvider,
      TrackSearch       $trackSearchApi,
      TrackGetInfo      $trackGetInfoApi,
      Id3TagService     $id3TagService
  ) {
    $this->jobsService       = $jobsService;
    $this->searchTermBuilder = $searchTermBuilder;
    $this->nameProvider      = $nameProvider;
    $this->trackSearchApi    = $trackSearchApi;
    $this->trackGetInfoApi   = $trackGetInfoApi;
    $this->id3TagService     = $id3TagService;
  }

  public function process(int $howMany): void {
    $jobs = $this->jobsService->consume($howMany);
    foreach ($jobs as $job) {
      $this->processJob($job);
    }
  }

  private function processJob(Job $job) {
    echo sprintf("\n\nPROCESSING %s\n", $job->getPath());

    $path   = $job->getPath();
    $genre  = $this->id3TagService->getGenre($path);
    $artist = $this->id3TagService->getArtist($path);
    $album  = $this->id3TagService->getAlbum($path);
    $title  = $this->id3TagService->getTitle($path);
    if (!empty($genre)) {
      echo "Detected genre: $genre \n";
    }
    if (!empty($artist)) {
      echo "Detected artist: $artist \n";
    }
    if (!empty($album)) {
      echo "Detected album: $album \n";
    }
    if (!empty($title)) {
      echo "Detected title: $title \n";
    }
    // Default search term
    $searchTerm = $this->searchTermBuilder->buldSearchTermFromPath($job->getPath());

    if (!empty($title) && !empty($artist) && !empty($album)) {
      // We have all info. We're done
      if (!empty($genre)) {
        $this->jobsService->updatePathAndTags($job, $artist, $album, $title, $genre);

        return;
      }
      // We still need genre
      $searchTerm = $this->searchTermBuilder->fromArtistSong($artist, $title);
    }

    //    if ($this->nameProvider->isPossiblyName($searchTerm->getArtist())) {
    //      echo sprintf("%s looks like it is clean. Not touching it\n", $job->getPath());
    //      $this->jobsService->markAsProcessed($job->getId());
    //
    //      return;
    //    }

    //    // Song is actually the collaboration of artists
    //    if ($this->nameProvider->isPossibleCollab($searchTerm->getSong())) {
    //      echo sprintf("%s looks reversed. Reversing.\n", $job->getPath());
    //      $this->jobsService->updatePathAndTags($job, $searchTerm->getSong(), $searchTerm->getAlbum(), $searchTerm->getArtist());
    //
    //      return;
    //    }

    // // If directories are reversed
    // if ($this->nameProvider->isPossiblyName($searchTerm->getSong())) {
    //   echo sprintf("Song `%s` looks like it is a name. Confirming...\n", $searchTerm->getSong());
    //   $this->confirmAndProcess($job, $searchTerm);
    // }
    //    echo "Cannot figure out. Checking lastfm...\n";

    $this->findTrackAndProcess($job, $searchTerm);
  }

  //  private function confirmAndProcess(Job $job, SearchTerm $searchTerm): void {
  //    $track = $this->trackSearch($searchTerm);
  //    if ($track === null) {
  //      $this->jobsService->markAsProcessed($job->getId());
  //
  //      return;
  //    }
  //
  //    // The song field is actually probably the title of the track
  //    if (levenshtein($searchTerm->getSong(), $track->getName()) < levenshtein($searchTerm->getArtist(), $track->getName())) {
  //      echo sprintf("%s is close to %s. not touching it\n", $searchTerm->getSong(), $track->getName());
  //      $this->jobsService->markAsProcessed($job->getId());
  //
  //      return;
  //    }
  //
  //    echo sprintf("%s is close to %s. reversing...\n", $searchTerm->getArtist(), $track->getName());
  //
  //    $album = !empty($track->getAlbum()) && !empty($track->getAlbum()->getTitle())
  //        ? $track->getAlbum()->getTitle()
  //        : $searchTerm->getAlbum();
  //    $this->jobsService->updatePathAndTags($job, $track->getArtist()->getName(), $album, $track->getName());
  //  }

  private function findTrackAndProcess(Job $job, SearchTerm $searchTerm) {
    $track = $this->trackSearch($searchTerm);
    if ($track === null) {
      $this->jobsService->markAsProcessed($job->getId());

      return;
    }

    $track = $this->trackInfo($track);
    if ($track === null) {
      $this->jobsService->markAsProcessed($job->getId());

      return;
    }

    $album = !empty($track->getAlbum()) && !empty($track->getAlbum()->getTitle())
        ? $track->getAlbum()->getTitle()
        : $searchTerm->getAlbum();
    $this->jobsService->updatePathAndTags($job, $track->getArtist()->getName(), $album, $track->getName(), implode(';', $track->getTopTagsAsStringArray()));
  }

  private function trackSearch(SearchTerm $searchTerm): ?Track {
    try {
      $searchString = $searchTerm->toString();
      echo "Checking LastFM API for `$searchString`\n";
      $result = $this->trackSearchApi->api($searchString);
    } catch (\Throwable $e) {
      echo sprintf("Exception during API call to Track Search: %s\n%s", $e->getMessage(), $e->getTraceAsString());

      return null;
    }
    $track = $result->getBestMatchingTrack();
    if ($track === null) {
      echo sprintf("No search results found for `%s`\n", $searchTerm->toString());

      return null;
    }

    return $track;
  }

  private function trackInfo(Track $track): ?Track {
    try {
      return !empty($track->getMbid())
          ? $this->trackGetInfoApi->findByMbId($track->getMbid())->getTrack()
          : $this->trackGetInfoApi->findByArtistAndTitle($track->getArtist()->getName(), $track->getName())->getTrack();
    } catch (\Throwable $e) {
      echo sprintf("Exception during API call to Track INFO: %s\nTrace:\n%s", $e->getMessage(), $e->getTraceAsString());

      return null;
    }
  }
}
