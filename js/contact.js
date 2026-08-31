function initMap() {
    const mapContainer = document.getElementById('map');

    if (!mapContainer || typeof window.BMap === 'undefined') {
        return;
    }

    const map = new BMap.Map('map');
    const point = new BMap.Point(103.823557, 36.059102);
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();

    const marker = new BMap.Marker(point);
    map.addOverlay(marker);

    const infoWindow = new BMap.InfoWindow('甘肃骐霖智能装备有限公司', {
        width: 220,
        height: 70,
        title: '公司地址',
    });

    marker.addEventListener('click', () => {
        map.openInfoWindow(infoWindow, point);
    });
}

const contactForm = document.getElementById('contactForm');

if (contactForm) {
    const pageLang = (document.documentElement.lang || 'zh').toLowerCase();
    const formCopy = pageLang.startsWith('ru')
        ? { contact: 'Укажите телефон или электронную почту', phone: 'Введите корректный номер телефона', failed: 'Не удалось отправить. Повторите попытку позже' }
        : pageLang.startsWith('en')
            ? { contact: 'Please provide a phone number or email address', phone: 'Please enter a valid phone number', failed: 'Submission failed. Please try again later' }
            : { contact: '请至少填写联系电话或电子邮箱', phone: '请输入有效的联系电话', failed: '提交失败，请稍后重试' };
    const requestedSlug = new URLSearchParams(window.location.search).get('product');
    const requestedNames = {
        'ps-dnj34': { zh: 'PS-DNJ34全自动胶囊生产线', en: 'PS-DNJ34 Automatic Capsule Production Line', ru: 'Автоматическая линия PS-DNJ34' },
        'ps-dnj35sn3': { zh: 'PS-DNJ35SN3全自动胶囊生产线', en: 'PS-DNJ35SN3 Automatic Capsule Production Line', ru: 'Автоматическая линия PS-DNJ35SN3' },
        'capsule-mould-components': { zh: '模具组件', en: 'Capsule Mould Components', ru: 'Формовочные компоненты' },
        'intelligent-control-system': { zh: '智能控制系统', en: 'Intelligent Control System', ru: 'Интеллектуальная система управления' },
    };
    const languageKey = pageLang.startsWith('ru') ? 'ru' : pageLang.startsWith('en') ? 'en' : 'zh';
    if (requestedSlug && requestedNames[requestedSlug] && contactForm.message && !contactForm.message.value.trim()) {
        const prefix = languageKey === 'ru' ? 'Интересующий продукт: ' : languageKey === 'en' ? 'Product inquiry: ' : '咨询产品：';
        contactForm.message.value = `${prefix}${requestedNames[requestedSlug][languageKey]}\n`;
    }
    contactForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const phoneValue = contactForm.phone?.value?.trim() || '';
        const mobileRegex = /^1[3-9]\d{9}$/;
        const telRegex = /^\+?[0-9\-()\s]{7,20}$/;

        const emailValue = contactForm.email?.value?.trim() || '';

        if (!phoneValue && !emailValue) {
            alert(formCopy.contact);
            return;
        }

        if (phoneValue && !mobileRegex.test(phoneValue) && !telRegex.test(phoneValue)) {
            alert(formCopy.phone);
            return;
        }

        try {
            const response = await fetch(contactForm.action, {
                method: contactForm.method,
                body: new FormData(contactForm),
            });

            const text = await response.text();

            if (!response.ok) {
                throw new Error(text || '提交失败');
            }

            contactForm.reset();
            document.open();
            document.write(text);
            document.close();
        } catch (error) {
            console.error('提交失败', error);
            alert(formCopy.failed);
        }
    });
}

window.addEventListener('load', initMap);
