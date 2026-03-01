<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | CI4Shop</title>
    <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #198754 0%, #157347 100%); min-height: 100vh; }
        .auth-card { max-width: 540px; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .type-btn { cursor: pointer; transition: all .2s; border: 2px solid #dee2e6; border-radius: 12px; }
        .type-btn:hover, .type-btn.active { border-color: #0d6efd; background: #e7f0ff; }
        .type-btn.active { box-shadow: 0 0 0 3px rgba(13,110,253,.2); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">

            <div class="text-center text-white mb-4">
                <h1 class="h3 fw-bold"><i class="bi bi-shop me-2"></i>CI4Shop</h1>
                <p class="opacity-75">Crie sua conta</p>
            </div>

            <div class="card auth-card border-0">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h5 fw-bold text-center mb-4">Cadastro</h2>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                                    <li><?= esc($e) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('register') ?>" method="POST" id="registerForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" id="userType" value="pf">

                        <!-- Tipo de pessoa -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tipo de Cadastro</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="type-btn active p-3 text-center" id="btnPF" onclick="setType('pf')">
                                        <i class="bi bi-person-fill fs-2 text-primary"></i>
                                        <div class="fw-semibold mt-1">Pessoa Física</div>
                                        <small class="text-muted">CPF</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="type-btn p-3 text-center" id="btnPJ" onclick="setType('pj')">
                                        <i class="bi bi-building fs-2 text-success"></i>
                                        <div class="fw-semibold mt-1">Pessoa Jurídica</div>
                                        <small class="text-muted">CNPJ</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dados comuns -->
                        <div class="mb-3">
                            <label class="form-label" id="nameLabel">Nome Completo</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                        </div>

                        <!-- Campos PF -->
                        <div id="pfFields">
                            <div class="row g-3 mb-3">
                                <div class="col-7">
                                    <label class="form-label">CPF</label>
                                    <input type="text" name="cpf" id="cpfInput" class="form-control" placeholder="000.000.000-00" value="<?= old('cpf') ?>" maxlength="14">
                                </div>
                                <div class="col-5">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="date" name="birth_date" id="birthDateInput" class="form-control" value="<?= old('birth_date') ?>">
                                    <div class="invalid-feedback" id="birthDateFeedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos PJ -->
                        <div id="pjFields" style="display:none">
                            <div class="mb-3">
                                <label class="form-label">CNPJ</label>
                                <input type="text" name="cnpj" id="cnpjInput" class="form-control" placeholder="00.000.000/0001-00" value="<?= old('cnpj') ?>" maxlength="18">
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-7">
                                    <label class="form-label">Razão Social</label>
                                    <input type="text" name="company_name" class="form-control" value="<?= old('company_name') ?>">
                                </div>
                                <div class="col-5">
                                    <label class="form-label">Nome Fantasia</label>
                                    <input type="text" name="trade_name" class="form-control" value="<?= old('trade_name') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="phone" id="phoneInput" class="form-control" placeholder="(11) 99999-0000" value="<?= old('phone') ?>" maxlength="16">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="bi bi-person-check me-2"></i>Criar Conta
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Já tem conta? </span>
                        <a href="<?= base_url('login') ?>" class="small fw-bold">Fazer login</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
<script>
function setType(type) {
    document.getElementById('userType').value = type;
    if (type === 'pf') {
        document.getElementById('pfFields').style.display = '';
        document.getElementById('pjFields').style.display = 'none';
        document.getElementById('nameLabel').textContent = 'Nome Completo';
        document.getElementById('btnPF').classList.add('active');
        document.getElementById('btnPJ').classList.remove('active');
    } else {
        document.getElementById('pfFields').style.display = 'none';
        document.getElementById('pjFields').style.display = '';
        document.getElementById('nameLabel').textContent = 'Nome do Responsável';
        document.getElementById('btnPJ').classList.add('active');
        document.getElementById('btnPF').classList.remove('active');
    }
}
// Restore type on error
<?php if (old('type') === 'pj'): ?>
setType('pj');
<?php endif; ?>

// Phone mask
(function () {
    const phone = document.getElementById('phoneInput');
    if (!phone) return;
    phone.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 11);
        if (v.length === 0)       this.value = '';
        else if (v.length <= 2)   this.value = '(' + v;
        else if (v.length <= 6)   this.value = '(' + v.slice(0,2) + ') ' + v.slice(2);
        else if (v.length <= 10)  this.value = '(' + v.slice(0,2) + ') ' + v.slice(2,6) + '-' + v.slice(6);
        else                      this.value = '(' + v.slice(0,2) + ') ' + v.slice(2,7) + '-' + v.slice(7);
    });
})();

// CPF mask: 000.000.000-00
(function () {
    const cpf = document.getElementById('cpfInput');
    if (!cpf) return;
    cpf.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 11);
        if (v.length <= 3)       this.value = v;
        else if (v.length <= 6)  this.value = v.slice(0,3) + '.' + v.slice(3);
        else if (v.length <= 9)  this.value = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6);
        else                     this.value = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6,9) + '-' + v.slice(9);
    });
})();

// CNPJ mask: 00.000.000/0001-00
(function () {
    const cnpj = document.getElementById('cnpjInput');
    if (!cnpj) return;
    cnpj.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 14);
        if (v.length <= 2)        this.value = v;
        else if (v.length <= 5)   this.value = v.slice(0,2) + '.' + v.slice(2);
        else if (v.length <= 8)   this.value = v.slice(0,2) + '.' + v.slice(2,5) + '.' + v.slice(5);
        else if (v.length <= 12)  this.value = v.slice(0,2) + '.' + v.slice(2,5) + '.' + v.slice(5,8) + '/' + v.slice(8);
        else                      this.value = v.slice(0,2) + '.' + v.slice(2,5) + '.' + v.slice(5,8) + '/' + v.slice(8,12) + '-' + v.slice(12);
    });
})();

// Birth date validation
(function () {
    const input    = document.getElementById('birthDateInput');
    const feedback = document.getElementById('birthDateFeedback');
    if (!input) return;

    const today = new Date();
    const maxDate = today.toISOString().split('T')[0];
    const minDate = new Date(today.getFullYear() - 120, today.getMonth(), today.getDate())
                        .toISOString().split('T')[0];
    input.setAttribute('max', maxDate);
    input.setAttribute('min', minDate);

    function validate() {
        if (!input.value) { input.classList.remove('is-invalid'); return true; }
        const d    = new Date(input.value + 'T00:00:00');
        const now  = new Date();
        const min  = new Date(now.getFullYear() - 120, now.getMonth(), now.getDate());
        const min1 = new Date(now.getFullYear() - 18,   now.getMonth(), now.getDate());
        let msg = '';
        if (isNaN(d.getTime()))       msg = 'Data inválida.';
        else if (d > now)             msg = 'A data de nascimento não pode ser no futuro.';
        else if (d > min1)            msg = 'Idade mínima: 18 ano.';
        else if (d < min)             msg = 'Data de nascimento inválida (mais de 120 anos).';

        if (msg) {
            input.classList.add('is-invalid');
            feedback.textContent = msg;
            return false;
        }
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        feedback.textContent = '';
        return true;
    }

    input.addEventListener('change', validate);
    input.addEventListener('blur',   validate);

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        if (!validate()) e.preventDefault();
    });
})();
</script>
</body>
</html>
