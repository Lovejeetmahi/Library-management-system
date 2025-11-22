<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $issue_id = $_POST['issue_id'];
    $actual_return_date = $_POST['actual_return_date'];

    $sql = "UPDATE issued_books SET actual_return_date = '$actual_return_date' WHERE issue_id = '$issue_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Return date updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating return date');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Return Date</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Update Return Date</h2>
    <form method="post" action="update_return.php">
      <label class="block text-sm font-semibold">Issue ID:</label>
      <input type="number" name="issue_id" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Actual Return Date:</label>
      <input type="date" name="actual_return_date" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Update Return" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
    </form>
  </div>
</body>
</html>
