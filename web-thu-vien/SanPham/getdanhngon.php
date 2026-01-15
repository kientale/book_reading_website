<?php
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Truy vấn danh ngôn ngẫu nhiên
$sql = "SELECT * FROM danh_ngon ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "id" => $row["id_danh_ngon"],
        "danh_ngon" => $row["danh_ngon"]  // Sử dụng đúng tên cột trong database
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không tìm thấy danh ngôn"]);
}

$conn->close();
?>
