<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];

    $sql = "INSERT INTO books (title, author) VALUES ('$title', '$author')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Book added successfully');</script>";
    } else {
        echo "<script>alert('Error adding book');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Book</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Add Book</h2>
    <form method="post" action="add_book.php">
      <label class="block text-sm font-semibold">Title:</label>
      <input type="text" name="title" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Author:</label>
      <input type="text" name="author" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Add Book" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
    </form>
  </div>
</body>
</html>
