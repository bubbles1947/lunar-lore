<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $name = $_POST['name'];
        unset($_SESSION['cart'][$name]);
    }
}

$total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="viewcart.css">
</head>
<body>
    <div class="cart-container">
        <h1>Your Cart</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <img src="productImages/<?= strtolower(str_replace(' ', '_', $item['name'])); ?>.jpg" alt="<?= $item['name'] ?>">
                        <div class="item-details">
                            <h3><?= $item['name'] ?></h3>
                            <p class="price">Price: $<?= $item['price'] ?> x <?= $item['quantity'] ?></p>
                            <p class="subtotal">Subtotal: $<?= $item['price'] * $item['quantity'] ?></p>
                            <form method="post">
                                <input type="hidden" name="name" value="<?= $item['name']; ?>">
                                <button type="submit" name="remove" class="remove-button">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                <p><strong>Total: $<?= $total ?></strong></p>
                <form action="checkoutCART.php" method="post">
                    <button type="submit" class="checkout-button">Buy Now</button>
                </form>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
