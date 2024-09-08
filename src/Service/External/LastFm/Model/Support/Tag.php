<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class Tag {
  private string $name;
  private ?string $url;

  public function __construct($tag) {
    $this->name = $tag['name'];
    $this->url  = $tag['url'] ?? null;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param array $tags
   *
   * @return Tag[]
   */
  public static function fromJsonArray(array $tags): array {
    return array_map(function (array $tag): Tag {
      return new Tag($tag);
    }, $tags);
  }
}

