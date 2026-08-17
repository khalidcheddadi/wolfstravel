<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>مبدل اللغة الذكي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            min-height: 100vh;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 20px;
            background: #f1f5f9;
            font-family: system-ui, sans-serif;
        }
        .lang-switcher-wrap {
            position: relative;
            display: inline-block;
            z-index: 1000;
        }
        .lang-toggle-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.2s;
            font-size: 0.9rem;
            min-height: 44px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .lang-toggle-btn:hover {
            border-color: #945d36;
            background: #fef7f1;
        }
        .lang-toggle-btn .lang-chevron {
            font-size: 0.65rem;
            color: #94a3b8;
            transition: transform 0.25s;
        }
        .lang-toggle-btn.open .lang-chevron {
            transform: rotate(180deg);
        }
        .lang-dropdown {
            position: fixed;
            min-width: 200px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: scale(0.95);
            transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
            z-index: 9999;
        }
        .lang-dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            pointer-events: auto;
        }
        .lang-dropdown::before {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background: #fff;
            border-top: 1.5px solid #e2e8f0;
            border-left: 1.5px solid #e2e8f0;
            transform: rotate(45deg);
            border-radius: 4px 0 0 0;
            z-index: -1;
        }
        .lang-dropdown.arrow-top::before {
            top: -8px;
            bottom: auto;
            border-bottom: none;
            border-right: none;
        }
        .lang-dropdown.arrow-bottom::before {
            bottom: -8px;
            top: auto;
            border-bottom: 1.5px solid #e2e8f0;
            border-right: 1.5px solid #e2e8f0;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }
        .lang-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.9rem;
            border-radius: 12px;
            text-decoration: none;
            color: #1e293b;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            min-height: 44px;
        }
        .lang-item:hover {
            background: #f1f5f9;
        }
        .lang-item.active {
            background: #fef7f1;
            color: #945d36;
            font-weight: 600;
        }
        .lang-item .li-code {
            width: 30px;
            font-weight: 700;
            font-size: 0.7rem;
            color: #94a3b8;
        }
        .lang-item.active .li-code {
            color: #945d36;
        }
        .lang-item .li-name {
            flex: 1;
            white-space: nowrap;
        }
        .lang-item .li-check {
            margin-left: auto;
            color: #945d36;
            opacity: 0;
            transform: scale(0.6);
            transition: all 0.2s;
        }
        .lang-item.active .li-check {
            opacity: 1;
            transform: scale(1);
        }
        @media (max-width: 480px) {
            .lang-toggle-btn { font-size: 0.8rem; padding: 0.4rem 0.8rem; min-height: 38px; }
            .lang-dropdown { min-width: 170px; }
            .lang-item { font-size: 0.85rem; padding: 0.5rem 0.7rem; min-height: 40px; }
        }
    </style>
</head>
<body>
<div class="lang-switcher-wrap">
    <button class="lang-toggle-btn" id="langToggleBtn">
        <i class="fa-solid fa-globe"></i>
        <span id="currentLangCode">EN</span>
        <i class="fa-solid fa-chevron-down lang-chevron"></i>
    </button>
    <div class="lang-dropdown" id="langDropdown">
        <a href="#" data-lang="en" class="lang-item active">
            <span class="li-code">EN</span>
            <span class="li-name">English</span>
            <i class="fa-solid fa-check li-check"></i>
        </a>
        <a href="#" data-lang="es" class="lang-item">
            <span class="li-code">ES</span>
            <span class="li-name">Español</span>
            <i class="fa-solid fa-check li-check"></i>
        </a>
        <a href="#" data-lang="fr" class="lang-item">
            <span class="li-code">FR</span>
            <span class="li-name">Français</span>
            <i class="fa-solid fa-check li-check"></i>
        </a>
        <a href="#" data-lang="ar" class="lang-item">
            <span class="li-code">AR</span>
            <span class="li-name">العربية</span>
            <i class="fa-solid fa-check li-check"></i>
        </a>
        <a href="#" data-lang="de" class="lang-item">
            <span class="li-code">DE</span>
            <span class="li-name">Deutsch</span>
            <i class="fa-solid fa-check li-check"></i>
        </a>
    </div>
</div>
<script>
(function() {
    const btn = document.getElementById('langToggleBtn');
    const dropdown = document.getElementById('langDropdown');
    const currentCode = document.getElementById('currentLangCode');
    const items = dropdown.querySelectorAll('.lang-item');

    function setActive(lang) {
        items.forEach(el => {
            el.classList.toggle('active', el.dataset.lang === lang);
        });
        if (currentCode) currentCode.textContent = lang.toUpperCase();
    }

    function closeDrop() {
        dropdown.classList.remove('open');
        btn.classList.remove('open');
    }

    function smartPosition() {
        const btnRect = btn.getBoundingClientRect();
        const dropW = dropdown.offsetWidth || 220;
        const dropH = dropdown.offsetHeight || 250;

        let leftPos, rightPos;
        const spaceRight = window.innerWidth - btnRect.right;
        if (spaceRight >= dropW) {
            leftPos = btnRect.left + 'px';
            rightPos = 'auto';
            dropdown.style.left = leftPos;
            dropdown.style.right = rightPos;
        } else {
            leftPos = 'auto';
            rightPos = (window.innerWidth - btnRect.right) + 'px';
            dropdown.style.left = leftPos;
            dropdown.style.right = rightPos;
        }

        const spaceBelow = window.innerHeight - btnRect.bottom;
        const spaceAbove = btnRect.top;
        dropdown.classList.remove('arrow-top', 'arrow-bottom');
        
        if (spaceBelow >= dropH + 15) {
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            dropdown.style.bottom = 'auto';
            dropdown.classList.add('arrow-top');
        } else if (spaceAbove >= dropH + 15) {
            dropdown.style.top = 'auto';
            dropdown.style.bottom = (window.innerHeight - btnRect.top + 10) + 'px';
            dropdown.classList.add('arrow-bottom');
        } else {
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            dropdown.style.bottom = 'auto';
            dropdown.classList.add('arrow-top');
            dropdown.style.maxHeight = (window.innerHeight - btnRect.bottom - 30) + 'px';
            dropdown.style.overflowY = 'auto';
        }
    }

    function toggleDrop(e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.contains('open');
        if (isOpen) {
            closeDrop();
        } else {
            smartPosition();
            dropdown.classList.add('open');
            btn.classList.add('open');
        }
    }

    btn.addEventListener('click', toggleDrop);

    document.addEventListener('click', function(e) {
        const wrap = document.querySelector('.lang-switcher-wrap');
        if (wrap && !wrap.contains(e.target)) closeDrop();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDrop();
    });

    items.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.dataset.lang;
            if (lang) {
                setActive(lang);
                closeDrop();
                console.log('تم اختيار:', lang);
                alert('سيتم التوجيه إلى: /language/switch/' + lang);
            }
        });
    });

    const defaultLang = document.querySelector('.lang-item.active')?.dataset.lang || 'en';
    setActive(defaultLang);
})();
</script>
</body>
</html>