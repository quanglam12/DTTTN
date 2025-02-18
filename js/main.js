// Lấy các phần tử cần thiết
const menuToggle = document.getElementById("menu-toggle");
const menu = document.getElementById("menu");
const dropdowns = document.querySelectorAll(".menu .dropdown");
/*
function handleResize() {
    if (window.innerWidth >= 768) {
        // Khi màn hình lớn, xóa thuộc tính để menu hoạt động như desktop
        menu.style.maxHeight = "";
        menu.style.opacity = "";
        menu.classList.remove("active");
    } else {
        // Khi màn hình nhỏ, đặt lại trạng thái ban đầu nếu menu chưa mở
        if (!menu.classList.contains("active")) {
            menu.style.maxHeight = "0";
            menu.style.opacity = "0";
        }
    }
}
//document.addEventListener("DOMContentLoaded", handleResize);
//window.addEventListener("resize", handleResize);
*/
// Xử lý mở/đóng navbar
menuToggle.addEventListener("click", (e) => {
    const isActive = menu.classList.contains("active");
    if (isActive) {
        menu.classList.remove("active");

    } else {
        menu.classList.add("active");

    }
});

// Xử lý mở/đóng menu con
dropdowns.forEach((dropdown) => {
    const dropdownMenu = dropdown.querySelector(".dropdown-menu");

    dropdown.addEventListener("click", function (e) {
        e.stopPropagation();
        const isActive = dropdown.classList.contains("active");

        // Đóng tất cả menu con khác trước khi mở menu được click
        dropdowns.forEach((d) => {
            d.classList.remove("active");
        });

        // Mở menu con nếu chưa active
        if (!isActive) {
            dropdown.classList.add("active");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var backToTopButton = document.getElementById("backToTop");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 124) {
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    });

    backToTopButton.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
