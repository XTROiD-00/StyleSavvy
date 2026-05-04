<?php
session_start();
include("DBConn.php");

$error = "";
$username = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Hash the entered password using md5
    $password_hash = md5($password);

    // Check if user exists with matching username and password hash
    $sql = "SELECT * FROM tblUser WHERE username='$username' AND password_hash='$password_hash'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);

        // Check if user is verified
        if($user['status'] == 'pending'){
            $error = "Your account is pending admin verification. Please wait.";
        } else {
            // Login successful - store in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['status'] = $user['status'];

            // Redirect to dashboard
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
          "surface-container-low": "#131313",
          "outline-variant": "#484848",
          "on-tertiary-fixed-variant": "#65666a",
          "inverse-on-surface": "#565555",
          "on-secondary": "#1f2024",
          "surface-container-lowest": "#000000",
          "on-error-container": "#ff9993",
          "outline": "#767575",
          "inverse-surface": "#fcf9f8",
          "error": "#ee7d77",
          "on-tertiary-fixed": "#48494e",
          "background": "#0e0e0e",
          "on-surface-variant": "#acabaa",
          "secondary-dim": "#9d9da2",
          "secondary-container": "#3a3b3f",
          "tertiary-fixed": "#f3f3f8",
          "on-error": "#490106",
          "primary-container": "#454747",
          "surface-dim": "#0e0e0e",
          "surface-container": "#191a1a",
          "surface": "#0e0e0e",
          "surface-variant": "#252626",
          "on-tertiary": "#5e5f63",
          "surface-container-high": "#1f2020",
          "secondary-fixed-dim": "#d5d4d9",
          "error-container": "#7f2927",
          "on-secondary-container": "#bfbfc4",
          "inverse-primary": "#5e5f5f",
          "secondary-fixed": "#e3e2e7",
          "primary-fixed": "#e2e2e2",
          "tertiary-container": "#ebeaf0",
          "surface-container-highest": "#252626",
          "primary": "#c6c6c7",
          "tertiary": "#f9f9fe",
          "surface-bright": "#2c2c2c",
          "on-surface": "#e7e5e4",
          "on-secondary-fixed": "#3e3f43",
          "secondary": "#9d9da2",
          "on-primary-fixed-variant": "#5a5c5c",
          "surface-tint": "#c6c6c7",
          "on-primary-container": "#d0d0d0",
          "error-dim": "#bb5551",
          "tertiary-fixed-dim": "#e5e5ea",
          "primary-dim": "#b8b9b9",
          "tertiary-dim": "#ebeaf0",
          "on-tertiary-container": "#55575b",
          "on-secondary-fixed-variant": "#5b5b60",
          "on-primary-fixed": "#3e4040",
          "primary-fixed-dim": "#d4d4d4",
          "on-primary": "#3f4041"
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
  <a class="text-neutral-300 hover:opacity-60 transition-opacity active:scale-95" href="register.php">
    <span class="material-symbols-outlined">close</span>
  </a>
</header>

<main class="min-h-screen grid grid-cols-1 md:grid-cols-12 overflow-hidden">

  <!-- Left Visual Side -->
  <div class="hidden md:flex md:col-span-7 lg:col-span-8 relative bg-surface-container-lowest overflow-hidden">
    <div class="absolute inset-0 opacity-60">
      <img class="w-full h-full object-cover grayscale brightness-50 contrast-125"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEq5haVw2VIsuVW2ZKTxx4qkDu3wX-h0DbLuP0aJmKaeFVn5i7VVb2GB8m_jWRbHcnr1HQ5doUblcT1f6GJVFoCdMpPyy-z93gD2kQzrWY7VYCA-MPreprPFECWeWQypts-kLkgYDdrzuKVzhGv6rEvkkTMqvaCR8K0s78DXi1MjjciwIySpA1zOn_kKAQX_sIccFssCUFt-mTusqwgMj9T7plYbvlm9bFSxXuAobZid8vu1DvY7ieNTolT2s4PTg7jPe52kGCMMM"
           alt="Editorial fashion studio"/>
    </div>
    <div class="absolute inset-0 editorial-gradient"></div>
    <div class="relative z-10 self-end p-20 max-w-2xl">
      <span class="font-label text-[0.6875rem] uppercase tracking-[0.2em] text-primary mb-6 block">Archive Collection 004</span>
      <h2 class="font-headline text-5xl lg:text-7xl font-bold tracking-tighter leading-none text-on-surface mb-8">
        Curation is an <br/>Art Form.
      </h2>
      <p class="font-body text-on-surface-variant text-lg max-w-md leading-relaxed">
        Access your curated portfolio of pre-loved garments. Every piece tells a history of design and craftsmanship.
      </p>
    </div>
  </div>

  <!-- Right Side: Login Form -->
  <div class="col-span-1 md:col-span-5 lg:col-span-4 flex flex-col justify-center items-center px-8 md:px-12 lg:px-16 pt-24 pb-12 bg-surface">
    <div class="w-full max-w-sm space-y-12">

      <!-- Intro -->
      <div class="space-y-3">
        <h1 class="font-headline text-4xl font-bold tracking-tight text-on-surface">Welcome Back</h1>
        <p class="font-body text-on-surface-variant">Sign in to your account</p>
      </div>

      <!-- Error Message -->
      <?php if($error): ?>
        <div class="px-4 py-3 border border-error/40 bg-error-container/20 rounded-lg">
          <p class="text-error font-label text-[0.75rem] uppercase tracking-widest"><?php echo $error; ?></p>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form action="login.php" method="POST" class="space-y-8">
        <div class="space-y-6">

          <!-- Username Field — sticky: repopulates on error -->
          <div class="group">
            <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant mb-2 group-focus-within:text-primary transition-colors" for="username">
              Username
            </label>
            <input
              class="w-full bg-surface-container-high border-none ring-1 ring-outline-variant/15 focus:ring-primary/50 rounded-lg py-4 px-5 text-on-surface placeholder:text-neutral-700 transition-all outline-none"
              id="username"
              name="username"
              placeholder="Curator ID"
              type="text"
              value="<?php echo htmlspecialchars($username); ?>"
              required/>
          </div>

          <!-- Password Field -->
          <div class="group">
            <div class="flex justify-between items-center mb-2">
              <label class="block font-label text-[0.6875rem] uppercase tracking-widest text-on-surface-variant group-focus-within:text-primary transition-colors" for="password">
                Password
              </label>
            </div>
            <div class="relative">
              <input
                class="w-full bg-surface-container-high border-none ring-1 ring-outline-variant/15 focus:ring-primary/50 rounded-lg py-4 px-5 text-on-surface placeholder:text-neutral-700 transition-all outline-none"
                id="password"
                name="password"
                placeholder="••••••••"
                type="password"
                required/>
            </div>
          </div>

        </div>

        <!-- Actions -->
        <div class="space-y-6 pt-4">
          <button
            class="w-full bg-primary hover:bg-primary-dim text-on-primary font-label text-[0.75rem] font-bold uppercase tracking-[0.2em] py-5 rounded-lg transition-all active:scale-[0.98] shadow-xl shadow-black/20"
            type="submit">
            LOGIN
          </button>

          <div class="relative py-4">
            <div aria-hidden="true" class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-outline-variant/10"></div>
            </div>
            <div class="relative flex justify-center text-[0.6875rem] uppercase tracking-widest">
              <span class="bg-surface px-4 text-neutral-600">OR</span>
            </div>
          </div>

          <div class="flex flex-col items-center space-y-4">
            <p class="font-body text-sm text-on-surface-variant">
              Don't have an account?
              <a class="text-on-surface font-semibold hover:text-primary transition-colors ml-1 border-b border-on-surface hover:border-primary" href="registration.php">
                Register
              </a>
            </p>

            <!-- Admin Login Button -->
            <a href="admin_login.php"
               class="w-full text-center border border-outline-variant/30 hover:border-primary/50 text-on-surface-variant hover:text-primary font-label text-[0.75rem] uppercase tracking-[0.2em] py-4 rounded-lg transition-all active:scale-[0.98]">
              Admin Login
            </a>
          </div>
        </div>

      </form>

      <!-- Footer -->
      <div class="pt-12 text-center">
        <p class="font-label text-[10px] uppercase tracking-widest text-neutral-700">
          © 2024 Pastimas Archive • Privacy Policy
        </p>
      </div>

    </div>
  </div>

</main>
</body>
</html>