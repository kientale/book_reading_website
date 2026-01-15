<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "localhost";  // Địa chỉ MySQL
$user = "root";       // Tên người dùng MySQL
$pass = "";           // Mật khẩu MySQL
$dbname = "thu_vien_online";  // Tên CSDL

// Kết nối CSDL
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Lấy tất cả tin tức từ CSDL
$sql = "SELECT id_tin_tuc, tieu_de, mo_ta, hinh_anh, ngay_dang, noi_dung FROM tin_tuc ORDER BY ngay_dang DESC";
$result = $conn->query($sql);

$news = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra nếu hinh_anh là dạng LONGBLOB
        if (!empty($row['hinh_anh'])) {
            $row['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($row['hinh_anh']);
        } else {
            $row['hinh_anh'] = null; // Nếu không có ảnh, trả về null
        }
        $news[] = $row;
    }
}

// Trả về dữ liệu JSON
echo json_encode($news, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
