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
    isset(
    $data["maNguoiDung"],
    $data["taiKhoan"],
    $data["matKhau"],
    $data["hoTen"],
    $data["tuoi"],
    $data["email"],
    $data["soDienThoai"],
    $data["diaChi"]
)
) {
    $maNguoiDung = $conn->real_escape_string($data["maNguoiDung"]);
    $taiKhoan = $conn->real_escape_string($data["taiKhoan"]);
    $matKhau = $conn->real_escape_string($data["matKhau"]);
    $hoTen = $conn->real_escape_string($data["hoTen"]);
    $tuoi = (int) $data["tuoi"];
    $email = $conn->real_escape_string($data["email"]);
    $soDienThoai = $conn->real_escape_string($data["soDienThoai"]);
    $diaChi = $conn->real_escape_string($data["diaChi"]);

    // Kiểm tra trống
    if (
        empty($maNguoiDung) || empty($taiKhoan) || empty($matKhau) ||
        empty($hoTen) || empty($tuoi) || empty($email) ||
        empty($soDienThoai) || empty($diaChi)
    ) {
        echo json_encode(["error" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit();
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Email không hợp lệ! Vui lòng nhập đúng định dạng."]);
        exit();
    }

    // Kiểm tra số điện thoại (10-11 số)
    if (!preg_match('/^[0-9]{10,11}$/', $soDienThoai)) {
        echo json_encode(["error" => "Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số."]);
        exit();
    }

    // Kiểm tra xem người dùng có tồn tại không
    $checkUser = $conn->query("SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = '$maNguoiDung'");
    if ($checkUser->num_rows == 0) {
        echo json_encode(["error" => "Mã người dùng không tồn tại!"]);
        exit();
    }

    // Cập nhật thông tin người dùng (KHÔNG mã hóa mật khẩu)
    $sql = "UPDATE nguoi_dung 
            SET tai_khoan='$taiKhoan', mat_khau='$matKhau', ho_ten='$hoTen', tuoi=$tuoi, 
                email='$email', so_dien_thoai='$soDienThoai', dia_chi='$diaChi'
            WHERE ma_nguoi_dung='$maNguoiDung'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Cập nhật người dùng thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi cập nhật người dùng: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

$conn->close();
?>