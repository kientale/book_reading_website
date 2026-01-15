<?php
header('Content-Type: application/json; charset=utf-8');

// Thông tin kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

// Kết nối tới MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "❌ Kết nối thất bại: " . $conn->connect_error]));
}
$conn->set_charset("utf8");

// Truy vấn dữ liệu từ bảng lien_he
$sql = "SELECT hoten, email, sdt, diachi, loinhan FROM lien_he";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>
