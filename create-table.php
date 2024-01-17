<?php
// SQL to create table
$sql = "CREATE TABLE customers (
    id BigInt UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
   
    )";

$sql = "CREATE TABLE book (
    id BigInt UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    des  TEXT NOT NULL,
    price int(50)
    )";

$sql = "CREATE TABLE sales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    book_id INT NOT NULL,
    sale_date VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
)";

    ?>