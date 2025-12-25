document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chevronIcon = document.getElementById('chevron-icon');
    const navItems = document.querySelectorAll('.nav-item');

    // Toggle Sidebar Collapse
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        
        // Change icon based on state
        if (sidebar.classList.contains('collapsed')) {
            // Chevron Right
            chevronIcon.innerHTML = '<path d="m9 18 6-6-6-6"/>';
        } else {
            // Chevron Left
            chevronIcon.innerHTML = '<path d="m15 18-6-6 6-6"/>';
        }
    });

    // Handle Active State Clicking
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            navItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });
});
// Add this to your existing script.js
document.addEventListener('DOMContentLoaded', () => {
    // Animate progress bars on load
    const bars = document.querySelectorAll('.progress-bar');
    bars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 200);
    });

    // Simple "New Donation" button interaction
    const donateBtn = document.querySelector('.btn-primary');
    if (donateBtn) {
        donateBtn.addEventListener('click', () => {
            alert('Redirecting to donation page...');
        });
    }
});
