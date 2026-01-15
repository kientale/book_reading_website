<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy 10 cuốn sách mới nhất + Tên chủ đề
$sql = "SELECT sach.ten_sach, sach.tac_gia, sach.nam_xuat_ban, sach.so_luong, 
               sach.so_luot_xem, sach.hinh_anh, chu_de.ten_chu_de 
        FROM sach 
        JOIN chu_de ON sach.id_chu_de = chu_de.id_chu_de
        ORDER BY sach.nam_xuat_ban DESC 
        LIMIT 10";

$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Chuyển ảnh dạng BLOB sang base64
        if (!empty($row['hinh_anh'])) {
            $row['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($row['hinh_anh']);
        } else {
            $row['hinh_anh'] = null;
        }
        $books[] = $row;
    }
}

// Trả về dữ liệu dạng JSON
header('Content-Type: application/json');
echo json_encode($books, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
