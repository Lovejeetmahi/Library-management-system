<?php
include 'db_connect.php';

if (isset($_POST['submit'])) {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    $sql = "INSERT INTO issued_books (book_id, member_id, issue_date, return_date) 
            VALUES ('$book_id', '$member_id', '$issue_date', '$return_date')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Book issued successfully');</script>";
    } else {
        echo "<script>alert('Error issuing book');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Issue Book</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-center text-2xl font-bold mb-4">Issue Book</h2>
    <form method="post" action="issue_book.php">
      <label class="block text-sm font-semibold">Book ID:</label>
      <input type="number" name="book_id" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Member ID:</label>
      <input type="number" name="member_id" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Issue Date:</label>
      <input type="date" name="issue_date" required class="w-full px-4 py-2 border rounded-md mb-4">

      <label class="block text-sm font-semibold">Return Date:</label>
      <input type="date" name="return_date" required class="w-full px-4 py-2 border rounded-md mb-4">

      <input type="submit" name="submit" value="Issue Book" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
    </form>
  </div>
</body>
</html>
