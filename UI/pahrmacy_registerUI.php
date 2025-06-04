
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alpha Pharma</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./styles/user.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen  bg-[url('./Assets/medicine-blue-background-flat-lay.jpg')] bg-cover bg-center">

  <form method="POST" action="../PHP/register.php" class="glass p-8 rounded-2xl shadow-lg w-full max-w-md space-y-5">
      <img src="./Assets/LogoAlpha.png" alt="Logo" class="w-48 h-auto mx-auto my-4">
    <h2 class="text-2xl font-bold text-center text-sky-800">Register Pharmacy</h2>

    <input type="text" name="name" placeholder="Pharmacy Name" required
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="email" name="email" placeholder="Email" required
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="text" name="address" placeholder="Address" required
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="text" name="contactNumber" placeholder="Contact Number"
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="text" name="username" placeholder="Username" required
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="password" name="password" placeholder="Password" required
     class="w-full glass px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />

    <input type="hidden" name="userType" value="pharmacy" />

    <button type="submit"
      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">
      Register
    </button>
  </form>

</body>
</html>


