<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]);
    exit;
}
$conn->set_charset("utf8");

// Kiểm tra dữ liệu đầu vào
if (!isset($_POST["idSach"])) {
    echo json_encode(["error" => "Thiếu dữ liệu sách!"]);
    exit;
}

$id_sach = $conn->real_escape_string($_POST["idSach"]);
$ten_sach = $conn->real_escape_string($_POST["tenSach"]);
$tac_gia = $conn->real_escape_string($_POST["tacGia"]);
$nam_xuat_ban = intval($_POST["namXuatBan"]);
$so_luong = intval($_POST["soLuong"]);
$so_luot_xem = intval($_POST["soLuotXem"]);
$id_chu_de = intval($_POST["idChuDe"]);
$mo_ta_sach = $conn->real_escape_string($_POST["moTaSach"]);

// Xử lý file ảnh
if (isset($_FILES["hinhAnh"]) && $_FILES["hinhAnh"]["error"] === UPLOAD_ERR_OK) {
    $hinhAnhData = file_get_contents($_FILES["hinhAnh"]["tmp_name"]);
    $hinhAnhData = $conn->real_escape_string($hinhAnhData);

    $sql = "UPDATE sach SET 
        ten_sach = '$ten_sach', 
        tac_gia = '$tac_gia', 
        nam_xuat_ban = $nam_xuat_ban, 
        so_luong = $so_luong, 
        so_luot_xem = $so_luot_xem, 
        id_chu_de = $id_chu_de, 
        mo_ta_sach = '$mo_ta_sach', 
        hinh_anh = '$hinhAnhData'
        WHERE id_sach = $id_sach";
} else {
    // Không có ảnh mới, chỉ cập nhật thông tin sách
    $sql = "UPDATE sach SET 
        ten_sach = '$ten_sach', 
        tac_gia = '$tac_gia', 
        nam_xuat_ban = $nam_xuat_ban, 
        so_luong = $so_luong, 
        so_luot_xem = $so_luot_xem, 
        id_chu_de = $id_chu_de, 
        mo_ta_sach = '$mo_ta_sach'
        WHERE id_sach = $id_sach";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Lỗi cập nhật: " . $conn->error]);
}

$conn->close();
?>
