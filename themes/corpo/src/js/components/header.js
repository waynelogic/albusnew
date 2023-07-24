class Header {
    constructor(element) {
        this.header = element;
        this.infoheight = this.infoline();
        this.navigation = this.header.querySelector('.navigation');
        this.setStickyHeader();
        window.onscroll = () => this.setStickyHeader();
    }
    infoline() {
        return this.header.querySelector('.infoline').offsetHeight;
    }
    setStickyHeader() {
        if (window.pageYOffset > this.infoheight) {
            this.navigation.classList.add("fx");
        } else {
            this.navigation.classList.remove("fx");
        }
    }
}

export default function initHeader() {
    const header = new Header(document.querySelector('header'));

    // const $subButtons = document.querySelectorAll('[data-submenu]');
    // const $menuTogglers = document.querySelectorAll('.menu-toggler');
    // const $menu = document.getElementById('site-menu');
    // const $menuOverlay = document.getElementById('menu-overlay');
    // $menuTogglers.forEach(toggler => {
    //     toggler.onclick = function() {
    //         toggleMenu();
    //     }
    // });
    // $subButtons.forEach(button => {
    //     button.onclick = function() {
    //         let aim = document.querySelector('#sub-' + button.dataset.submenu);
    //         toggleSubMenu(aim);
    //     }
    // });
    // function toggleMenu() {
    //     $menu.classList.toggle('active'); 
    //     $menuOverlay.classList.toggle('active');
    // }

    // function toggleSubMenu($aim) {
    //     console.log($aim);
    //     $aim.classList.toggle('active'); 
    // }
}