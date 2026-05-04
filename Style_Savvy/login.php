<?php
session_start();
include("DBConn.php");

$error = "";
$username = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $password_hash = md5($password);

    $sql = "SELECT * FROM tblUser WHERE username='$username' AND password_hash='$password_hash'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);

        if($user['status'] == 'pending'){
            $error = "Your account is pending admin verification. Please wait.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['status'] = $user['status'];

            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid username or password.";
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body {
    background: linear-gradient(135deg, #0f172a, #020617);
}
.glass {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.08);
}
.input {
    background: rgba(255,255,255,0.03);
}
</style>
</head>

<body class="text-white min-h-screen flex items-center justify-center px-4">

<div class="glass w-full max-w-md rounded-2xl p-8 shadow-2xl space-y-6">

    <!-- Header -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold tracking-tight">Welcome Back</h1>
        <p class="text-gray-400 text-sm">Sign in to your account</p>
    </div>

    <!-- Error -->
    <?php if($error): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm px-4 py-3 rounded-lg text-center">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" class="space-y-5">

        <!-- Username -->
        <input type="text" name="username"
        placeholder="Username"
        value="<?php echo htmlspecialchars($username); ?>"
        class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition"
        required>

        <!-- Password -->
        <div class="relative">
            <input id="password" type="password" name="password"
            placeholder="Password"
            class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition"
            required>

            <button type="button" onclick="togglePassword()"
            class="absolute right-3 top-3 text-gray-400 text-sm">
                Show
            </button>
        </div>

        <!-- Button -->
        <button type="submit"
        class="w-full bg-white text-black font-semibold py-3 rounded-lg hover:opacity-80 transition active:scale-95">
            Login
        </button>

    </form>

    <!-- Footer -->
    <div class="text-center space-y-3">
        <p class="text-sm text-gray-400">
            Don't have an account?
            <a href="registration.php" class="text-white hover:underline">Register</a>
        </p>

        <a href="admin_login.php"
        class="block border border-white/20 hover:border-white text-sm py-3 rounded-lg transition">
            Admin Login
        </a>
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>