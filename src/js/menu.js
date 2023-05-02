"use strict";
import * as $ from "jquery";

const menu = document.querySelector( ".ec-menu" );
const toggler = document.querySelector( ".ec-header__menu-toggler" );
let menuBuffer = [];

const setMenuPosition = () => {
	menu.style.top = toggler.getBoundingClientRect().top + 70 + "px";
};

const menuAnimate = (element) => {
    if (element.classList.contains("_animate")) {
        element.classList.remove("_animate" );
        const timer = setTimeout(() => {
            element.classList.remove("_show");
            clearInterval(timer);
        }, 150);
    } else {
        element.classList.add("_show" );
        const timer = setTimeout(() => {
            element.classList.add("_animate");
            clearInterval(timer);
        }, 150);
    }
};

const setMenuMobileTitle = (selector, element) => {
    selector.innerText = element?.querySelector("a").innerText || "";
};

const menuMobile = () => {
    const width = window.innerWidth;
    const $item = $(".ec-menu__chevron");
    const $back = $(".ec-header__back");
    const $close = $(".ec-header__close");
    const $menuMobileTitle = document.querySelector(".ec-header__menu-title");

    if (width < 992) {
        $item.on("click", (e) => {
            e.preventDefault();
            const menuItem = e.currentTarget.parentNode.parentNode;

            menuAnimate(menuItem.querySelector(".sub-menu"));
            setMenuMobileTitle($menuMobileTitle, menuItem);
            menuBuffer.push(menuItem);
            console.log($back.hasClass("d-none"));
            if (menuBuffer?.length && $back.hasClass("d-none")) {
                $back.removeClass("d-none");
            }
        });

        $back.on("click", (e) => {
            const element = menuBuffer.pop();
            menuAnimate(element.querySelector(".sub-menu"));

            if (menuBuffer.length) {
                setMenuMobileTitle($menuMobileTitle, menuBuffer[menuBuffer.length - 1]);
            } else {
                setMenuMobileTitle($menuMobileTitle, null);
                $(e.currentTarget).addClass("d-none");
            }
        });

        $close.on("click", (e) => {
            togglerTrigger(e);
        });
    }
};

const togglerTrigger = (e) => {
    const headerMobile = document.querySelector(".ec-header__mobile");
    const width = window.innerWidth;

    e.preventDefault();
    menuAnimate(menu);

    if (width < 992) {
        headerMobile.classList.toggle("d-flex");
    }
};

setMenuPosition();
menuMobile();

document.addEventListener("scroll", function() {
	setMenuPosition();
});
toggler.addEventListener("click", function(e) {
    togglerTrigger(e);
} );
document.addEventListener("resize", function() {
    menuMobile();
});

