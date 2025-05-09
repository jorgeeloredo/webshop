<?php
// app/Models/Review.php
namespace App\Models;

class Review
{
  private string $dataPath;
  private array $reviews = [];
  private int $perPage = 5; // Number of reviews per page

  public function __construct()
  {
    $config = require __DIR__ . '/../config/config.php';
    $this->dataPath = $config['reviews']['data_path'] ?? __DIR__ . '/../../data/reviews';
    $this->loadReviews();
  }

  private function loadReviews()
  {
    $jsonFile = $this->dataPath . '/reviews.json';

    if (file_exists($jsonFile)) {
      $this->reviews = json_decode(file_get_contents($jsonFile), true) ?? [];
    }
  }

  public function getAll()
  {
    return $this->reviews;
  }

  public function getByProductId($productId, $page = 1, $filter = null, $sort = 'recent')
  {
    // Filter reviews by product ID
    $reviews = array_filter($this->reviews, function ($review) use ($productId) {
      return $review['product_id'] == $productId;
    });

    // Apply star rating filter if provided
    if ($filter && is_numeric($filter) && $filter >= 1 && $filter <= 5) {
      $reviews = array_filter($reviews, function ($review) use ($filter) {
        return $review['rating'] == $filter;
      });
    }

    // Sort reviews based on the selected sorting method
    usort($reviews, function ($a, $b) use ($sort) {
      switch ($sort) {
        case 'recent':
          return strtotime($b['date']) - strtotime($a['date']);
        case 'helpful':
          // Assuming there's a 'helpful_votes' field, or default to 0
          return ($b['helpful_votes'] ?? 0) - ($a['helpful_votes'] ?? 0);
        case 'highest':
          return $b['rating'] - $a['rating'];
        case 'lowest':
          return $a['rating'] - $b['rating'];
        default:
          return strtotime($b['date']) - strtotime($a['date']);
      }
    });

    // Get total count before pagination
    $totalReviews = count($reviews);

    // Calculate pagination
    $offset = ($page - 1) * $this->perPage;
    $reviews = array_slice($reviews, $offset, $this->perPage);

    return [
      'reviews' => $reviews,
      'total' => $totalReviews,
      'per_page' => $this->perPage,
      'current_page' => $page,
      'last_page' => ceil($totalReviews / $this->perPage)
    ];
  }

  public function getAverageRating($productId)
  {
    $reviews = array_filter($this->reviews, function ($review) use ($productId) {
      return $review['product_id'] == $productId;
    });

    if (empty($reviews)) {
      return 0;
    }

    $sum = array_sum(array_column($reviews, 'rating'));
    return round($sum / count($reviews), 1);
  }

  public function getReviewCount($productId)
  {
    return count(array_filter($this->reviews, function ($review) use ($productId) {
      return $review['product_id'] == $productId;
    }));
  }
}
