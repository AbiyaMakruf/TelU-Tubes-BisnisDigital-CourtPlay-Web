document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector(".navbar-scroll");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const main = document.querySelector("main");

    // Saat halaman dimuat, tambahkan fade-in
    requestAnimationFrame(() => {
        main.classList.add("fade-in");
    });

    // Saat klik link internal, kasih efek fade-out
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (e) {
            const target = this.getAttribute("href");

            // pastikan bukan link eksternal atau #
            if (target && !target.startsWith("#") && !target.startsWith("http")) {
                e.preventDefault();

                main.classList.remove("fade-in");

                setTimeout(() => {
                    window.location.href = target;
                }, 500); // sama dengan transition di CSS
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(function (icon) {
        const input = document.querySelector(icon.getAttribute("data-toggle"));

        icon.addEventListener("click", function () {
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });
    });
});

