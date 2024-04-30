class Sidebar {
    /**
     * Sidebar constructor
     * @param sidebarSelector Selector string for the sidebar menu
     * @param burgerSelector Selector string for the burger div
     * @param darkBackgroundSelector Selector string for the dark background
     */
    constructor(sidebarSelector, burgerSelector, darkBackgroundSelector) {
        // Save the sidebar element
        this.sidebarElement = $(sidebarSelector);
        this.burgerElement = $(burgerSelector);
        this.darkBackgroundElement = $(darkBackgroundSelector);
        this.shown = false;

        // Constructor
        this.init();
    }

    /**
     * Initialize the sidebar
     */
    init() {
        // Bind the toggle event
        this.burgerElement.click((event) => {
            this.toggleSidebar();
        });
    }

    /**
     * Toggle the sidebar and dark background
     */
    toggleSidebar() {
        // Toggle navigator
        this.sidebarElement.toggleClass("header-navigator-default");
        this.sidebarElement.toggleClass("header-navigator-active");

        // Toggle dark background
        this.darkBackgroundElement.toggleClass("header-dark-background-default");
        this.darkBackgroundElement.toggleClass("header-dark-background-active");

        // Change burger
        this.burgerElement.toggleClass("close");

        // Toggle scrolling
        if(this.shown) {
            document.body.style.overflow = null;
            document.body.style.position = null;
            document.querySelector('html').style.overflow = null;
            document.querySelector('html').style.position = null;

            this.shown = false;
        } else {
            document.querySelector('html').scrollTop = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'relative';
            document.querySelector('html').style.overflow = 'hidden';
            document.querySelector('html').style.position = 'relative';

            this.shown = true;
        }
    }
}
