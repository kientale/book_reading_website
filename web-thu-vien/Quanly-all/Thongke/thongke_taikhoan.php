<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Kết nối Database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'thu_vien_online';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Truy vấn tổng số tài khoản
$sql_total_users = "SELECT COUNT(*) AS total_users FROM nguoi_dung";
$result_total = $conn->query($sql_total_users);
$total_users = $result_total ? $result_total->fetch_assoc()["total_users"] : 0;

// Truy vấn dữ liệu độ tuổi và địa chỉ
$sql_users = "SELECT tuoi, TRIM(dia_chi) AS dia_chi FROM nguoi_dung";
$result_users = $conn->query($sql_users);

$age_groups = ["Dưới 20" => 0, "20-30" => 0, "30-40" => 0, "Trên 40" => 0];
$address_counts = [];

if ($result_users) {
    while ($row = $result_users->fetch_assoc()) {
        // Thống kê độ tuổi
        $age = intval($row["tuoi"]);
        if ($age < 20)
            $age_groups["Dưới 20"]++;
        elseif ($age <= 30)
            $age_groups["20-30"]++;
        elseif ($age <= 40)
            $age_groups["30-40"]++;
        else
            $age_groups["Trên 40"]++;

        // Thống kê địa chỉ
        $address = $row["dia_chi"];
        if (!empty($address)) {
            $address_counts[$address] = ($address_counts[$address] ?? 0) + 1;
        }
    }
} else {
    die(json_encode(["success" => false, "error" => "Lỗi truy vấn dữ liệu"]));
}

// Trả về dữ liệu JSON
echo json_encode([
    "success" => true,
    "total_users" => $total_users,
    "age_groups" => $age_groups,
    "address_counts" => $address_counts
]);

$conn->close();
?>