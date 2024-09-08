<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

use MusicCleaner\Model\SearchTerm;

class SearchTermBuilder {

  public function fromArtistSong($artist, $song): SearchTerm {
    return new SearchTerm($song, '', $artist);
  }

  public function buldSearchTermFromPath(string $path): SearchTerm {
    // remove extension
    $path = strtolower(preg_replace('/\.(\w+)$/', '', $path));

    $parts  = explode(DIRECTORY_SEPARATOR, $path);
    $song   = is_array($parts) ? array_pop($parts) : '';
    $album  = is_array($parts) ? array_pop($parts) : '';
    $artist = is_array($parts) ? array_pop($parts) : '';

    if (empty($song)) {
      $song = '';
    }
    if (empty($album)) {
      $album = '';
    }
    if (empty($artist)) {
      $artist = '';
    }

    // Remove any annotations to the filename like "[FLAC]"
    $song = preg_replace('/on spotify(\s& apple)?/', '', strtolower($song));
    $cleanSong = preg_replace('/\[[^\]]+\]/', '', $song);
    $cleanSong = preg_replace('/\([^\)]+\)/', '', $cleanSong);
    // Remove any non-alpha prefix/suffix
    $cleanSong   = preg_replace('/^[^a-z]*([a-z].*[a-z])[^a-z]*$/', '$1', $cleanSong);
    // Restore any cover tags e.g. Eric Clapton (Boyce Avenue acoustic cover)
    if (preg_match('/([\(\[][^\)\]]*(cover|feat)[^\)\]]*[\)\]])/', $song, $matches)) {
      $cleanSong .= $matches[1];
    }

    $album  = preg_replace('/\[[^\]]+\]/', '', $album);
    $album  = preg_replace('/\([^\)]+\)/', '', $album);
    // Remove any non-alpha prefix/suffix
    $album  = preg_replace('/^[^a-z]*([a-z].*[a-z])[^a-z]*$/', '$1', $album);

    $cleanArtist = preg_replace('/\[[^\]]+\]/', '', $artist);
    $cleanArtist = preg_replace('/\([^\)]+\)/', '', $cleanArtist);
    // Remove any non-alpha prefix/suffix
    $cleanArtist = preg_replace('/^[^a-z]*([a-z].*[a-z])[^a-z]*$/', '$1', $cleanArtist);

    // Restore any cover tags e.g. Eric Clapton (Boyce Avenue acoustic cover)
    if (preg_match('/([\(\[][^\)\]]*(cover|feat)[^\)\]]*[\)\]])/', $artist, $matches)) {
      $cleanArtist .= $matches[1];
    }

    // Collabs are represented by underscore
    $cleanArtist = str_replace('_', ' ', $cleanArtist);
    $cleanSong   = str_replace('_', ' ', $cleanSong); // In case of folder reversal

    return new SearchTerm($cleanSong, $album, $cleanArtist);
  }
}
