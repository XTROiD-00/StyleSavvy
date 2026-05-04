<?php
include("DBConn.php");

$error = "";
$success = "";

// Sticky form variables
$full_name = "";
$username = "";
$email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if($password !== $confirm_password){
        $error = "Passwords do not match.";
    } else {
        $password_hash = md5($password);

        $checkSql = "SELECT * FROM tblUser WHERE username='$username' OR email='$email'";
        $checkResult = mysqli_query($conn, $checkSql);

        if(mysqli_num_rows($checkResult) > 0){
            $error = "Username or email already exists.";
        } else {
            $sqlInsert = "INSERT INTO tblUser(full_name, username, email, password_hash, status)
                          VALUES('$full_name','$username','$email','$password_hash','pending')";

            if(mysqli_query($conn, $sqlInsert)){
                $success = "Registration successful. Await admin approval.";
                $full_name = "";
                $username = "";
                $email = "";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

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
        <h1 class="text-3xl font-bold tracking-tight">Create Account</h1>
        <p class="text-gray-400 text-sm">Join the system and get started</p>
    </div>

    <!-- Error -->
    <?php if($error): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm px-4 py-3 rounded-lg">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Success -->
    <?php if($success): ?>
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 text-sm px-4 py-3 rounded-lg">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">

        <!-- Name -->
        <input type="text" name="full_name"
        placeholder="Full Name"
        value="<?php echo htmlspecialchars($full_name); ?>"
        class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition">

        <!-- Email -->
        <input type="email" name="email"
        placeholder="Email Address"
        value="<?php echo htmlspecialchars($email); ?>"
        class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition">

        <!-- Username -->
        <input type="text" name="username"
        placeholder="Username"
        value="<?php echo htmlspecialchars($username); ?>"
        class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition">

        <!-- Password -->
        <div class="relative">
            <input id="password" type="password" name="password"
            placeholder="Password"
            class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition">

            <button type="button" onclick="togglePassword('password')" 
            class="absolute right-3 top-3 text-gray-400 text-sm">Show</button>
        </div>

        <!-- Confirm -->
        <div class="relative">
            <input id="confirm_password" type="password" name="confirm_password"
            placeholder="Confirm Password"
            class="input w-full px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-white/30 transition">

            <button type="button" onclick="togglePassword('confirm_password')" 
            class="absolute right-3 top-3 text-gray-400 text-sm">Show</button>
        </div>

        <!-- Button -->
        <button type="submit"
        class="w-full bg-white text-black font-semibold py-3 rounded-lg hover:opacity-80 transition active:scale-95">
            Register
        </button>

    </form>

    <!-- Footer -->
    <p class="text-center text-sm text-gray-400">
        Already have an account?
        <a href="login.php" class="text-white hover:underline">Login</a>
    </p>

</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>