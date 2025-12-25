document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevron = document.getElementById('chevron-icon');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        chevron.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
    });

    // 2. BACKEND CONNECTIVITY LOGIC
    // Replace this URL with your actual API endpoint (e.g., http://localhost:5000/api/admin/stats)
    const API_BASE_URL = "https://api.yourcharity.com"; 

    async function loadAdminDashboard() {
        try {
            // Simulated Data Fetching (Replace with real fetch calls)
            // const statsResponse = await fetch(`${API_BASE_URL}/stats`);
            // const stats = await statsResponse.json();

            // Mocking the result for demonstration
            const mockStats = {
                funds: "$142,500",
                cases: 24,
                users: 1104,
                pending: 3
            };

            // Update DOM with Stats
            document.getElementById('totalFunds').innerText = mockStats.funds;
            document.getElementById('activeCases').innerText = mockStats.cases;
            document.getElementById('totalUsers').innerText = mockStats.users;
            document.getElementById('pendingApprovals').innerText = mockStats.pending;

            loadCampaignTable();
        } catch (err) {
            console.error("Error loading dashboard data:", err);
        }
    }

    async function loadCampaignTable() {
        const tableBody = document.getElementById('campaignBody');
        
        try {
            // Real fetch call would look like this:
            // const response = await fetch(`${API_BASE_URL}/campaigns`);
            // const campaigns = await response.json();

            const mockCampaigns = [
                { name: "Clean Water Initiative", target: "$20k", raised: "$15k", status: "Active" },
                { name: "Education for All", target: "$10k", raised: "$3k", status: "Active" },
                { name: "Emergency Relief", target: "$50k", raised: "$42k", status: "Active" }
            ];

            tableBody.innerHTML = ""; // Clear loader

            mockCampaigns.forEach(campaign => {
                const row = `
                    <tr>
                        <td><strong>${campaign.name}</strong></td>
                        <td>${campaign.target}</td>
                        <td>${campaign.raised}</td>
                        <td><span class="status-tag active">${campaign.status}</span></td>
                        <td>
                            <button class="btn-edit">Edit</button>
                            <button class="btn-edit" style="color: #ef4444;">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

        } catch (err) {
            tableBody.innerHTML = "<tr><td colspan='5'>Failed to load campaigns.</td></tr>";
        }
    }

    // Call on load
    loadAdminDashboard();
});