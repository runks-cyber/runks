// product.js

// Get cart from cookies (or localStorage)
function getCartFromCookies() {
    let cart = JSON.parse(localStorage.getItem('cart'));
    return cart ? cart : [];
}

// Save cart to cookies (or localStorage)
function saveCartToCookies(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Update cart count on page load
function updateCartCount() {
    const cart = getCartFromCookies();
    const cartCount = cart.length;
    document.getElementById('cart-count').innerText = cartCount;
}

// Add product to cart
function addToCart(productName, size) {
    const cart = getCartFromCookies();
    const product = { name: productName, size: size };
    cart.push(product);
    saveCartToCookies(cart);
    updateCartCount();
}

// Show the modal to select size when the user clicks "Buy Now"
function showSizeModal(productName, price16, price22) {
    const modal = document.getElementById('size-modal');
    const selectedProduct = document.getElementById('selected-product');
    const price16oz = document.getElementById('price-16oz');
    const price22oz = document.getElementById('price-22oz');
    
    selectedProduct.textContent = productName;
    price16oz.textContent = price16;
    price22oz.textContent = price22;
    
    // Open the modal
    modal.style.display = 'block';
    
    // Add event listeners for size selection
    const sizeButtons = document.querySelectorAll('.size-option');
    sizeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedSize = button.dataset.size;
            addToCart(productName, selectedSize); // Add to cart with the selected size
            window.location.href = 'checkout.php'; // Redirect to checkout page after selecting size
        });
    });
}

// Event listener for "Buy Now" buttons
document.querySelectorAll('.buy-now').forEach(button => {
    button.addEventListener('click', (e) => {
        const productName = e.target.dataset.name;
        const price16 = e.target.dataset.price16;
        const price22 = e.target.dataset.price22;
        showSizeModal(productName, price16, price22);
    });
});

// Close modal on clicking the close button
document.querySelector('.close-button').addEventListener('click', () => {
    const modal = document.getElementById('size-modal');
    modal.style.display = 'none';
});

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
