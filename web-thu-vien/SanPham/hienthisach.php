<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Số sách trên mỗi trang
$books_per_page = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $books_per_page;

// Xác định tiêu chí sắp xếp
$order_by = "so_luot_xem DESC";
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    if ($sort == "year") {
        $order_by = "nam_xuat_ban DESC";
    } elseif ($sort == "quantity") {
        $order_by = "so_luong DESC";
    }
}

// Truy vấn dữ liệu có phân trang + lấy id_sach + tên chủ đề
$sql = "SELECT sach.id_sach, sach.ten_sach, sach.tac_gia, sach.nam_xuat_ban, sach.so_luong, 
               sach.so_luot_xem, sach.hinh_anh, chu_de.ten_chu_de 
        FROM sach 
        JOIN chu_de ON sach.id_chu_de = chu_de.id_chu_de
        ORDER BY $order_by 
        LIMIT $books_per_page OFFSET $offset";

$result = $conn->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    // Chuyển đổi ảnh BLOB sang base64
    if (!empty($row['hinh_anh'])) {
        $row['hinh_anh'] = "data:image/jpeg;base64," . base64_encode($row['hinh_anh']);
    } else {
        $row['hinh_anh'] = null;
    }
    $books[] = $row;
}

// Lấy tổng số sách để tính số trang
$total_sql = "SELECT COUNT(*) as total FROM sach";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $books_per_page);

// Trả về JSON đầy đủ, bao gồm ID sách để đọc
header('Content-Type: application/json');
echo json_encode([
    "books" => $books,
    "totalPages" => $total_pages
], JSON_UNESCAPED_UNICODE);

$conn->close();
?>