<?php
include 'connect.php';
if(isset($_GET['expenseId'])){
    $expId = $_GET['expenseId'];
    $deleteQry = "DELETE FROM `expenses` WHERE `expense_id`='$expId'";
    $delResult = mysqli_query($conn,$deleteQry);
    if($delResult){
        messageLog('Expense deleted successfully!!');
       header("Location: main.php");
      exit();
    }
    else{
        $errMsg = "Error: ". mysqli_error($conn);
        messageLog($errMsg);
    }

}
mysqli_close($conn);
?>