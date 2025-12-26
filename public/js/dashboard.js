document.addEventListener('DOMContentLoaded', () => {
    // --- 1. SIDEBAR TOGGLE ---
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevron = document.getElementById('chevron-icon');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (chevron) {
                chevron.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    }

    // --- 2. FETCH DASHBOARD DATA ---
    async function loadDashboard() {
        try {
            const response = await fetch('../php/get_dashboard_data.php');
            const data = await response.json();

            if (data.success) {
                // Safety Check: Only update elements if they exist on the current page
                const userNameEl = document.getElementById('user-name');
                if (userNameEl) userNameEl.innerText = data.user_name;

                const totalDonatedEl = document.getElementById('stat-total-donated');
                if (totalDonatedEl) totalDonatedEl.innerText = `ETB ${data.stats.total}`;

                const livesEl = document.getElementById('stat-lives-impacted');
                if (livesEl) livesEl.innerText = data.stats.lives;

                const activeCasesEl = document.getElementById('stat-active-cases');
                if (activeCasesEl) activeCasesEl.innerText = data.stats.active;

                // Update Table (if on dashboard page)
                const tableBody = document.getElementById('history-table-body');
                if (tableBody) {
                    tableBody.innerHTML = "";
                    if (data.history.length === 0) {
                        tableBody.innerHTML = "<tr><td colspan='4' style='text-align:center'>No recent donations</td></tr>";
                    } else {
                        data.history.forEach(row => {
                            tableBody.innerHTML += `
                                <tr>
                                    <td>${row.title}</td>
                                    <td>${new Date(row.donated_at).toLocaleDateString()}</td>
                                    <td>ETB ${parseFloat(row.amount).toLocaleString()}</td>
                                    <td><span class="badge success">Completed</span></td>
                                </tr>
                            `;
                        });
                    }
                }
            }
        } catch (err) {
            console.error("Dashboard Load Error:", err);
        }
    }

    // Only run the fetch if we are on the dashboard page
    // (Check for a unique dashboard element)
    if (document.getElementById('user-name')) {
        loadDashboard();
    }
});