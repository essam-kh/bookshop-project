<?php
    session_start();
    $conn = new mysqli("localhost","root","","bookshop");

    // Check connection
    if ($conn->connect_error) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['cart'])) {
        if (!isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['email']) || !isset($_POST['address']) || !isset($_POST['city']) || !isset($_POST['state']) || !isset($_POST['zip']) || !isset($_POST['payment-method'])) {
            echo('ERROR: One of the values is empty.');
            exit();
        }
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $payment_method = $_POST['payment-method'];
        $ccnum = "";
        $expmonth = "";
        $expyear = "";
        $cvv = "";
        if (empty($fname) || empty($lname) || empty($email) || empty($address) || empty($city) || empty($state) || empty($zip) || empty($payment_method)) {
            echo('ERROR: One of the values is empty.');
            exit();
        }

        if ($payment_method == "visa" && (!isset($_POST['ccnum']) || !isset($_POST['expmonth']) || !isset($_POST['expyear']) || !isset($_POST['cvv'])) && (empty($_POST['ccnum']) || empty($_POST['expmonth']) || empty($_POST['expyear']) || empty($_POST['cvv']))) {
            echo('ERROR: Visa values cannot be empty if payment method is visa.');
            exit();
        } else {
            $ccnum = $_POST['ccnum'];
            $expmonth = $_POST['expmonth'];
            $expyear = $_POST['expyear'];
            $cvv = $_POST['cvv'];
        }
        
        $products = json_encode($_SESSION['cart']);

        $name = $fname . ' ' . $lname;
        $cash = 0;
        if ($payment_method == "visa") $cash = 0;
        else $cash = 1;
        $sql = "INSERT INTO orders (custname,products,email,address,city,state,zipcode,cash) VALUES (?,?,?,?,?,?,?,?)";
        $result = $conn->execute_query($sql,[$name,$products,$email,$address,$city,$state,$zip,$cash]);
        $_SESSION['post'] = array('name'=>$name,'email'=>$email,'address'=>$address.','.$city.','.$state.','.$zip);
        $result = TRUE;
        if ($result == TRUE) {
            header('Location: orderconfirmed.php');
        } else {
            echo "ERROR: Error while inserting order.\n" . $conn->error;
        }
    }
?>