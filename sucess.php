<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Success | Good Hands</title>
    <style>
        body { background: #E1E7EF; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: 'Inter', sans-serif; }
        .card { background: white; padding: 50px; border-radius: 30px; text-align: center; max-width: 450px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); width: 90%; }
        .circle { 
            width: 70px; height: 70px; background: #4FD1B5; color: #22262A; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 20px; 
        }
        .receipt { background: #f8fafc; padding: 20px; border-radius: 15px; margin: 25px 0; text-align: left; border: 1px solid #eef2f6; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; }
        .btn-home { display: block; width: 100%; padding: 15px; background: #4FD1B5; color: #22262A; text-decoration: none; border-radius: 12px; font-weight: 800; }
    </style>
</head>
<body>
    <div class="card">
        <div class="circle">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Thank You!</h1>
        <p>Your donation has been confirmed.</p>
        <div class="receipt">
            <div class="row"><span>Transaction ID</span> <strong>#GH-<?php echo $_GET['donation_id'] ?? '000'; ?></strong></div>
            <div class="row"><span>Status</span> <strong style="color: #10b981;">Completed</strong></div>
            <div class="row"><span>Date</span> <strong><?php echo date('M d, Y'); ?></strong></div>
        </div>
        <a href="user-dashboard.html" class="btn-home">Return to Dashboard</a>
    </div>
</body>
</html>