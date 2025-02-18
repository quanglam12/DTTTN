document.querySelectorAll(".tab-link").forEach(button => {
    button.addEventListener("click", function () {
        document.querySelectorAll(".tab-link").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));

        this.classList.add("active");
        document.getElementById(this.dataset.tab).classList.add("active");
    });
});