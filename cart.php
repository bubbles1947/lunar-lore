<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'][$name])) {
        $_SESSION['cart'][$name] = ["name" => $name, "price" => $price, "quantity" => 1];
    } else {
        $_SESSION['cart'][$name]['quantity'] += 1;
    }
}

header("Location: store.php");
exit();
?>
