<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO members (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Member added successfully');</script>";
    } else {
        echo "<script>alert('Error adding member');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Member</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Add Member</h2>
    <form method="post" action="add_member.php">
      <label class="block text-sm font-semibold">Name:</label>
      <input type="text" name="name" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Email:</label>
      <input type="email" name="email" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Phone:</label>
      <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Add Member" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
    </form>
  </div>
</body>
</html>
