<?php
header("Content-Type: application/json");

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Truy vấn lấy ngẫu nhiên 4 cuốn sách, bao gồm cả id_sach
$sql = "SELECT sach.id_sach, sach.ten_sach, sach.tac_gia, sach.hinh_anh, sach.nam_xuat_ban, 
               sach.so_luong, sach.so_luot_xem, chu_de.ten_chu_de AS chu_de, sach.mo_ta_sach 
        FROM sach 
        JOIN chu_de ON sach.id_chu_de = chu_de.id_chu_de 
        ORDER BY RAND() 
        LIMIT 4";

$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Lỗi truy vấn: " . $conn->error]));
}

$sach_goi_y = [];
while ($row = $result->fetch_assoc()) {
    // Chuyển đổi ảnh từ BLOB sang base64
    if (!empty($row['hinh_anh'])) {
        $row['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($row['hinh_anh']);
    } else {
        $row['hinh_anh'] = null;
    }

    $sach_goi_y[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($sach_goi_y, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
?>
