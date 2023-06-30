import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

export default function initCarousel($element) {
    const defOptions = {
        modules: [Navigation, Pagination, Autoplay],
        loop: true,
        pagination: {
            el: '.swiper-pagination',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        slidesPerView: "auto",
        speed: 700,
    }; 

    let dataOptions = {}
    if ($element.dataset.autoplay != undefined) {
        dataOptions.autoplay = true;
    }
    if($element.dataset.items != undefined) {
        dataOptions.slidesPerView = $element.dataset.items;
    }
    if($element.dataset.slidertype != undefined) {
        if($element.dataset.slidertype == 'partners') {
            dataOptions.breakpoints = {
                '320': {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                '480': {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                '1024': {
                    slidesPerView: 7,
                    spaceBetween: 40
                }
            }
        }
        if($element.dataset.slidertype == 'reviews') {
            dataOptions.breakpoints = {
                '480': {
                    slidesPerView: 2,
                    spaceBetween: 30
                },
            }
        }
    };

    const init = () => {
        const hero_slider = new Swiper($element, {...defOptions, ...dataOptions});
    }

    init();
};