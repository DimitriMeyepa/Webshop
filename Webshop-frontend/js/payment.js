document.addEventListener('DOMContentLoaded', () => {
  displayCartSummary();
});

function displayCartSummary() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const cartItemsContainer = document.getElementById('cart-items-summary');
  
  if (cart.length === 0) {
    cartItemsContainer.innerHTML = '<p class="text-gray-500 text-center">Votre panier est vide</p>';
    updateCalculations(0);
    return;
  }

  let subtotal = 0;
  let html = '';

  cart.forEach(item => {
    const itemTotal = (item.price || 0) * item.quantity;
    subtotal += itemTotal;

    html += `
      <div class="flex justify-between items-start">
        <div>
          <p class="font-semibold">${item.name}</p>
          <p class="text-sm text-gray-600">Quantité: ${item.quantity}</p>
        </div>
        <p class="font-semibold">${itemTotal} MUR</p>
      </div>
    `;
  });

  cartItemsContainer.innerHTML = html;
  updateCalculations(subtotal);
}

function updateCalculations(subtotal) {
  const shipping = 500;
  const taxRate = 0.15;
  const taxes = Math.round(subtotal * taxRate);
  const total = subtotal + shipping + taxes;

  document.getElementById('subtotal').textContent = subtotal + ' MUR';
  document.getElementById('taxes').textContent = taxes + ' MUR';
  document.getElementById('total-price').textContent = total + ' MUR';
}
