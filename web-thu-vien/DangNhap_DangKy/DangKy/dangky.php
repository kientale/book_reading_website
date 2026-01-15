<?php
header('Content-Type: application/json');

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thu_vien_online";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Hàm tạo mã người dùng ngẫu nhiên từ 1 đến 49 (chưa tồn tại)
function generateUserId($conn) {
    do {
        $random_id = rand(1, 49);
        $sql_check = "SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = $random_id";
        $result = $conn->query($sql_check);
    } while ($result->num_rows > 0);
    return $random_id;
}

// Xử lý khi nhận được dữ liệu từ AJAX
$data = json_decode(file_get_contents("php://input"), true);
if ($data) {
    $ma_nguoi_dung = generateUserId($conn);
    $tai_khoan = $data["username"];
    $mat_khau = $data["password"];
    $ho_ten = $data["fullname"];
    $tuoi = intval($data["age"]); // Chuyển đổi thành số nguyên
    $email = $data["email"];
    $so_dien_thoai = $data["phone"];
    $dia_chi = $data["address"];

    // Kiểm tra tuổi hợp lệ
    if ($tuoi < 13 || $tuoi > 100) {
        echo json_encode(["status" => "error", "message" => "Tuổi phải từ 13 đến 100!"]);
        exit();
    }

    // Kiểm tra tài khoản đã tồn tại
    $sql_check = "SELECT * FROM nguoi_dung WHERE tai_khoan = '$tai_khoan'";
    $result = $conn->query($sql_check);
    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Tài khoản đã tồn tại!"]);
        exit();
    }

    // Chèn dữ liệu vào database
    $sql = "INSERT INTO nguoi_dung (ma_nguoi_dung, tai_khoan, mat_khau, ho_ten, tuoi, email, so_dien_thoai, dia_chi) 
            VALUES ('$ma_nguoi_dung', '$tai_khoan', '$mat_khau', '$ho_ten', '$tuoi', '$email', '$so_dien_thoai', '$dia_chi')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Đăng ký thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi: " . $conn->error]);
    }
}

$conn->close();
?>
