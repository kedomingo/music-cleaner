<?php
declare(strict_types=1);

namespace MusicCleaner\Service\Id3;

use getid3_writetags;

class Id3Writer {
  private getid3_writetags $wrapped;

  public function __construct(getid3_writetags $wrapped) {
    $this->wrapped = $wrapped;
  }

  /**
   * @throws Id3Exception
   */
  public function writeTags(string $filePath, array $tagData): bool {

    $this->wrapped->filename          = $filePath;
    $this->wrapped->tagformats        = ['id3v1', 'id3v2.3'];
    $this->wrapped->overwrite_tags    = true;
    $this->wrapped->remove_other_tags = true;
    $this->wrapped->tag_data          = $tagData;

    if ($this->wrapped->WriteTags()) {
      return true;
    }
    throw new Id3Exception(implode(';', $this->wrapped->errors));
  }
}
