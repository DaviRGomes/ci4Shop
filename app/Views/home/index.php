<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<?php $isPJ = session()->get('user_type') === 'pj'; ?>
<div class="rounded-4 p-4 mb-4 <?= $isPJ ? 'bg-info' : 'bg-success' ?> bg-gradient text-white shadow">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">
                <?php if ($isPJ): ?>
                    <i class="bi bi-building me-2"></i>Portal Empresarial
                <?php else: ?>
                    <i class="bi bi-shop me-2"></i>Bem-vindo(a) à CI4Shop!
                <?php endif; ?>
            </h2>
            <p class="mb-0 opacity-90">
                <?php if ($isPJ): ?>
                    Faça pedidos para sua empresa. Selecione os produtos e finalize no carrinho.
                <?php else: ?>
                    Escolha seus produtos favoritos e finalize o pedido com facilidade.
                <?php endif; ?>
            </p>
        </div>
        <div class="text-end">
            <div class="badge bg-white <?= $isPJ ? 'text-info' : 'text-success' ?> fs-6 px-3 py-2">
                <?= $isPJ ? '<i class="bi bi-building me-1"></i>Pessoa Jurídica' : '<i class="bi bi-person me-1"></i>Pessoa Física' ?>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar produto...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="categoryFilter" class="form-select">
                    <option value="">Todas as categorias</option>
                    <?php foreach (array_keys($grouped_products) as $cat): ?>
                        <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm flex-fill" id="btnGrid" title="Grade">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm flex-fill" id="btnList" title="Lista">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products -->
<div id="productsContainer">
    <?php foreach ($grouped_products as $category => $products): ?>
    <div class="category-section mb-5" data-category="<?= esc($category) ?>">
        <div class="d-flex align-items-center mb-3">
            <h4 class="fw-bold mb-0 me-3"><?= esc($category) ?></h4>
            <span class="badge bg-secondary"><?= count($products) ?> produto(s)</span>
            <hr class="flex-grow-1 ms-3">
        </div>

        <div class="row g-3 product-grid">
            <?php foreach ($products as $product): ?>
            <div class="col-sm-6 col-md-4 col-xl-3 product-item"
                 data-name="<?= esc(strtolower($product['name'])) ?>"
                 data-category="<?= esc($category) ?>">
                <div class="card h-100 border-0 shadow-sm product-card" data-id="<?= $product['id'] ?>">
                    <!-- Product image placeholder -->
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height:140px; border-radius: 8px 8px 0 0;">
                        <?php
                        $icons = ['Eletrônicos'=>'bi-laptop','Periféricos'=>'bi-keyboard','Armazenamento'=>'bi-hdd','Componentes'=>'bi-cpu','Redes'=>'bi-wifi','Móveis'=>'bi-easel'];
                        $icon  = $icons[$category] ?? 'bi-box';
                        ?>
                        <i class="bi <?= $icon ?> text-primary" style="font-size:3.5rem;opacity:.35;"></i>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold"><?= esc($product['name']) ?></h6>
                        <p class="card-text text-muted small flex-grow-1" style="font-size:.8rem">
                            <?= esc($product['description']) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                            <span class="fw-bold text-primary fs-5">
                                R$ <?= number_format($product['price'], 2, ',', '.') ?>
                            </span>
                            <span class="badge <?= $product['stock'] <= 5 ? 'bg-warning text-dark' : 'bg-light text-secondary' ?>">
                                <i class="bi bi-box me-1"></i><?= $product['stock'] ?> un.
                            </span>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <button class="btn btn-outline-secondary btn-qty-minus" type="button">-</button>
                            <input type="number" class="form-control text-center qty-input"
                                   value="1" min="1" max="<?= $product['stock'] ?>">
                            <button class="btn btn-outline-secondary btn-qty-plus" type="button">+</button>
                        </div>
                        <button class="btn btn-primary btn-sm btn-add-cart"
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= esc($product['name']) ?>"
                                data-price="<?= $product['price'] ?>"
                                data-stock="<?= $product['stock'] ?>">
                            <i class="bi bi-cart-plus me-1"></i>Adicionar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- No results -->
<div id="noResults" class="text-center py-5 d-none">
    <i class="bi bi-search fs-1 text-muted"></i>
    <p class="mt-2 text-muted">Nenhum produto encontrado.</p>
</div>

<!-- Toast notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
    <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle me-2"></i><span id="toastMsg"></span></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    // Quantity +/-
    $(document).on('click', '.btn-qty-minus', function () {
        const input = $(this).siblings('.qty-input');
        const val   = parseInt(input.val()) - 1;
        if (val >= 1) input.val(val);
    });
    $(document).on('click', '.btn-qty-plus', function () {
        const input = $(this).siblings('.qty-input');
        const max   = parseInt(input.attr('max'));
        const val   = parseInt(input.val()) + 1;
        if (val <= max) input.val(val);
    });

    // Add to cart
    $(document).on('click', '.btn-add-cart', function () {
        const id    = $(this).data('id');
        const name  = $(this).data('name');
        const price = parseFloat($(this).data('price'));
        const stock = parseInt($(this).data('stock'));
        const qty   = parseInt($(this).closest('.card-body').find('.qty-input').val()) || 1;
        Cart.addItem(id, name, price, qty, stock);
        showToast(qty + 'x "' + name + '" adicionado ao carrinho!');
    });

    // Search
    $('#searchInput').on('input', filterProducts);
    $('#categoryFilter').on('change', filterProducts);

    function filterProducts() {
        const q   = $('#searchInput').val().toLowerCase();
        const cat = $('#categoryFilter').val();
        let hasResults = false;

        $('.product-item').each(function () {
            const name    = $(this).data('name');
            const itemCat = $(this).data('category');
            const show    = name.includes(q) && (!cat || itemCat === cat);
            $(this).toggleClass('d-none', !show);
            if (show) hasResults = true;
        });

        // Hide empty category sections
        $('.category-section').each(function () {
            const visible = $(this).find('.product-item:not(.d-none)').length > 0;
            $(this).toggleClass('d-none', !visible);
        });

        $('#noResults').toggleClass('d-none', hasResults);
    }

    // View toggle
    $('#btnGrid').on('click', function () {
        $('.product-item').removeClass('col-12').addClass('col-sm-6 col-md-4 col-xl-3');
        $(this).addClass('btn-secondary').removeClass('btn-outline-secondary');
        $('#btnList').addClass('btn-outline-secondary').removeClass('btn-secondary');
    });
    $('#btnList').on('click', function () {
        $('.product-item').addClass('col-12').removeClass('col-sm-6 col-md-4 col-xl-3');
        $(this).addClass('btn-secondary').removeClass('btn-outline-secondary');
        $('#btnGrid').addClass('btn-outline-secondary').removeClass('btn-secondary');
    });

    function showToast(msg) {
        $('#toastMsg').text(msg);
        new bootstrap.Toast(document.getElementById('cartToast'), {delay: 2500}).show();
    }
});
</script>
<?= $this->endSection() ?>
