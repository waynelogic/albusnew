window.slider = function(object){
    import('./components/swiper-slider.js').then(({ default : initCarousel }) => initCarousel(object) );
}

window.ajaxModal = function(object){
    import('./components/modalAjax').then(({ default : init }) => init(object) );
}

window.modal = function(object){
    import('./components/modal').then(({ default : init }) => init(object) );
}

window.accordion = function(object){
    import('./components/accordion').then(({ default : init }) => init(object) );
}

window.onload = () => {
    // import('./components/header').then(({ default : initHeader }) => initHeader() );
    import('./components/dropdown').then(({ default : initDropdown }) => initDropdown());
    const cloak = document.querySelectorAll('[x-cloak]');
    cloak.forEach(element => {
        element.removeAttribute('x-cloak');
    });
    const arLazyItems = document.querySelectorAll('[data-lazy]');
    arLazyItems.forEach(element => {
        var fnName = element.dataset.lazy;
        if (window[fnName] == undefined || typeof window[fnName] != 'function') return console.log(`Lazy функция ${fnName} не найдена`);
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.5
        }
        const server = new IntersectionObserver(handlerObserver, options);

        function handlerObserver(entries, observer) {
            entries.forEach(entry =>{
                if (!entry.isIntersecting) return;
                const object = entry.target;
                window[object.dataset.lazy](object);
                observer.unobserve(object);
            })
        }
        server.observe(element);
    })
}

class TabManager {
    constructor(element) {
        this.tabArea = element;
        this.tabs = this.tabArea.querySelectorAll('[data-tab]');
        this.buttons = this.tabArea.querySelectorAll('[data-tab-button]');
        this.buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.changeTab(e.target);
            });
        });
    }
    changeTab(button) {
        let aimTab = button.dataset.tabButton;
        this.tabs.forEach(tab => {
            if (tab.dataset.tab == aimTab) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
        this.buttons.forEach(button => {
            if (button.dataset.tabButton == aimTab) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }
}

