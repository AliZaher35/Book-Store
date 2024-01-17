<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    header('Location: index.php');
    
    exit;
  }
// Read the JSON file
$jsonData = file_get_contents('data.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_store";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind statements for customer and book insertions
$customerInsert = $conn->prepare("INSERT INTO customers (name) VALUES (?)");
$customerInsert->bind_param("s", $customerName);

$bookInsert = $conn->prepare("INSERT INTO book (name, des, price) VALUES (?, ?, ?)");
$bookInsert->bind_param("ssd", $bookName, $bookDescription, $bookPrice);

// Prepare and bind statement for sales insertion
$salesInsert = $conn->prepare("INSERT INTO sales (customer_id, book_id, sale_date) VALUES (?, ?, ?)");
$salesInsert->bind_param("iis", $customerId, $bookId, $saleData);

// Insert data into the database
foreach ($data as $record) {
    // Check if the combination of customer and book already exists in the sales table

    $c = $record['customer_name'];
    $checkcustomerQuery = "SELECT * FROM customers WHERE name = '$c' ";
    $result = $conn->query($checkcustomerQuery);
    $customer_object = $result->fetch_object();
    if (!isset($customer_object)) {
        // If the combination of customer  is unique, insert the  data
        $customerName = $record['customer_name'];
        $customerInsert->execute();
        $customerId = $conn->insert_id;
    } else {
        $customerId = $customer_object->id;
    }

    $b = $record['book_name'];
    $checkbookQuery = "SELECT * FROM book WHERE name= '$b'";
    $result = $conn->query($checkbookQuery);
    $book_object = $result->fetch_object();

    if (!isset($book_object)) {

        // Insert book data if not already exists
        $bookName = $record['book_name'];
        $bookDescription = $record['description'];
        $bookPrice = $record['price'];
        $bookInsert->execute();
        $bookId = $conn->insert_id;
    } else {
        $bookId = $book_object->id;
    }
    // Insert sales data if the combination of customer and book is unique
    if (isset($book_object->id) && isset($customer_object->id)) {
        $book_id = $book_object->id;
        $customer_id = $customer_object->id;
        $checksaleQuery = "SELECT * FROM sales 
        WHERE customer_id= '$customer_id' AND book_id ='$book_id'";

        $result = $conn->query($checksaleQuery);
        $sale_object = $result->fetch_object();
    }
    if (!isset($sale_object)) {
        $saleData = $record['sale_data'];
        $salesInsert->execute();
    }
}

// Close the prepared statements
$customerInsert->close();
$bookInsert->close();
$salesInsert->close();

// Close the database connection
$conn->close();
header('location: index.php');
