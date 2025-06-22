<?php
// app/Helpers/ImageHelper.php
namespace App\Helpers;

class ImageHelper
{
  /**
   * The directory where original product images are stored
   * @var string
   */
  private static $originalPath = __DIR__ . '/../../public/assets/images/products/';

  /**
   * The directory where thumbnail images will be stored
   * @var string
   */
  private static $thumbnailPath = __DIR__ . '/../../public/assets/images/products/thumbnails/';

  /**
   * The URL path for original images
   * @var string
   */
  private static $originalUrl = '/assets/images/products/';

  /**
   * The URL path for thumbnail images
   * @var string
   */
  private static $thumbnailUrl = '/assets/images/products/thumbnails/';

  /**
   * Default thumbnail dimensions
   * @var array
   */
  private static $defaultSize = [
    'width' => 500,
    'height' => 500
  ];

  /**
   * Get URL for an image, generating a thumbnail if necessary
   * 
   * @param string $imageName Image filename
   * @param string $type 'thumbnail' or 'original'
   * @param array $size Optional custom size [width, height]
   * @param bool $preferWebP Whether to prefer WebP format if available
   * @return string URL to the image
   */
  public static function getImageUrl($imageName, $type = 'thumbnail', $size = null, $preferWebP = true)
  {
    if (empty($imageName)) {
      return '';
    }

    // For original images, just return the URL
    if ($type === 'original') {
      return self::$originalUrl . $imageName;
    }

    // Set thumbnail dimensions
    $width = $size['width'] ?? self::$defaultSize['width'];
    $height = $size['height'] ?? self::$defaultSize['height'];

    // Generate thumbnail filename
    $thumbnailName = self::getThumbnailName($imageName, $width, $height);

    // Check if thumbnail directory exists, create if not
    if (!file_exists(self::$thumbnailPath)) {
      mkdir(self::$thumbnailPath, 0755, true);
    }

    // Check if thumbnail exists, create if not
    $thumbnailFullPath = self::$thumbnailPath . $thumbnailName;
    $originalFullPath = self::$originalPath . $imageName;

    if (!file_exists($thumbnailFullPath) && file_exists($originalFullPath)) {
      self::generateThumbnail($originalFullPath, $thumbnailFullPath, $width, $height);
    }

    // Check if WebP version exists and is preferred
    if ($preferWebP && function_exists('imagewebp')) {
      $pathInfo = pathinfo($thumbnailName);
      $webpName = $pathInfo['filename'] . '.webp';
      $webpFullPath = self::$thumbnailPath . $webpName;

      if (file_exists($webpFullPath)) {
        return self::$thumbnailUrl . $webpName;
      }
    }

    return self::$thumbnailUrl . $thumbnailName;
  }

  /**
   * Generate a thumbnail name based on original name and dimensions
   * 
   * @param string $imageName Original image name
   * @param int $width Target width
   * @param int $height Target height
   * @return string Thumbnail filename
   */
  private static function getThumbnailName($imageName, $width, $height)
  {
    $pathInfo = pathinfo($imageName);
    return $pathInfo['filename'] . '_' . $width . 'x' . $height . '.' . $pathInfo['extension'];
  }

  /**
   * Generate a thumbnail from an original image
   * 
   * @param string $originalPath Full path to original image
   * @param string $thumbnailPath Full path where thumbnail will be saved
   * @param int $targetWidth Target width
   * @param int $targetHeight Target height
   * @return bool Success or failure
   */
  private static function generateThumbnail($originalPath, $thumbnailPath, $targetWidth, $targetHeight)
  {
    // Get image information
    $imageInfo = getimagesize($originalPath);
    if (!$imageInfo) {
      return false;
    }

    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $type = $imageInfo[2];

    // Create image resource based on type
    switch ($type) {
      case IMAGETYPE_JPEG:
        $sourceImage = imagecreatefromjpeg($originalPath);
        break;
      case IMAGETYPE_PNG:
        $sourceImage = imagecreatefrompng($originalPath);
        break;
      case IMAGETYPE_GIF:
        $sourceImage = imagecreatefromgif($originalPath);
        break;
      default:
        return false;
    }

    if (!$sourceImage) {
      return false;
    }

    // Calculate dimensions to maintain aspect ratio
    $sourceRatio = $width / $height;
    $targetRatio = $targetWidth / $targetHeight;

    if ($sourceRatio > $targetRatio) {
      // Source image is wider
      $sourceX = (int) round(($width - $height * $targetRatio) / 2);
      $sourceY = 0;
      $sourceW = (int) round($height * $targetRatio);
      $sourceH = $height;
    } else {
      // Source image is taller
      $sourceX = 0;
      $sourceY = (int) round(($height - $width / $targetRatio) / 2);
      $sourceW = $width;
      $sourceH = (int) round($width / $targetRatio);
    }

    // Create thumbnail image
    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

    // Preserve transparency for PNG images
    if ($type == IMAGETYPE_PNG) {
      imagealphablending($thumbnail, false);
      imagesavealpha($thumbnail, true);
      $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
      imagefilledrectangle($thumbnail, 0, 0, $targetWidth, $targetHeight, $transparent);
    }

    // Copy and resize the image
    imagecopyresampled(
      $thumbnail,
      $sourceImage,
      0,
      0,
      $sourceX,
      $sourceY,
      $targetWidth,
      $targetHeight,
      $sourceW,
      $sourceH
    );

    // Check if WebP is supported
    $webpSupported = function_exists('imagewebp');
    $pathInfo = pathinfo($thumbnailPath);
    $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

    // Save the thumbnail
    $result = false;
    switch ($type) {
      case IMAGETYPE_JPEG:
        $result = imagejpeg($thumbnail, $thumbnailPath, 85); // 85% quality

        // Also save as WebP if supported (better compression)
        if ($webpSupported) {
          imagewebp($thumbnail, $webpPath, 85);
        }
        break;
      case IMAGETYPE_PNG:
        $result = imagepng($thumbnail, $thumbnailPath, 8); // Compression level 8

        // Also save as WebP if supported
        if ($webpSupported) {
          imagewebp($thumbnail, $webpPath, 85);
        }
        break;
      case IMAGETYPE_GIF:
        $result = imagegif($thumbnail, $thumbnailPath);

        // Only save static GIFs as WebP
        if ($webpSupported && !self::isAnimatedGif($originalPath)) {
          imagewebp($thumbnail, $webpPath, 85);
        }
        break;
    }

    // Free memory
    imagedestroy($sourceImage);
    imagedestroy($thumbnail);

    return $result;
  }

  /**
   * Check if a GIF image is animated
   * 
   * @param string $filepath Path to the GIF file
   * @return bool True if animated, false if static
   */
  private static function isAnimatedGif($filepath)
  {
    if (!($fh = @fopen($filepath, 'rb'))) {
      return false;
    }

    $count = 0;

    // An animated gif contains multiple "frames", with each frame having a
    // header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C)

    // We read through the file until we reach the end of the file, or we've found
    // at least 2 frame headers
    while (!feof($fh) && $count < 2) {
      $chunk = fread($fh, 1024 * 100); // Read 100KB at a time
      $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches);
    }

    fclose($fh);
    return $count > 1;
  }
}
