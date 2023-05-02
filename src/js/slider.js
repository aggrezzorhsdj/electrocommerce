import Glide from "@glidejs/glide";
import Swiper, { Navigation, Pagination, Thumbs } from "swiper";
const element = document.querySelector(".ec-carousel.glide");

if (element) {
    new Glide(element, {
        startAt: 0,
        perView: 2,
        gap: 15,
        autoHeight: true,
        breakpoints: {
            768: { perView: 1 }
        }
    }).mount();
}

const productGalleryThumbnails = new Swiper(".swiper.ec-product-gallery.gallery-thumbs", {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
    direction: "vertical",
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    modules: [ Navigation, Pagination, Thumbs ]
});

const productGalleryTop = new Swiper(".swiper.ec-product-gallery.gallery-top", {
    spaceBetween: 10,
    modules: [ Navigation, Pagination, Thumbs ],
    navigation: {
        nextEl: ".swiper-next",
        prevEl: ".swiper-prev",
    },
    thumbs: {
        swiper: productGalleryThumbnails
    }
});
