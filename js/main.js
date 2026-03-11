// 初始化轮播图
const swiper = new Swiper('.swiper-container', {
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    // 添加渐变效果
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    },
    // 添加触摸滑动
    touchEventsTarget: 'wrapper',
    touchRatio: 1,
    touchAngle: 45,
    grabCursor: true
});



// 移动端菜单
const mobileMenu = document.querySelector('.mobile-menu');
const navLinks = document.querySelector('.nav-links');

mobileMenu.addEventListener('click', () => {
    mobileMenu.classList.toggle('active');
    navLinks.classList.toggle('active');
}); 