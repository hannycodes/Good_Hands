let currentCases = []; // Global storage

document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('caseTableBody');
    const modal = document.getElementById('caseModal');
    const caseForm = document.getElementById('caseForm');

    // Load function
    async function loadCases() {
        const res = await fetch('../php/manage_cases_api.php?action=list');
        const data = await res.json();
        if(data.success) {
            currentCases = data.cases;
            renderTable();
        }
    }

    function renderTable() {
        tableBody.innerHTML = currentCases.map(c => `
            <tr>
                <td><strong>${c.title}</strong></td>
                <td>ETB ${Number(c.goal_amount).toLocaleString()}</td>
                <td>${c.category}</td>
                <td><span class="status-tag ${c.status}">${c.status}</span></td>
                <td>
                    <button class="btn-edit" onclick="prepareEdit(${c.id})">Edit</button>
                    <button class="btn-delete" onclick="deleteCase(${c.id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    // Prepare Edit
    window.prepareEdit = (id) => {
        const c = currentCases.find(item => item.id == id);
        if(!c) return;
        
        document.getElementById('caseId').value = c.id;
        document.getElementById('title').value = c.title;
        document.getElementById('goal_amount').value = c.goal_amount;
        document.getElementById('category').value = c.category;
        document.getElementById('status').value = c.status;
        document.getElementById('description').value = c.description;
        document.getElementById('video_url').value = c.video_url || "";
        
        document.getElementById('modalTitle').innerText = "Edit Campaign";
        modal.style.display = 'flex';
    };

    // Open for Create
    document.getElementById('addCaseBtn').onclick = () => {
        caseForm.reset();
        document.getElementById('caseId').value = "";
        document.getElementById('modalTitle').innerText = "Create New Campaign";
        modal.style.display = 'flex';
    };

    // Form Submit
    caseForm.onsubmit = async (e) => {
        e.preventDefault();
        const action = document.getElementById('caseId').value ? 'update' : 'create';
        const res = await fetch(`../php/manage_cases_api.php?action=${action}`, {
            method: 'POST',
            body: new FormData(caseForm)
        });
        const data = await res.json();
        if(data.success) {
            modal.style.display = 'none';
            loadCases();
        } else { alert(data.error); }
    };

    // Delete
    window.deleteCase = async (id) => {
        if(confirm("Are you sure?")) {
            const res = await fetch(`../php/manage_cases_api.php?action=delete&id=${id}`);
            const data = await res.json();
            if(data.success) loadCases();
        }
    };

    document.getElementById('closeModal').onclick = () => modal.style.display = 'none';
    document.getElementById('toggleBtn').onclick = () => document.getElementById('sidebar').classList.toggle('collapsed');
    
    loadCases();
});