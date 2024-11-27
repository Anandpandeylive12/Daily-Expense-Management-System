<?php
  include("session.php");
  
  // Fetch and prepare data for charts
  $categories = [];
  $amounts = [];
  $dates = [];
  $dateAmounts = [];

  $exp_category_dc = mysqli_query($con, "SELECT expensecategory FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");
  $exp_amt_dc = mysqli_query($con, "SELECT SUM(expense) as total FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");

  while($row = mysqli_fetch_assoc($exp_category_dc)) {
      $categories[] = $row['expensecategory'];
  }

  while($row = mysqli_fetch_assoc($exp_amt_dc)) {
      $amounts[] = $row['total'];
  }

  $exp_date_line = mysqli_query($con, "SELECT expensedate FROM expenses WHERE user_id = '$userid' GROUP BY expensedate");
  $exp_amt_line = mysqli_query($con, "SELECT SUM(expense) as total FROM expenses WHERE user_id = '$userid' GROUP BY expensedate");

  while($row = mysqli_fetch_assoc($exp_date_line)) {
      $dates[] = $row['expensedate'];
  }

  while($row = mysqli_fetch_assoc($exp_amt_line)) {
      $dateAmounts[] = $row['total'];
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Expense Manager - Dashboard</title>


  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">

  <!-- Feather JS for Icons -->
  <script src="js/feather.min.js"></script>
  <style>
    .card a {
      color: #000;
      font-weight: 500;
    }

    .card a:hover {
      color: #28a745;
      text-decoration: dotted;
    }
  </style>
</head>

<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar and Page Content goes here... -->

    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <!-- Brand -->
      <a class="navbar-brand" href="#">Dashboard</a>

      <!-- Toggler/collapsibe Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/manage_expense.php">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </nav>
      <!-- Dashboard content here... -->

      <h3 class="mt-4">Full-Expense Report</h3>
      <div class="row">
        <div class="col-md">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title text-center">Yearly Expenses</h5>
            </div>
            <div class="card-body">
              <canvas id="expense_line" height="150"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title text-center">Expense Category</h5>
            </div>
            <div class="card-body">
              <canvas id="expense_category_pie" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript -->
  <script src="js/jquery.slim.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  
  <script>
    feather.replace();

    var ctx = document.getElementById('expense_category_pie').getContext('2d');
    var categoryData = {
      labels: <?php echo json_encode($categories); ?>,
      datasets: [{
        label: 'Expense by Category',
        data: <?php echo json_encode($amounts); ?>,
        backgroundColor: [
          '#6f42c1', '#dc3545', '#28a745', '#007bff', '#ffc107', 
          '#20c997', '#17a2b8', '#fd7e14', '#e83e8c', '#6610f2'
        ],
        borderWidth: 1
      }]
    };
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: categoryData
    });

    var lineCtx = document.getElementById('expense_line').getContext('2d');
    var lineData = {
      labels: <?php echo json_encode($dates); ?>,
      datasets: [{
        label: 'Expense by Month (Whole Year)',
        data: <?php echo json_encode($dateAmounts); ?>,
        borderColor: '#adb5bd',
        backgroundColor: '#6f42c1',
        fill: false,
        borderWidth: 2
      }]
    };
    var myLineChart = new Chart(lineCtx, {
      type: 'line',
      data: lineData
    });
  </script>
</body>
</html>
