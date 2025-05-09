<?php
// app/controllers/ProductController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
  private Product $productModel;
  private Category $categoryModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
  }

  public function index()
  {
    $products = $this->productModel->getAll();
    $categories = $this->categoryModel->getAll();

    // Check if a category filter is applied
    $categorySlug = isset($_GET['category']) ? $_GET['category'] : null;

    if ($categorySlug) {
      $category = $this->categoryModel->findBySlug($categorySlug);

      if ($category) {
        $products = $this->productModel->getByCategory($category['id']);
      } else {
        // Category not found, show 404
        $this->view('error/404', [
          'message' => __('error.category_not_found'),
          'title' => '404 - ' . __('error.category_not_found')
        ]);
        return;
      }
    }

    $this->view('product/list', [
      'products' => $products,
      'categories' => $categories,
      'category' => $category ?? null,
      'title' => isset($category) ? $category['name'] : __('listing.all_products')
    ]);
  }

  public function show($slug)
  {
    $product = $this->productModel->findBySlug($slug);

    if (!$product) {
      $this->view('error/404', [
        'message' => __('error.product_not_found'),
        'title' => '404 - ' . __('error.product_not_found')
      ]);
      return;
    }

    // Get query parameters for filtering, sorting, and pagination
    $page = isset($_GET['review_page']) ? max(1, intval($_GET['review_page'])) : 1;
    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

    // Load reviews for this product
    $reviewModel = new \App\Models\Review();
    $reviewData = $reviewModel->getByProductId($product['id'], $page, $filter, $sort);
    $averageRating = $reviewModel->getAverageRating($product['id']);
    $reviewCount = $reviewModel->getReviewCount($product['id']);

    // Get related products
    $relatedProducts = [];
    if (isset($product['category_id'])) {
      $categoryProducts = $this->productModel->getByCategory($product['category_id']);

      // Filter out the current product and limit to 4 products
      $count = 0;
      foreach ($categoryProducts as $categoryProduct) {
        if ($categoryProduct['id'] != $product['id']) {
          $relatedProducts[] = $categoryProduct;
          $count++;

          if ($count >= 4) {
            break;
          }
        }
      }
    }

    // Get category name
    $category = null;
    if (isset($product['category_id'])) {
      $category = $this->categoryModel->find($product['category_id']);
    }

    // SEO meta data
    $metaDescription = strip_tags($product['description']);
    if (strlen($metaDescription) > 160) {
      $metaDescription = substr($metaDescription, 0, 157) . '...';
    }

    // Create meta keywords from product features
    $metaKeywords = 'Singer, ' . $product['name'];
    if (isset($product['features']) && !empty($product['features'])) {
      $featureKeywords = array_slice($product['features'], 0, 5);
      $metaKeywords .= ', ' . implode(', ', $featureKeywords);
    }

    // Set canonical URL
    $canonicalUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') .
      $_SERVER['HTTP_HOST'] . '/product/' . $product['slug'];

    // Set Open Graph image
    $ogImage = isset($product['images'][0]) ?
      (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') .
      $_SERVER['HTTP_HOST'] . '/assets/images/products/' . $product['images'][0] : '';

    $this->view('product/index', [
      'product' => $product,
      'relatedProducts' => $relatedProducts,
      'category' => $category,
      'title' => $product['name'],
      'metaDescription' => $metaDescription,
      'metaKeywords' => $metaKeywords,
      'canonicalUrl' => $canonicalUrl,
      'ogImage' => $ogImage,
      'reviewData' => $reviewData,
      'averageRating' => $averageRating,
      'reviewCount' => $reviewCount,
      'currentFilter' => $filter,
      'currentSort' => $sort
    ]);
  }
}
