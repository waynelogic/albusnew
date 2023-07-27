import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

export default function init($element) {
    const progressCircle = $element.querySelector(".autoplay-progress .autoplay-circle");
    const progressContent = $element.querySelector(".autoplay-progress span");
    const pagination = $element.querySelector('.swiper-pagination');
    const nextButton = $element.querySelector('.swiper-button-next');
    const prevButton = $element.querySelector('.swiper-button-prev');
    let defOptions = {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        pagination: {
            el: pagination,
        },
        navigation: {
            nextEl: nextButton,
            prevEl: prevButton,
        },
        on: {
            autoplayTimeLeft(s, time, progress) {
                progressCircle.style.setProperty("--progress", 1 - progress);
                // progressContent.textContent = `${Math.ceil(time / 1000)}s`;
            }
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
        } else if (key == 'autoplay') {
            result['autoplay'] = {
                delay: 5000,
                disableOnInteraction: false
            }
        } else {
            result[key] = data[key];
        }
        return result;
    }, {});
    new Swiper($element, { ...defOptions, ...params });
};
