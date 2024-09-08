<?php
declare(strict_types=1);

namespace MusicCleaner\Service\External\LastFm\Model\Support;

class Image {
  private string $url;
  private ?string $size;

  public function __construct($tag) {
    $this->url = $tag['#text'] ?? '';
    $this->size  = $tag['size'] ?? null;
  }

  /**
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * @param array $tags
   *
   * @return Image[]
   */
  public static function fromJsonArray(array $tags): array {
    $items = array_map(function (array $tag): Image {
      return new Image($tag);
    }, $tags);

    return array_filter($items, function(Image $item) {
      return !empty($item->getUrl());
    });
  }
}

