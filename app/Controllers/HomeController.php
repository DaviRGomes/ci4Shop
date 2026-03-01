<?php

namespace App\Controllers;

use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index()
    {
        $model    = new ProductModel();
        $products = $model->getByCategory();

        // Agrupar por categoria
        $grouped = [];
        foreach ($products as $p) {
            $grouped[$p['category']][] = $p;
        }

        return view('home/index', [
            'title'           => 'Loja - Produtos',
            'grouped_products' => $grouped,
            'products'        => $products,
        ]);
    }

    public function products()
    {
        $model    = new ProductModel();
        $products = $model->getActive();
        return $this->response->setJSON($products);
    }
}
