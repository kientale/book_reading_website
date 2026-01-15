<?php
session_start();

// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['maNguoiDung'])) {
    echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập"]);
    exit;
}

$ma_nguoi_dung = $_SESSION['maNguoiDung'];

// Lấy danh sách sách đã lưu
$sql = "SELECT id, book_title, author, year, image_url FROM saved_books WHERE ma_nguoi_dung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ma_nguoi_dung);
if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Lỗi truy vấn: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);

// Đóng kết nối
$stmt->close();
$conn->close();

// Trả về JSON danh sách sách đã lưu
echo json_encode(["status" => "success", "books" => $books]);
?>
