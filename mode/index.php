<?php 
include_once('../function/connect.php');
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Mode of Study</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Update Student Mode of Study</h2>
    <form method="POST" action="update.php" class="p-4 bg-white rounded shadow-sm">
      <div class="mb-3">
        <label for="matric" class="form-label">Matric Number</label>
        <input type="text" class="form-control" id="matric" name="matric" required>
      </div>
      <div class="mb-3">
        <label for="mode" class="form-label">Select New Mode</label>
        <select class="form-select" name="mode" id="mode" required>
          <option value="1">Full-time</option>
          <option value="2">Part-time</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update Mode</button>
    </form>
  </div>
</body>
</html>
