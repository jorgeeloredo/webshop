<?php
// app/controllers/HomeController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
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
    // Get featured products (for example, products with featured=true)
    $featuredProducts = [];
    $products = $this->productModel->getAll();

    foreach ($products as $product) {
      if (isset($product['featured']) && $product['featured']) {
        $featuredProducts[] = $product;
      }

      // Limit to 8 featured products
      if (count($featuredProducts) >= 8) {
        break;
      }
    }

    // Get all categories
    $categories = $this->categoryModel->getAll();

    $this->view('home/index', [
      'featuredProducts' => $featuredProducts,
      'categories' => $categories,
      'title' => __('general.home')
    ]);
  }
}
