<?php
// app/Helpers/Language.php
namespace App\Helpers;

class Language
{
  private static $instance = null;
  private $lang = '';
  private $translations = [];

  /**
   * Private constructor for singleton pattern
   */
  private function __construct()
  {
    // Load the language from config
    $config = require __DIR__ . '/../config/config.php';
    $this->lang = $config['language']['default'] ?? 'fr';

    // Validate the language is available
    if (!in_array($this->lang, $config['language']['available'])) {
      $this->lang = 'fr'; // Fallback to French if invalid
    }
  }

  /**
   * Get singleton instance
   * 
   * @return Language
   */
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Get current language code
   * 
   * @return string
   */
  public function getLanguage()
  {
    return $this->lang;
  }

  /**
   * Translate a string using the correct language file
   * 
   * @param string $key Translation key in format 'file.key'
   * @param array $replacements Optional replacements for placeholders
   * @return string
   */
  public function translate($key, $replacements = [])
  {
    // Split the key into file and translation key
    $parts = explode('.', $key);
    if (count($parts) !== 2) {
      return $key; // Return the key itself if not properly formatted
    }

    list($file, $translationKey) = $parts;

    // Load the translation file if not already loaded
    if (!isset($this->translations[$file])) {
      $this->loadTranslations($file);
    }

    // Get the translation or fallback to the key
    $translation = $this->translations[$file][$translationKey] ?? $translationKey;

    // Apply replacements if any
    if (!empty($replacements)) {
      foreach ($replacements as $search => $replace) {
        $translation = str_replace(':' . $search, $replace, $translation);
      }
    }

    return $translation;
  }

  /**
   * Load translations from a specific file
   * 
   * @param string $file
   * @return void
   */
  private function loadTranslations($file)
  {
    $filePath = __DIR__ . '/../lang/' . $this->lang . '/' . $file . '.php';

    if (file_exists($filePath)) {
      $this->translations[$file] = require $filePath;
    } else {
      // If file doesn't exist, set an empty array to prevent multiple attempts
      $this->translations[$file] = [];

      // Try to load the fallback language file (French)
      if ($this->lang !== 'fr') {
        $fallbackPath = __DIR__ . '/../lang/fr/' . $file . '.php';
        if (file_exists($fallbackPath)) {
          $this->translations[$file] = require $fallbackPath;
        }
      }
    }
  }
}
