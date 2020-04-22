<?php
namespace App\Utils;
/**
 * A utility class with one method that produces a URL-friendly slug of some given text
 */
class Slugger {
  /**
   * Make a URL-friendly slug. Based off of: http://stackoverflow.com/a/2955878
   * which was based off Symfony's Jobeet tutorial: http://symfony.com/legacy/doc/jobeet/1_4/en/05?orm=Doctrine
   *
   * @param string $text The text to Slugify
   *
   * @return string The Slugified text
   */
  static public function slugify($text)
  {
    // make 7-character hash
    $title_hash = substr(md5($text), 0, 7);
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    // trim
    $text = trim($text, '-');
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // lowercase
    $text = strtolower($text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = substr($text, 0, 100);
    $text = $text . '-' . $title_hash;
    if (empty($text))
    {
      return 'n-a';
    }
    return $text;
  }
}