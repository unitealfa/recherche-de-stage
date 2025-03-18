document.addEventListener("DOMContentLoaded", function () {
    gsap.from(".sidebar", { duration: 1, x: -200, opacity: 0, ease: "power2.out" });
    gsap.from(".navbar", { duration: 1, y: -50, opacity: 0, ease: "power2.out" });
    gsap.from(".card", { duration: 1, y: 50, opacity: 0, stagger: 0.2, ease: "power2.out" });

    function animateNumbers(id, endValue, duration) {
        let obj = document.getElementById(id);
        let start = 0;
        let increment = endValue / (duration * 60);

        function updateNumber() {
            start += increment;
            if (start < endValue) {
                obj.textContent = Math.floor(start);
                requestAnimationFrame(updateNumber);
            } else {
                obj.textContent = endValue;
            }
        }
        updateNumber();
    }

    animateNumbers("users", 40689, 2);
    animateNumbers("apply", 1093, 2);
    animateNumbers("offers", 89000, 2);
    animateNumbers("enterprises", 2040, 2);
});

