<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $member_id = $_POST['member_id'];

    $sql = "DELETE FROM members WHERE member_id = '$member_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Member deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting member');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Member</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Delete Member</h2>
    <form method="post" action="delete_member.php">
      <label class="block text-sm font-semibold">Member ID:</label>
      <input type="number" name="member_id" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Delete Member" class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600">
    </form>
  </div>
</body>
</html>
