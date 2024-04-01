<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['expenseId'])) {
  $expenseId = $_GET['expenseId'];
  
  // Fetch the existing data from the database based on $expenseId
  // Replace the following lines with your actual database query
  $sql = "SELECT `expense_date`, `expense_category`, `amount` FROM `expenses` WHERE `expense_id` = $expenseId";
  $result = mysqli_query($conn, $sql);
  

  if ($result) {
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
    
  } else {
    echo json_encode(['error' => 'Failed to fetch expense data']);
  }
} else {
  echo json_encode(['error' => 'Invalid request']);
}
?>
