<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['expenseId'])) {

    $expId = $_GET['expenseId'];
    messageLog("point 3");
    messageLog("expid: $expId");
    $dateInp = isset($_GET['dateInp']) ? $_GET['dateInp'] : '';
    $categorySelect = isset($_GET['categorySelect']) ? $_GET['categorySelect'] : '';
    $amount = isset($_GET['amountInp']) ? $_GET['amountInp'] : '';
    
    
    
    // Insert into the Expense table with the logged-in user's ID
    $sql = "UPDATE `expenses` SET `expense_date`='$dateInp', `expense_category`='$categorySelect', `amount`='$amount' WHERE `expense_id`='$expId'";

  
    if (mysqli_query($conn, $sql)) {
       
        messageLog("Expense updated successfully!");
        
       

        exit();
    } else {
        $errMsg = "Error: " . mysqli_error($conn);
        messageLog($errMsg);
    }
}
    

mysqli_close($conn);
?>