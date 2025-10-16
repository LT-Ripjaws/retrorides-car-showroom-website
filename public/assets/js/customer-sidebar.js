/**
 * Customer Sidebar JavaScript
 * 
 * Handles the interactivity of the customer sidebar
 */

const sidebar = document.querySelector(".customer-sidebar");
const sidebarToggle = document.querySelector(".sidebar-toggle");
const menuToggle = document.querySelector(".menu-toggle");
const container = document.querySelector(".full-container");

// Desktop toggle
if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
        container.classList.toggle("collapsed");
    });
}

// Mobile toggle
const collapsedSidebarHeight = "65px";
const fullSidebarHeight = "calc(100vh - 24px)";

const toggleMenu = (isMenuActive) => {
    sidebar.style.height = isMenuActive ? `${sidebar.scrollHeight}px` : collapsedSidebarHeight;
    if (menuToggle) {
        menuToggle.querySelector("span").innerText = isMenuActive ? "close" : "menu";
    }
}

if (menuToggle) {
    menuToggle.addEventListener("click", () => {
        toggleMenu(sidebar.classList.toggle("menu-active"));
    });
}

// Handle window resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        sidebar.style.height = fullSidebarHeight;
        sidebar.classList.remove("menu-active");
        if (container) {
            container.classList.remove("mobile-layout");
        }
    } else {
        sidebar.classList.remove("collapsed");
        container.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
        if (container) {
            container.classList.add("mobile-layout");
        }
    }
});

// Initialize on load
if (window.innerWidth < 1024 && container) {
    container.classList.add("mobile-layout");
}

// Active link highlighting
const currentPath = window.location.pathname;
const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');

navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath || 
        currentPath.includes(link.getAttribute('href').split('/').pop())) {
        link.classList.add('active');
    }
});