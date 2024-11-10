let cart = [];
const cartCountElement = document.getElementById('cart-count');
const cartItemsElement = document.getElementById('cart-items');
const totalPriceElement = document.getElementById('total');
const categoryLinks = document.querySelectorAll('.category');
const allProductsLink = document.getElementById('all-products');

// Size Selection Modal Elements
const modal = document.getElementById('size-modal');
const closeButton = document.querySelector('.close-button');
const selectedProductElement = document.getElementById('selected-product');
const priceElements = {
    '16oz': 29,
    '22oz': 39
};

// Open modal for size selection (event delegation)
document.getElementById('products').addEventListener('click', (event) => {
    if (event.target.classList.contains('add-to-cart')) {
        const productName = event.target.getAttribute('data-name');

        // Set product details in modal
        selectedProductElement.textContent = productName;
        document.getElementById('price-16oz').textContent = priceElements['16oz'];
        document.getElementById('price-22oz').textContent = priceElements['22oz'];

        // Show modal
        modal.style.display = 'flex';
        modal.dataset.productName = productName;
    }
});

// Close modal when 'x' is clicked
closeButton.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Add event listener for size option buttons
document.querySelectorAll('.size-option').forEach(option => {
    option.addEventListener('click', () => {
        const size = option.getAttribute('data-size');
        const productName = modal.dataset.productName;
        const productPrice = priceElements[size];  // Get price directly from priceElements

        // Add product to cart with selected size
        addToCart(productName, productPrice, size);

        // Hide modal after selection
        modal.style.display = 'none';
    });
});

function addToCart(name, price, size) {
    cart.push({ name, price, size });
    updateCart();  // Call to update the cart UI
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

function updateCart() {
    cartCountElement.textContent = cart.length;
    renderCartItems();
    updateTotalPrice();
}

function renderCartItems() {
    cartItemsElement.innerHTML = '';
    cart.forEach((item, index) => {
        const div = document.createElement('div');
        div.textContent = `${item.name} (${item.size}) - ₱${item.price.toFixed(2)}`;
        
        const removeButton = document.createElement('button');
        removeButton.textContent = 'Remove';
        removeButton.classList.add('remove-from-cart');
        removeButton.addEventListener('click', () => removeFromCart(index));
        
        div.appendChild(removeButton);
        cartItemsElement.appendChild(div);
    });
}

function updateTotalPrice() {
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    totalPriceElement.textContent = total.toFixed(2);
}

// Category filtering
const handleCategoryClick = (link) => {
    return (e) => {
        e.preventDefault();
        const category = link.getAttribute('data-category');

        document.querySelectorAll('.product').forEach(product => {
            product.style.display = category === 'all-products' || product.getAttribute('data-category') === category ? 'block' : 'none';
        });

        document.querySelector('.active')?.classList.remove('active');
        link.classList.add('active');
    };
};

categoryLinks.forEach(link => link.addEventListener('click', handleCategoryClick(link)));

allProductsLink.addEventListener('click', (e) => {
    e.preventDefault();
    document.querySelectorAll('.product').forEach(product => product.style.display = 'block');
    document.querySelector('.active')?.classList.remove('active');
    allProductsLink.classList.add('active');
});
