let nCount = selector => {
  $(selector).each(function () {
    $(this)
      .animate({
        Counter: $(this).text()
      }, {
        //xác định thời gian hoạt ảnh sẽ chạy.
        duration: 5000,
        // Một chuỗi cho biết hàm nới lỏng nào sẽ được sử dụng cho quá trình chuyển đổi.
        easing: "swing",
        /**
          * Một hàm được gọi cho từng thuộc tính hoạt hình của từng phần tử hoạt hình. 
          * Chức năng này tạo cơ hội để
          * sửa đổi đối tượng Tween để thay đổi giá trị của thuộc tính trước khi nó được đặt.
          */
        step: function (value) {
          $(this).text(Math.ceil(value));
        }
      });
  });
};

/* Phần loading */
document.body.classList.add('loading');

window.onload = function () {
    setTimeout(function () {
        document.body.classList.remove('loading');
        document.body.classList.add('loaded');
    }, 1500);
};


let a = 0;
$(window).scroll(function () {
  // Phương thức .offset() cho phép chúng ta truy xuất vị trí hiện tại của một phần tử so với tài liệu
  let oTop = $(".numbers").offset().top - window.innerHeight;
  if (a == 0 && $(window).scrollTop() >= oTop) {
    a++;
    nCount(".rect > h1");
  }
});


let navbar = $(".navbar");

$(window).scroll(function () {
  let oTop = $(".section-3").offset().top - window.innerHeight;
  if ($(window).scrollTop() > oTop) {
    navbar.addClass("sticky");
  } else {
    navbar.removeClass("sticky");
  }
});


/***************donate************/
const slider = document.getElementById("donateSlider");
const donateAmount = document.getElementById("donateAmount");
const selectedAmount = document.getElementById("selectedAmount");
const modal = document.getElementById("paymentModal");

// Cập nhật số tiền khi kéo thanh trượt
slider.oninput = function () {
  donateAmount.textContent = "$" + this.value;
  selectedAmount.textContent = "$" + this.value;
};

// Hiển thị pop-up thanh toán
function openModal() {
  console.log("Mở pop-up thanh toán"); // Kiểm tra debug
  modal.style.display = "block";
}

// Đóng pop-up thanh toán
function closeModal() {
  console.log("Đóng pop-up"); // Kiểm tra debug
  modal.style.display = "none";
}

// Xác nhận thanh toán
function confirmDonation() {
  alert("Cảm ơn bạn đã donate " + slider.value + " USD!");
  closeModal();
}

//Scroll xuống khi chọn section-3
function scrollToSection() {
  let target = document.querySelector("#section-3"); // Thay bằng ID của phần muốn cuộn tới
  if (target) {
    let offset = 169; // Điều chỉnh số pixel để cuộn xuống thêm (tăng nếu cần)
    let targetPosition = target.offsetTop - offset;
    window.scrollTo({ top: targetPosition, behavior: "smooth" });
  }
}

//Section-3
document.addEventListener("DOMContentLoaded", function () {
  const newsData = [
    {
      title: "Hội thảo sách hằng năm - Đợt 1",
      desc: {
        time: "10:00 AM, 15/03/2025",
        location: "Thư viện Quốc gia, Hà Nội",
        details: "Hội thảo sách hằng năm là một sự kiện quan trọng nhằm tôn vinh văn hóa đọc và khuyến khích thói quen đọc sách trong cộng đồng. Chương trình năm nay quy tụ nhiều nhà văn, nhà nghiên cứu và chuyên gia xuất bản hàng đầu, hứa hẹn mang đến những góc nhìn mới về ngành xuất bản hiện đại. Ngoài ra, các độc giả cũng sẽ có cơ hội tham gia các buổi tọa đàm, workshop kỹ năng đọc hiệu quả, cũng như giao lưu với các tác giả nổi tiếng. Sự kiện không chỉ dành cho những người yêu sách mà còn cho các nhà xuất bản, nhà giáo dục và những ai quan tâm đến tương lai của sách.",
        contact: "Liên hệ: 0987 654 321"
      }
    },
    {
      title: "Ngày sách",
      desc: {
        time: "08:00 AM, 03/03/2025",
        location: "Nhà Văn hóa Thanh Niên, TP.HCM",
        details: "Ngày sách là một sự kiện thường niên được tổ chức nhằm khuyến khích thói quen đọc sách và truyền bá tri thức đến cộng đồng. Chương trình năm nay sẽ có hàng loạt hoạt động hấp dẫn như hội chợ sách giảm giá, giao lưu tác giả, giới thiệu các đầu sách mới và các buổi tọa đàm về văn học. Ngoài ra, còn có các gian hàng của nhiều nhà xuất bản nổi tiếng, giúp độc giả dễ dàng tìm kiếm và mua sắm những cuốn sách yêu thích. Đây cũng là cơ hội để những người yêu sách chia sẻ niềm đam mê và kết nối với cộng đồng yêu đọc sách.",
        contact: "Liên hệ: 0901 234 567"
      }
    },
    {
      title: "Gặp gỡ tác giả nổi tiếng - Mùa 11",
      desc: {
        time: "14:00 PM, 20/04/2025",
        location: "Cà phê sách ABC, Đà Nẵng",
        details: "Gặp gỡ tác giả nổi tiếng là chuỗi sự kiện được tổ chức thường niên, nơi độc giả có thể giao lưu trực tiếp với những nhà văn, nhà thơ có tầm ảnh hưởng lớn trong nền văn học Việt Nam. Mùa 11 của chương trình năm nay sẽ có sự góp mặt của nhà văn Nguyễn Nhật Ánh, một trong những tác giả được yêu thích nhất với nhiều tác phẩm để đời. Người tham gia sẽ có cơ hội lắng nghe chia sẻ của ông về quá trình sáng tác, những câu chuyện hậu trường thú vị và định hướng văn học trong tương lai. Đây cũng là dịp để độc giả đặt câu hỏi trực tiếp và nhận được chữ ký từ tác giả.",
        contact: "Liên hệ: 0977 123 456"
      }
    },
    {
      title: "Hội thảo sách hằng năm - Đợt 2",
      desc: {
        time: "09:30 AM, 22/06/2025",
        location: "Trung tâm Hội nghị Quốc gia, Hà Nội",
        details: "Đợt 2 của Hội thảo sách hằng năm tiếp tục mang đến những chủ đề nóng hổi về ngành xuất bản và văn hóa đọc trong thời đại số. Năm nay, sự kiện sẽ có các buổi thảo luận với các chuyên gia đến từ nhiều nước, nhằm tìm kiếm giải pháp thúc đẩy ngành xuất bản tại Việt Nam. Ngoài ra, hội thảo còn tổ chức các buổi giới thiệu sách mới và ký tặng của nhiều tác giả nổi tiếng. Đây là cơ hội không thể bỏ lỡ cho các nhà nghiên cứu, giáo viên, sinh viên và những người quan tâm đến sách.",
        contact: "Liên hệ: 0932 456 789"
      }
    },
    {
      title: "Gặp gỡ tác giả nổi tiếng - Mùa 12",
      desc: {
        time: "15:00 PM, 10/08/2025",
        location: "Nhà sách Fahasa, TP.HCM",
        details: "Sự kiện Gặp gỡ tác giả nổi tiếng - Mùa 12 năm nay sẽ có sự tham gia của nhà văn trẻ An Ni Bảo, tác giả của nhiều cuốn sách best-seller về phát triển bản thân. Trong buổi giao lưu, độc giả sẽ được nghe cô chia sẻ về hành trình viết lách, kinh nghiệm vượt qua khó khăn và cách để xây dựng một thói quen đọc sách hiệu quả. Ngoài ra, chương trình cũng có phần hỏi đáp trực tiếp, nơi độc giả có thể đặt câu hỏi và lắng nghe những quan điểm sâu sắc từ tác giả. Cuối chương trình, An Ni Bảo sẽ ký tặng sách cho những người tham dự.",
        contact: "Liên hệ: 0968 789 101"
      }
    },
    {
      title: "Gặp gỡ tác giả nổi tiếng - Mùa 13",
      desc: {
        time: "17:00 PM, 25/09/2025",
        location: "Cà phê sách BookLand, Cần Thơ",
        details: "Chuỗi sự kiện Gặp gỡ tác giả nổi tiếng bước sang mùa 13 với sự tham gia đặc biệt của nhà văn Hồ Anh Thư, tác giả của nhiều cuốn sách nổi tiếng trong thể loại tiểu thuyết lịch sử. Sự kiện lần này không chỉ là buổi giao lưu thông thường mà còn có phần tọa đàm sâu sắc về văn học lịch sử, giúp độc giả hiểu hơn về quá trình nghiên cứu và sáng tác. Bên cạnh đó, buổi gặp gỡ còn mang đến những thông tin thú vị về các dự án sách sắp tới của tác giả. Đây là cơ hội tuyệt vời để độc giả yêu thích văn học lịch sử có thể tìm hiểu và giao lưu với tác giả.",
        contact: "Liên hệ: 0912 345 678"
      }
    }
  ];

  const carousel = document.querySelector("#carouselExample");
  const titleElement = document.querySelector("#news-title");
  const descElement = document.querySelector("#news-desc");
  const newsContainer = document.querySelector(".news-container");
  const section = document.querySelector(".section-3");

  // Hiệu ứng khi cuộn đến phần tin tức
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        newsContainer.classList.add("show");
      } else {
        newsContainer.classList.remove("show");
      }
    });
  }, { threshold: 0.05 });

  observer.observe(section);

  // Xử lý sự kiện thay đổi slide
  carousel.addEventListener("slid.bs.carousel", function (event) {
    const index = [...event.target.querySelectorAll(".carousel-item")].indexOf(event.relatedTarget);
    if (index !== -1) {
      titleElement.textContent = newsData[index].title;
      descElement.innerHTML = `
              <strong>Thời gian:</strong> ${newsData[index].desc.time}<br>
              <strong>Địa điểm:</strong> ${newsData[index].desc.location}<br>
              <strong>Mô tả:</strong> ${newsData[index].desc.details}<br>
              <strong>Thông tin liên hệ:</strong> ${newsData[index].desc.contact}
          `;
    }
  });
});







