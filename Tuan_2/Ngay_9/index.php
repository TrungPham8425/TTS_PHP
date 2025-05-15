<?php
# 1. Kết nối csdl
// Dùng DNS để kết nối với csdl
// Dùng new PDO(...) để khởi tạo kết nối
// Có thể dùng error mode để hiển thị lỗi rõ hơn
// VD:
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS tt_ngay_9");
    echo "Tạo CSDL thành công<hr>";
} catch (PDOException $e) {
    die("Lỗi tạo CSDL: " . $e->getMessage());
}

# 2. Tạo csdl(create db)
// CSDL được tạo bằng lênh "CREATE DATABASE"
// Để kiểm tra CSDL có tồn tại hay chưa trước khi tạo dùng "IF NOT EXISTS"
// VD:
$dns = 'mysql:host=localhost;dbname=tt_ngay_9;charset=utf8mb4';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dns, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kết nối CSDL thành công<hr>";
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
# 3. Tạo bảng 
// Sử dụng CREATE_TABLE để tạo bảng
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
)";
try {
    $pdo->exec($sql);
    echo "Tạo bảng 'users' thành công<hr>";
} catch (PDOException $e) {
    die("Lỗi tạo bảng: " . $e->getMessage());
}

# 4. insert data
// Dùng INSERT INTO để thêm dữ liệu vào bảng
$sql = "INSERT INTO users (name, email) VALUES ('Nguyen Van A', 'nguyenvana@gmail.com')";
$pdo->exec($sql);

# 5. Get last id (Lấy ID cuối)
// Dùng lastInsertID() để lấy ID cuối trong bảng
$lastId = $pdo->lastInsertId();
echo "ID cuối trong bảng: " . $lastId;

# 6. Insert Multiple( chèn nhiều dòng)
$sql = "INSERT INTO users (name, email) VALUES
    ('Nguyen Van B', 'nguyenvanb@gmail.com'),
    ('Nguyen Van C', 'nguyenvanc@gmail.com')";
$pdo->exec($sql);
echo "<hr>";
# 7. Prepared (Chuẩn bị câu lệnh)
$stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->execute(['David', 'david@example.com']);

# 8 .Select data(Lấy dữ liệu)
// Dùng SELECT để lấy dữ liệu
$stmt = $pdo->query("SELECT * FROM users");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' - ' . $row['name'] . ' - ' . $row['email'] . '<br>';
}
echo "<hr>";

# 9. Where
$stmt = $pdo->query("SELECT * FROM users WHERE id = 1");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

# 10. Oder by
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' - ' . $row['name'] . ' - ' . $row['email'] . '<br>';
}
echo "<hr>";

# 11. Delete Data
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([2]);

# 12. Update Data
$stmt = $pdo->prepare("UPDATE users SET email = ? WHERE name = ?");
$stmt->execute(['newalice@example.com', 'Alice']);

# 13. Limit Data
$stmt = $pdo->query("SELECT * FROM users LIMIT 2");
$limited = $stmt->fetchAll(PDO::FETCH_ASSOC);
