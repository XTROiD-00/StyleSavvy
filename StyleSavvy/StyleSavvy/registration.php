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

    // Check if passwords match
    if($password !== $confirm_password){
        $error = "Passwords do not match.";
    } else {
        // Hash the password using md5
        $password_hash = md5($password);

        // Check if username or email already exists
        $checkSql = "SELECT * FROM tblUser WHERE username='$username' OR email='$email'";
        $checkResult = mysqli_query($conn, $checkSql);

        if(mysqli_num_rows($checkResult) > 0){
            $error = "Username or email already exists.";
        } else {
            // Insert new user with pending status
            $sqlInsert = "INSERT INTO tblUser(full_name, username, email, password_hash, status)
                          VALUES('$full_name','$username','$email','$password_hash','pending')";

            if(mysqli_query($conn, $sqlInsert)){
                $success = "Registration successful. Please wait for admin verification before logging in.";
                // Clear fields on success
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
          "tertiary-dim": "#ebeaf0",
          "surface-container-lowest": "#000000",
          "secondary-dim": "#9d9da2",
          "outline-variant": "#484848",
          "on-secondary": "#1f2024",
          "on-secondary-container": "#bfbfc4",
          "on-tertiary-fixed": "#48494e",
          "surface-tint": "#c6c6c7",
          "inverse-surface": "#fcf9f8",
          "surface-container-highest": "#252626",
          "surface-container-high": "#1f2020",
          "tertiary-container": "#ebeaf0",
          "on-tertiary": "#5e5f63",
          "on-error": "#490106",
          "outline": "#767575",
          "background": "#0e0e0e",
          "on-primary-fixed-variant": "#5a5c5c",
          "error-container": "#7f2927",
          "primary-fixed-dim": "#d4d4d4",
          "surface": "#0e0e0e",
          "secondary-fixed-dim": "#d5d4d9",
          "secondary-container": "#3a3b3f",
          "tertiary": "#f9f9fe",
          "on-primary-container": "#d0d0d0",
          "inverse-on-surface": "#565555",
          "on-primary": "#3f4041",
          "surface-dim": "#0e0e0e",
          "surface-container-low": "#131313",
          "surface-container": "#191a1a",
          "surface-variant": "#252626",
          "surface-bright": "#2c2c2c",
          "on-tertiary-fixed-variant": "#65666a",
          "on-primary-fixed": "#3e4040",
          "on-secondary-fixed-variant": "#5b5b60",
          "primary-dim": "#b8b9b9",
          "secondary": "#9d9da2",
          "tertiary-fixed-dim": "#e5e5ea",
          "inverse-primary": "#5e5f5f",
          "error-dim": "#bb5551",
          "secondary-fixed": "#e3e2e7",
          "tertiary-fixed": "#f3f3f8",
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
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
  }
  body {
    min-height: max(884px, 100dvh);
  }
</style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary selection:text-on-primary">

<!-- Top App Bar -->
<header class="fixed top-0 w-full z-50 bg-[#0e0e0e]/60 backdrop-blur-xl flex items-center justify-between px-8 py-6">
  <div class="flex items-center">
    <a href="login.php">
      <span class="material-symbols-outlined text-[#c6c6c7] cursor-pointer">close</span>
    </a>
  </div>
  <h1 class="font-headline tracking-tighter font-bold uppercase text-xl tracking-[0.2em] font-light text-[#e7e5e4]">PASTIMAS</h1>
  <div class="w-6"></div>
</header>

<main class="min-h-screen flex flex-col md:flex-row pt-24 pb-12">

  <!-- Visual Sidebar -->
  <div class="hidden md:flex md:w-5/12 lg:w-1/2 flex-col justify-end p-12 lg:p-20 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img alt="High-end editorial fashion"
           class="w-full h-full object-cover opacity-40 grayscale"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuDPz6iiBdUWW-fyYDYB5T7TUFzjA4-30zeE4QHIdAOMOXhZfSU_JVXxByePvi_btqNUNrxCG8E31gGAC8w7Beb9QpoU254iZ6UbYxfR2VMoNXVHAKc2OS_Yf9olsG2t84D9TX8C5yBMKwzr0QeECgkQnosuJvyda89UWuDGitdURV2UC-WMJdFaFa5BgI_Io8WcXXLJ_YEvI8W5ZotQngAw9qgOkJkcH5ASPKpaSIKhbvIsRaqpiM6AMXdgQYkRDMyYhPYXv6R-IvA"/>
      <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
    </div>
    <div class="relative z-10">
      <p class="font-label text-[0.6875rem] uppercase tracking-[0.3em] text-primary mb-4">Curated Archives</p>
      <h2 class="font-headline text-5xl lg:text-7xl font-extrabold tracking-tighter leading-none text-on-surface">JOIN THE<br/>ATELIER.</h2>
      <p class="mt-6 text-on-surface-variant max-w-sm font-light text-sm tracking-wide">Enter the space where pre-loved luxury meets modern curation. Authenticated, archived, and timeless.</p>
    </div>
  </div>

  <!-- Registration Canvas -->
  <div class="flex-1 flex flex-col items-center justify-center px-6 md:px-12 lg:px-24">
    <div class="w-full max-w-md">

      <div class="mb-12 text-center md:text-left">
        <span class="font-label text-[0.625rem] text-on-surface-variant uppercase tracking-[0.4em] block mb-2">Registration</span>
        <h3 class="font-headline text-3xl font-bold tracking-tight text-on-surface">Create your account</h3>
      </div>

      <!-- Error Message -->
      <?php if($error): ?>
        <div class="mb-6 px-4 py-3 border border-error/40 bg-error-container/20 rounded-lg">
          <p class="text-error font-label text-[0.75rem] uppercase tracking-widest"><?php echo $error; ?></p>
        </div>
      <?php endif; ?>

      <!-- Success Message -->
      <?php if($success): ?>
        <div class="mb-6 px-4 py-3 border border-primary/40 bg-primary-container/20 rounded-lg">
          <p class="text-primary font-label text-[0.75rem] uppercase tracking-widest"><?php echo $success; ?></p>
        </div>
      <?php endif; ?>

      <form method="POST" action="registration.php" class="space-y-8">
        <div class="space-y-6">

          <!-- Full Name -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors">Full Name</label>
            <input
              class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 py-4 px-0 focus:ring-0 focus:border-primary/50 text-on-surface placeholder:text-outline/50 transition-all font-light tracking-wide"
              type="text"
              name="full_name"
              placeholder="ALEXANDER MCQUEEN"
              value="<?php echo htmlspecialchars($full_name); ?>"
              required/>
          </div>

          <!-- Email -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors">Email Address</label>
            <input
              class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 py-4 px-0 focus:ring-0 focus:border-primary/50 text-on-surface placeholder:text-outline/50 transition-all font-light tracking-wide"
              type="email"
              name="email"
              placeholder="ARCHIVE@PASTIMAS.COM"
              value="<?php echo htmlspecialchars($email); ?>"
              required/>
          </div>

          <!-- Username -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors">Username</label>
            <input
              class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 py-4 px-0 focus:ring-0 focus:border-primary/50 text-on-surface placeholder:text-outline/50 transition-all font-light tracking-wide"
              type="text"
              name="username"
              placeholder="CURATOR_01"
              value="<?php echo htmlspecialchars($username); ?>"
              required/>
          </div>

          <!-- Password Pair -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="group">
              <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors">Password</label>
              <input
                class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 py-4 px-0 focus:ring-0 focus:border-primary/50 text-on-surface placeholder:text-outline/50 transition-all font-light tracking-wide"
                type="password"
                name="password"
                placeholder="••••••••"
                required/>
            </div>
            <div class="group">
              <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors">Confirm Password</label>
              <input
                class="w-full bg-surface-container-high border-none border-b border-outline-variant/30 py-4 px-0 focus:ring-0 focus:border-primary/50 text-on-surface placeholder:text-outline/50 transition-all font-light tracking-wide"
                type="password"
                name="confirm_password"
                placeholder="••••••••"
                required/>
            </div>
          </div>

        </div>

        <div class="pt-6 space-y-8">
          <button
            class="w-full bg-primary text-on-primary py-5 rounded-lg font-label text-[0.75rem] font-bold uppercase tracking-[0.2em] shadow-lg hover:bg-primary-dim transition-all active:scale-[0.98]"
            type="submit">
            Register
          </button>

          <div class="flex items-center justify-center space-x-2 text-sm">
            <span class="text-on-surface-variant font-light">Already have an account?</span>
            <a class="text-primary font-medium hover:underline underline-offset-4 transition-all" href="login.php">Login</a>
          </div>
        </div>

      </form>

      <div class="mt-20 pt-8 border-t border-outline-variant/10 flex flex-wrap gap-4 justify-center md:justify-start">
        <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">Privacy Policy</span>
        <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">Terms of Service</span>
        <span class="font-label text-[0.625rem] text-outline uppercase tracking-widest">© 2024 Pastimas</span>
      </div>

    </div>
  </div>
</main>

</body>
</html>