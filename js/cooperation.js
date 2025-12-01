// 地图点击交互
document.querySelectorAll('.map-point').forEach(point => {
    point.addEventListener('mouseenter', () => {
        point.querySelector('.point-info').style.display = 'block';
    });
    
    point.addEventListener('mouseleave', () => {
        point.querySelector('.point-info').style.display = 'none';
    });
});

// 滚动动画
window.addEventListener('scroll', () => {
    const elements = document.querySelectorAll('.client-card, .institution-item, .advantage-item');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const triggerBottom = window.innerHeight * 0.8;
        
        if (elementTop < triggerBottom) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
});

// 地图响应式处理
function adjustMapPoints() {
    const map = document.querySelector('.china-map');
    const mapWidth = map.offsetWidth;
    const mapHeight = map.offsetHeight;
    
    // 根据地图尺寸调整点的大小
    document.querySelectorAll('.point').forEach(point => {
        const size = Math.max(8, Math.min(12, mapWidth / 50));
        point.style.width = `${size}px`;
        point.style.height = `${size}px`;
    });
}

window.addEventListener('resize', adjustMapPoints);
window.addEventListener('load', adjustMapPoints); 