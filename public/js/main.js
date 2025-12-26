document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const navActions = document.getElementById('nav-actions');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            // Toggles both boxes and the X animation
            navLinks.classList.toggle('active');
            navActions.classList.toggle('active');
            hamburger.classList.toggle('is-open');
        });
    }

    // Close menu when clicking a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            navActions.classList.remove('active');
            hamburger.classList.remove('is-open');
        });
    });
});