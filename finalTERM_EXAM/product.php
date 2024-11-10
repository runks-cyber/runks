<?php
// Add PHP code at the top for dynamic content if needed (e.g., fetching product details from a database).
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <header>
        <h1>Big Brew</h1>
        <nav>
            <ul>
                <li><a href="#products" id="all-products" class="active">All Products</a></li>
                <li><a href="#coffee" class="category" data-category="coffee">Coffee</a></li>
                <li><a href="#milktea" class="category" data-category="milktea">Milk Tea</a></li>
                <li><a href="#fruitt" class="category" data-category="fruitt">Fruit Tea</a></li>
                <li><a href="#praf" class="category" data-category="praf">Praf</a></li>
                <li><a href="cart.php">Cart (<span id="cart-count">0</span>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="products">
            <?php
            // Define products as an array
            $products = [
                // Coffee
                ['name' => 'Vanilla', 'category' => 'coffee', 'img' => 'bigbrew/coffee1.jpg', 'description' => 'Smooth, creamy flavor with subtle sweetness.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Spanish', 'category' => 'coffee', 'img' => 'bigbrew/coffee2.jpg', 'description' => 'Rich coffee with bold, caramel undertones.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Mocha', 'category' => 'coffee', 'img' => 'bigbrew/coffee3.jpg', 'description' => 'Chocolatey blend with robust espresso flavor.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Vietnamese', 'category' => 'coffee', 'img' => 'bigbrew/coffee4.jpg', 'description' => 'Strong, sweet brew with condensed milk.', 'price16' => 29, 'price22' => 39],
            
                // Milk Tea
                ['name' => 'Double Dutch', 'category' => 'milktea', 'img' => 'bigbrew/milktea1.jpg', 'description' => 'A rich blend of chocolate, nuts, and marshmallow.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Dark Choco', 'category' => 'milktea', 'img' => 'bigbrew/milktea2.jpg', 'description' => 'Deep, intense chocolate flavor with a hint of bitterness.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Taro', 'category' => 'milktea', 'img' => 'bigbrew/milktea3.jpg', 'description' => 'Creamy, sweet taro with a subtle earthy taste.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Okinawa', 'category' => 'milktea', 'img' => 'bigbrew/milktea4.jpg', 'description' => 'Brown sugar-infused tea with a smooth, caramel-like sweetness.', 'price16' => 29, 'price22' => 39],
            
                // Fruit Tea
                ['name' => 'Honey Peach', 'category' => 'fruitt', 'img' => 'bigbrew/fruittea1.jpg', 'description' => 'Sweet, floral, and subtly juicy.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Mango', 'category' => 'fruitt', 'img' => 'bigbrew/fruittea2.jpg', 'description' => 'Tropical, rich, and vibrantly sweet.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Passion Fruit', 'category' => 'fruitt', 'img' => 'bigbrew/fruittea3.jpg', 'description' => 'Tart, tangy, and refreshingly exotic.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Kiwi', 'category' => 'fruitt', 'img' => 'bigbrew/fruittea4.jpg', 'description' => 'Zesty, tangy, and subtly sweet.', 'price16' => 29, 'price22' => 39],
            
                // Praf
                ['name' => 'Strawberry', 'category' => 'praf', 'img' => 'bigbrew/praf1.jpg', 'description' => 'A sweet, fruity, rich with refreshing flavor.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Java Chip', 'category' => 'praf', 'img' => 'bigbrew/praf2.jpg', 'description' => 'Rich chocolate with bold coffee crunch.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Cookies & Cream', 'category' => 'praf', 'img' => 'bigbrew/praf3.jpg', 'description' => 'Creamy blend with crunchy cookie pieces.', 'price16' => 29, 'price22' => 39],
                ['name' => 'Avocado', 'category' => 'praf', 'img' => 'bigbrew/praf4.jpg', 'description' => 'Smooth, creamy, and subtly nutty taste.', 'price16' => 29, 'price22' => 39],
            ];
            

            // Loop through products and render each one
            foreach ($products as $product) {
                echo "<div class='product' data-category='{$product['category']}'>";
                echo "<img src='{$product['img']}' alt='{$product['name']}' />";
                echo "<h4>{$product['name']}</h4>";
                echo "<p>{$product['description']}</p>";
                echo "<p class='price'>₱{$product['price16']}.00 - 16oz | ₱{$product['price22']}.00 - 22oz</p>";
                echo "<div class='button-container'>";
                echo "<button class='add-to-cart' data-name='{$product['name']}'>Add to Cart</button>";
                echo "<button class='buy-now' data-name='{$product['name']}'>Buy Now</button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </section>

        <section id="cart">
            <h2>Shopping Cart</h2>
            <div id="cart-items"></div>
            <p class="total-price">Total: ₱<span id="total">0.00</span></p>
        </section>
        
    </main>

    <footer>
        <p>&copy; 2024 Bigbrew | Wanderpets</p>
    </footer>
    
    <!-- Size Selection Modal -->
    <div id="size-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Select Size</h3>
            <p>Please choose a size for <span id="selected-product"></span>:</p>
            <button class="size-option" data-size="16oz">16oz - ₱<span id="price-16oz"></span></button>
            <button class="size-option" data-size="22oz">22oz - ₱<span id="price-22oz"></span></button>
        </div>
    </div>

    <script src="product.js"></script>
</body>
</html>

