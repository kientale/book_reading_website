<?php
session_start();

// Xóa toàn bộ SESSION
session_unset();
session_destroy();

// Xóa toàn bộ COOKIE (nếu có)
setcookie("username", "", time() - 3600, "/");
setcookie("hoTen", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");
setcookie("tuoi", "", time() - 3600, "/");
setcookie("soDienThoai", "", time() - 3600, "/");
setcookie("diaChi", "", time() - 3600, "/");

// Hiển thị thông báo và chuyển hướng về trang chủ
echo "<script>
    alert('Bạn đã đăng xuất thành công!');
    window.location.href = 'http://localhost/PHP-project/Web%20Th%c6%b0%20vi%e1%bb%87n/trangchu/trangchu.html';
</script>";
exit();
?>
