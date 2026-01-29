\<body class="bg-slate-50 min-h-screen">
  <?php include 'navbar.php'; ?>
  
  <div class="flex justify-center items-center py-20">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 w-full max-w-md transition-all duration-500 hover:shadow-2xl">
      <h2 class="text-center text-3xl font-extrabold text-gray-900 mb-8">Add New Book</h2>
      <form method="post" action="add_book.php" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Book Title</label>
          <input type="text" name="title" required class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Author Name</label>
          <input type="text" name="author" required class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
        </div>

        <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all duration-300 shadow-lg active:scale-95">
          Save to Library
        </button>
      </form>
    </div>
  </div>
</body>
