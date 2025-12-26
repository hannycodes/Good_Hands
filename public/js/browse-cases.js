document.addEventListener('DOMContentLoaded', () => {
    const casesGrid = document.getElementById('casesGrid');
    const searchInput = document.getElementById('caseSearch');

    // --- 1. FETCH CASES FROM BACKEND ---
    async function fetchCases() {
        try {
            const response = await fetch('../php/get_cases.php');
            const data = await response.json();

            if (data.success) {
                renderCards(data.cases);
            } else {
                console.error("Server Error:", data.error);
            }
        } catch (err) {
            console.error("Connection error:", err);
            if (casesGrid) casesGrid.innerHTML = "<p>Failed to load cases. Please try again later.</p>";
        }
    }

    // --- 2. RENDER CARDS INTO HTML ---
    function renderCards(cases) {
        if (!casesGrid) return;
        casesGrid.innerHTML = ""; // Clear loader
        
        cases.forEach(c => {
            const raised = parseFloat(c.raised_amount || 0);
            const goal = parseFloat(c.goal_amount);
            const percent = Math.min(Math.round((raised / goal) * 100), 100);
            
            // FIX: Add ../ before image_path so it looks in the root uploads folder
            const imgSrc = c.image_path ? `../${c.image_path}` : 'images/default-case.jpg';

            casesGrid.innerHTML += `
                <div class="case-card" data-title="${c.title.toLowerCase()}">
                    <div class="card-img-wrapper">
                        <img src="${imgSrc}" alt="Case Image" onerror="this.src='https://via.placeholder.com/400x250?text=Good+Hands+Mission'">
                        <span class="category-label" style="text-transform: capitalize;">${c.category}</span>
                    </div>
                    <div class="card-info">
                        <h3>${c.title}</h3>
                        <p>${c.description.substring(0, 85)}...</p>
                        <div class="progress-container">
                            <div class="progress-bar"><div class="fill" style="width: ${percent}%;"></div></div>
                            <div class="stats">
                                <span>ETB ${raised.toLocaleString()} raised</span>
                                <span>${percent}%</span>
                            </div>
                        </div>
                        <a href="case-details.html?case_id=${c.id}"><button class="action-btn">View Details</button></a>
                    </div>
                </div>
            `;
        });
    }

    // --- 3. SEARCH LOGIC ---
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.case-card');
            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                if (title.includes(term)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    fetchCases();
});