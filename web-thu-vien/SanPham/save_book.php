<?php
session_start();

// Debug để kiểm tra session
if (!isset($_SESSION['maNguoiDung'])) {
    echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập"]);
    exit;
}

$ma_nguoi_dung = $_SESSION['maNguoiDung']; // Lấy ID người dùng từ session

// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Nhận dữ liệu từ JavaScript
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["title"], $data["author"], $data["year"], $data["image_url"])) {
    echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ"]);
    exit;
}

$book_title = $data["title"];
$author = $data["author"];
$year = intval($data["year"]);
$image_url = $data["image_url"];

// Chèn sách vào database
$sql = "INSERT INTO saved_books (ma_nguoi_dung, book_title, author, year, image_url) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issis", $ma_nguoi_dung, $book_title, $author, $year, $image_url);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Lưu sách thành công"]);
} else {
    echo json_encode(["status" => "error", "message" => "Lưu sách thất bại: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>
