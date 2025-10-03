const sidebar =  document.querySelector(".sidebar");
const sidebarToggle = document.querySelector(".sidebar-toggle");
const menuToggle = document.querySelector(".menu-toggle");
const container = document.querySelector(".full-container");

sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    container.classList.toggle("collapsed");
})


const collapsedSidebarHeight = "56px";
const fullSidebarHeight = "calc(100vh - 24px)";

const toggleMenu = (isMenuActive) => {
    sidebar.style.height = isMenuActive ? `${sidebar.scrollHeight}px` : collapsedSidebarHeight;
    menuToggle.querySelector("span").innerText = isMenuActive ? "close" : "menu";
}



menuToggle.addEventListener("click", () => {
    toggleMenu(sidebar.classList.toggle("menu-active"));
})

window.addEventListener("resize", () => {
    if(window.innerWidth >= 1024) {
        sidebar.style.height = fullSidebarHeight;
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
    }
})