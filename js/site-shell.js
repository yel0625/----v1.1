(() => {
    const scriptEl = document.currentScript || Array.from(document.scripts).find((script) =>
        /site-shell\.js(?:\?.*)?$/.test(script.getAttribute("src") || "")
    );

    const assetPrefix = (scriptEl?.getAttribute("src") || "js/site-shell.js").replace(/js\/site-shell\.js(?:\?.*)?$/, "");
    const htmlLang = (document.documentElement.lang || "zh-CN").toLowerCase();
    const lang = htmlLang.startsWith("ru") ? "ru" : htmlLang.startsWith("en") ? "en" : "zh";
    const pageKey = document.body.dataset.page || "home";
    const currentYear = new Date().getFullYear();

    const logoText = {
        zh: "甘肃骐霖智能装备",
        en: "Gansu Qilin Intelligent Equipment",
        ru: "Gansu Qilin Intelligent Equipment",
    };

    const footerContent = {
        zh: {
            title: "联系我们",
            address: "地址：甘肃省兰州市西固区柳泉镇东坪村557号",
            phone: "电话：+86-18919006708",
            email: "邮箱：2041539565@qq.com",
            copyright: `© ${currentYear} 甘肃骐霖智能装备有限公司 版权所有`,
            logoAlt: "骐霖智能装备 logo",
            tagline: "药用硬胶囊生产线、配套设备与精密机加工服务。",
            quickTitle: "快速入口",
            quote: "获取方案",
            call: "电话咨询",
            products: "查看产品",
        },
        en: {
            title: "Contact Us",
            address: "Address: No. 557, Dongping Village, Liuquan Town, Xigu District, Lanzhou, Gansu, China",
            phone: "Tel: +86-18919006708",
            email: "Email: 2041539565@qq.com",
            copyright: `© ${currentYear} Gansu Qilin Intelligent Equipment Co., Ltd. All Rights Reserved`,
            logoAlt: "Qilin Intelligent Equipment logo",
            tagline: "Pharmaceutical capsule production lines, supporting equipment, and precision machining services.",
            quickTitle: "Quick Links",
            quote: "Request a Solution",
            call: "Call Sales",
            products: "View Products",
        },
        ru: {
            title: "Свяжитесь с нами",
            address: "Адрес: Китай, провинция Ганьсу, г. Ланьчжоу, район Сигу, пос. Люцюань, деревня Дунпин, дом 557",
            phone: "Тел: +86-18919006708",
            email: "Email: 2041539565@qq.com",
            copyright: `© ${currentYear} Gansu Qilin Intelligent Equipment Co., Ltd. Все права защищены`,
            logoAlt: "Логотип Qilin Intelligent Equipment",
            tagline: "Линии для фармацевтических капсул, комплектующие и услуги точной мехобработки.",
            quickTitle: "Быстрые ссылки",
            quote: "Запросить решение",
            call: "Позвонить",
            products: "Продукция",
        },
    };

    const navItems = {
        zh: [
            { key: "home", label: "首页", path: "index.php" },
            { key: "products", label: "产品介绍", path: "products.php" },
            { key: "capsule-production", label: "胶囊生产线", path: "capsule-production.html" },
            { key: "cnc-machining", label: "精密机加工", path: "CNC-machining.html" },
            { key: "technology", label: "技术实力", path: "technology.html" },
            { key: "history", label: "历史沿革", path: "history.html" },
            { key: "information", label: "行业资料", path: "information.php" },
            { key: "contact", label: "联系我们", path: "contact.php" },
        ],
        en: [
            { key: "home", label: "Home", path: "en/index.html" },
            { key: "products", label: "Products", path: "en/products.html" },
            { key: "history", label: "History", path: "en/history.html" },
            { key: "technology", label: "Technology", path: "en/technology.html" },
            { key: "information", label: "Information", path: "en/information.html" },
            { key: "contact", label: "Contact", path: "en/contact.html" },
        ],
        ru: [
            { key: "home", label: "Главная", path: "ru/index.html" },
            { key: "products", label: "Продукция", path: "ru/products.html" },
            { key: "history", label: "История", path: "ru/history.html" },
            { key: "technology", label: "Технологии", path: "ru/technology.html" },
            { key: "information", label: "Информация", path: "ru/information.html" },
            { key: "contact", label: "Контакты", path: "ru/contact.html" },
        ],
    };

    const pageConfig = {
        home: {
            navKey: "home",
            translations: { zh: "index.php", en: "en/index.html", ru: "ru/index.html" },
        },
        products: {
            navKey: "products",
            translations: { zh: "products.php", en: "en/products.html", ru: "ru/products.html" },
        },
        "capsule-production": {
            navKey: "capsule-production",
            translations: { zh: "capsule-production.html", en: "en/index.html", ru: "ru/index.html" },
        },
        "cnc-machining": {
            navKey: "cnc-machining",
            translations: { zh: "CNC-machining.html", en: "en/index.html", ru: "ru/index.html" },
        },
        technology: {
            navKey: "technology",
            translations: { zh: "technology.html", en: "en/technology.html", ru: "ru/technology.html" },
        },
        history: {
            navKey: "history",
            translations: { zh: "history.html", en: "en/history.html", ru: "ru/history.html" },
        },
        information: {
            navKey: "information",
            translations: { zh: "information.php", en: "en/information.html", ru: "ru/information.html" },
        },
        contact: {
            navKey: "contact",
            translations: { zh: "contact.php", en: "en/contact.html", ru: "ru/contact.html" },
        },
        about: {
            navKey: "home",
            translations: { zh: "about.html", en: "en/about.html", ru: "ru/about.html" },
        },
        "tech-standards": {
            navKey: "information",
            translations: { zh: "tech-standards.html", en: "en/tech-standards.html", ru: "ru/tech-standards.html" },
        },
        "industry-news": {
            navKey: "information",
            translations: { zh: "industry-news.html", en: "en/industry-news.html", ru: "ru/industry-news.html" },
        },
        "tech-docs": {
            navKey: "information",
            translations: { zh: "tech-docs.html", en: "en/tech-docs.html", ru: "ru/tech-docs.html" },
        },
        "capsule-standard": {
            navKey: "information",
            translations: { zh: "capsule-standard.html", en: "en/tech-standards/capsule-standard.html", ru: "ru/tech-standards.html" },
        },
        cooperation: {
            navKey: "contact",
            translations: { zh: "contact.php", en: "en/cooperation.html", ru: "ru/contact.html" },
        },
    };

    const languageOrder = [
        { key: "zh", label: "中文" },
        { key: "en", label: "EN" },
        { key: "ru", label: "RU" },
    ];

    const currentPage = pageConfig[pageKey] || pageConfig.home;
    const currentNavKey = currentPage.navKey || pageKey;
    const toHref = (path) => `${assetPrefix}${path}`;

    const languageButtons = languageOrder
        .map(({ key, label }) => {
            const target = currentPage.translations[key] || pageConfig.home.translations[key];
            const active = key === lang ? " active" : "";
            return `<a href="${toHref(target)}" class="language-btn${active}">${label}</a>`;
        })
        .join("");

    const navigation = navItems[lang]
        .map(({ key, label, path }) => {
            const active = key === currentNavKey ? ' class="active"' : "";
            return `<li><a href="${toHref(path)}"${active}>${label}</a></li>`;
        })
        .join("");

    const footerNavigation = navItems[lang]
        .slice(0, 4)
        .map(({ label, path }) => `<a href="${toHref(path)}">${label}</a>`)
        .join("");

    const headerMarkup = `
<header>
    <div class="language-switcher">
        <div class="container">
            <div class="language-links">
                ${languageButtons}
            </div>
        </div>
    </div>
    <nav class="main-nav">
        <div class="container">
            <div class="nav-content">
                <a class="logo" href="${toHref(pageConfig.home.translations[lang])}" aria-label="Home">
                    <img src="${toHref("images/logo.png")}" alt="${footerContent[lang].logoAlt}">
                    <span class="logo-text">${logoText[lang]}</span>
                </a>
                <ul class="nav-links">
                    ${navigation}
                </ul>
                <a class="nav-quote" href="${toHref(lang === "zh" ? "contact.php" : `${lang}/contact.html`)}">${footerContent[lang].quote}</a>
                <div class="mobile-menu">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </nav>
    <div class="mobile-language-switcher">
        ${languageButtons}
    </div>
</header>`.trim();

    const footerMarkup = `
<footer>
    <div class="footer-content">
        <div class="footer-brand">
            <h3>${logoText[lang]}</h3>
            <p>${footerContent[lang].tagline}</p>
            <a href="${toHref(lang === "zh" ? "products.php" : `${lang}/products.html`)}">${footerContent[lang].products}</a>
        </div>
        <div class="contact-info">
            <h3>${footerContent[lang].title}</h3>
            <p>${footerContent[lang].address}</p>
            <p>${footerContent[lang].phone}</p>
            <p>${footerContent[lang].email}</p>
        </div>
        <div class="footer-links">
            <h3>${footerContent[lang].quickTitle}</h3>
            ${footerNavigation}
        </div>
    </div>
    <div class="copyright">
        <p>${footerContent[lang].copyright}</p>
    </div>
</footer>
<div class="mobile-contact-dock" aria-label="Quick contact">
    <a href="tel:+8618919006708">${footerContent[lang].call}</a>
    <a href="${toHref(lang === "zh" ? "contact.php" : `${lang}/contact.html`)}">${footerContent[lang].quote}</a>
</div>`.trim();

    const headerTarget = document.querySelector("[data-site-header]");
    if (headerTarget) {
        headerTarget.outerHTML = headerMarkup;
    }

    const footerTarget = document.querySelector("[data-site-footer]");
    if (footerTarget) {
        footerTarget.outerHTML = footerMarkup;
    }
})();
