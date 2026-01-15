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

// Nhận dữ liệu từ AJAX (JSON)
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data["maNguoiDung"], $data["taiKhoan"], $data["matKhau"], $data["hoTen"], $data["tuoi"], 
          $data["email"], $data["soDienThoai"], $data["diaChi"])
) {
    $maNguoiDung = $conn->real_escape_string($data["maNguoiDung"]);
    $taiKhoan     = $conn->real_escape_string($data["taiKhoan"]);
    $matKhau      = $conn->real_escape_string($data["matKhau"]);
    $hoTen        = $conn->real_escape_string($data["hoTen"]);
    $tuoi         = (int)$data["tuoi"];
    $email        = $conn->real_escape_string($data["email"]);
    $soDienThoai  = $conn->real_escape_string($data["soDienThoai"]);
    $diaChi       = $conn->real_escape_string($data["diaChi"]);

    // Kiểm tra trống (tầng server, đề phòng bypass client)
    if (empty($maNguoiDung) || empty($taiKhoan) || empty($matKhau) ||
        empty($hoTen) || empty($tuoi) || empty($email) ||
        empty($soDienThoai) || empty($diaChi)) {
        echo json_encode(["error" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    // Kiểm tra định dạng email hợp lệ
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Email không hợp lệ! Vui lòng nhập đúng định dạng."]);
        exit;
    }

    // Kiểm tra số điện thoại: Chỉ chứa số & độ dài từ 10-11 ký tự
    if (!preg_match('/^[0-9]{10,11}$/', $soDienThoai)) {
        echo json_encode(["error" => "Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số."]);
        exit;
    }

    // Kiểm tra mã người dùng đã tồn tại
    $sqlCheck = "SELECT ma_nguoi_dung FROM nguoi_dung WHERE ma_nguoi_dung = '$maNguoiDung'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        echo json_encode(["error" => "Mã người dùng đã tồn tại!"]);
        exit;
    }

    // Thực hiện thêm mới
    $sql = "INSERT INTO nguoi_dung (ma_nguoi_dung, tai_khoan, mat_khau, ho_ten, tuoi, email, so_dien_thoai, dia_chi)
            VALUES ('$maNguoiDung', '$taiKhoan', '$matKhau', '$hoTen', $tuoi, '$email', '$soDienThoai', '$diaChi')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Thêm người dùng thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm người dùng: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>
