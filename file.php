<?php
// Thiết lập thông tin kết nối đến database
$servername = "database-server-lab7.cocgl5wbv5ga.ap-southeast-1.rds.amazonaws.com";
$db_username = "admin";
$db_password = "12345678";
$dbname = "myDB";

// Tạo kết nối đến database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Kiểm tra nếu form đã submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị từ form
    $input_username = trim($_POST["username"]);
    $input_password = trim($_POST["password"]);

    // Kiểm tra dữ liệu không rỗng
    if (!empty($input_username) && !empty($input_password)) {
        // Sử dụng prepared statement để chống SQL Injection
        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $input_username, $input_password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra số lượng bản ghi trả về
        if ($result->num_rows > 0) {
            echo "Bạn đã đăng nhập thành công";
            // Có thể chuyển hướng hoặc lưu session ở đây
        } else {
            echo "Bạn đã đăng nhập không thành công";
        }

        $stmt->close();
    } else {
        echo "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>

<body>
    <h2>Đăng nhập</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Tên đăng nhập:</label>
        <input type="text" name="username" required><br><br>

        <label>Mật khẩu:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Đăng nhập">
    </form>
</body>

</html>
