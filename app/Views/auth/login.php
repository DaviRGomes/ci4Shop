<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CI4Shop</title>
    <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); min-height: 100vh; }
        .auth-card { max-width: 460px; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .auth-logo { font-size: 2.5rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">

            <!-- Logo -->
            <div class="text-center text-white mb-4">
                <div class="auth-logo"><i class="bi bi-shop"></i></div>
                <h1 class="h3 fw-bold">CI4Shop</h1>
                <p class="opacity-75">Sistema de Pedidos Online</p>
            </div>

            <!-- Card -->
            <div class="card auth-card border-0">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h5 fw-bold text-center mb-4">Entrar na sua conta</h2>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('login') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control"
                                       value="<?= old('email') ?>" placeholder="seu@email.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePwd">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                        </button>
                    </form>

                    <div class="text-center">
                        <span class="text-muted small">Não tem conta? </span>
                        <a href="<?= base_url('register') ?>" class="small fw-bold">Cadastre-se</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
<script>
document.getElementById('togglePwd').addEventListener('click', function() {
    const input = document.getElementById('passwordInput');
    const icon  = this.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
</body>
</html>
