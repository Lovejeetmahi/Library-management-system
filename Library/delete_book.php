<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM books WHERE book_id = '$book_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Book deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting book');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Book</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Delete Book</h2>
    <form method="post" action="delete_book.php">
      <label class="block text-sm font-semibold">Book ID:</label>
      <input type="number" name="book_id" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Delete Book" class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600">
    </form>
  </div>
</body>
</html>
