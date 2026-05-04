<?php
session_start();
include("DBConn.php");

// Protect admin page
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

$error = "";
$success = "";

// =============================
// VERIFY USER
// =============================
if(isset($_GET['verify_id'])){
    $verify_id = (int) $_GET['verify_id'];

    $verifySql = "UPDATE tblUser SET status='verified' WHERE user_id=$verify_id";

    if(mysqli_query($conn, $verifySql)){
        $success = "User verified successfully.";
    } else {
        $error = "Failed to verify user.";
    }
}

// =============================
// DELETE USER
// =============================
if(isset($_GET['delete_id'])){
    $delete_id = (int) $_GET['delete_id'];

    $deleteSql = "DELETE FROM tblUser WHERE user_id=$delete_id";

    if(mysqli_query($conn, $deleteSql)){
        $success = "User deleted successfully.";
    } else {
        $error = "Failed to delete user.";
    }
}

// =============================
// ADD USER
// =============================
if(isset($_POST['add_user'])){
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $status = trim($_POST['status']);

    $password_hash = md5($password);

    // Check duplicates
    $checkSql = "SELECT * FROM tblUser WHERE username='$username' OR email='$email'";
    $checkResult = mysqli_query($conn, $checkSql);

    if(mysqli_num_rows($checkResult) > 0){
        $error = "Username or Email already exists.";
    } else {
        $insertSql = "INSERT INTO tblUser(full_name, username, email, password_hash, status)
                      VALUES('$full_name','$username','$email','$password_hash','$status')";

        if(mysqli_query($conn, $insertSql)){
            $success = "New customer added successfully.";
        } else {
            $error = "Failed to add customer.";
        }
    }
}

// =============================
// UPDATE USER
// =============================
if(isset($_POST['update_user'])){
    $user_id = (int) $_POST['user_id'];
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);

    $updateSql = "UPDATE tblUser 
                  SET full_name='$full_name', username='$username', email='$email', status='$status'
                  WHERE user_id=$user_id";

    if(mysqli_query($conn, $updateSql)){
        $success = "Customer updated successfully.";
    } else {
        $error = "Failed to update customer.";
    }
}

// =============================
// FETCH USERS
// =============================
$pendingSql = "SELECT * FROM tblUser WHERE status='pending' ORDER BY date_registered DESC";
$pendingUsers = mysqli_query($conn, $pendingSql);

$allUsersSql = "SELECT * FROM tblUser ORDER BY user_id DESC";
$allUsers = mysqli_query($conn, $allUsersSql);

// =============================
// EDIT MODE (Load user data into form)
// =============================
$editMode = false;
$editUser = null;

if(isset($_GET['edit_id'])){
    $edit_id = (int) $_GET['edit_id'];
    $editSql = "SELECT * FROM tblUser WHERE user_id=$edit_id";
    $editResult = mysqli_query($conn, $editSql);

    if(mysqli_num_rows($editResult) == 1){
        $editUser = mysqli_fetch_assoc($editResult);
        $editMode = true;
    }
}

?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>

<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300;400;700;800&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "background": "#0e0e0e",
          "surface": "#0e0e0e",
          "surface-container": "#191a1a",
          "surface-container-high": "#1f2020",
          "outline-variant": "#484848",
          "outline": "#767575",
          "primary": "#c6c6c7",
          "primary-dim": "#b8b9b9",
          "on-primary": "#3f4041",
          "on-surface": "#e7e5e4",
          "on-surface-variant": "#acabaa",
          "error": "#ee7d77",
          "error-container": "#7f2927"
        },
        fontFamily: {
          headline: ["Epilogue"],
          body: ["Manrope"],
          label: ["Manrope"]
        }
      }
    }
  }
</script>

<style>
  body {
    min-height: max(884px, 100dvh);
    font-family: "Manrope", sans-serif;
  }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
  }
</style>

<title>PASTIMAS ADMIN | Atelier Control</title>
</head>

<body class="bg-background text-on-surface font-body">

<!-- TOP BAR -->
<header class="fixed top-0 w-full z-50 bg-[#0e0e0e]/70 backdrop-blur-xl flex items-center justify-between px-8 py-6 border-b border-outline-variant/20">
  <div>
    <h1 class="font-headline tracking-[0.3em] uppercase text-xl text-primary font-bold">PASTIMAS</h1>
    <p class="text-[0.75rem] text-on-surface-variant uppercase tracking-widest mt-1">
      Admin Panel
    </p>
  </div>

  <div class="flex items-center gap-6">
    <span class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant">
      <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
    </span>

    <a href="admin_logout.php" class="text-error uppercase tracking-widest text-[0.6875rem] hover:opacity-70">
      Logout
    </a>
  </div>
</header>

<main class="pt-32 pb-20 px-8 md:px-16 lg:px-24 space-y-16">

  <!-- Messages -->
  <?php if($error): ?>
    <div class="px-4 py-3 border border-error/40 bg-error-container/20 rounded-lg">
      <p class="text-error font-label text-[0.75rem] uppercase tracking-widest"><?php echo $error; ?></p>
    </div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="px-4 py-3 border border-primary/40 bg-surface-container-high rounded-lg">
      <p class="text-primary font-label text-[0.75rem] uppercase tracking-widest"><?php echo $success; ?></p>
    </div>
  <?php endif; ?>

  <!-- SECTION: Pending Users -->
  <section class="space-y-6">
    <div>
      <h2 class="font-headline text-3xl font-bold tracking-tight">Pending Registrations</h2>
      <p class="text-on-surface-variant text-sm">Verify new customers before they can log in.</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-outline-variant/20">
      <table class="w-full text-left border-collapse">
        <thead class="bg-surface-container-high">
          <tr class="border-b border-outline-variant/20">
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">ID</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Full Name</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Username</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Email</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Status</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Action</th>
          </tr>
        </thead>

        <tbody>
          <?php if(mysqli_num_rows($pendingUsers) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($pendingUsers)): ?>
              <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
                <td class="py-4 px-6"><?php echo $row['user_id']; ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['username']); ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="py-4 px-6">
                  <span class="text-error uppercase text-[0.75rem] tracking-widest">
                    <?php echo htmlspecialchars($row['status']); ?>
                  </span>
                </td>
                <td class="py-4 px-6">
                  <a href="admin_panel.php?verify_id=<?php echo $row['user_id']; ?>"
                     class="bg-primary text-on-primary px-4 py-2 rounded uppercase text-[0.75rem] tracking-widest font-bold hover:bg-primary-dim transition-all">
                    Verify
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="py-6 px-6 text-on-surface-variant text-sm">
                No pending users found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </section>

  <!-- SECTION: Add / Update Customer -->
  <section class="space-y-6">
    <div>
      <h2 class="font-headline text-3xl font-bold tracking-tight">
        <?php echo $editMode ? "Update Customer" : "Add New Customer"; ?>
      </h2>
      <p class="text-on-surface-variant text-sm">
        Admin can add, update, or delete customers.
      </p>
    </div>

    <form method="POST" action="admin_panel.php" class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface-container-high p-8 rounded-lg border border-outline-variant/20">

      <?php if($editMode): ?>
        <input type="hidden" name="user_id" value="<?php echo $editUser['user_id']; ?>">
      <?php endif; ?>

      <div>
        <label class="block text-[0.75rem] uppercase tracking-widest text-on-surface-variant mb-2">Full Name</label>
        <input class="w-full bg-surface border border-outline-variant/20 rounded-lg py-3 px-4 text-on-surface"
               type="text" name="full_name"
               value="<?php echo $editMode ? htmlspecialchars($editUser['full_name']) : ""; ?>"
               required>
      </div>

      <div>
        <label class="block text-[0.75rem] uppercase tracking-widest text-on-surface-variant mb-2">Username</label>
        <input class="w-full bg-surface border border-outline-variant/20 rounded-lg py-3 px-4 text-on-surface"
               type="text" name="username"
               value="<?php echo $editMode ? htmlspecialchars($editUser['username']) : ""; ?>"
               required>
      </div>

      <div>
        <label class="block text-[0.75rem] uppercase tracking-widest text-on-surface-variant mb-2">Email</label>
        <input class="w-full bg-surface border border-outline-variant/20 rounded-lg py-3 px-4 text-on-surface"
               type="email" name="email"
               value="<?php echo $editMode ? htmlspecialchars($editUser['email']) : ""; ?>"
               required>
      </div>

      <div>
        <label class="block text-[0.75rem] uppercase tracking-widest text-on-surface-variant mb-2">Status</label>
        <select class="w-full bg-surface border border-outline-variant/20 rounded-lg py-3 px-4 text-on-surface"
                name="status" required>
          <option value="pending" <?php echo ($editMode && $editUser['status']=="pending") ? "selected" : ""; ?>>pending</option>
          <option value="verified" <?php echo ($editMode && $editUser['status']=="verified") ? "selected" : ""; ?>>verified</option>
        </select>
      </div>

      <?php if(!$editMode): ?>
      <div class="md:col-span-2">
        <label class="block text-[0.75rem] uppercase tracking-widest text-on-surface-variant mb-2">Password</label>
        <input class="w-full bg-surface border border-outline-variant/20 rounded-lg py-3 px-4 text-on-surface"
               type="password" name="password"
               placeholder="Enter a password for customer"
               required>
      </div>
      <?php endif; ?>

      <div class="md:col-span-2 flex gap-4">
        <?php if($editMode): ?>
          <button type="submit" name="update_user"
                  class="bg-primary text-on-primary px-6 py-4 rounded-lg uppercase tracking-widest text-[0.75rem] font-bold hover:bg-primary-dim transition-all">
            Update Customer
          </button>

          <a href="admin_panel.php"
             class="border border-outline-variant/30 px-6 py-4 rounded-lg uppercase tracking-widest text-[0.75rem] hover:border-primary/40 hover:text-primary transition-all">
            Cancel
          </a>
        <?php else: ?>
          <button type="submit" name="add_user"
                  class="bg-primary text-on-primary px-6 py-4 rounded-lg uppercase tracking-widest text-[0.75rem] font-bold hover:bg-primary-dim transition-all">
            Add Customer
          </button>
        <?php endif; ?>
      </div>
    </form>
  </section>

  <!-- SECTION: All Customers Table -->
  <section class="space-y-6">
    <div>
      <h2 class="font-headline text-3xl font-bold tracking-tight">All Customers</h2>
      <p class="text-on-surface-variant text-sm">Manage verified and pending users.</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-outline-variant/20">
      <table class="w-full text-left border-collapse">
        <thead class="bg-surface-container-high">
          <tr class="border-b border-outline-variant/20">
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">ID</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Full Name</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Username</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Email</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Status</th>
            <th class="py-4 px-6 text-[0.75rem] uppercase tracking-widest text-on-surface-variant">Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php if(mysqli_num_rows($allUsers) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($allUsers)): ?>
              <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
                <td class="py-4 px-6"><?php echo $row['user_id']; ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['username']); ?></td>
                <td class="py-4 px-6"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="py-4 px-6">
                  <span class="uppercase tracking-widest text-[0.75rem] <?php echo ($row['status']=="verified") ? "text-primary" : "text-error"; ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                  </span>
                </td>

                <td class="py-4 px-6 flex flex-wrap gap-3">
                  <a href="admin_panel.php?edit_id=<?php echo $row['user_id']; ?>"
                     class="border border-outline-variant/30 px-4 py-2 rounded uppercase tracking-widest text-[0.75rem] hover:border-primary/40 hover:text-primary transition-all">
                    Edit
                  </a>

                  <a href="admin_panel.php?delete_id=<?php echo $row['user_id']; ?>"
                     onclick="return confirm('Are you sure you want to delete this customer?');"
                     class="border border-error/40 text-error px-4 py-2 rounded uppercase tracking-widest text-[0.75rem] hover:bg-error-container/20 transition-all">
                    Delete
                  </a>

                  <?php if($row['status'] == 'pending'): ?>
                    <a href="admin_panel.php?verify_id=<?php echo $row['user_id']; ?>"
                       class="bg-primary text-on-primary px-4 py-2 rounded uppercase tracking-widest text-[0.75rem] font-bold hover:bg-primary-dim transition-all">
                      Verify
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="py-6 px-6 text-on-surface-variant text-sm">
                No users found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </section>

</main>

</body>
</html>

<?php mysqli_close($conn); ?>