document.addEventListener('DOMContentLoaded', () => {
  const cartIcon = document.getElementById('cart-icon');
  const cartSidebar = document.getElementById('cart-sidebar');
  const cartBackdrop = document.getElementById('cart-backdrop');
  const closeCartBtn = document.getElementById('close-cart-btn');

  if (cartIcon) {
    cartIcon.addEventListener('click', (e) => {
      e.stopPropagation();
      openCart();
    });
  }

  if (closeCartBtn) {
    closeCartBtn.addEventListener('click', () => {
      closeCart();
    });
  }

  if (cartBackdrop) {
    cartBackdrop.addEventListener('click', () => {
      closeCart();
    });
  }

  const continueShoppingBtn = document.getElementById('continue-shopping-btn');
  if (continueShoppingBtn) {
    continueShoppingBtn.addEventListener('click', () => {
      closeCart();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && cartSidebar && !cartSidebar.classList.contains('translate-x-full')) {
      closeCart();
    }
  });

  function openCart() {
    if (cartSidebar && cartBackdrop) {
      cartSidebar.classList.remove('translate-x-full');
      cartBackdrop.classList.remove('hidden');
    }
  }

  function closeCart() {
    if (cartSidebar && cartBackdrop) {
      cartSidebar.classList.add('translate-x-full');
      cartBackdrop.classList.add('hidden');
    }
  }

  window.cartFunctions = {
    openCart,
    closeCart,
    updateCartCount: updateCartCount,
    addToCart: addToCart
  };

  function updateCartCount(count) {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
      cartCount.textContent = count;
    }
  }

  function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    const existingProduct = cart.find(item => item.id === product.id);
    
    if (existingProduct) {
      existingProduct.quantity += product.quantity || 1;
    } else {
      cart.push({
        ...product,
        quantity: product.quantity || 1
      });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    
    updateCartDisplay();
  }

  function updateCartDisplay() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    updateCartCount(totalItems);

    if (!cartItems) return;

    if (cart.length === 0) {
      cartItems.innerHTML = `<p class="text-gray-500 text-center">Votre panier est vide</p>`;
      if (cartTotal) cartTotal.textContent = '0 MUR';
      return;
    }

    let html = '';
    let total = 0;

    cart.forEach(item => {
      const itemTotal = (item.price || 0) * item.quantity;
      total += itemTotal;

      html += `
        <div class="flex items-start gap-4 pb-4 border-b">
          <img src="${item.image || 'img/homepage/chemise_en_lin.jpg'}" alt="${item.name}" class="w-20 h-20 object-cover rounded">
          <div class="flex-1">
            <h3 class="font-semibold text-sm">${item.name}</h3>
            ${item.color ? `<p class="text-gray-500 text-xs">Couleur: ${item.color}</p>` : ''}
            ${item.size ? `<p class="text-gray-500 text-xs">Taille: ${item.size}</p>` : ''}
            <p class="text-gray-500 text-xs font-medium">${item.price} MUR</p>
            <div class="mt-2 flex items-center gap-2">
              <button onclick="window.cartFunctions.changeQuantity('${item.id}', -1)" class="w-8 h-8 flex items-center justify-center border rounded text-gray-700 hover:bg-gray-100">-</button>
              <span class="px-3 py-1 border rounded text-sm">${item.quantity}</span>
              <button onclick="window.cartFunctions.changeQuantity('${item.id}', 1)" class="w-8 h-8 flex items-center justify-center border rounded text-gray-700 hover:bg-gray-100">+</button>
            </div>
            <p class="font-semibold text-sm mt-2">${itemTotal} MUR</p>
          </div>
          <div class="flex flex-col gap-1">
            <button onclick="window.cartFunctions.editProduct('${item.productId}', '${item.color}', '${item.size}')" class="text-blue-500 hover:text-blue-700" title="Modifier">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="window.cartFunctions.removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700" title="Supprimer">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      `;
    });

    cartItems.innerHTML = html;
    if (cartTotal) cartTotal.textContent = total + ' MUR';
  }

  window.cartFunctions.changeQuantity = function(productId, delta) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const idx = cart.findIndex(i => i.id === productId);
    if (idx === -1) return;
    cart[idx].quantity = (cart[idx].quantity || 0) + delta;
    if (cart[idx].quantity <= 0) {
      cart.splice(idx, 1);
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
  };

  window.cartFunctions.removeFromCart = function(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
  };

  window.cartFunctions.editProduct = function(productId, color, size) {
    closeCart();
    window.location.href = `display_men.php?ref=${productId}&color=${encodeURIComponent(color)}&size=${encodeURIComponent(size)}&edit=true`;
  };

  updateCartDisplay();
});

