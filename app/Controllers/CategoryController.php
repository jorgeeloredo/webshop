<?php
// app/controllers/CategoryController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
  private Category $categoryModel;
  private Product $productModel;

  public function __construct()
  {
    $this->categoryModel = new Category();
    $this->productModel = new Product();
  }

  public function index()
  {
    $categories = $this->categoryModel->getAll();

    $this->view('category/index', [
      'categories' => $categories,
      'title' => __('general.all_categories')
    ]);
  }

  public function show($slug)
  {
    $category = $this->categoryModel->findBySlug($slug);

    if (!$category) {
      $this->view('error/404', [
        'message' => __('error.category_not_found'),
        'title' => '404 - ' . __('error.category_not_found')
      ]);
      return;
    }

    $products = $this->productModel->getByCategory($category['id']);

    $this->view('product/list', [
      'category' => $category,
      'products' => $products,
      'title' => $category['name']
    ]);
  }
}
