window.slider = function(object){
    import('./components/swiper-slider.js').then(({ default : initCarousel }) => initCarousel(object) );
}

window.lightbox = function(object){
    import('./components/lightbox').then(({ default : initLightbox }) => initLightbox(object) );
}

window.cartCounter = function(object){
    import('./components/cartCounter').then(({ default : initCounter }) => initCounter(object) );
}

window.shop = function(object){
    import('./components/shop').then(({ default : initShop }) => initShop(object) );
}

window.onload = () => {
    import('./components/header').then(({ default : initHeader }) => initHeader() );
    import('./components/dropdown').then(({ default : initDropdown }) => initDropdown());
    import('./components/modal').then(({ default : initModal }) => initModal());
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