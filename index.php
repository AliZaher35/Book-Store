<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="pb-5 pt-5">
      <form method="post" action="insertData.php">
        <button type="submit" class="btn btn-primary" name="insertData">Press to Insert Data IN Database</button>
      </form>

    </div>

    <div>
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="Post">

        <div class="form-group">
          <label for="inputAddress">Customer</label>
          <input type="text" class="form-control" name="costumer" placeholder="Add Name to Search">
        </div>
        <div class="form-group">
          <label for="inputAddress2">Product</label>
          <input type="text" class="form-control" name="product" placeholder="Name Product">
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputCity">Price</label>
            <input type="text" class="form-control" name="price">
          </div>
          <button type="submit" class="btn btn-primary" name="filter">Filter</button>
      </form>
    </div>
    <?PHP
    if (isset($_POST['filter'])) {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Assuming you have received the filter values from the form
        $customerName = $_POST['costumer'];
        $bookName = $_POST['product'];
        $price = $_POST['price'];

        // Start building the SQL query
        $sql = "SELECT sales.*, book.name AS book_name, customers.name AS customer_name , book.price As book_price
FROM sales

        INNER JOIN book ON sales.book_id = book.id
        INNER JOIN customers ON sales.customer_id = customers.id
        WHERE 1=1"; // 1=1 is used as a placeholder to start the query

        // Add filters based on the form inputs
        if (!empty($customerName)) {
          $sql .= " AND customers.name = :customerName";
        }

        if (!empty($bookName)) {
          $sql .= " AND book.name = :bookName";
        }

        if (!empty($price)) {
          $sql .= " AND book.price = :price";
        }

        // Prepare and execute the SQL query
        $stmt = $db->prepare($sql);

        // Bind the parameters for the filters
        if (!empty($customerName)) {
          $stmt->bindParam(':customerName', $customerName);
        }

        if (!empty($bookName)) {
          $stmt->bindParam(':bookName', $bookName);
        }

        if (!empty($price)) {
          $stmt->bindParam(':price', $price);
        }

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    
    ?>
    <div>
      <table class="table">
        <thead class="thead-dark">
          <tr>

            <th scope="col">Customer</th>
            <th scope="col">Book</th>
            <th scope="col">Price</th>
            <th scope="col">Sale Date</th>

          </tr>
        </thead>
        <tbody>
          <?php
           $totalPrice = 0;
          
          //  Display the results
          foreach ($results as $result) {
            // Display the result
            $totalPrice += $result['book_price'];
          ?>

            <tr>

              <td><?php echo $result['customer_name'] ?></td>
              <td><?php echo $result['book_name'] ?></td>
              <td><?php echo $result['book_price'] ?></td>
              <td><?php echo $result['sale_date'] ?></td>
            </tr>
          <?php
          }
        ?>
        <tr>
          <td>Total Price Calculation</td>
          <td><?php echo $totalPrice; ?></td>
        </tr>
        <?php
        }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>