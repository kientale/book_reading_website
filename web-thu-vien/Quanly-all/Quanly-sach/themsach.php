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

// Kiểm tra dữ liệu đầu vào
if (
    isset(
        $_POST["idSach"],
        $_POST["tenSach"],
        $_POST["tacGia"],
        $_POST["namXuatBan"],
        $_POST["soLuong"],
        $_POST["idChuDe"],
        $_POST["moTaSach"]
    ) && isset($_FILES["hinhAnh"])
) {
    $idSach     = (int)$_POST["idSach"];
    $tenSach    = $conn->real_escape_string($_POST["tenSach"]);
    $tacGia     = $conn->real_escape_string($_POST["tacGia"]);
    $namXuatBan = (int)$_POST["namXuatBan"];
    $soLuong    = (int)$_POST["soLuong"];
    $idChuDe    = (int)$_POST["idChuDe"];
    $moTaSach   = $conn->real_escape_string($_POST["moTaSach"]);

    // 1) Kiểm tra lỗi khi upload file
    if ($_FILES["hinhAnh"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(["error" => "Lỗi khi tải ảnh lên! Mã lỗi: " . $_FILES["hinhAnh"]["error"]]);
        exit;
    }

    // 2) Đọc dữ liệu nhị phân của ảnh
    $hinhAnh = file_get_contents($_FILES["hinhAnh"]["tmp_name"]);
    if (!$hinhAnh) {
        echo json_encode(["error" => "Không thể đọc dữ liệu ảnh hoặc ảnh trống!"]);
        exit;
    }

    // 3) Kiểm tra sách đã tồn tại chưa
    $sqlCheck = "SELECT id_sach FROM sach WHERE id_sach = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $idSach);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if ($resultCheck->num_rows > 0) {
        echo json_encode(["error" => "ID sách đã tồn tại!"]);
        exit;
    }
    $stmtCheck->close();

    // 4) Tạo câu lệnh INSERT
    $sql = "INSERT INTO sach (
                id_sach, ten_sach, tac_gia, nam_xuat_ban, so_luong, 
                id_chu_de, so_luot_xem, hinh_anh, mo_ta_sach
            ) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)";

    // 5) Chuẩn bị statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["error" => "Lỗi prepare: " . $conn->error]);
        exit;
    }

    // - Có 8 dấu '?' -> tương ứng "issiiibs"
    //   1) i: idSach
    //   2) s: tenSach
    //   3) s: tacGia
    //   4) i: namXuatBan
    //   5) i: soLuong
    //   6) i: idChuDe
    //   7) b: hinhAnh (BLOB)
    //   8) s: moTaSach
    //
    // - Ta bind tạm một biến NULL ở vị trí ảnh, rồi sẽ dùng send_long_data()
    $null = null;
    $stmt->bind_param("issiiibs",
        $idSach,
        $tenSach,
        $tacGia,
        $namXuatBan,
        $soLuong,
        $idChuDe,
        $null,       // Ảnh tạm thời
        $moTaSach
    );

    // 6) Gửi dữ liệu ảnh BLOB
    // Tham số thứ 7 (zero-based index = 6) là "b"
    $stmt->send_long_data(6, $hinhAnh);

    // 7) Thực thi
    if ($stmt->execute()) {
        echo json_encode(["success" => "Thêm sách thành công!"]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm sách: " . $stmt->error]);
    }

    // Đóng statement
    $stmt->close();
} else {
    echo json_encode(["error" => "Dữ liệu không hợp lệ!"]);
}

// Đóng kết nối
$conn->close();
