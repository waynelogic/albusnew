export default function initHeader() {
    const $subButtons = document.querySelectorAll('[data-submenu]');
    const $menuTogglers = document.querySelectorAll('.menu-toggler');
    const $menu = document.getElementById('site-menu');
    const $menuOverlay = document.getElementById('menu-overlay');
    $menuTogglers.forEach(toggler => {
        toggler.onclick = function() {
            toggleMenu();
        }
    });


    $subButtons.forEach(button => {
        button.onclick = function() {
            let aim = document.querySelector('#sub-' + button.dataset.submenu);
            toggleSubMenu(aim);
        }
    });
    function toggleMenu() {
        $menu.classList.toggle('active'); 
        $menuOverlay.classList.toggle('active');
    }

    function toggleSubMenu($aim) {
        console.log($aim);
        $aim.classList.toggle('active'); 
    }


    // window.onscroll = function() {
    //     checkBg();
    // };
    // function checkBg() {
    //     if (window.pageYOffset > $element.offsetHeight) {
    //         $element.classList.remove("bg-blur");
    //         $element.classList.add("bg-white");
    //     } else {
    //         $element.classList.add("bg-blur");
    //         $element.classList.remove("bg-white");
    //     }
    // }
    // checkBg();

    // const $siteNavbar = document.getElementById('site-navbar');
    // const $arTogglers = document.querySelectorAll('.menu_toggler');
    // const $siteOverlay = document.getElementById('site-overlay');
    // $arTogglers.forEach(button => {
    //     button.onclick = function() {
    //         toggleMenu();
    //     }
    // });
    // $siteOverlay.onclick = function() {
    //     toggleMenu();
    // }
    // function toggleMenu() {
    //     $siteNavbar.classList.toggle('max-lg:-translate-x-[150%]');
    //     $siteOverlay.classList.toggle('hidden');
    // }
    // const $childTogglers = document.querySelectorAll('.child_toggler');
    // $childTogglers.forEach(button => {
    //     button.onclick = function() {
    //         toggleParent(this.closest('li'))
    //     }
    // });
    // function toggleParent($parent) {
    //     console.log($parent.classList.toggle('opened'));
    // }
}