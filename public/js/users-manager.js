document.addEventListener('DOMContentLoaded', () => {
    // 1. SELECT ELEMENTS
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevron = document.getElementById('chevron-icon');
    const modal = document.getElementById('adminModal');
    const addAdminBtn = document.getElementById('addAdminBtn');
    const closeModal = document.getElementById('closeModal');
    const tableBody = document.getElementById('userTableBody');
    const adminForm = document.getElementById('adminForm');

    // 2. SIDEBAR TOGGLE LOGIC
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            // Rotate chevron
            if (sidebar.classList.contains('collapsed')) {
                chevron.style.transform = 'rotate(180deg)';
            } else {
                chevron.style.transform = 'rotate(0deg)';
            }
        });
    }

    // 3. MODAL LOGIC (OPEN/CLOSE)
    if (addAdminBtn) {
        addAdminBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Close on overlay click
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // 4. BACKEND: LOAD USERS
    async function loadUsers() {
        try {
            const res = await fetch('../php/manage_users_api.php?action=list');
            const data = await res.json();
            
            if (data.success) {
                tableBody.innerHTML = data.users.map(u => `
                    <tr>
                        <td><strong>${u.name}</strong></td>
                        <td>${u.email}</td>
                        <td><span class="role-badge ${u.role === 'admin' ? 'role-admin' : 'role-donor'}">${u.role}</span></td>
                        <td>${new Date(u.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="btn-edit" onclick="toggleRole(${u.id}, '${u.role}')">Toggle Role</button>
                            <button class="btn-delete" onclick="deleteUser(${u.id})">Remove</button>
                        </td>
                    </tr>
                `).join('');
            }
        } catch (err) {
            console.error("User fetch failed", err);
            tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:20px;">Error connecting to database.</td></tr>`;
        }
    }

    // 5. BACKEND: ADD NEW ADMIN
    if (adminForm) {
        adminForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const res = await fetch('../php/manage_users_api.php?action=add_admin', {
                    method: 'POST',
                    body: new FormData(adminForm)
                });
                const result = await res.json();
                if (result.success) {
                    modal.style.display = 'none';
                    adminForm.reset();
                    loadUsers(); // Refresh the table
                } else {
                    alert("Error: " + result.error);
                }
            } catch (err) {
                alert("Failed to connect to server.");
            }
        });
    }

    // 6. GLOBAL FUNCTIONS FOR BUTTONS
    window.toggleRole = async (id, current) => {
        if(confirm(`Change role to ${current === 'admin' ? 'Donor' : 'Admin'}?`)) {
            const res = await fetch(`../php/manage_users_api.php?action=toggle_role&id=${id}&current=${current}`);
            const data = await res.json();
            if(data.success) loadUsers();
        }
    };

    window.deleteUser = async (id) => {
        if(confirm("Permanently delete this user? This cannot be undone.")) {
            const res = await fetch(`../php/manage_users_api.php?action=delete&id=${id}`);
            const data = await res.json();
            if(data.success) loadUsers();
        }
    };

    loadUsers(); // Initial table load
});