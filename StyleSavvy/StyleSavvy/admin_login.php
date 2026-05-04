<?php
session_start();
include("DBConn.php");

$error = "";
$admin_username = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $admin_username = trim($_POST["admin_username"]);
    $admin_password = trim($_POST["admin_password"]);

    // Hash the password
    $password_hash = md5($admin_password);

    // Check admin credentials against tblAdmin
    $sql = "SELECT * FROM tblAdmin WHERE admin_name='$admin_username' AND admin_password_hash='$password_hash'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $admin = mysqli_fetch_assoc($result);

        // Store admin session
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name'];
        $_SESSION['admin_email'] = $admin['admin_email'];

        // Redirect to admin panel
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Invalid admin credentials.";
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300;400;500;700;800&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "on-background": "#e7e5e4",
          "outline-variant": "#484848",
          "on-secondary": "#1f2024",
          "surface-container-lowest": "#000000",
          "on-error-container": "#ff9993",
          "outline": "#767575",
          "inverse-surface": "#fcf9f8",
          "error": "#ee7d77",
          "background": "#0e0e0e",
          "on-surface-variant": "#acabaa",
          "secondary-container": "#3a3b3f",
          "on-error": "#490106",
          "primary-container": "#454747",
          "surface-container": "#191a1a",
          "surface": "#0e0e0e",
          "surface-container-high": "#1f2020",
          "error-container": "#7f2927",
          "primary-fixed": "#e2e2e2",
          "surface-container-highest": "#252626",
          "primary": "#c6c6c7",
          "on-surface": "#e7e5e4",
          "primary-dim": "#b8b9b9",
          "on-primary": "#3f4041",
          "surface-container-low": "#131313",
        },
        borderRadius: {
          DEFAULT: "0.125rem",
          lg: "0.25rem",
          xl: "0.5rem",
          full: "0.75rem"
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
    background-color: #0e0e0e;
    color: #e7e5e4;
    font-family: 'Manrope', sans-serif;
    min-height: max(884px, 100dvh);
  }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
  }
  .editorial-gradient {
    background: linear-gradient(180deg, rgba(14,14,14,0) 0%, rgba(14,14,14,1) 100%);
  }
</style>
</head>
<body class="bg-background selection:bg-primary selection:text-on-primary">

<!-- Top App Bar -->
<header class="bg-neutral-950/80 backdrop-blur-xl fixed top-0 z-50 flex justify-between items-center w-full px-8 py-6">
  <div class="text-lg font-bold tracking-[0.3em] text-neutral-100 font-headline">
    PASTIMAS
  </div>
  <a class="text-neutral-300 hover:opacity-60 transition-opacity" href="login.php">
    <span class="material-symbols-outlined">close</span>
  </a>
</header>

<main class="min-h-screen grid grid-cols-1 md:grid-cols-12 overflow-hidden">

  <!-- Left Visual Side -->
  <div class="hidden md:flex md:col-span-7 lg:col-span-8 relative bg-surface-container-lowest overflow-hidden">
    <div class="absolute inset-0 opacity-60">
      <img class="w-full h-full object-cover grayscale brightness-50 contrast-125"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEq5haVw2VIsuVW2ZKTxx4qkDu3wX-h0DbLuP0aJmKaeFVn5i7VVb2GB8m_jWRbHcnr1HQ5doUblcT1f6GJVFoCdMpPyy-z93gD2kQzrWY7VYCA-MPreprPFECWeWQypts-kLkgYDdrzuKVzhGv6rEvkkTMqvaCR8K0s78DXi1MjjciwIySpA1zOn_kKAQX_sIccFssCUFt-mTusqwgMj9T7plYbvlm9bFSxXuAobZid8vu1DvY7ieNTolT2s4PTg7jPe52kGCMMM"
           alt="Editorial fashion"/>
    </div>
    <div class="absolute inset-0 editorial-gradient"></div>
    <div class="relative z-10 self-end p-20 max-w-2xl">
      <span class="font-label text-[0.6875rem] uppercase tracking-[0.2em] text-primary mb-6 block">Restricted Access</span>
      <h2 class="font-headline text-5xl lg:text-7xl font-bold tracking-tighter leading-none text-on-surface mb-8">
        Atelier<br/>Control.
      </h2>
      <p class="font-body text-on-surface-variant text-lg max-w-md leading-relaxed">
        Administrator access only. Manage customers, verify registrations, and maintain the archive.
      </p>
    </div>
  </div>

  <!-- Right Side: Admin Login Form -->
  <div class="col-span-1 md:col-span-5 lg:col-span-4 flex flex-col justify-center items-center px-8 md:px-12 lg:px-16 pt-24 pb-12 bg-surface">
    <div class="w-full max-w-sm space-y-12">

      <!-- Intro -->
      <div class="space-y-3">
        <span class="font-label text-[0.625rem] text-on-surface-variant uppercase tracking-[0.4em] block">Admin Portal</span>
        <h1 class="font-headline text-4xl font-bold tracking-tight text-on-surface">Admin Login</h1>
        <p class="font-body text-on-surface-variant text-sm">Restricted to authorised personnel only.</p>
      </div>

      <!-- Error Message -->
      <?php if($error): ?>
        <div class="px-4 py-3 border border-error/40 bg-error-container/20 rounded-lg">
          <p class="text-error font-label text-[0.75rem] uppercase tracking-widest"><?php echo $error; ?></p>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form action="admin_login.php" method="POST" class="space-y-8">
        <div class="space-y-6">

          <!-- Admin Username -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors" for="admin_username">
              Admin Username
            </label>
            <input
              class="w-full bg-surface-container-high border-none ring-1 ring-outline-variant/15 focus:ring-primary/50 rounded-lg py-4 px-5 text-on-surface placeholder:text-neutral-700 transition-all outline-none"
              id="admin_username"
              name="admin_username"
              placeholder="Admin ID"
              type="text"
              value="<?php echo htmlspecialchars($admin_username); ?>"
              required/>
          </div>

          <!-- Admin Password -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors" for="admin_password">
              Password
            </label>
            <input
              class="w-full bg-surface-container-high border-none ring-1 ring-outline-variant/15 focus:ring-primary/50 rounded-lg py-4 px-5 text-on-surface placeholder:text-neutral-700 transition-all outline-none"
              id="admin_password"
              name="admin_password"
              placeholder="••••••••"
              type="password"
              required/>
          </div>

        </div>

        <!-- Actions -->
        <div class="space-y-6 pt-4">
          <button
            class="w-full bg-primary hover:bg-primary-dim text-on-primary font-label text-[0.75rem] font-bold uppercase tracking-[0.2em] py-5 rounded-lg transition-all active:scale-[0.98] shadow-xl shadow-black/20"
            type="submit">
            Access Admin Panel
          </button>

          <div class="flex flex-col items-center space-y-4">
            <p class="font-body text-sm text-on-surface-variant">
              Not an admin?
              <a class="text-on-surface font-semibold hover:text-primary transition-colors ml-1 border-b border-on-surface hover:border-primary" href="login.php">
                User Login
              </a>
            </p>
          </div>
        </div>
      </form>

      <!-- Footer -->
      <div class="pt-12 text-center">
        <p class="font-label text-[10px] uppercase tracking-widest text-neutral-700">
          © 2024 Pastimas Archive • Restricted Access
        </p>
      </div>

    </div>
  </div>

</main>
</body>
</html>