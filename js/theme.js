// bytekit_infotech/js/theme.js
// This script handles cycling through Light, Dark, and Computer modes.

document.addEventListener('DOMContentLoaded', () => {
    const themeToggleButton = document.getElementById('theme-toggle-button'); // The img element for the bulb
    const body = document.body;

    // Define the order of themes
    // The order determines how clicking the bulb cycles through the themes
    const themes = ['light-mode', 'dark-mode', 'computer-mode'];
    let currentThemeIndex = 0; // Tracks the index of the currently active theme

    // Map theme names to specific bulb image paths
    // Ensure these paths are correct relative to your website's root
    const bulbImages = {
        'light-mode': '/bytekit_infotech/images/icons/light-bulb-off.png',
        'dark-mode': '/bytekit_infotech/images/icons/light-bulb-on.png',
        'computer-mode': '/bytekit_infotech/images/icons/light-bulb-on.png' // You can use a different bulb for computer mode if desired
    };

    // --- Initial Load Logic ---
    // Check for a saved theme preference in the user's browser (localStorage)
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme && themes.includes(savedTheme)) {
        // If a valid theme is saved, apply it
        body.classList.add(savedTheme);
        currentThemeIndex = themes.indexOf(savedTheme); // Set the starting index
        themeToggleButton.src = bulbImages[savedTheme]; // Update bulb image to match
    } else {
        // If no theme is saved or the saved theme is invalid, default to 'light-mode'
        body.classList.add('light-mode');
        localStorage.setItem('theme', 'light-mode');
        themeToggleButton.src = bulbImages['light-mode']; // Set bulb to 'off'
        currentThemeIndex = 0; // Start with light mode
    }

    // --- Click Event Listener for Theme Toggle ---
    themeToggleButton.addEventListener('click', () => {
        // 1. Remove the current theme class from the body
        body.classList.remove(themes[currentThemeIndex]);

        // 2. Calculate the index of the next theme in the cycle
        // The modulo operator (%) ensures the index wraps around (0 -> 1 -> 2 -> 0)
        currentThemeIndex = (currentThemeIndex + 1) % themes.length;
        const nextTheme = themes[currentThemeIndex];

        // 3. Add the class for the new theme to the body
        body.classList.add(nextTheme);

        // 4. Save the new theme preference to localStorage
        localStorage.setItem('theme', nextTheme);

        // 5. Update the bulb image source to reflect the new theme
        themeToggleButton.src = bulbImages[nextTheme];

        // Optional: Log the current theme to console for debugging
        console.log("Switched to theme:", nextTheme);
    });
});