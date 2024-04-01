<?php
session_start();  // Start the session
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php");
    exit();
}
$loggedInUserId = $_SESSION['user_id'];
include 'connect.php';
if(isset($_POST['insert-btn'])){
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date-inp']) && isset($_POST['category-select']) && isset($_POST['amount-inp'])) {
    $dateInp =  mysqli_real_escape_string($conn, $_POST['date-inp']);
    $categorySelect = mysqli_real_escape_string($conn, $_POST['category-select']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount-inp']);

    // Insert into the Expense table with the logged-in user's ID
    $sql = "INSERT INTO expenses (user_id,expense_date, expense_category, amount) 
            VALUES ('$loggedInUserId','$dateInp', '$categorySelect', '$amount')";

    if (mysqli_query($conn, $sql)) {
        messageLog("Expense inserted successfully!");
    } else {
        $errMsg = "Error: " . mysqli_error($conn);
        messageLog($errMsg);
    }
}}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   
    
</head>
<body>
<main>
        <h2>Expense Tracker</h2>
        <div class="overlay" id="overlay"></div>
        
        <div class="exp-form" id="exp-form">
        <form id="post-form" method="post">
          <label for="date-inp">Date:</label>
          <input type="date" name="date-inp" id="date-inp">
          <label for="category-select">Category</label>
          <select name="category-select" id="category-select">
            <option value="Food & beverage">Food & beverage</option>
            <option value="transport">Transport</option>
            <option value="utilities">utilities</option>
          </select>
          <label for="amount-inp">Amount:</label>
          <input type="number" name="amount-inp" id="amount-inp">
          <button type="submit" id="insert-update">Dum</button>
        </form>
        <button onclick="closePopup()">Close</button>
        </div>
        <button type="button" id="popup-btn" onclick="insertForm()">Add Expense</button>

        <h3>Expense Log</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Category</th>
              <th scope="col">Amount</th>

              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
         <?php
            $sql1 = "SELECT `expense_id`,`expense_date`, `expense_category`, `amount` FROM `expenses` WHERE user_id = {$_SESSION['user_id']}";
            $result1 = mysqli_query($conn,$sql1);
            $totalAmt = 0;
            if($result1){
                while($row=mysqli_fetch_assoc($result1)){
                  $expId = $row['expense_id'];
                  
                    $date = $row['expense_date'];
                    $category = $row['expense_category'];
                    $amt = $row['amount'];
                    $totalAmt += $amt;
                    echo ' <tr>
                    <th scope="row" id="">'.$date.'</th>
                    <td>'.$category.'</td>
                    <td>'.$amt.'</td>
                    <td><button onclick="openUpdateForm('.$expId.')">Update</button>
                    <button name="del-btn"><a href="delete.php?expenseId='.$expId.'">Delete</a></button>
                    <input type="text" name="id" style="display: none;" value="'.$expId.'">
                    </td>
                            </tr>';

                }
            } 
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th scope="row"></th>
              <td>Total :</td>
              <td id="totalAmt"><?php echo $totalAmt;  ?></td>
            </tr> 
          </tfoot>
        </table>
      </main>

    <?php mysqli_close($conn);?>
    <script>
      let insUpd = document.getElementById("insert-update");
    function openPopup() {
    
    document.getElementById("exp-form").style.display = "block";
    document.getElementById("overlay").style.display = "block";
  }

  function closePopup() {
    document.getElementById("exp-form").style.display = "none";
    document.getElementById("overlay").style.display = "none";
  }
  function insertForm(){
    insUpd.innerText = "Insert";
    insUpd.setAttribute("name", "insert-btn");
    insUpd.setAttribute("onclick", "");
    //insUpd.setAttribute("type", "submit");
    openPopup();
  }
  
  function openUpdateForm(expenseId) {
    
    insUpd.innerText = "Update";
    insUpd.setAttribute("name", "update-btn");
   insUpd.setAttribute("type", "button");
    insUpd.setAttribute(`onclick`, `updateQry(${expenseId})`);

    fetch(`retrieve.php?expenseId=${expenseId}`)
      .then(response => response.json())
      .then(data => {
        
        document.getElementById("date-inp").value = data.expense_date;
        document.getElementById("category-select").value = data.expense_category;
        document.getElementById("amount-inp").value = data.amount;
        
        openPopup();
      })
      .catch(error => console.error('Error fetching expense data:', error));
  }

  function updateQry(expenseId) {
    var dateInp = document.getElementById("date-inp").value;
    var categorySelect = document.getElementById("category-select").value;
    var amountInp = document.getElementById("amount-inp").value;
    var queryString = "update.php?expenseId=" + expenseId +
                      "&dateInp=" + encodeURIComponent(dateInp) +
                      "&categorySelect=" + encodeURIComponent(categorySelect) +
                      "&amountInp=" + encodeURIComponent(amountInp);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Handle the response if needed
            console.log(this.responseText);

            // Reload the page upon successful update
            location.reload();
        }
    };
    xhttp.open("GET", queryString, true);
  xhttp.send();
  
}

    </script>
</body>
</html>
