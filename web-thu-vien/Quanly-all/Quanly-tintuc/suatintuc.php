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

// Nhận dữ liệu JSON từ yêu cầu POST
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data["idTinTuc"], $data["tieuDe"], $data["noiDung"], 
          $data["hinhAnh"], $data["ngayDang"], $data["moTaTin"])
) {
    $idTinTuc = $conn->real_escape_string($data["idTinTuc"]);
    $tieuDe = $conn->real_escape_string($data["tieuDe"]);
    $noiDung = $conn->real_escape_string($data["noiDung"]);
    $hinhAnh = $conn->real_escape_string($data["hinhAnh"]);
    $ngayDang = $conn->real_escape_string($data["ngayDang"]);
    $moTaTin = $conn->real_escape_string($data["moTaTin"]);

    // Kiểm tra xem tin tức có tồn tại không
    $checkNews = $conn->query("SELECT * FROM tin_tuc WHERE id_tin_tuc = '$idTinTuc'");
    if ($checkNews->num_rows == 0) {
        echo json_encode(["error" => "Mã tin tức không tồn tại!"]);
        exit();
    }

    // Cập nhật thông tin tin tức
    $sql = "UPDATE tin_tuc 
            SET tieu_de='$tieuDe', noi_dung='$noiDung', hinh_anh='$hinhAnh', 
                ngay_dang='$ngayDang', mo_ta='$moTaTin'
            WHERE id_tin_tuc='$idTinTuc'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Cập nhật tin tức thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi cập nhật tin tức: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
