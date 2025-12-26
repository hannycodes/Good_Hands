document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevron = document.getElementById('chevron-icon');
    const tableBody = document.getElementById('fundsTableBody');
    const searchInput = document.getElementById('fundSearch');
    
    let allDonations = [];

    // --- 1. Sidebar Toggle ---
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            chevron.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }

    // --- 2. Load Funds ---
    async function loadFunds() {
        try {
            const res = await fetch('../php/manage_funds_api.php');
            const data = await res.json();
            
            if (data.success) {
                allDonations = data.donations;
                
                // Update Top Stats
                document.getElementById('totalRevenue').innerText = `ETB ${data.stats.total}`;
                document.getElementById('donationCount').innerText = data.stats.count;
                document.getElementById('avgDonation').innerText = `ETB ${data.stats.avg}`;

                renderTable(allDonations);
            }
        } catch (err) {
            tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Error loading data.</td></tr>`;
        }
    }

    function renderTable(list) {
        if (list.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">No transactions found.</td></tr>`;
            return;
        }

        tableBody.innerHTML = list.map(d => `
            <tr>
                <td><strong>${d.donor_name}</strong></td>
                <td>${d.case_title}</td>
                <td><span class="amount-text">ETB ${parseFloat(d.amount).toLocaleString()}</span></td>
                <td>${new Date(d.donated_at).toLocaleDateString()}</td>
                <td><small style="color:#64748b;">#GH-00${d.id}</small></td>
            </tr>
        `).join('');
    }

    // --- 3. Filter Search ---
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = allDonations.filter(d => 
            d.donor_name.toLowerCase().includes(term) || 
            d.case_title.toLowerCase().includes(term)
        );
        renderTable(filtered);
    });

    loadFunds();
});