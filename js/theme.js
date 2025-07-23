// bytekit_infotech/js/theme.js

document.addEventListener('DOMContentLoaded', () => {
    const themeToggleButton = document.getElementById('theme-toggle-button');
    const body = document.body;

    // Load saved theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        body.classList.add(savedTheme); // Add 'dark-mode' or 'light-mode'
        // Update bulb image based on initial load
        if (savedTheme === 'dark-mode') {
            themeToggleButton.src = '/bytekit_infotech/images/icons/light-bulb-on.png';
        } else {
            themeToggleButton.src = '/bytekit_infotech/images/icons/light-bulb-off.png';
        }
    } else {
        // Default to light mode if no theme saved, and ensure bulb is 'off'
        body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light-mode');
        themeToggleButton.src = '/bytekit_infotech/images/icons/light-bulb-off.png';
    }


    themeToggleButton.addEventListener('click', () => {
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light-mode');
            themeToggleButton.src = '/bytekit_infotech/images/icons/light-bulb-off.png';
        } else {
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark-mode');
            themeToggleButton.src = '/bytekit_infotech/images/icons/light-bulb-on.png';
        }
    });
});