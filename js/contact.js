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
    contactForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const phoneValue = contactForm.phone?.value?.trim() || '';
        const mobileRegex = /^1[3-9]\d{9}$/;
        const telRegex = /^\+?[0-9\-()\s]{7,20}$/;

        if (!mobileRegex.test(phoneValue) && !telRegex.test(phoneValue)) {
            alert('请输入有效的联系电话');
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
            alert('提交失败，请稍后重试');
        }
    });
}

window.addEventListener('load', initMap);
