<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$statusMap = [
    'pending'   => ['label' => 'Pendente',   'class' => 'bg-warning text-dark', 'icon' => 'bi-clock'],
    'confirmed' => ['label' => 'Confirmado', 'class' => 'bg-success',           'icon' => 'bi-check-circle'],
    'shipped'   => ['label' => 'Enviado',    'class' => 'bg-info',              'icon' => 'bi-truck'],
    'delivered' => ['label' => 'Entregue',   'class' => 'bg-primary',           'icon' => 'bi-bag-check'],
    'cancelled' => ['label' => 'Cancelado',  'class' => 'bg-danger',            'icon' => 'bi-x-circle'],
];
$s = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary', 'icon' => 'bi-question'];
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Início</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('orders') ?>">Meus Pedidos</a></li>
        <li class="breadcrumb-item active">Pedido #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Left: Order Details -->
    <div class="col-lg-8">

        <!-- Order Header Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1">
                            Pedido #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                        </h3>
                        <div class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d/m/Y \à\s H:i', strtotime($order['created_at'])) ?>
                        </div>
                    </div>
                    <span class="badge <?= $s['class'] ?> fs-6 px-3 py-2">
                        <i class="bi <?= $s['icon'] ?> me-2"></i><?= $s['label'] ?>
                    </span>
                </div>

                <?php if ($order['notes']): ?>
                <div class="alert alert-light border mt-3 mb-0">
                    <i class="bi bi-chat-left-text me-2 text-muted"></i>
                    <strong>Observações:</strong> <?= esc($order['notes']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2"></i>Itens do Pedido</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produto</th>
                                <th class="text-center">Qtd.</th>
                                <th class="text-end">Preço Unit.</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold"><?= esc($item['product_name']) ?></div>
                                <small class="text-muted"><?= esc($item['category']) ?></small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border"><?= $item['quantity'] ?>x</span>
                            </td>
                            <td class="text-end text-muted">
                                R$ <?= number_format($item['unit_price'], 2, ',', '.') ?>
                            </td>
                            <td class="text-end pe-4 fw-semibold">
                                R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-4">Total do Pedido:</td>
                                <td class="text-end pe-4 fw-bold fs-5 text-primary">
                                    R$ <?= number_format($order['total'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Right: Summary + Actions -->
    <div class="col-lg-4">

        <!-- Summary Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Resumo</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>R$ <?= number_format($order['total'], 2, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Frete</span>
                    <span class="text-success fw-semibold">Grátis</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span>
                    <span class="text-primary">R$ <?= number_format($order['total'], 2, ',', '.') ?></span>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-box me-1"></i>
                        <?= count($order['items']) ?> tipo(s) de produto
                        · <?= array_sum(array_column($order['items'], 'quantity')) ?> item(ns)
                    </small>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-body d-grid gap-2">
                <a href="<?= base_url('orders') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar aos Pedidos
                </a>
                <a href="<?= base_url('home') ?>" class="btn btn-primary">
                    <i class="bi bi-shop me-2"></i>Novo Pedido
                </a>
                <?php if (!in_array($order['status'], ['cancelled', 'delivered'])): ?>
                <form action="<?= base_url('orders/' . $order['id'] . '/cancel') ?>" method="POST"
                      onsubmit="return confirm('Deseja cancelar este pedido?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-2"></i>Cancelar Pedido
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
