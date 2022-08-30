import Glide from '@glidejs/glide';
const element = document.querySelector('.ec-carousel.glide');

if (element) {
    new Glide(element, {
        startAt: 0,
        perView: 2,
        gap: 15,
        autoHeight: true
    }).mount();
}