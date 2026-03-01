const Cart = (function ($) {
    'use strict';

    let items = JSON.parse(localStorage.getItem('ci4shop_cart') || '[]');

    function save() {
        localStorage.setItem('ci4shop_cart', JSON.stringify(items));
        render();
    }

    function addItem(id, name, price, qty, stock) {
        id    = parseInt(id);
        qty   = parseInt(qty) || 1;
        const existing = items.find(i => i.id === id);
        if (existing) {
            const newQty = existing.qty + qty;
            existing.qty   = Math.min(newQty, stock);
            existing.stock = stock;
        } else {
            items.push({ id, name, price: parseFloat(price), qty, stock });
        }
        save();
    }

    function removeItem(id) {
        items = items.filter(i => i.id !== parseInt(id));
        save();
    }

    function updateQty(id, qty) {
        id  = parseInt(id);
        qty = parseInt(qty);
        const item = items.find(i => i.id === id);
        if (item) {
            if (qty <= 0) {
                removeItem(id);
            } else {
                item.qty = Math.min(qty, item.stock);
                save();
            }
        }
    }

    function getTotal() {
        return items.reduce((acc, i) => acc + i.price * i.qty, 0);
    }

    function getCount() {
        return items.reduce((acc, i) => acc + i.qty, 0);
    }

    function clear() {
        items = [];
        save();
    }

    function formatCurrency(val) {
        return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }

    function render() {
        const count = getCount();
        const total = getTotal();

        $('#cartCount').text(count);

        if (items.length === 0) {
            $('#emptyCartMsg').show();
            $('#cartFooter').hide();
            $('#cartItemsList').html('<div class="text-center text-muted py-5" id="emptyCartMsg"><i class="bi bi-cart-x fs-1"></i><p class="mt-2">Seu carrinho está vazio</p></div>');
            return;
        }

        $('#emptyCartMsg').hide();
        $('#cartFooter').css('display', 'block');

        let html = '';
        items.forEach(item => {
            html += `
            <div class="d-flex align-items-center gap-3 mb-3 p-2 bg-light rounded-3 cart-item" data-id="${item.id}">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold text-truncate small">${item.name}</div>
                    <div class="text-primary small">${formatCurrency(item.price)}</div>
                </div>
                <div class="input-group input-group-sm" style="width:100px">
                    <button class="btn btn-outline-secondary py-0 cart-qty-minus" type="button">-</button>
                    <input type="number" class="form-control text-center cart-qty-input py-0" value="${item.qty}" min="1" max="${item.stock}" style="font-size:.8rem">
                    <button class="btn btn-outline-secondary py-0 cart-qty-plus" type="button">+</button>
                </div>
                <div class="text-end" style="min-width:70px">
                    <div class="fw-bold small">${formatCurrency(item.price * item.qty)}</div>
                    <button class="btn btn-link btn-sm text-danger p-0 cart-remove" style="font-size:.75rem">remover</button>
                </div>
            </div>`;
        });

        $('#cartItemsList').html(html);
        $('#cartTotal').text(formatCurrency(total));
    }

    $(document).on('click', '.cart-qty-minus', function () {
        const id  = $(this).closest('.cart-item').data('id');
        const item = items.find(i => i.id === parseInt(id));
        if (item) updateQty(id, item.qty - 1);
    });
    $(document).on('click', '.cart-qty-plus', function () {
        const id  = $(this).closest('.cart-item').data('id');
        const item = items.find(i => i.id === parseInt(id));
        if (item) updateQty(id, item.qty + 1);
    });
    $(document).on('change', '.cart-qty-input', function () {
        const id  = $(this).closest('.cart-item').data('id');
        updateQty(id, $(this).val());
    });
    $(document).on('click', '.cart-remove', function () {
        const id = $(this).closest('.cart-item').data('id');
        removeItem(id);
    });

    // Finaliza pedido
    $(document).on('click', '#btnFinishOrder', function () {
        if (items.length === 0) return;

        const btn   = $(this);
        const notes = $('#orderNotes').val();
        const data  = {
            items: items.map(i => ({ product_id: i.id, quantity: i.qty })),
            notes: notes,
        };

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');

        $.ajax({
            url: BASE_URL + 'orders/create',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: { [CSRF_NAME]: CSRF_HASH },
            success: function (res) {
                if (res.success) {
                    Cart.clear();
                    bootstrap.Offcanvas.getInstance(document.getElementById('cartOffcanvas'))?.hide();
                    window.location.href = BASE_URL + 'orders/' + res.order_id;
                } else {
                    alert('Erro: ' + res.message);
                    btn.prop('disabled', false).html('<i class="bi bi-bag-check me-2"></i>Finalizar Pedido');
                }
            },
            error: function () {
                alert('Erro de comunicação com o servidor.');
                btn.prop('disabled', false).html('<i class="bi bi-bag-check me-2"></i>Finalizar Pedido');
            }
        });
    });

    render();

    return { addItem, removeItem, updateQty, clear, getCount, getTotal };

})(jQuery);
