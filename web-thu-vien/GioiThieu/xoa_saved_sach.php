<?php
session_start();
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Kiểm tra session đăng nhập
if (!isset($_SESSION["maNguoiDung"])) {
    echo json_encode(["success" => false, "message" => "Bạn chưa đăng nhập!"]);
    exit;
}

$ma_nguoi_dung = $_SESSION["maNguoiDung"];

// Nhận dữ liệu JSON từ JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["ten_sach"])) {
    echo json_encode(["success" => false, "message" => "Tên sách không hợp lệ"]);
    exit;
}

$ten_sach = trim($data["ten_sach"]);

// Xóa sách trong database
$sql = "DELETE FROM saved_books WHERE book_title = ? AND ma_nguoi_dung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $ten_sach, $ma_nguoi_dung);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Sách đã bị xóa"]);
} else {
    echo json_encode(["success" => false, "message" => "Không tìm thấy sách hoặc lỗi xóa"]);
}

$stmt->close();
$conn->close();
?>
