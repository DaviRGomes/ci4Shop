<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'status', 'total', 'notes'];
    protected $useTimestamps = true;

    public function getOrdersByUser(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getOrderWithItems(int $orderId, int $userId)
    {
        $order = $this->where('id', $orderId)->where('user_id', $userId)->first();
        if (!$order) return null;

        $db = \Config\Database::connect();
        $order['items'] = $db->table('order_items oi')
            ->select('oi.*, p.name as product_name, p.category')
            ->join('products p', 'p.id = oi.product_id')
            ->where('oi.order_id', $orderId)
            ->get()->getResultArray();

        return $order;
    }
}
