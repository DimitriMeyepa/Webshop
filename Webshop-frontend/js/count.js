class Count {
    constructor(priceId, quantityId, btnIncreaseId, btnDecreaseId) {
        this.priceElement = document.getElementById(priceId);
        this.quantityElement = document.getElementById(quantityId);
        this.btnIncrease = document.getElementById(btnIncreaseId);
        this.btnDecrease = document.getElementById(btnDecreaseId);

        this.unitPrice = parseFloat(this.priceElement.dataset.unitPrice); // prix unitaire
        this.quantity = parseInt(this.quantityElement.textContent);

        this.initEvents();
        this.updatePrice();
    }

    initEvents() {
        this.btnIncrease.addEventListener('click', () => {
            this.quantity++;
            this.updateQuantity();
            this.updatePrice();
        });

        this.btnDecrease.addEventListener('click', () => {
            if (this.quantity > 1) {
                this.quantity--;
                this.updateQuantity();
                this.updatePrice();
            }
        });
    }

    updateQuantity() {
        this.quantityElement.textContent = this.quantity;
    }

    updatePrice() {
        const total = this.unitPrice * this.quantity;
        this.priceElement.textContent = total.toLocaleString('fr-MU') + ' MUR';
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    const productCount = new Count('product-price', 'quantity', 'increase', 'decrease');
});
