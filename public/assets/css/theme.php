<?php
// public/assets/css/theme.php
header('Content-Type: text/css');

// Set application root path
$appRoot = dirname(__DIR__, 3);

// Load autoloader to get access to Dotenv class
require_once $appRoot . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable($appRoot);
$dotenv->safeLoad();

// Load configuration
$config = require $appRoot . '/app/config/config.php';
$colors = $config['app']['colors'];

// Get colors with fallbacks
$primary = $colors['primary'] ?? '';
$secondary = $colors['secondary'] ?? '';
$secondaryLight = $colors['secondary_light'] ?? '';
$priceColor = $colors['price'] ?? '';
?>

/* Dynamic Color Variables */
:root {
--color-primary: <?= $primary ?>;
--color-primary-hover: <?= adjustBrightness($primary, -20) ?>;
--color-secondary: <?= $secondary ?>;
--color-secondary-light: <?= $secondaryLight ?>;
--color-price: <?= $priceColor ?>;
}


/* Common Classes */
.singer-red {
background-color: var(--color-primary);
}

.singer-red-text {
color: var(--color-primary);
}

.singer-red-border {
border-color: var(--color-primary);
}

.bg-secondary-light {
background-color: var(--color-secondary-light);
}

.price-color {
color: var(--color-price);
background-color: <?= adjustBrightness($secondary, 5) ?>;
padding: 1px 5px 2px;
}

.custom-input:focus {
outline: none;
border-color: var(--color-primary);
}

.product-image-bg {
background-color: <?= adjustBrightness($secondaryLight, -2) ?>;
}

.thumbnail-active {
border-color: var(--color-primary);
}

/* Button Hover States */
.singer-red:hover {
background-color: var(--color-primary-hover);
}

.singer-red-text:hover {
color: var(--color-primary-hover);
}

<?php
/**
 * Adjust the brightness of a hex color
 * @param string $hex The hex color to adjust
 * @param int $steps The number of steps to adjust (positive = lighter, negative = darker)
 * @return string The adjusted hex color
 */
function adjustBrightness($hex, $steps)
{
  // Remove # if present
  $hex = ltrim($hex, '#');

  // Convert to RGB
  $r = hexdec(substr($hex, 0, 2));
  $g = hexdec(substr($hex, 2, 2));
  $b = hexdec(substr($hex, 4, 2));

  // Adjust brightness
  $r = max(0, min(255, $r + $steps));
  $g = max(0, min(255, $g + $steps));
  $b = max(0, min(255, $b + $steps));

  // Convert back to hex
  return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}
?>