document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevron = document.getElementById('chevron-icon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            chevron.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }

    // 2. Fetch Admin Data
    async function loadAdminHub() {
        const tableBody = document.getElementById('campaignBody');
        
        try {
            const response = await fetch('../php/get_admin_dashboard.php');
            const data = await response.json();

            if (data.success) {
                // Update Stat Cards
                document.getElementById('totalFunds').innerText = `ETB ${data.stats.funds}`;
                document.getElementById('activeCases').innerText = data.stats.active;
                document.getElementById('totalUsers').innerText = data.stats.users;
                document.getElementById('pendingApprovals').innerText = data.stats.pending;

                // Update Campaign Table
                tableBody.innerHTML = ""; 
                data.campaigns.forEach(c => {
                    const statusClass = c.status === 'active' ? 'active' : '';
                    tableBody.innerHTML += `
                        <tr>
                            <td>
                                <div style="font-weight:700;">${c.title}</div>
                                <div style="font-size:0.75rem; color:#64748b;">${c.category}</div>
                            </td>
                            <td>ETB ${Number(c.goal_amount).toLocaleString()}</td>
                            <td>ETB ${Number(c.raised).toLocaleString()}</td>
                            <td><span class="status-tag ${statusClass}">${c.status}</span></td>
                            <td>
                                <button class="btn-edit" onclick="editCase(${c.id})">Edit</button>
                                <button class="btn-edit" style="color: #ef4444;" onclick="deleteCase(${c.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                alert("Error: " + data.error);
                if(data.error === 'Unauthorized Access') window.location.href = 'login.html';
            }
        } catch (err) {
            console.error("Admin Load Error:", err);
            tableBody.innerHTML = "<tr><td colspan='5'>Connection failed.</td></tr>";
        }
    }

    loadAdminHub();
});

// Placeholders for future management actions
function editCase(id) { console.log("Editing case:", id); }
function deleteCase(id) { if(confirm("Are you sure?")) console.log("Deleting case:", id); }