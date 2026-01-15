<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM nguoi_dung WHERE tai_khoan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password == $row["mat_khau"]) {

            // 🔥 Lưu vào SESSION
            $_SESSION["maNguoiDung"] = $row["ma_nguoi_dung"];
            $_SESSION["hoTen"] = $row["ho_ten"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["tuoi"] = $row["tuoi"];
            $_SESSION["soDienThoai"] = $row["so_dien_thoai"];
            $_SESSION["diaChi"] = $row["dia_chi"];

            // 🔥 Lưu vào COOKIE (nếu cần)
            setcookie("username", $row["tai_khoan"], time() + 86400, "/");
            setcookie("hoTen", $row["ho_ten"], time() + 86400, "/");
            setcookie("email", $row["email"], time() + 86400, "/");
            setcookie("tuoi", $row["tuoi"], time() + 86400, "/");
            setcookie("soDienThoai", $row["so_dien_thoai"], time() + 86400, "/");
            setcookie("diaChi", $row["dia_chi"], time() + 86400, "/");

            // 🔥 Kiểm tra nếu là admin
            if ($username === "admin" && $password === "admin") {
                echo "<script>
                    alert('Đăng nhập thành công với tư cách quản trị viên!');
                    window.location.href = 'http://localhost/PHP-project/Web%20Th%c6%b0%20vi%e1%bb%87n/Quanly-all/Quanly-nguoidung/quanlynguoidung.html';
                </script>";
            } else {
                echo "<script>
                    alert('Đăng nhập thành công!');
                    window.location.href = 'http://localhost/PHP-project/Web%20Th%c6%b0%20vi%e1%bb%87n/trangchu/trangchu.html';
                </script>";
            }
            exit();
        } else {
            echo "<script>
                alert('Mật khẩu không đúng!');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Tài khoản không tồn tại!');
            window.history.back();
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>