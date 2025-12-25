<?php
session_start();
require_once 'php/db.php'; 

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get ID from URL
$case_id = $_GET['case_id'] ?? $_GET['id'] ?? null;

if (!$case_id) {
    header("Location: browse-cases.html");
    exit();
}

// Fetch case details
$stmt = $pdo->prepare("SELECT title FROM cases WHERE id = ?");
$stmt->execute([$case_id]);
$case = $stmt->fetch();
$title = $case ? $case['title'] : "Charity Mission";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate | Good Hands</title>
    <style>
        :root {
            --sidebar-bg: #E1E7EF;
            --text-dark: #22262A;
            --teal: #4FD1B5;
            --bg-main: #F4F7FA;
            --white: #ffffff;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-main); color: var(--text-dark); display: flex; min-height: 100vh; }

        /* --- INTEGRATED SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: sticky;
            top: 0;
            transition: width var(--transition);
            flex-shrink: 0;
            border-right: 1px solid rgba(0,0,0,0.05);
        }
        .sidebar.collapsed { width: 80px; }
        .nav-container { padding: 20px 15px; display: flex; flex-direction: column; height: 100%; }
        
        .sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 40px; height: 40px;
        }
        .logo-text { font-weight: 800; font-size: 1.25rem; white-space: nowrap; transition: opacity var(--transition); }
        .sidebar.collapsed .logo-text { opacity: 0; width: 0; pointer-events: none; }

        .toggle-btn {
            background: white; border: none; padding: 6px; border-radius: 8px;
            cursor: pointer; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .nav-list { list-style: none; flex: 1; }
        .nav-item a {
            display: flex; align-items: center; padding: 12px 15px;
            color: var(--text-dark); text-decoration: none; border-radius: 12px;
            margin-bottom: 8px; transition: 0.2s; font-weight: 600;
        }
        .nav-item.active a { background-color: var(--text-dark); color: var(--sidebar-bg); }
        
        .icon { min-width: 24px; display: flex; justify-content: center; align-items: center; }
        .link-text { margin-left: 15px; transition: opacity var(--transition); white-space: nowrap; }
        .sidebar.collapsed .link-text { opacity: 0; pointer-events: none; }

        /* --- DONATION CARD --- */
        .main-content { flex-grow: 1; padding: 40px; display: flex; justify-content: center; align-items: flex-start; }
        .donation-card { background: white; width: 100%; max-width: 500px; padding: 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
        
        .amount-pills { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 25px 0; }
        .pill { padding: 15px; border: 2px solid #edf2f7; border-radius: 12px; background: none; font-weight: 700; cursor: pointer; transition: 0.2s; color: var(--text-dark); }
        .pill.active { background: var(--text-dark); color: white; border-color: var(--text-dark); }
        
        .custom-input { width: 100%; padding: 15px; border: 2px solid #edf2f7; border-radius: 12px; margin-bottom: 30px; outline: none; font-size: 1rem; text-align: center; }
        
        .form-section { border-top: 1px solid #eee; padding-top: 30px; text-align: left; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-size: 0.8rem; font-weight: 700; margin-bottom: 5px; color: #64748b; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; }
        
        .btn-confirm { width: 100%; padding: 18px; background: var(--teal); color: var(--text-dark); border: none; border-radius: 12px; font-weight: 800; cursor: pointer; font-size: 1.1rem; transition: 0.3s; }
        .btn-confirm:hover { transform: translateY(-2px); opacity: 0.9; }
    </style>
</head>
<body>

    <!-- SIDEBAR WITH SVG ICONS -->
    <aside class="sidebar" id="sidebar">
        <nav class="nav-container">
            <div class="sidebar-header">
                <div class="logo-text">Good Hands</div>
                <button class="toggle-btn" id="toggleBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="chevron-icon"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            </div>

            <ul class="nav-list">
                <li class="nav-item">
                    <a href="user-dashboard.html">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        </span>
                        <span class="link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="browse-cases.html">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <span class="link-text">Browse Cases</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="history.html">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                        </span>
                        <span class="link-text">History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.html">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="link-text">Profile</span>
                    </a>
                </li>
                <li class="nav-item" style="margin-top:auto;">
                    <a href="php/logout.php">
                        <span class="icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </span>
                        <span class="link-text">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="donation-card">
            <h2>Support this Case</h2>
            <p>Target: <strong><?php echo htmlspecialchars($title); ?></strong></p>

            <form action="php/process-donations.php" method="POST">
                <input type="hidden" name="case_id" value="<?php echo $case_id; ?>">
                
                <div class="amount-pills">
                    <button type="button" class="pill" onclick="setAmt(10, this)">$10</button>
                    <button type="button" class="pill active" onclick="setAmt(50, this)">$50</button>
                    <button type="button" class="pill" onclick="setAmt(100, this)">$100</button>
                </div>
                <input type="number" name="amount" id="amtInput" class="custom-input" value="50" required>

                <div class="form-section">
                    <div class="input-group"><label>Cardholder Name</label><input type="text" placeholder="John Doe" required></div>
                    <div class="input-group"><label>Card Number</label><input type="text" placeholder="XXXX XXXX XXXX XXXX" required></div>
                </div>

                <button type="submit" class="btn-confirm">Confirm Payment</button>
                <a href="case-details.html?case_id=<?php echo $case_id; ?>" style="display:block; margin-top:20px; text-decoration:none; color:#64748b; font-size:0.85rem; font-weight:600;">Cancel and Go Back</a>
            </form>
        </div>
    </main>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const chevron = document.getElementById('chevron-icon');
        toggleBtn.onclick = () => {
            sidebar.classList.toggle('collapsed');
            chevron.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
        };

        // Amount Selection
        function setAmt(val, btn) {
            document.getElementById('amtInput').value = val;
            document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
        }
    </script>
</body>
</html>