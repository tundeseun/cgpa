<?php 
include_once('../function/connect.php');
ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
 ?>
<!DOCTYPE html>
<html>
<head>
  <title>Check Student Course Status</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Student Course Status Checker</h2>
    <form action="fetch.php" method="POST" class="card shadow p-4">
      <div class="mb-3">
        <label for="matric" class="form-label">Enter Matric Number:</label>
        <input type="text" class="form-control" id="matric" name="matric" required>
      </div>
      <button type="submit" class="btn btn-success">Check Details</button>
    </form>
  </div>
</body>
</html>
