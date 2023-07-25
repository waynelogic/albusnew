import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

export default function init($element) {
    const pagination = $element.querySelector('.swiper-pagination');
    const nextEl = $element.querySelector('.swiper-button-next');
    const prevEl = $element.querySelector('.swiper-button-prev');
    let defOptions = {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: pagination,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    }
    let data = $element.dataset;

    let params = Object.keys(data).reduce((result, key) => {
        if (key === 'breakpoints') {
            result[key] = JSON.parse(data[key]);
        } else if (key == 'controlButtons') {
            $element.querySelectorAll('[data-slide-to]').forEach(element => {
                element.onclick = () => console.log(this);
            });;
        } else {
            result[key] = data[key];
        }
        return result;
    }, {});
    new Swiper($element, { ...defOptions, ...params });
};
