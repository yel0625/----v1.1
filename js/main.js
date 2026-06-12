const swiperContainer = document.querySelector('.swiper-container');

if (swiperContainer && typeof window.Swiper !== 'undefined') {
    new Swiper('.swiper-container', {
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
        effect: 'fade',
        fadeEffect: {
            crossFade: true,
        },
        touchEventsTarget: 'wrapper',
        touchRatio: 1,
        touchAngle: 45,
        grabCursor: true,
    });
}

const mobileMenu = document.querySelector('.mobile-menu');
const navLinks = document.querySelector('.nav-links');

if (mobileMenu && navLinks) {
    mobileMenu.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        navLinks.classList.toggle('active');
    });
}

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (event) => {
        const href = anchor.getAttribute('href') || '';
        const targetId = href.substring(1);
        if (!targetId) {
            return;
        }

        const targetElement = document.getElementById(targetId);
        if (!targetElement) {
            return;
        }

        event.preventDefault();
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    });
});

if (typeof window.IntersectionObserver !== 'undefined') {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    });

    document.querySelectorAll('.card, .advantage-item, .company-intro, .contact-info').forEach((element) => {
        element.classList.add('scroll-animate');
        observer.observe(element);
    });
}

document.querySelectorAll('button, .cta-button').forEach((button) => {
    button.addEventListener('click', (event) => {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');

        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;

        button.appendChild(ripple);

        requestAnimationFrame(() => {
            ripple.classList.add('active');
        });

        window.setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

document.querySelectorAll('.form-group input, .form-group textarea').forEach((input) => {
    input.addEventListener('focus', () => {
        input.parentElement?.classList.add('focused');
    });

    input.addEventListener('blur', () => {
        input.parentElement?.classList.remove('focused');
    });
});

document.querySelectorAll('.nav-links li a').forEach((link) => {
    link.addEventListener('mouseenter', () => {
        link.style.transform = 'translateY(-2px)';
    });

    link.addEventListener('mouseleave', () => {
        link.style.transform = 'translateY(0)';
    });
});

window.addEventListener('load', () => {
    document.body.classList.add('loaded');
});
