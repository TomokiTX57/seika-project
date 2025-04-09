import "./bootstrap";
import "./input-format";
import "./player-index";

import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// モバイルメニューの開閉処理
document.addEventListener("DOMContentLoaded", () => {
    const menuButton = document.getElementById("menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    if (menuButton && mobileMenu) {
        menuButton.addEventListener("click", () => {
            mobileMenu.classList.toggle("translate-x-full");
        });
    } else if (mobileMenu) {
        // ボタンが無い場合はメニューを閉じた状態にしておく
        mobileMenu.classList.add("translate-x-full");
    }
});
