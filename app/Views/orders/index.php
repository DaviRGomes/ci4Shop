<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-bag-check me-2 text-primary"></i>Resumo dos Pedidos</h2>
        <p class="text-muted mb-0">Histórico de todos os seus pedidos</p>
    </div>
    <a href="<?= base_url('home') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Novo Pedido
    </a>
</div>

<?php if (empty($orders)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Você ainda não fez nenhum pedido</h5>
            <a href="<?= base_url('home') ?>" class="btn btn-primary mt-3">
                <i class="bi bi-shop me-2"></i>Ver Produtos
            </a>
        </div>
    </div>
<?php else: ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <?php
    $statusCount  = array_count_values(array_column($orders, 'status'));
    $totalSpent   = array_sum(array_column($orders, 'total'));
    $confirmed    = $statusCount['confirmed'] ?? 0;
    $cancelled    = $statusCount['cancelled'] ?? 0;
    ?>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary"><?= count($orders) ?></div>
                <div class="small text-muted">Total de Pedidos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success"><?= $confirmed ?></div>
                <div class="small text-muted">Confirmados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-danger"><?= $cancelled ?></div>
                <div class="small text-muted">Cancelados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-5 fw-bold text-dark">R$ <?= number_format($totalSpent, 2, ',', '.') ?></div>
                <div class="small text-muted">Total Gasto</div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" id="orderSearch" class="form-control form-control-sm" placeholder="Buscar pedido...">
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">Todos os status</option>
                    <option value="pending">Pendente</option>
                    <option value="confirmed">Confirmado</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregue</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="ordersTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Pedido</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-end pe-4">Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                <?php
                    $statusMap = [
                        'pending'   => ['label' => 'Pendente',   'class' => 'bg-warning text-dark'],
                        'confirmed' => ['label' => 'Confirmado', 'class' => 'bg-success'],
                        'shipped'   => ['label' => 'Enviado',    'class' => 'bg-info'],
                        'delivered' => ['label' => 'Entregue',   'class' => 'bg-primary'],
                        'cancelled' => ['label' => 'Cancelado',  'class' => 'bg-danger'],
                    ];
                    $s = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary'];
                ?>
                <tr data-status="<?= $order['status'] ?>">
                    <td class="ps-4">
                        <span class="fw-bold">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span>
                        <?php if ($order['notes']): ?>
                            <i class="bi bi-chat-left-text ms-1 text-muted" title="<?= esc($order['notes']) ?>"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div><?= date('d/m/Y', strtotime($order['created_at'])) ?></div>
                        <small class="text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></small>
                    </td>
                    <td>
                        <span class="badge <?= $s['class'] ?>"><?= $s['label'] ?></span>
                    </td>
                    <td class="text-end fw-semibold">
                        R$ <?= number_format($order['total'], 2, ',', '.') ?>
                    </td>
                    <td class="text-end pe-4">
                        <a href="<?= base_url('orders/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Ver
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    function filterOrders() {
        const q      = $('#orderSearch').val().toLowerCase();
        const status = $('#statusFilter').val();
        $('#ordersTable tbody tr').each(function () {
            const text     = $(this).text().toLowerCase();
            const rowStatus = $(this).data('status');
            $(this).toggle(text.includes(q) && (!status || rowStatus === status));
        });
    }
    $('#orderSearch, #statusFilter').on('input change', filterOrders);
});
</script>
<?= $this->endSection() ?>
