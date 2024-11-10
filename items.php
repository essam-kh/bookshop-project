<?php
  session_start();
  // session_destroy();
  $conn = new mysqli("localhost", "root", "", "bookshop");

  // Check connection
  if ($conn->connect_error) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
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
    } else {
      $_SESSION['cart'][$bookId] = $quantity; // Add new book to cart
    }
  }

  $sql = "SELECT * FROM books";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      background-color: #fff2df;
      margin: 0;
      color: white;
      font-family: "Courier New";
    }
    #navigation {
      background-color: #D9AD9C; 
      padding: 10px;
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
    #body {
      background-color: rgba(162, 131, 110, 0.5);
      height: 400px;
      width: 100%;
      margin-top: 30px;
    }
    #welcome-img {
      float: right;
      margin-right: 20px;
      margin-top: 50px;
      width: 400px;
      border-radius: 10px;
    }
    #welcome-caption {
      font-size: 24px;
      text-align: center;
      width: 50%;
      position: absolute;
      bottom: 50%;
    }
    li a {
      text-decoration: none;
      color: white;
    }
    li a:hover {
      text-decoration: underline;
      font-size: 20px;
    }
    h1 {
      color: #D9AD9C;
      margin-left: 10px;
    }
    #books-container {
      display: grid;
      grid-template-rows: 1fr 1fr 1fr;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    .container-items {
      text-align: center;
    }
    .item-container {
      border: 2px solid #A9A198;
      padding: 10px;
      margin-bottom: 15px;
      margin-left: 7.5px;
      margin-right: 7.5px;
      border-radius: 10px;
    }
    .item-container img {
      display: block;
      margin: auto;
      width: 230px;
      height: 300px;
      border-color: black;
      border-style: solid;
      border-radius: 10px;
    }
    .item-container h5 {
      font-family: georgia;
      color: #A9A198;
      text-align: center;
    }
    #pricenum {
      font-family: 'Times New Roman', Times, serif;
      font-size: 14px;
      font-weight: bold;
    }
    input[type="button"] {
      margin: auto;
    }
    .add-to-cart-button {
      padding: 10px 20px;
      background-color: #D9AD9C;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .add-to-cart-button:hover {
      background-color: #A9A198;
    }

    @keyframes cart-bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-10px);
      }
      60% {
        transform: translateY(-5px);
      }
    }

    #cart-icon.animate {
      animation: cart-bounce 1s;
      color: #A9A198;
    }

    #cart-icon {
      transition: color 0.3s;
    }

    #cart-icon.added {
      color: #D9AD9C;
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
          <li><a href="cart.php" target="_self"><i id="cart-icon" class="fas fa-shopping-cart"></i></a></li>
   </ul>
 </div>

  <br><br>
  <hr>
  <h1>ITEMS :</h1>

  <div id="books-container">
    <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<div class=\"container-items\"> 
          <div class=\"item-container\">
            <a href=\"itemdetail.php?id=" . $row["id"] . "\"><img src=\"" . $row["image"] . "\"></a>
            <h5>" . strtoupper($row["name"]) . "</h5>
            <h5><span id=\"pricenum\">" . $row["price"] . "</span> JD</h5>
            <form method=\"post\">
              <button class=\"add-to-cart-button\" type=\"submit\">Add to Cart</button>
              <input type=\"hidden\" name=\"id\" value=\"" . $row["id"] . "\">
              <input type=\"hidden\" name=\"quantity\" value=\"1\">
            </form>
          </div>
        </div>";
      }
    }
    ?>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const addToCartButtons = document.querySelectorAll('.add-to-cart-button');

    addToCartButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        event.preventDefault();

        // Simulate form submission
        const form = button.closest('form');
        const formData = new FormData(form);

        fetch('', { // Assuming current script handles the request
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Add animation class
          cartIcon.classList.add('animate', 'added');
          setTimeout(() => {
            cartIcon.classList.remove('animate');
          }, 1000);
        })
        .catch(error => console.error('Error:', error));
      });
    });
  });
  
  </script>
</body>
</html>
