<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'stock', 'category', 'image_url', 'active'];
    protected $useTimestamps = true;

    public function getActive()
    {
        return $this->where('active', 1)->where('stock >', 0)->findAll();
    }

    public function getByCategory()
    {
        return $this->where('active', 1)
                    ->where('stock >', 0)
                    ->orderBy('category', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function decreaseStock(int $productId, int $qty): bool
    {
        $product = $this->find($productId);
        if (!$product || $product['stock'] < $qty) return false;
        return $this->update($productId, ['stock' => $product['stock'] - $qty]);
    }

    public function increaseStock(int $productId, int $qty): bool
    {
        $product = $this->find($productId);
        if (!$product) return false;
        return $this->update($productId, ['stock' => $product['stock'] + $qty]);
    }
}
