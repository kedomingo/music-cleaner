<?php
declare(strict_types=1);

namespace MusicCleaner\Service\Id3;

class Id3TagService {
  private Id3Getter $reader;
  private Id3Writer $writer;

  private static array $analyzed;

  public function __construct(Id3Getter $reader, Id3Writer $writer) {
    $this->reader = $reader;
    $this->writer = $writer;
  }

  public function getTitle(string $filePath): ?string {
    return $this->getScalarTag($filePath, 'title');
  }

  public function getGenre(string $filePath) {
    return $this->getScalarTag($filePath, 'genre');
  }

  public function getAlbum(string $filePath) {
    return $this->getScalarTag($filePath, 'album');
  }

  public function getArtist(string $filePath) {
    return $this->getScalarTag($filePath, 'artist');
  }

  /**
   * @throws Id3Exception
   */
  public function updateTitle(string $filePath, string $newTitle): void {
    $this->writer->writeTags($filePath, [
        'title' => [$newTitle]
    ]);
  }

  /**
   * @throws Id3Exception
   */
  public function updateGenre(string $filePath, string $newGenre): void {
    $this->writer->writeTags($filePath, [
        'genre' => [$newGenre]
    ]);
  }

  private function getScalarTag(string $filePath, string $whichTag): ?string {
    if (!isset(static::$analyzed[$filePath])) {
      // Only store one analyzed file at a time. When we moved to another file, clear memory
      static::$analyzed = [];
      static::$analyzed[$filePath] = $this->reader->analyze($filePath);
    }
    $fileInfo = static::$analyzed[$filePath];

    return $fileInfo['tags']['id3v2'][$whichTag][0] ?? null;
  }
}
