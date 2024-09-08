<?php
declare(strict_types=1);

namespace MusicCleaner\Service;

class NameProvider {

  static array $names = [];

  public function isPossiblyName(string $str): bool {
    if ($this->isPossibleCollab($str)) {
      return true;
    }
    // get the first alpabetic string
    $name = strtolower($str);
    $name = preg_replace('/[^a-z]*([a-z]+)[^a-z]*.*$/', '$1', $name);
    if (empty($name) || $name === 'a' || $name === 'the') {
      return false;
    }

    return $this->isName($name);
  }

  public function isPossibleCollab(string $str): bool {
    return preg_match('/ f(ea)?t(\.|uring)? /', strtolower($str)) || str_contains(' / ', $str) || str_contains(' + ', $str);
  }

  public function isName(string $name): bool {
    if (empty(static::$names)) {
      static::$names = explode("\n", preg_replace('/\s+/', "\n", file_get_contents('/app/names.txt')));
      static::$names = array_combine(static::$names, static::$names);
    }

    $name = trim(strtolower($name));

    return isset(static::$names[$name]);
  }
}
