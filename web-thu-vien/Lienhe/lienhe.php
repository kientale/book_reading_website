<?php
// Kết nối đến cơ sở dữ liệu MySQL
$servername = "localhost";  // Thay đổi theo máy chủ của bạn
$username = "root";         // Thay đổi theo tài khoản của bạn
$password = "";             // Thay đổi theo mật khẩu của bạn
$dbname = "thu_vien_online";  // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu có dữ liệu POST gửi lên từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin người dùng từ form
    $hoten = $_POST["name"];
    $email = $_POST["email"];
    $sdt = $_POST["phone"];
    $dichi = $_POST["address"];
    $loinhan = $_POST["message"];

    // Thực hiện lưu dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO lien_he (hoten, email, sdt, diachi, loinhan)
            VALUES ('$hoten', '$email', '$sdt', '$dichi', '$loinhan')";

    // Kiểm tra nếu câu lệnh SQL thực thi thành công
    if ($conn->query($sql) === TRUE) {
        // Nếu dữ liệu đã được lưu thành công, hiển thị thông báo thành công
        echo "<div class='container mt-5'>
                <div class='alert alert-success'>
                    <strong>Gửi thành công!</strong> Cảm ơn bạn đã liên hệ với chúng tôi.
                </div>
              </div>";
    } else {
        // Hiển thị lỗi chi tiết nếu không thành công
        echo "<div class='container mt-5'>
                <div class='alert alert-danger'>
                    <strong>Lỗi:</strong> " . $conn->error . "
                </div>
              </div>";
    }
}

// Đóng kết nối CSDL
$conn->close();
?>
