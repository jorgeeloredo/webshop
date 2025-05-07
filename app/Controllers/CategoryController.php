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
      'title' => 'Toutes nos catégories'
    ]);
  }

  public function show($slug)
  {
    $category = $this->categoryModel->findBySlug($slug);

    if (!$category) {
      $this->view('error/404', [
        'message' => 'Catégorie non trouvée',
        'title' => '404 - Catégorie non trouvée'
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
