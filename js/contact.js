// 初始化百度地图
function initMap() {
    const map = new BMap.Map("map");
    const point = new BMap.Point(103.823557, 36.059102); // 兰州市坐标
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();

    // 添加标记
    const marker = new BMap.Marker(point);
    map.addOverlay(marker);

    // 添加信息窗口
    const infoWindow = new BMap.InfoWindow("甘肃骐霖智能装备有限公司", {
        width: 200,
        height: 60,
        title: "公司地址"
    });
    marker.addEventListener("click", function() {
        map.openInfoWindow(infoWindow, point);
    });
}

// 表单验证和提交
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // 前端验证
    const phoneRegex = /^1[3-9]\d{9}$/;
    if (!phoneRegex.test(this.phone.value)) {
        alert('请输入有效的手机号码');
        return;
    }
    
    // 异步提交
    fetch(this.action, {
        method: this.method,
        body: new FormData(this)
    })
    .then(response => {
        if (response.ok) {
            this.reset();
            return response.text();
        }
        throw new Error('提交失败');
    })
    .then(data => {
        console.log('提交成功:', data);
    })
    .catch(error => {
        console.error('错误:', error);
        alert('提交失败，请稍后重试');
    });
});

// 页面加载完成后初始化地图
window.addEventListener('load', initMap);