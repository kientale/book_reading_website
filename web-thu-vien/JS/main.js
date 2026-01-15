/*HEADER */
window.onscroll = function () {
    const header = document.querySelector('header');
    const sticky = header.offsetTop;

    if (window.pageYOffset > sticky) {
        header.classList.add("header-fixed");
    } else {
        header.classList.remove("header-fixed");
    }
};

document.body.classList.add('loading');

document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        document.body.classList.remove('loading');
        document.body.classList.add('loaded');
    }, 1500);
    console.log("Trang đã tải xong!");
});


//Sửa 1-in đậm menu lúc click vào chọn
document.addEventListener("DOMContentLoaded", function () {
    let menuItems = document.querySelectorAll(".nav-link");
    let currentURL = window.location.pathname;
    menuItems.forEach(item => {
        if (item.getAttribute("href") === currentURL) {
            item.classList.add("active");  
        } else {
            item.classList.remove("active"); 
        }
    });
});