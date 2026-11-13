document.addEventListener("DOMContentLoaded", function () {

    /* =========================================
       MOBILE MENU
    ========================================= */

    const menuButton = document.getElementById("menuButton");
    const mobileMenu = document.getElementById("mobileMenu");

    if (menuButton && mobileMenu) {

        menuButton.addEventListener("click", function () {

            mobileMenu.classList.toggle("active");

        });

    }


    /* =========================================
       SCROLL REVEAL
    ========================================= */

    const reveals = document.querySelectorAll(".reveal");

    const revealObserver = new IntersectionObserver(
        function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {

                    entry.target.classList.add("active");

                }

            });

        },
        {
            threshold: 0.1
        }
    );


    reveals.forEach(function (element) {

        revealObserver.observe(element);

    });

});