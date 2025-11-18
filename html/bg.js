// bg.js
document.addEventListener("DOMContentLoaded", function () {
    if (typeof VANTA !== "undefined" && VANTA.NET) {
        VANTA.NET({
            el: "#bg",
            mouseControls: false,
            touchControls: false,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00
        });
    }
});
