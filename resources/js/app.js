import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

// Initialize Alpine.js
document.addEventListener("DOMContentLoaded", () => {
    // Initialize theme before Alpine loads to prevent flash
    const savedTheme = localStorage.getItem("theme");
    const systemPrefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)"
    ).matches;

    if (savedTheme === "dark" || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }

    // Start Alpine
    Alpine.start();
});

// Create Alpine stores when Alpine is initialized
document.addEventListener("alpine:init", () => {
    // Create sidebar store
    Alpine.store("sidebar", {
        open: localStorage.getItem("sidebar-state") !== "closed",

        toggle() {
            this.open = !this.open;
            localStorage.setItem(
                "sidebar-state",
                this.open ? "open" : "closed"
            );
        },
    });

    // Create theme store for dark/light mode
    Alpine.store("theme", {
        dark:
            localStorage.getItem("theme") === "dark" ||
            (!localStorage.getItem("theme") &&
                window.matchMedia("(prefers-color-scheme: dark)").matches),

        toggle() {
            this.dark = !this.dark;
            localStorage.setItem("theme", this.dark ? "dark" : "light");
            this.updateDocumentClass();
        },

        updateDocumentClass() {
            if (this.dark) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        },
    });
});

// Initialize the stores as soon as Alpine is loaded
document.addEventListener("DOMContentLoaded", () => {
    if (Alpine.store) {
        // Initialize sidebar
        const sidebarStore = Alpine.store("sidebar");
        if (sidebarStore && typeof sidebarStore.init === "function") {
            sidebarStore.init();
        }

        // Initialize theme
        const themeStore = Alpine.store("theme");
        if (themeStore && typeof themeStore.init === "function") {
            themeStore.init();
        }
    }
});

Alpine.start();
