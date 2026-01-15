<?php
header('Content-Type: application/json');

// Kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

$sql = "SELECT id_sach, ten_sach, tac_gia, nam_xuat_ban, so_luong, so_luot_xem, id_chu_de, hinh_anh, mo_ta_sach FROM sach";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra nếu hình ảnh là URL hoặc dữ liệu BLOB
        if (!empty($row["hinh_anh"])) {
            if (filter_var($row["hinh_anh"], FILTER_VALIDATE_URL)) {
                $row["hinh_anh"] = $row["hinh_anh"]; // Nếu là URL, giữ nguyên
            } else {
                $row["hinh_anh"] = "data:image/png;base64," . base64_encode($row["hinh_anh"]); // Nếu là BLOB, chuyển sang Base64
            }
        } else {
            $row["hinh_anh"] = ""; // Không có ảnh
        }
        $books[] = $row;
    }
}

echo json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$conn->close();
?>