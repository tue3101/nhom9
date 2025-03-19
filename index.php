<?php
$apiUrl = "https://67da2eee35c87309f52b4803.mockapi.io/User";

// Lấy danh sách người dùng
function getUsers() {
    global $apiUrl;
    return json_decode(file_get_contents($apiUrl), true);
}

// Thêm hoặc cập nhật người dùng
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $mssv = $_POST["mssv"];

    if (!empty($_POST["id"])) {
        // Sửa người dùng
        $id = $_POST["id"];
        $data = json_encode(["name" => $name, "mssv" => $mssv]);
        $options = [
            "http" => [
                "method"  => "PUT",
                "header"  => "Content-Type: application/json",
                "content" => $data
            ]
        ];
        file_get_contents("$apiUrl/$id", false, stream_context_create($options));
    } else {
        // Thêm người dùng
        $data = json_encode(["name" => $name, "mssv" => $mssv]);
        $options = [
            "http" => [
                "method"  => "POST",
                "header"  => "Content-Type: application/json",
                "content" => $data
            ]
        ];
        file_get_contents($apiUrl, false, stream_context_create($options));
    }
    header("Location: index.php");
    exit();
}

// Xóa người dùng
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $options = [
        "http" => [
            "method"  => "DELETE",
            "header"  => "Content-Type: application/json"
        ]
    ];
    file_get_contents("$apiUrl/$id", false, stream_context_create($options));
    header("Location: index.php");
    exit();
}

// Lấy danh sách người dùng
$users = getUsers();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng (Nhóm 9 - Sáng thứ bốn)</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 60%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 10px; }
        form { margin: 20px auto; width: 50%; text-align: left; }
        input, button { padding: 8px; margin: 5px; }
    </style>
</head>
<body>

<h2>Quản Lý Người Dùng</h2>

<!-- Form thêm/sửa người dùng -->
<form method="POST">
    <input type="hidden" id="id" name="id">
    <label>Tên: <input type="text" id="name" name="name" required></label><br>
    <label>MSSV: <input type="text" id="mssv" name="mssv" required></label><br>
    <button type="submit">Lưu</button>
    <button type="button" onclick="resetForm()">Hủy</button>
</form>

<!-- Bảng danh sách người dùng -->
<table>
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>MSSV</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user["id"] ?></td>
        <td><?= htmlspecialchars($user["name"]) ?></td>
        <td><?= htmlspecialchars($user["mssv"]) ?></td>
        <td>
            <button onclick="editUser('<?= $user["id"] ?>', '<?= htmlspecialchars($user["name"]) ?>', '<?= htmlspecialchars($user["mssv"]) ?>')">Sửa</button>
            <a href="?delete=<?= $user["id"] ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    function editUser(id, name, mssv) {
        document.getElementById('id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('mssv').value = mssv;
    }

    function resetForm() {
        document.getElementById('id').value = "";
        document.getElementById('name').value = "";
        document.getElementById('mssv').value = "";
    }
</script>

</body>
</html>
