document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('caseModal');
    const closeBtn = document.querySelector('.close-modal');

    // Function to Open Modal
    window.openCaseDetails = (caseData) => {
        // 1. Fill in the text and images
        document.getElementById('modalTitle').innerText = caseData.title;
        document.getElementById('modalDesc').innerText = caseData.description;
        document.getElementById('modalImg').src = caseData.image;
        document.getElementById('modalCategory').innerText = caseData.category;
        
        const percent = Math.round((caseData.raised / caseData.goal) * 100);
        document.getElementById('modalRaised').innerText = `$${caseData.raised} raised of $${caseData.goal}`;
        document.getElementById('modalBar').style.width = percent + "%";

        // --- THE CRITICAL FIX ---
        // 2. Find the "Donate Now" button inside the MODAL 
        // and update its link to include the real Case ID
        const modalDonateBtn = modal.querySelector('.donate-now-btn') || modal.querySelector('.action-btn');
        if (modalDonateBtn) {
            // This ensures the link becomes: donate.php?case_id=5
            modalDonateBtn.href = `donate.php?case_id=${caseData.id}`;
        }
        // ------------------------

        modal.style.display = 'flex';
    };

    // Close Modal Logic
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => { if (event.target == modal) modal.style.display = 'none'; };
});