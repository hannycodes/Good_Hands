<?php
session_start();
$d_id = $_GET['donation_id'] ?? "0000";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You | Good Hands</title>
    <style>
        :root { --sidebar-bg: #E1E7EF; --text-dark: #22262A; --teal: #4FD1B5; }
        body { background: var(--sidebar-bg); display: flex; min-height: 100vh; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .card { background: white; padding: 50px; border-radius: 30px; text-align: center; max-width: 450px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .check { width: 75px; height: 75px; background: var(--teal); color: var(--text-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; }
        .receipt { background: #f8fafc; padding: 20px; border-radius: 15px; margin: 30px 0; text-align: left; border: 1px solid #eef2f6; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; }
        .btn-home { display: block; width: 100%; padding: 16px; background: var(--teal); color: var(--text-dark); text-decoration: none; border-radius: 12px; font-weight: 800; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="check">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Donation Successful!</h1>
        <p style="color: #64748b; margin-top: 10px;">Your contribution helps change lives.</p>
        
        <div class="receipt">
            <div class="row"><span>Transaction ID</span> <strong>#GH-<?php echo htmlspecialchars($d_id); ?></strong></div>
            <div class="row"><span>Date</span> <strong><?php echo date('M d, Y'); ?></strong></div>
            <div class="row"><span>Status</span> <strong style="color: #10b981;">Verified ✓</strong></div>
        </div>

        <button onclick="window.print()" style="width:100%; padding:15px; background:#22262A; color:white; border-radius:12px; border:none; cursor:pointer; font-weight:bold; margin-bottom:10px;">Print Receipt</button>
        <a href="user-dashboard.html" class="btn-home">Return to Dashboard</a>
    </div>
</body>
</html>