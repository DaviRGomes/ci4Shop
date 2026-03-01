<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CI4Shop') ?> | CI4Shop</title>
    <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
</head>
<body>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('home') ?>">
            <i class="bi bi-shop me-2"></i>CI4Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= (uri_string() === 'home') ? 'active' : '' ?>" href="<?= base_url('home') ?>">
                        <i class="bi bi-grid me-1"></i>Produtos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(uri_string(), 'orders') !== false) ? 'active' : '' ?>" href="<?= base_url('orders') ?>">
                        <i class="bi bi-bag-check me-1"></i>Meus Pedidos
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Cart button -->
                <li class="nav-item me-3">
                    <button class="btn btn-warning btn-sm" id="btnCart" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="bi bi-cart3 me-1"></i>Carrinho
                        <span class="badge bg-danger ms-1" id="cartCount">0</span>
                    </button>
                </li>
                <!-- User info -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <?php if (session()->get('user_type') === 'pj'): ?>
                            <i class="bi bi-building me-1"></i>
                            <?= esc(session()->get('trade_name') ?: session()->get('user_name')) ?>
                            <span class="badge bg-info text-dark ms-1">PJ</span>
                        <?php else: ?>
                            <i class="bi bi-person-circle me-1"></i>
                            <?= esc(session()->get('user_name')) ?>
                            <span class="badge bg-success ms-1">PF</span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                <small><?= esc(session()->get('user_email')) ?></small>
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('profile/password') ?>">
                                <i class="bi bi-key me-2"></i>Trocar Senha
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Main Content -->
<main class="container my-4">
    <?= $this->renderSection('content') ?>
</main>

<!-- Cart Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" style="width: 420px;">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title"><i class="bi bi-cart3 me-2"></i>Carrinho de Compras</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="flex-grow-1 p-3" id="cartItemsList">
            <div class="text-center text-muted py-5" id="emptyCartMsg">
                <i class="bi bi-cart-x fs-1"></i>
                <p class="mt-2">Seu carrinho está vazio</p>
            </div>
        </div>
        <div class="border-top p-3 bg-light" id="cartFooter" style="display:none!important;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold fs-5">Total:</span>
                <span class="fw-bold fs-5 text-primary" id="cartTotal">R$ 0,00</span>
            </div>
            <div class="mb-3">
                <label class="form-label small">Observações (opcional)</label>
                <textarea class="form-control form-control-sm" id="orderNotes" rows="2" placeholder="Ex: entrega rápida..."></textarea>
            </div>
            <button class="btn btn-success w-100 btn-lg" id="btnFinishOrder">
                <i class="bi bi-bag-check me-2"></i>Finalizar Pedido
            </button>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light border-top mt-5 py-3 text-center text-muted small">
    <div class="container">
        CI4Shop &copy; <?= date('Y') ?> — Sistema de Pedidos em CodeIgniter 4
    </div>
</footer>

<!-- Scripts -->
<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('js/jquery-3.7.1.min.js') ?>"></script>
<script>
    const CSRF_NAME  = '<?= csrf_token() ?>';
    const CSRF_HASH  = '<?= csrf_hash() ?>';
    const BASE_URL   = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/cart.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
