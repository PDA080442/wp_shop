document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-quantity-step]');

    if (!button) {
        return;
    }

    const input = button.parentElement?.querySelector('input[name="quantity"]');

    if (!input) {
        return;
    }

    const step = Number(button.dataset.quantityStep);
    const min = Number(input.min || 1);
    const max = Number(input.max || Number.MAX_SAFE_INTEGER);
    const next = Number(input.value || min) + step;

    input.value = Math.min(max, Math.max(min, next));
});
