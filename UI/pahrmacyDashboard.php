<?php
session_start();
require_once '../Database/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'pharmacy') {
    header('Location: index.php');
    exit();
}



$pharmacy = [
  'id'=> $_SESSION['user_id'],
    'Name' => $_SESSION['userName'],
   
];




$stmt = $db->prepare("SELECT * FROM prescription WHERE pharmacyID = $pharmacy[id] ");
$result = $stmt->execute();
$orders = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $orders[] = $row;
}
$stmt->close();

if (empty($orders)) {
    $orders = [];
}











?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Alpha Pharma</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./styles/user.css">

</head>
<body class="font-sans backMesh ">

<div class="flex h-screen">
   
 <aside class="w-24 h-screen flex flex-col justify-between bg-transparent  relative rounded-r-3xl">

  <div class="p-6 text-xl font-bold text-blue-600 flex flex-col items-center justify-center">
   <img src="./Assets/Untitled-5.png" alt="Logo" class="w-12 h-12 rounded-full mx-auto">

   <div class="text-blue-600 mt-4" style="writing-mode: vertical-rl;">
Alpha Pharma
</div>
  </div>


  <nav class="absolute top-1/2 left-0 w-full -translate-y-1/2 flex flex-col items-center gap-4">
    <a href="pahrmacyDashboard.php" class="block px-6 py-2 bg-blue-100 text-blue-900 rounded-full">
      <i class="fa-solid fa-house text-2xl"></i>
    </a>
    <a href="pharmAllQuates.php" class="block px-6 py-2  text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
      <i class="fa-solid fa-truck-medical text-2xl"></i>
    </a>
    <a href="#" class="block px-6 py-2  text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
      <i class="fa-solid fa-file-prescription text-2xl"></i>
    
    </a>
  </nav>


  <div class="p-6">
 <a href="../PHP/logout.php" class="flex items-center shadow-xl justify-center px-6 py-2 text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
    
      <i class="fa-solid fa-right-from-bracket text-xl"></i>
    </a>
  </div>
</aside>


  
  <div class="flex-1 flex flex-col m-2 rounded-[36px]  glass  bg-[url('./Assets/Asset3.png')] bg-contain bg-center">
    
      <header class="glass shadow p-4 px-8 flex justify-between items-center rounded-t-[36px]">
        <h1 class="text-xl font-semibold ">Welcome ! <?php echo $pharmacy['Name']; ?></h1>

        <div class="text-gray-800">📅 <?php echo  date('F j, Y') ?></div>
      </header>

     
      <main class="p-6 overflow-y-auto">
         <header class="bg-white bg-opacity-75 shadow-lg p-4 px-8 flex justify-between items-center rounded-t-lg ">
        <h1 class="text-xl font-semibold ">Orders</h1>

        
      </header>
        <div class="bg-white bg-opacity-75 rounded-b-lg shadow p-6">
          

        <div class="overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow">
    <thead class="bg-blue-600 text-white">
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Customer</th>
           <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Delivery Address</th>
      
        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        <?php foreach ($orders as $order): ?>
            <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $order['prescriptionID']; ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php 
            
            $stmt = $db->prepare("SELECT name FROM users WHERE id =$order[customerID]");
            $result = $stmt->execute();
            echo $row = $result->fetchArray(SQLITE3_ASSOC)['name'];
            
            ?></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $order['deliveryAddress']; ?></td>
            
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="pharmacySetQuates.php?id=<?php echo $order['prescriptionID']; ?>" class="text-blue-600 hover:text-blue-900">View</a>
                <a href="#" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
            </td>
            </tr>
        <?php endforeach; ?>
   
    </tbody>
  </table>
</div>
          
        </div>
      </main>
      
    </div>
  </div>


</body>
</html>
