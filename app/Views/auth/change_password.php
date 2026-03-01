<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Trocar Senha</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?= base_url('profile/password') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Senha atual</label>
                        <input type="password" name="current_password" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nova senha</label>
                        <input type="password" name="new_password" class="form-control"
                               minlength="6" required placeholder="Mínimo 6 caracteres">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmar nova senha</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Salvar nova senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
