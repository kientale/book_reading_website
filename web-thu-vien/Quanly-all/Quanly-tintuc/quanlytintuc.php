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

// Truy vấn lấy danh sách tin tức
$sql = "SELECT id_tin_tuc, tieu_de, noi_dung, hinh_anh, ngay_dang, mo_ta FROM tin_tuc";
$result = $conn->query($sql);

$news = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Debug xem dữ liệu gốc của ảnh là gì
        error_log("Dữ liệu ảnh trước khi encode: " . print_r($row['hinh_anh'], true));

        // Kiểm tra nếu hình ảnh là dạng BLOB thì mã hóa sang base64
        if (!empty($row['hinh_anh'])) {
            $row['hinh_anh'] = 'data:image/jpeg;base64,' . base64_encode($row['hinh_anh']);
        }

        // Debug xem ảnh đã được encode chưa
        error_log("Dữ liệu ảnh sau khi encode: " . substr($row['hinh_anh'], 0, 100) . "...");

        $news[] = $row;
    }
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$conn->close();
?>
