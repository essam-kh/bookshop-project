<?php
  session_start();
  $conn = new mysqli("localhost","root","","bookshop");

  // Check connection
  if ($conn->connect_error) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
  }
  $id = $_GET['id'];
  $sql = "SELECT * FROM books WHERE id=?";
  $result = $conn->execute_query($sql,[$id]);
  $data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  *{
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

 .item-container {
  text-align: left;
 }
 h1 {
    color: #D9AD9C;
    margin: 20px;
    text-align: center;
  }
  .item-container {
    display: flex;
    align-items: flex-start;
    padding: 20px;
    margin: 20px;
    border: 2px solid #A9A198;
    border-radius: 10px;
    background-color: #efe2ce;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .item-image {
    width: 200px;
    height: auto;
    border-color: black;
    border-style: solid;
    border-radius: 10px;
    margin-right: 20px;
  }
  .item-details {
    flex: 1;
  }
  .item-name {
    font-family: georgia;
    color: #A9A198;
    font-size: 24px;
    margin-bottom: 10px;
  }
  .item-description {
    font-family: georgia;
    color: #A9A198;
    font-size: 16px;
    margin-bottom: 20px;
  }
  .add-to-cart-button {
    padding: 10px 20px;
    background-color: #D9AD9C;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
	float:right;
  }
  .add-to-cart-button:hover {
    background-color: #A9A198;
  }
  #text-paragraph{
  text-align:justify;
  }
   p {
  color:#A9A198;
  font-weight:bold;
  }
  
 
  
  
</style>
</head>

<body>

  <div id="navigation">
    <a href="HomePage.html"><img src="LOGO.png" id="logo" alt="Logo"></a>
    <ul id="taskbar">
          <li><a href="http://www.sample.org/head">About US </a></li>
          <li><a href="mailto:contact@info.com">Contact US</a></li>
          <li ><a href="items.php" target="_self">Shop</a></li>
          <li><a href="cart.php" target="_self"><i class="fas fa-shopping-cart"></i></a></li>
   </ul>
 </div>
 
<br><br>
<hr>
<h1> Item Details </h1>
<div class="item-container">

    <img src="<?php echo $data['image'] ?>" alt="Book Image" class="item-image">
    <div class="item-details">
    <div id="text-paragraph">
      <h5 class="item-name"><?php echo $data['name'] ?></h5>
      <p class="item-description"><?php echo $data['description'] ?></p>
    </div>
    <form method="post" action="items.php">
	  <p>Category:<?php echo $data['category'] ?></p>
	  <p>Type:<?php echo $data['type'] ?></p><br>
	  <p style="color:#808080; font-weight:bold;">Price: <?php echo $data['price'] ?> JD</p>
        <button class="add-to-cart-button">Add to Cart</button>
        <input type="number" name="id" value="<?php echo $data['id'] ?>" hidden>
        <input type="number" name="quantity" value="1" hidden>
      </form>
    </div>
  </div>
    
</div>

</body>
</html>