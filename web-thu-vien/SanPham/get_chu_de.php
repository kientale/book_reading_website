<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root"; // Thay bằng user của bạn
$password = ""; // Thay bằng mật khẩu nếu có
$database = "thu_vien_online"; // Thay bằng tên CSDL

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT id_chu_de, ten_chu_de FROM chu_de";
$result = $conn->query($sql);

$chuDeList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chuDeList[] = $row;
    }
}

$conn->close(); // Đóng kết nối

echo json_encode($chuDeList);
?>