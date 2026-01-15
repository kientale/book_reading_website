<?php
// Kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

header("Content-Type: application/json");

// Kiểm tra xem có dữ liệu gửi đến không
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["maNguoiDung"])) {
        echo json_encode(["error" => "Thiếu mã người dùng"]);
        exit();
    }

    $maNguoiDung = $data["maNguoiDung"];

    // Kiểm tra xem có người dùng khác có cùng mã người dùng hay không
    $stmt = $conn->prepare("SELECT COUNT(*) FROM nguoi_dung WHERE ma_nguoi_dung = ? AND ma_nguoi_dung != ?");
    $stmt->bind_param("ss", $maNguoiDung, $maNguoiDung);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
} else {
    echo json_encode(["error" => "Phương thức không hợp lệ"]);
}
?>