<?php
// Kết nối MySQL
$servername = "localhost";
$username = "root";  // Thay bằng username thực tế
$password = "";       // Thay bằng mật khẩu thực tế
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy 8 tin tức ngẫu nhiên
$sql = "SELECT id_tin_tuc, tieu_de, noi_dung, hinh_anh, ngay_dang, mo_ta FROM tin_tuc ORDER BY RAND() LIMIT 8";
$result = $conn->query($sql);

$news = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Chuyển ảnh dạng BLOB sang base64
        if (!empty($row['hinh_anh'])) {
            $row['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($row['hinh_anh']);
        } else {
            $row['hinh_anh'] = null;
        }
        $news[] = $row;
    }
}

// Trả về dữ liệu dạng JSON
header('Content-Type: application/json');
echo json_encode($news, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
