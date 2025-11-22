<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white shadow-lg rounded-lg p-8 max-w-3xl w-full text-center">
    <h1 class="text-4xl font-bold text-blue-700 mb-6"> Library Management Dashboard</h1>
    <p class="mb-8 text-gray-600">Welcome! Choose an action below:</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      <a href="add_book.php" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg text-lg"> Add Book</a>
      <a href="add_member.php" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg text-lg"> Add Member</a>
      <a href="issue_book.php" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 rounded-lg text-lg"> Issue Book</a>
      <a href="update_return.php" class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg text-lg"> Update Return</a>
      <a href="delete_book.php" class="bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg text-lg"> Delete Book</a>
      <a href="delete_member.php" class="bg-pink-500 hover:bg-pink-600 text-white py-3 px-4 rounded-lg text-lg"> Delete Member</a>
      <a href="view_books.php" class="bg-indigo-500 hover:bg-indigo-600 text-white py-3 px-4 rounded-lg text-lg"> View Books</a>
      <a href="overdue.php" class="bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg text-lg"> Overdue Books</a>
    </div>
  </div>
</body>
</html>
