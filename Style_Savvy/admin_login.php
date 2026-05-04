<?php
session_start();
include("DBConn.php");

$error = "";
$admin_username = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $admin_username = trim($_POST["admin_username"]);
    $admin_password = trim($_POST["admin_password"]);

    $password_hash = md5($admin_password);

    $sql = "SELECT * FROM tblAdmin 
            WHERE admin_name='$admin_username' 
            AND admin_password_hash='$password_hash'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $admin = mysqli_fetch_assoc($result);

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name'];

        header("Location: admin_products.php");
        exit();
    } else {
        $error = "Invalid admin credentials.";
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-6 rounded shadow w-96">

    <h2 class="text-xl font-bold mb-4">Admin Login</h2>

    <?php if($error): ?>
        <p class="text-red-500 mb-3"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-3">

        <input type="text" name="admin_username"
            placeholder="Username"
            class="w-full border p-2 rounded" required>

        <input type="password" name="admin_password"
            placeholder="Password"
            class="w-full border p-2 rounded" required>

        <button class="w-full bg-black text-white py-2 rounded">
            Login
        </button>

    </form>

</div>

</body>
</html>