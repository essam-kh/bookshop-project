<?php 
    session_start();
    $conn = new mysqli("localhost","root","","bookshop");

  // Check connection
  if ($conn->connect_error) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $bookId = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Check if cart session exists
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }

    // Add book to cart
    if (isset($_SESSION['cart'][$bookId])) {
      $_SESSION['cart'][$bookId] += $quantity; // Increment quantity if book already in cart
      if ($_SESSION['cart'][$bookId] == 0) {
        unset($_SESSION['cart'][$bookId]);
      }
    } else {
      $_SESSION['cart'][$bookId] = $quantity; // Add new book to cart
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: "Courier New", Courier, monospace;
            background-color: #fff2df;
            margin: 0;
            color: #333333;
        }
        #navigation {
            background-color: #D9AD9C; 
            padding: 10px;
            position: sticky;
        }
        #logo {
            margin: 0;
            width: 200px;
            vertical-align: middle; 
        }
        #taskbar {
            list-style-type: none; 
            float: right;
            padding: 0;
            vertical-align: middle; 
        }
        #taskbar li {
            display: inline-block;
            margin-right: 10px;
            margin-left: 10px;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        li a {
            text-decoration: none;
            color: white;
        }
        li a:hover {
            text-decoration: underline;
            font-size: 20px;
        }
        .container {
            background-color: #ffffff;
            width: 80%;
            max-width: 1200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            margin: 20px auto;
            overflow: hidden;
        }
        .header {
            background-color: #D9AD9C;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .cart-items {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .item-details {
            display: flex;
            align-items: center;
        }
        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            margin-right: 20px;
        }
        .item-name {
            font-size: 18px;
            color: #333333;
        }
        .item-price {
            font-size: 18px;
            color: #333333;
        }
        .total {
            padding: 20px;
            text-align: left;
            font-size: 20px;
            color: #333333;
            background-color: #f4f4f4;
        }
        .total_price{
            float:right;
            font-size: 20px;
            color: #333333;
            background-color: #f4f4f4;
        }
        .checkout-button {
            background-color: #6fc3df;
            color: white;
            border: none;
            padding: 15px;
            font-size: 18px;
            width: 100%;
            cursor: pointer;
            border-top: 1px solid #e0e0e0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .checkout-button:hover {
            background-color: #5daabc;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .quantity-button {
            background-color: #D9AD9C;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 3px;
            border-radius: 10px;
            width: 35px;
            display: block;
        }
        .quantity-button:hover {
            background-color: #B89B91;
        }
    </style>
</head>
<body>
<div id="navigation">
    <a href="HomePage.html"><img src="LOGO.png" id="logo" alt="Logo"></a>
    <ul id="taskbar">
          <li><a href="aboutus.html">About US </a></li>
          <li><a href="mailto:contact@info.com">Contact US</a></li>
          <li ><a href="items.php" target="_self">Shop</a></li>
          <li><a href="cart.php" target="_self"><i class="fas fa-shopping-cart"></i></a></li>
   </ul>
 </div>
    <div class="container">
        <div class="header">Shopping Cart</div>
        <div class="cart-items">
            <?php
            $sum = 0.0;
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $sum=2.0;
                foreach ($_SESSION['cart'] as $key => $value) { 
                    $sql = "SELECT * FROM books WHERE id=?";
                    $result = $conn->execute_query($sql,[$key]);
                    $data = $result->fetch_assoc();
                    echo "<div class=\"cart-item\">
                        <div class=\"item-details\">
                            <div class=\"quantity-controls\">
                                <form method=\"post\">
                                    <input type=\"submit\" class=\"quantity-button\" value=\"-\">
                                    <input type=\"number\" name=\"id\" value=\"$key\" hidden>
                                    <input type=\"number\" name=\"quantity\" value=\"-1\" hidden>
                                </form>
                                <form method=\"post\">
                                    <input type=\"submit\" class=\"quantity-button\" value=\"+\">
                                    <input type=\"number\" name=\"id\" value=\"$key\" hidden>
                                    <input type=\"number\" name=\"quantity\" value=\"1\" hidden>
                                </form>
                            </div>
                            <img src=\"" . $data["image"] . "\" alt=\"Book image\" class=\"item-image\">
                            <div class=\"item-name\">" . $data["name"] . " x ".$value."</div>
                        </div>
                        <div class=\"item-price\">" . $value*$data["price"] . " JD</div>
                    </div>";
                    $sum+=$value*$data["price"];
                }
            } else {
                echo "No items in shopping cart.";
            }
            ?>
        </div>
        <div class="total">Shipping: <span class="total_price">2 JD</span></div>
        <div class="total">Total: <span class="total_price"><?php echo $sum ?> JD</span></div>
        <a href="checkout.html"><button class="checkout-button">Proceed to Checkout</button></a>
    </div>
</body>
</html>
