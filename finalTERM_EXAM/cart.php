<?php
session_start();

// Initialize cart session if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['name'], $_GET['price'], $_GET['size'])) {
    $product = [
        'name' => $_GET['name'],
        'price' => $_GET['price'],
        'size' => $_GET['size']
    ];
    $_SESSION['cart'][] = $product;
}

// Remove product from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    unset($_SESSION['cart'][$_GET['index']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
}

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <header>
        <h1>Big Brew</h1>
        <nav>
            <ul>
                <li><a href="product.php" id="all-products">All Products</a></li>
                <li><a href="cart.php" id="cart-link">Cart (<?php echo count($_SESSION['cart']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="cart">
            <h2>Shopping Cart</h2>
            <?php if (empty($_SESSION['cart'])): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <div id="cart-items">
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <div>
                            <p><?php echo $item['name']; ?> (<?php echo $item['size']; ?>) - ₱<?php echo number_format($item['price'], 2); ?></p>
                            <a href="cart.php?action=remove&index=<?php echo $index; ?>">Remove</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="total-price">Total: ₱<?php echo number_format($total, 2); ?></p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bigbrew | Wanderpets</p>
    </footer>
</body>
</html>
