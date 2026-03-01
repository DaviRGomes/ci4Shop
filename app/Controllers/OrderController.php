<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;

class OrderController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $model  = new OrderModel();
        $orders = $model->getOrdersByUser($userId);

        return view('orders/index', [
            'title'  => 'Meus Pedidos',
            'orders' => $orders,
        ]);
    }

    public function show(int $id)
    {
        $userId = session()->get('user_id');
        $model  = new OrderModel();
        $order  = $model->getOrderWithItems($id, $userId);

        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pedido não encontrado.');
        }

        return view('orders/show', [
            'title' => 'Pedido #' . $id,
            'order' => $order,
        ]);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $json   = $this->request->getJSON(true);
        $items  = $json['items'] ?? null;
        $notes  = $json['notes'] ?? null;

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nenhum item selecionado.']);
        }

        $productModel = new ProductModel();
        $orderModel   = new OrderModel();
        $itemModel    = new OrderItemModel();

        $db = \Config\Database::connect();
        $db->transStart();

        // Calcular total e validar estoque
        $total = 0;
        $itemsToSave = [];

        foreach ($items as $item) {
            $pid = (int)($item['product_id'] ?? 0);
            $qty = (int)($item['quantity'] ?? 0);
            if ($pid <= 0 || $qty <= 0) continue;

            $product = $productModel->find($pid);
            if (!$product || $product['stock'] < $qty) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produto "' . ($product['name'] ?? 'desconhecido') . '" sem estoque suficiente.'
                ]);
            }

            $subtotal      = $product['price'] * $qty;
            $total        += $subtotal;
            $itemsToSave[] = [
                'product'   => $product,
                'quantity'  => $qty,
                'subtotal'  => $subtotal,
            ];
        }

        if (empty($itemsToSave)) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Nenhum item válido.']);
        }

        // Criar pedido
        $orderId = $orderModel->insert([
            'user_id' => $userId,
            'status'  => 'confirmed',
            'total'   => $total,
            'notes'   => $notes,
        ]);

        // Inserir itens e baixar estoque
        foreach ($itemsToSave as $i) {
            $itemModel->insert([
                'order_id'   => $orderId,
                'product_id' => $i['product']['id'],
                'quantity'   => $i['quantity'],
                'unit_price' => $i['product']['price'],
                'subtotal'   => $i['subtotal'],
            ]);
            $productModel->decreaseStock($i['product']['id'], $i['quantity']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao processar pedido.']);
        }

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Pedido realizado com sucesso!',
            'order_id' => $orderId,
        ]);
    }

    public function cancel(int $id)
    {
        $userId       = session()->get('user_id');
        $orderModel   = new OrderModel();
        $productModel = new ProductModel();
        $itemModel    = new OrderItemModel();

        $order = $orderModel->where('id', $id)->where('user_id', $userId)->first();
        if (!$order || $order['status'] === 'cancelled') {
            return redirect()->to('/orders')->with('error', 'Pedido não pode ser cancelado.');
        }

        // Devolver estoque
        $items = $itemModel->where('order_id', $id)->findAll();
        foreach ($items as $item) {
            $productModel->increaseStock($item['product_id'], $item['quantity']);
        }

        $orderModel->update($id, ['status' => 'cancelled']);

        return redirect()->to('/orders/' . $id)->with('success', 'Pedido cancelado com sucesso.');
    }
}
