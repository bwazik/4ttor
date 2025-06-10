/**
 * Main - Front Pages
 */
"use strict";

(function () {
    // Hero
    const mediaQueryXL = "1200";
    const width = screen.width;
    if (width >= mediaQueryXL) {
        document.addEventListener("mousemove", function parallax(e) {
            this.querySelectorAll(".animation-img").forEach((layer) => {
                let speed = layer.getAttribute("data-speed");
                let x = (window.innerWidth - e.pageX * speed) / 100;
                let y = (window.innerWidth - e.pageY * speed) / 100;
                layer.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    }
})();
