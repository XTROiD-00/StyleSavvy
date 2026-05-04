<?php
session_start();

// If user is not logged in, redirect to login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
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
          "on-background": "#e7e5e4",
          "surface-container-lowest": "#000000",
          "secondary-dim": "#9d9da2",
          "outline-variant": "#484848",
          "on-secondary": "#1f2024",
          "on-secondary-container": "#bfbfc4",
          "surface-tint": "#c6c6c7",
          "inverse-surface": "#fcf9f8",
          "surface-container-highest": "#252626",
          "surface-container-high": "#1f2020",
          "tertiary-container": "#ebeaf0",
          "on-tertiary": "#5e5f63",
          "on-error": "#490106",
          "outline": "#767575",
          "background": "#0e0e0e",
          "error-container": "#7f2927",
          "primary-fixed-dim": "#d4d4d4",
          "surface": "#0e0e0e",
          "secondary-fixed-dim": "#d5d4d9",
          "secondary-container": "#3a3b3f",
          "on-primary-container": "#d0d0d0",
          "inverse-on-surface": "#565555",
          "on-primary": "#3f4041",
          "surface-dim": "#0e0e0e",
          "surface-container-low": "#131313",
          "surface-container": "#191a1a",
          "surface-variant": "#252626",
          "surface-bright": "#2c2c2c",
          "on-primary-fixed": "#3e4040",
          "on-secondary-fixed-variant": "#5b5b60",
          "primary-dim": "#b8b9b9",
          "secondary": "#9d9da2",
          "inverse-primary": "#5e5f5f",
          "error-dim": "#bb5551",
          "secondary-fixed": "#e3e2e7",
          "primary-container": "#454747",
          "on-surface-variant": "#acabaa",
          "primary-fixed": "#e2e2e2",
          "on-error-container": "#ff9993",
          "primary": "#c6c6c7",
          "on-secondary-fixed": "#3e3f43",
          "on-surface": "#e7e5e4",
          "error": "#ee7d77",
          "on-tertiary-container": "#55575b"
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
    font-family: 'Manrope', sans-serif;
    background-color: #0e0e0e;
    color: #e7e5e4;
    min-height: max(884px, 100dvh);
  }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
  }
</style>
</head>
<body class="bg-background text-on-surface font-body">

<!-- Top App Bar -->
<header class="fixed top-0 w-full z-50 bg-[#0e0e0e]/60 backdrop-blur-xl flex items-center justify-between px-8 py-6">
  <h1 class="font-headline tracking-tighter font-bold uppercase text-xl tracking-[0.2em] text-on-surface">PASTIMAS</h1>
  <div class="flex items-center gap-6">
    <span class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant"><?php echo htmlspecialchars($username); ?></span>
    <a href="logout.php" class="font-label text-[0.6875rem] uppercase tracking-widest text-error hover:text-error-dim transition-colors">Logout</a>
  </div>
</header>

<main class="min-h-screen pt-32 pb-12 px-8 md:px-20 lg:px-32">

  <!-- Welcome Message — Assignment Requirement -->
  <div class="mb-16 border-b border-outline-variant/10 pb-12">
    <span class="font-label text-[0.625rem] text-on-surface-variant uppercase tracking-[0.4em] block mb-3">Active Session</span>
    <h2 class="font-headline text-4xl md:text-6xl font-extrabold tracking-tighter text-on-surface">
      User <?php echo htmlspecialchars($full_name); ?> is logged in.
    </h2>
  </div>

  <!-- User Details Table — Assignment Requirement: associative read -->
  <div class="mb-16">
    <span class="font-label text-[0.625rem] text-on-surface-variant uppercase tracking-[0.4em] block mb-6">Account Details</span>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="border-b border-outline-variant/20">
            <th class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-4 pr-12">Field</th>
            <th class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-4">Value</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
            <td class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-5 pr-12">Full Name</td>
            <td class="font-body text-on-surface py-5"><?php echo htmlspecialchars($full_name); ?></td>
          </tr>
          <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
            <td class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-5 pr-12">Username</td>
            <td class="font-body text-on-surface py-5"><?php echo htmlspecialchars($username); ?></td>
          </tr>
          <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
            <td class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-5 pr-12">Email Address</td>
            <td class="font-body text-on-surface py-5"><?php echo htmlspecialchars($email); ?></td>
          </tr>
          <tr class="border-b border-outline-variant/10 hover:bg-surface-container transition-colors">
            <td class="font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant py-5 pr-12">Account Status</td>
            <td class="py-5">
              <span class="font-label text-[0.6875rem] uppercase tracking-widest text-primary border border-primary/30 px-3 py-1 rounded">
                <?php echo htmlspecialchars($_SESSION['status']); ?>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer -->
  <div class="pt-8 border-t border-outline-variant/10 flex flex-wrap gap-4">
    <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">Privacy Policy</span>
    <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">Terms of Service</span>
    <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">© 2024 Pastimas</span>
  </div>

</main>
</body>
</html>