<?php
include 'db_connect.php';

$today = date('Y-m-d');
$query = "SELECT ib.issue_id, b.title, m.name, ib.issue_date, ib.return_date 
          FROM issued_books ib 
          JOIN books b ON ib.book_id = b.book_id 
          JOIN members m ON ib.member_id = m.member_id 
          WHERE ib.actual_return_date IS NULL AND ib.return_date < '$today'";
$overdue_books = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Overdue Books</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 p-8">
  <h2 class="text-3xl font-bold mb-6 text-center text-red-700">Overdue Books</h2>

  <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-red-300 text-gray-800">
      <tr>
        <th class="px-4 py-2">Issue ID</th>
        <th class="px-4 py-2">Book Title</th>
        <th class="px-4 py-2">Issued To</th>
        <th class="px-4 py-2">Issue Date</th>
        <th class="px-4 py-2">Return By</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($overdue_books)) { ?>
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
</body>
</html>
