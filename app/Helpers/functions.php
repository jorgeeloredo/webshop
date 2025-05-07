<?php
// app/helpers/functions.php

use App\Helpers\Language;

/**
 * Translate a string
 * 
 * @param string $key The translation key in format 'file.key'
 * @param array $replacements Optional replacements for placeholders
 * @return string
 */
function __($key, $replacements = [])
{
  return Language::getInstance()->translate($key, $replacements);
}

/**
 * Echo a translated string
 * 
 * @param string $key The translation key in format 'file.key'
 * @param array $replacements Optional replacements for placeholders
 * @return void
 */
function _e($key, $replacements = [])
{
  echo __($key, $replacements);
}

/**
 * Get current language code
 * 
 * @return string
 */
function get_language()
{
  return Language::getInstance()->getLanguage();
}
