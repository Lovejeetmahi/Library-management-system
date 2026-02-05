<?php
/* --------------------------------------------------------------
   LIBRA-OS: MONGODB ATLAS EDITION
   --------------------------------------------------------------
*/

// Require Composer Autoload (Essential for MongoDB Library)
require __DIR__ . '/../vendor/autoload.php';

// --- 1. MONGODB CONNECTION ---
try {
    // Get URI from Vercel Environment Variables
    $uri = getenv('MONGODB_URI');
    
    // Fallback for local testing (Replace with your Atlas string if testing locally)
    if (!$uri) {
        $uri = "mongodb+srv://<username>:<password>@cluster.mongodb.net/?retryWrites=true&w=majority";
    }

    $client = new MongoDB\Client($uri);
    $db = $client->library_db; // Database name: library_db
    
    // Collections
    $books = $db->books;
    $members = $db->members;
    $issued = $db->issued_books;

} catch (Exception $e) {
    $db_error = "Database Connection Failed: " . $e->getMessage();
}

// --- 2. LOGIC HANDLER ---
$toast_message = ""; 
$toast_type = ""; // success, error

// Helper to generate simple random IDs (simulating SQL auto_increment)
function generateID() {
    return rand(100000, 999999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($client)) {
    
    function set_toast($msg, $type) {
        global $toast_message, $toast_type;
        $toast_message = $msg;
        $toast_type = $type;
    }

    // Add Book
    if (isset($_POST['add_book'])) {
        $doc = [
            'book_id' => generateID(),
            'title' => $_POST['title'],
            'author' => $_POST['author']
        ];
        $result = $books->insertOne($doc);
        $result->getInsertedCount() ? set_toast("Book Protocol Initiated. ID: " . $doc['book_id'], "success") : set_toast("Write Failed.", "error");
    }

    // Add Member
    if (isset($_POST['add_member'])) {
        $doc = [
            'member_id' => generateID(),
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];
        $result = $members->insertOne($doc);
        $result->getInsertedCount() ? set_toast("Identity Created. ID: " . $doc['member_id'], "success") : set_toast("Creation Failed.", "error");
    }

    // Issue Book
    if (isset($_POST['issue_book'])) {
        $doc = [
            'issue_id' => generateID(),
            'book_id' => (int)$_POST['book_id'],
            'member_id' => (int)$_POST['member_id'],
            'issue_date' => $_POST['issue_date'],
            'return_date' => $_POST['return_date'],
            'actual_return_date' => null
        ];
        $result = $issued->insertOne($doc);
        $result->getInsertedCount() ? set_toast("Transfer Complete. TX-ID: " . $doc['issue_id'], "success") : set_toast("Transfer Failed.", "error");
    }

    // Update Return
    if (isset($_POST['update_return'])) {
        $result = $issued->updateOne(
            ['issue_id' => (int)$_POST['issue_id']],
            ['$set' => ['actual_return_date' => $_POST['actual_return_date']]]
        );
        $result->getModifiedCount() ? set_toast("Asset Recovery Confirmed.", "success") : set_toast("Update Failed or ID not found.", "error");
    }

    // Delete Operations
    if (isset($_POST['delete_book'])) {
        $result = $books->deleteOne(['book_id' => (int)$_POST['book_id']]);
        $result->getDeletedCount() ? set_toast("Book Data Purged.", "success") : set_toast("ID Not Found.", "error");
    }
    if (isset($_POST['delete_member'])) {
        $result = $members->deleteOne(['member_id' => (int)$_POST['member_id']]);
        $result->getDeletedCount() ? set_toast("Member Data Purged.", "success") : set_toast("ID Not Found.", "error");
    }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIBRA-OS | Atlas Edition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { neon: { blue: '#00f3ff', purple: '#bd00ff', pink: '#ff0055' } },
                    animation: { 'blob': 'blob 10s infinite', 'float': 'float 6s ease-in-out infinite' },
                    keyframes: {
                        blob: { '0%': { transform: 'translate(0px, 0px) scale(1)' }, '33%': { transform: 'translate(30px, -50px) scale(1.1)' }, '66%': { transform: 'translate(-20px, 20px) scale(0.9)' }, '100%': { transform: 'translate(0px, 0px) scale(1)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .aurora-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #0f172a; z-index: -1; overflow: hidden; }
        .aurora-blob { position: absolute; filter: blur(80px); opacity: 0.6; animation: blob 10s infinite; border-radius: 50%; }
        .blob-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: #4f46e5; }
        .blob-2 { bottom: -10%; right: -10%; width: 500px; height: 500px; background: #ec4899; animation-delay: 2s; }
        .blob-3 { top: 40%; left: 40%; width: 400px; height: 400px; background: #06b6d4; animation-delay: 4s; }
        .holo-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.4s ease; }
        .holo-card:hover { transform: translateY(-5px) scale(1.02); background: rgba(255, 255, 255, 0.08); }
        .input-cyber { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s ease; }
        .input-cyber:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 15px rgba(6, 182, 212, 0.3); background: rgba(0,0,0,0.4); }
        html:not(.dark) .aurora-bg { background: #f0f9ff; }
        html:not(.dark) .holo-card { background: rgba(255,255,255,0.6); color: #1e293b; border: 1px solid rgba(255,255,255,0.8); }
        html:not(.dark) .input-cyber { background: rgba(255,255,255,0.7); border: 1px solid #cbd5e1; color: #0f172a; }
    </style>
</head>
<body class="text-gray-200 min-h-screen selection:bg-neon-blue selection:text-black">

    <div class="aurora-bg"><div class="aurora-blob blob-1"></div><div class="aurora-blob blob-2"></div><div class="aurora-blob blob-3"></div></div>

    <div id="toast-container" class="fixed top-6 right-6 z-[100] transition-all duration-500 translate-x-full opacity-0">
        <?php if($toast_message): ?>
        <div class="flex items-center gap-4 px-6 py-4 rounded-xl holo-card bg-black/40 border-l-4 <?php echo $toast_type == 'success' ? 'border-green-500' : 'border-red-500'; ?> backdrop-blur-md shadow-2xl">
            <div class="text-2xl"><?php echo $toast_type == 'success' ? '✅' : '⚠️'; ?></div>
            <div>
                <h4 class="font-bold text-white"><?php echo $toast_type == 'success' ? 'System Success' : 'System Error'; ?></h4>
                <p class="text-sm text-gray-300"><?php echo $toast_message; ?></p>
            </div>
        </div>
        <script>
            setTimeout(() => { document.getElementById('toast-container').classList.remove('translate-x-full', 'opacity-0'); }, 100);
            setTimeout(() => { document.getElementById('toast-container').classList.add('translate-x-full', 'opacity-0'); }, 4000);
        </script>
        <?php endif; ?>
    </div>

    <nav class="fixed top-0 w-full z-40 border-b border-white/10 bg-black/10 backdrop-blur-lg">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="?page=dashboard" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold shadow-[0_0_15px_rgba(6,182,212,0.5)]">L</div>
                <span class="text-2xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-purple-400">LIBRA<span class="font-light text-white/50">OS</span></span>
            </a>
            <button id="theme-toggle" class="p-3 rounded-full hover:bg-white/10"><i class="ph ph-sun text-2xl"></i></button>
        </div>
    </nav>

    <main class="pt-32 pb-20 px-4 md:px-8 max-w-7xl mx-auto relative z-10">
        <?php if ($page == 'dashboard'): ?>
            <div class="text-center mb-16 animate-float">
                <h1 class="text-5xl md:text-7xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-cyan-200 to-purple-200 drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]">Command Center</h1>
                <p class="text-cyan-200/60 text-lg uppercase tracking-[0.2em] font-light">Atlas Cloud Connected</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $modules = [
                    ['Add Book', 'add_book', 'ph-book-bookmark', 'from-blue-500 to-cyan-500'],
                    ['Add Member', 'add_member', 'ph-user-plus', 'from-emerald-500 to-green-500'],
                    ['Issue Book', 'issue_book', 'ph-export', 'from-amber-400 to-orange-500'],
                    ['Return Book', 'update_return', 'ph-arrow-u-down-left', 'from-violet-500 to-purple-500'],
                    ['Inventory', 'view_books', 'ph-books', 'from-indigo-400 to-blue-600'],
                    ['Overdue', 'overdue', 'ph-warning-circle', 'from-red-500 to-pink-600'],
                    ['Purge Book', 'delete_book', 'ph-trash', 'from-gray-500 to-gray-700'],
                    ['Purge User', 'delete_member', 'ph-user-minus', 'from-gray-500 to-gray-700'],
                ];
                foreach($modules as $m): ?>
                <a href="?page=<?php echo $m[1]; ?>" class="holo-card rounded-2xl p-6 relative group overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br <?php echo $m[3]; ?> opacity-20 blur-2xl group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <i class="ph <?php echo $m[2]; ?> text-5xl mb-4 text-transparent bg-clip-text bg-gradient-to-br <?php echo $m[3]; ?>"></i>
                        <div><h3 class="text-xl font-bold group-hover:text-white"><?php echo $m[0]; ?></h3></div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        <?php elseif (in_array($page, ['add_book', 'add_member', 'issue_book', 'update_return', 'delete_book', 'delete_member'])): ?>
            <?php
                $configs = [
                    'add_book' => ['Add New Asset', 'Enter details', 'ph-book-open', 'bg-blue-600'],
                    'add_member' => ['Register User', 'New identity', 'ph-identification-card', 'bg-emerald-600'],
                    'issue_book' => ['Initialize Transfer', 'Assign asset', 'ph-arrows-left-right', 'bg-amber-600'],
                    'update_return' => ['Process Return', 'Log recovery', 'ph-check-circle', 'bg-violet-600'],
                    'delete_book' => ['Delete Asset', 'Irreversible', 'ph-trash-simple', 'bg-red-600'],
                    'delete_member' => ['Delete User', 'Irreversible', 'ph-user-minus', 'bg-red-600'],
                ];
                $c = $configs[$page];
            ?>
            <div class="max-w-xl mx-auto mt-10">
                <a href="?page=dashboard" class="inline-flex items-center gap-2 text-cyan-400 mb-6 hover:text-cyan-300"><i class="ph ph-arrow-left"></i> Return</a>
                <div class="holo-card p-8 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 <?php echo $c[3]; ?> shadow-[0_0_20px_currentColor]"></div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 rounded-xl <?php echo $c[3]; ?> bg-opacity-20 text-white"><i class="ph <?php echo $c[2]; ?> text-3xl"></i></div>
                        <div><h2 class="text-2xl font-bold text-white"><?php echo $c[0]; ?></h2><p class="text-gray-400 text-sm"><?php echo $c[1]; ?></p></div>
                    </div>
                    <form method="post" action="?page=<?php echo $page; ?>" class="space-y-5">
                        <?php if($page == 'add_book'): ?>
                            <input type="text" name="title" placeholder="Book Title" required class="w-full p-4 rounded-xl input-cyber text-white">
                            <input type="text" name="author" placeholder="Author Name" required class="w-full p-4 rounded-xl input-cyber text-white">
                        <?php elseif($page == 'add_member'): ?>
                            <input type="text" name="name" placeholder="Full Name" required class="w-full p-4 rounded-xl input-cyber text-white">
                            <input type="email" name="email" placeholder="Email Address" required class="w-full p-4 rounded-xl input-cyber text-white">
                            <input type="text" name="phone" placeholder="Phone Number" required class="w-full p-4 rounded-xl input-cyber text-white">
                        <?php elseif($page == 'issue_book'): ?>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="book_id" placeholder="Book ID" required class="w-full p-4 rounded-xl input-cyber text-white">
                                <input type="number" name="member_id" placeholder="Member ID" required class="w-full p-4 rounded-xl input-cyber text-white">
                            </div>
                            <input type="date" name="issue_date" required class="w-full p-4 rounded-xl input-cyber text-white text-gray-400">
                            <input type="date" name="return_date" required class="w-full p-4 rounded-xl input-cyber text-white text-gray-400">
                        <?php elseif($page == 'update_return'): ?>
                            <input type="number" name="issue_id" placeholder="Transaction Issue ID" required class="w-full p-4 rounded-xl input-cyber text-white">
                            <input type="date" name="actual_return_date" required class="w-full p-4 rounded-xl input-cyber text-white text-gray-400">
                        <?php elseif($page == 'delete_book' || $page == 'delete_member'): ?>
                            <input type="number" name="<?php echo $page == 'delete_book' ? 'book_id' : 'member_id'; ?>" placeholder="Target ID" required class="w-full p-4 rounded-xl input-cyber text-white text-center text-xl">
                        <?php endif; ?>
                        <button type="submit" name="<?php echo $page; ?>" class="w-full py-4 mt-4 rounded-xl font-bold text-white uppercase tracking-wider transition-all shadow-lg hover:shadow-[0_0_20px_rgba(255,255,255,0.3)] <?php echo $c[3]; ?> bg-gradient-to-r from-white/10 to-transparent border border-white/20">Execute</button>
                    </form>
                </div>
            </div>

        <?php elseif ($page == 'view_books' || $page == 'overdue'): ?>
             <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400"><?php echo $page == 'view_books' ? 'System Database' : 'Overdue Alerts'; ?></h2>
                <a href="?page=dashboard" class="px-6 py-2 rounded-full holo-card hover:bg-white/10 text-sm font-bold flex items-center gap-2"><i class="ph ph-house"></i> Dashboard</a>
            </div>

            <div class="grid gap-8">
            <?php 
            if ($page == 'view_books'): 
                // MongoDB Aggregation to mimic JOINs
                $avail_cursor = $books->find([], ['sort' => ['book_id' => -1]]);
                
                // Pipeline to get Issued Books with names
                $issued_pipeline = [
                    ['$match' => ['actual_return_date' => null]],
                    ['$lookup' => ['from' => 'books', 'localField' => 'book_id', 'foreignField' => 'book_id', 'as' => 'book_details']],
                    ['$lookup' => ['from' => 'members', 'localField' => 'member_id', 'foreignField' => 'member_id', 'as' => 'member_details']],
                    ['$unwind' => '$book_details'],
                    ['$unwind' => '$member_details']
                ];
                $issued_cursor = $issued->aggregate($issued_pipeline);
            ?>
                <div class="holo-card rounded-2xl overflow-hidden border-t-4 border-green-500">
                    <div class="p-4 bg-green-500/10 backdrop-blur-md flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div><h3 class="font-bold text-green-400">All Registered Books</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs uppercase text-gray-500 bg-white/5"><tr><th class="p-4">ID</th><th class="p-4">Title</th><th class="p-4">Author</th></tr></thead>
                            <tbody class="divide-y divide-white/5 text-sm text-gray-300">
                                <?php foreach($avail_cursor as $doc): ?>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 font-mono text-xs text-gray-500">#<?php echo $doc['book_id']; ?></td>
                                    <td class="p-4 font-semibold text-white"><?php echo $doc['title']; ?></td>
                                    <td class="p-4"><?php echo $doc['author']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="holo-card rounded-2xl overflow-hidden border-t-4 border-blue-500">
                    <div class="p-4 bg-blue-500/10 backdrop-blur-md flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div><h3 class="font-bold text-blue-400">Active Transfers (Issued)</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs uppercase text-gray-500 bg-white/5"><tr><th class="p-4">TX ID</th><th class="p-4">Title</th><th class="p-4">Holder</th><th class="p-4">Return By</th></tr></thead>
                            <tbody class="divide-y divide-white/5 text-sm text-gray-300">
                                <?php foreach($issued_cursor as $doc): ?>
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 font-mono text-xs text-gray-500">TX-<?php echo $doc['issue_id']; ?></td>
                                    <td class="p-4 font-semibold text-white"><?php echo $doc['book_details']['title']; ?></td>
                                    <td class="p-4"><?php echo $doc['member_details']['name']; ?></td>
                                    <td class="p-4 text-blue-400"><?php echo $doc['return_date']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php else: 
                // Overdue Pipeline
                $today = date('Y-m-d');
                $overdue_pipeline = [
                    ['$match' => ['actual_return_date' => null, 'return_date' => ['$lt' => $today]]],
                    ['$lookup' => ['from' => 'books', 'localField' => 'book_id', 'foreignField' => 'book_id', 'as' => 'book_details']],
                    ['$lookup' => ['from' => 'members', 'localField' => 'member_id', 'foreignField' => 'member_id', 'as' => 'member_details']],
                    ['$unwind' => '$book_details'],
                    ['$unwind' => '$member_details']
                ];
                $overdue_cursor = $issued->aggregate($overdue_pipeline);
            ?>
                <div class="holo-card rounded-2xl overflow-hidden border-t-4 border-red-500">
                    <div class="p-4 bg-red-500/10 backdrop-blur-md flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div><h3 class="font-bold text-red-400">Critical Alerts: Overdue</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs uppercase text-gray-500 bg-white/5"><tr><th class="p-4">TX ID</th><th class="p-4">Title</th><th class="p-4">Holder</th><th class="p-4">Due Date</th></tr></thead>
                            <tbody class="divide-y divide-white/5 text-sm text-gray-300">
                                <?php foreach($overdue_cursor as $doc): ?>
                                <tr class="hover:bg-red-500/10 transition-colors">
                                    <td class="p-4 font-mono text-xs text-gray-500">TX-<?php echo $doc['issue_id']; ?></td>
                                    <td class="p-4 font-semibold text-white"><?php echo $doc['book_details']['title']; ?></td>
                                    <td class="p-4"><?php echo $doc['member_details']['name']; ?></td>
                                    <td class="p-4 font-bold text-red-500"><?php echo $doc['return_date']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        const toggleBtn=document.getElementById('theme-toggle'),html=document.documentElement;
        if(localStorage.getItem('theme')==='light'){html.classList.remove('dark');}else{html.classList.add('dark');}
        toggleBtn.addEventListener('click',()=>{html.classList.toggle('dark');localStorage.setItem('theme',html.classList.contains('dark')?'dark':'light');});
    </script>
</body>
</html>