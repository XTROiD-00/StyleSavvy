<?php
session_start();
include("DBConn.php");

// =====================
// PROTECT PAGE
// =====================
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// =====================
// UPDATE DETAILS
// =====================
if(isset($_POST['update_details'])){
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    if(empty($full_name) || empty($email)){
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format.";
    } else {

        // CHECK IF EMAIL EXISTS (excluding current user)
        $check = $conn->prepare("SELECT user_id FROM tbluser WHERE email=? AND user_id!=?");
        $check->bind_param("si", $email, $user_id);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $error = "Email already in use.";
        } else {
            $stmt = $conn->prepare("UPDATE tbluser SET full_name=?, email=? WHERE user_id=?");
            $stmt->bind_param("ssi", $full_name, $email, $user_id);

            if($stmt->execute()){
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email'] = $email;
                $success = "Details updated successfully!";
            } else {
                $error = "Update failed.";
            }
            $stmt->close();
        }
        $check->close();
    }
}

// =====================
// CHANGE PASSWORD
// =====================
if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if(empty($current) || empty($new) || empty($confirm)){
        $error = "All password fields are required.";
    } elseif($new !== $confirm){
        $error = "New passwords do not match.";
    } else {

        // GET CURRENT PASSWORD HASH
        $stmt = $conn->prepare("SELECT password_hash FROM tbluser WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // VERIFY PASSWORD
        if(password_verify($current, $user['password_hash'])){
            $new_hash = password_hash($new, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE tbluser SET password_hash=? WHERE user_id=?");
            $update->bind_param("si", $new_hash, $user_id);

            if($update->execute()){
                $success = "Password changed successfully!";
            } else {
                $error = "Password update failed.";
            }

            $update->close();
        } else {
            $error = "Current password is incorrect.";
        }

        $stmt->close();
    }
}

// =====================
// FETCH USER DATA
// =====================
$stmt = $conn->prepare("SELECT full_name, username, email, status FROM tbluser WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$full_name = $user['full_name'];
$username = $user['username'];
$email = $user['email'];
$status = $user['status'];

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<!-- HEADER -->
<header class="flex justify-between items-center px-6 py-4 bg-black">
    <h1 class="font-bold text-lg">PASTIMAS</h1>
    <div>
        <span><?php echo htmlspecialchars($username); ?></span>
        <a href="logout.php" class="ml-4 text-red-400">Logout</a>
    </div>
</header>

<div class="max-w-3xl mx-auto p-6">

<!-- MESSAGES -->
<?php if($success): ?>
    <p class="bg-green-600 p-2 mb-4 rounded"><?php echo $success; ?></p>
<?php endif; ?>

<?php if($error): ?>
    <p class="bg-red-600 p-2 mb-4 rounded"><?php echo $error; ?></p>
<?php endif; ?>

<!-- USER INFO -->
<h2 class="text-2xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($full_name); ?></h2>

<div class="bg-gray-800 p-4 rounded mb-8">
    <p><b>Username:</b> <?php echo htmlspecialchars($username); ?></p>
    <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
    <p><b>Status:</b> <?php echo htmlspecialchars($status); ?></p>
</div>

<!-- UPDATE DETAILS -->
<h3 class="text-xl mb-2">Update Details</h3>
<form method="POST" class="space-y-3 mb-8">

    <input type="text" name="full_name"
        value="<?php echo htmlspecialchars($full_name); ?>"
        class="w-full p-2 text-black rounded" required>

    <input type="email" name="email"
        value="<?php echo htmlspecialchars($email); ?>"
        class="w-full p-2 text-black rounded" required>

    <button name="update_details"
        class="bg-white text-black px-4 py-2 rounded">
        Update Details
    </button>

</form>

<!-- CHANGE PASSWORD -->
<h3 class="text-xl mb-2">Change Password</h3>
<form method="POST" class="space-y-3">

    <input type="password" name="current_password"
        placeholder="Current Password"
        class="w-full p-2 text-black rounded" required>

    <input type="password" name="new_password"
        placeholder="New Password"
        class="w-full p-2 text-black rounded" required>

    <input type="password" name="confirm_password"
        placeholder="Confirm Password"
        class="w-full p-2 text-black rounded" required>

    <button name="change_password"
        class="bg-white text-black px-4 py-2 rounded">
        Change Password
    </button>

</form>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>