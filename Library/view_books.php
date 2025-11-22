<?php
include 'db_connect.php';

// Available books (not issued or returned already)
$available_query = "SELECT * FROM books WHERE book_id NOT IN (SELECT book_id FROM issued_books WHERE actual_return_date IS NULL)";
$available_books = mysqli_query($conn, $available_query);

// Issued books
$issued_query = "SELECT ib.issue_id, b.title, m.name, ib.issue_date, ib.return_date 
                 FROM issued_books ib 
                 JOIN books b ON ib.book_id = b.book_id 
                 JOIN members m ON ib.member_id = m.member_id 
                 WHERE ib.actual_return_date IS NULL";
$issued_books = mysqli_query($conn, $issued_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Books</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <h2 class="text-3xl font-bold mb-6 text-center">Library Book Status</h2>

  <div class="mb-12">
    <h3 class="text-xl font-semibold mb-4 text-green-700">Available Books</h3>
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
      <thead class="bg-green-200 text-gray-800">
        <tr>
          <th class="px-4 py-2">Book ID</th>
          <th class="px-4 py-2">Title</th>
          <th class="px-4 py-2">Author</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($available_books)) { ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?php echo $row['book_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['title']; ?></td>
            <td class="px-4 py-2"><?php echo $row['author']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div>
    <h3 class="text-xl font-semibold mb-4 text-red-700">Issued Books</h3>
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
      <thead class="bg-red-200 text-gray-800">
        <tr>
          <th class="px-4 py-2">Issue ID</th>
          <th class="px-4 py-2">Title</th>
          <th class="px-4 py-2">Issued To</th>
          <th class="px-4 py-2">Issue Date</th>
          <th class="px-4 py-2">Return By</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($issued_books)) { ?>
          <tr class="border-t">
            <td class="px-4 py-2"><?php echo $row['issue_id']; ?></td>
            <td class="px-4 py-2"><?php echo $row['title']; ?></td>
            <td class="px-4 py-2"><?php echo $row['name']; ?></td>
            <td class="px-4 py-2"><?php echo $row['issue_date']; ?></td>
            <td class="px-4 py-2"><?php echo $row['return_date']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
