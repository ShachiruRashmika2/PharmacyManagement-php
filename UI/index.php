<?php if (isset($_GET['message']) && $_GET['message'] === 'loggedout'): ?>
  <script>
    alert("You have been logged out.");
  </script>
<?php endif; ?>

<?php
session_start();
$status = $_SESSION['status'] ?? "";
unset($_SESSION['status']);

if ($status === "success") {
    echo "<script>alert('✅ Registration successful!'); window.location.href = '../UI/index.php';</script>";
    exit();
} elseif ($status === "error") {
    echo "<script>
   
       alert('❌ Registration failed!');
   
    </script>";
}


require_once '../Database/db.php'; 
$error='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['userName']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE userName = ?");
    if (!$stmt) {
        die("Database error: " . $db->lastErrorMsg());
    }

    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['userType'] = $user['userType'];

        if ($user['userType'] === 'pharmacy') {
            header("Location: pahrmacyDashboard.php");
        } if($user['userType']=='user') {
            header("Location: userDashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username or password.";
     echo "<script>alert('❌ Registration Error! $error'); window.location.href = '../UI/index.php';</script>";
        exit;
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alpha Pharma</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./styles/user.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="relative min-h-screen flex items-center justify-center bg-gray-100 " style="
  background-image: url('./Assets/Asset3.png'),radial-gradient(circle, rgba(140, 237, 237, 1) 0%, rgba(0, 167, 209, 1) 55%, rgba(3, 135, 168, 1) 100%);
  background-size: contain;
  background-position: center;
  background-repeat: repeat;
" >


  <div class="w-full max-w-sm p-8 glass rounded-xl rounded-tr-[76px] rounded-bl-[56px] shadow-lg z-10">
    <img src="./Assets/LogoAlpha.png" alt="Logo" class="w-48 h-auto mx-auto my-4">
    <h2 class="text-2xl font-bold text-center text-sky-800 mb-6">Login</h2>
    
    <form action="index.php" method="POST" class="space-y-4">
      <div>
        <label for="username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="username" name="userName" required
               class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <button type="submit"
              class="w-full py-2 mt-4 font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-200">
        Login
      </button>
    </form>

    <div>
      <label class="block my-1  text-sm font-medium text-gray-700">Don't have an account?</label>
      <a href="user_registerUI.php"
         class="flex glass items-center justify-start gap-2 px-6 py-2 w-50 my-4 text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
        <i class="fa-solid fa-user-plus text-2xl"></i>
        Register User
      </a>
      <a href="pahrmacy_registerUI.php"
         class="flex glass items-center justify-start gap-2 px-6 py-2 w-50 my-4 text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
        <i class="fa-solid fa-shop text-2xl"></i>
        Register Pharmacy
      </a>
    </div>
  </div>


  <div class="absolute bottom-0 left-32  z-0">
    <img src="./Assets/asd.png" alt="Left Background" class="w-[125%] h-auto rounded-lg" style="max-width: 75%;">
  </div>

 
  <div class="absolute bottom-0 right-20  z-0 flex items-center justify-center">
    <img src="./Assets/xzczx.png" alt="Right Background" class=" h-auto rounded-lg" style="max-width: 72%;">
  </div>

</body>
</html>
