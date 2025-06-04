<?php
session_start();
require_once '../Database/db.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['userType']) || $_SESSION['userType'] !== 'user') {
    header('Location: index.php');
    exit();
}



$user = [
  'id'=> $_SESSION['user_id'],
    'userName' => $_SESSION['userName'],
   
];

$stmt = $db->prepare("SELECT * FROM users WHERE userType = 'pharmacy' ");
$result = $stmt->execute();
$pharmacies = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $pharmacies[] = $row;
}
$stmt->close();




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $note = $_POST['note'] ;
  $address = $_POST['address'] ;
  $timeslot = $_POST['timeslot'] ;
  $customerID = $user['id']; 
  $pharmacyID = $_POST['pharmacyID'] ;



 
  $stmt = $db->prepare("INSERT INTO prescription (customerID, note, deliveryAddress, deliveryTimeSlot,pharmacyID) VALUES (?, ?, ?, ?,?)");
 $stmt->bindValue(1, $customerID, SQLITE3_INTEGER);
  $stmt->bindValue(2, $note, SQLITE3_TEXT);
  $stmt->bindValue(3, $address, SQLITE3_TEXT);  
  $stmt->bindValue(4, $timeslot, SQLITE3_TEXT);
  $stmt->bindValue(5, $pharmacyID, SQLITE3_INTEGER); 
  $stmt->execute();
 $prescriptionId = $db->lastInsertRowID();

  $stmt->close();

  // Upload images
  $uploadDir = '../Database/Uploads/';

  $imageCount = 0;

  foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
    if ($imageCount >= 5) break;

    $type = $_FILES['images']['type'][$index];
    $name = basename($_FILES['images']['name'][$index]);
    $targetPath = $uploadDir . time() . '_' . $name;

    if (move_uploaded_file($tmpName, $targetPath)) {
      $stmt = $db->prepare("INSERT INTO prescriptionImage (prescriptionID, ImagePath) VALUES (?, ?)");
    
      $stmt->bindValue(1, $prescriptionId, SQLITE3_INTEGER);
      $stmt->bindValue(2, $targetPath, SQLITE3_TEXT);
      $stmt->execute();
      $stmt->close();
      $imageCount++;
    }
    else {
      echo "Error uploading image: " . $_FILES['images']['error'][$index];
    }
  }
    echo "<script>alert('✅ Prescription Sent successful!'); window.location.href = './userDashboard.php';</script>";
  exit();
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
<body class="font-sans backGrad overflow-hidden">

<div class="flex h-screen">
  <aside class="w-24 h-screen flex flex-col justify-between bg-transparent  relative rounded-r-3xl">

  <div class="p-6 text-xl font-bold text-blue-600 flex flex-col items-center justify-center">
   <img src="./Assets/Untitled-3.png" alt="Logo" class="w-12 h-12 rounded-full mx-auto">

   <div class=" text-slate-100 mt-4" style="writing-mode: vertical-rl;">
Alpha Pharma
</div>
  </div>


  <nav class="absolute top-1/2 left-0 w-full -translate-y-1/2 flex flex-col items-center gap-4">
    <a href="pahrmacyDashboard.php" class="block px-6 py-2 bg-blue-100 text-blue-900 rounded-full">
      <i class="fa-solid fa-house text-2xl"></i>
    </a>
    <a href="#" class="block px-6 py-2   text-slate-100 hover:bg-blue-100 hover:text-blue-900 rounded-full">
      <i class="fa-solid fa-truck-medical text-2xl"></i>
    </a>
    <a href="userQuotes.php" class="block px-6 py-2  text-slate-100 hover:bg-blue-100 hover:text-blue-900 rounded-full">
      <i class="fa-solid fa-suitcase-medical text-2xl"></i>
    </a>
  </nav>


  <div class="p-6">
 <a href="../PHP/logout.php" class="flex items-center shadow-xl justify-center px-6 py-2 text-slate-100 hover:bg-blue-100 hover:text-blue-900 rounded-full">
    
      <i class="fa-solid fa-right-from-bracket text-xl"></i>
    </a>
  </div>
</aside>

  
    <div class="flex-1 flex flex-col m-2 rounded-[36px]  glass  bg-[url('./Assets/Asset3.png')] bg-contain bg-center">
    
      <header class="glass shadow p-4 px-8 flex justify-between items-center rounded-t-[36px]">
           <h1 class="text-xl font-semibold ">Welcome ! <?php echo $user['userName']; ?></h1>
           <div class="text-gray-800">📅 <?php echo  date('F j, Y') ?></div>
      </header>

     
      <main class="p-6 overflow-y-auto">
         <header class="bg-white bg-opacity-75 shadow-lg p-4 px-8 flex justify-between items-center rounded-t-lg ">
        <h1 class="text-xl font-semibold ">Upload Prescriptions</h1>

        
      </header>
        <div class="bg-white bg-opacity-75 rounded-b-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Upload Your Prescription</h2>

         
          <form class="space-y-4" action="userDashboard.php" method="POST" enctype="multipart/form-data">
            <div>
              <label class="block text-sm font-medium my-2">Note</label>
              <textarea  class="w-full bg-white bg-opacity-75  px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" name="note"></textarea>
            </div>

           <div>

              <label class="block text-sm font-medium">Upload up to 5 images</label>
               <div class="flex-row flex-wrap justify-center align-center flex items-center">
             <div class="relative w-1/6 h-1/2 rounded-lg overflow-hidden m-4 h-24 :hover bg-gray-200  hover:opacity-25 Htransition-opacity duration-300 justify-center align-center flex items-center">
<lable class="absolute z-20"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg>
</lable>
  <input
    type="file"
    name="images[]"
    multiple
    accept="image/*"
    id="inputimg"
       onchange="previewImage(this, 'preview')"
    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20  "
  />

  <img
    id="preview"
    src="./Assets/prescription.png"
    alt="Image preview"
    class="absolute inset-0 w-full h-full object-cover z-10 rounded"
  />
</div>

    <div class="relative w-1/6 h-1/2 rounded-lg overflow-hidden m-4 h-24 :hover bg-gray-200  hover:opacity-25 Htransition-opacity duration-300 justify-center align-center flex items-center">
<lable class="absolute z-20"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg>
</lable>
  <input
    type="file"
    name="images[]"
    multiple
    accept="image/*"
    id="inputimg"
       onchange="previewImage(this, 'preview2')"
    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20  "
  />

  <img
    id="preview2"
    src="./Assets/prescription.png"
    alt="Image preview"
    class="absolute inset-0 w-full h-full object-cover z-10 rounded"
  />
</div>


    <div class="relative w-1/6 h-1/2 rounded-lg overflow-hidden m-4 h-24 :hover bg-gray-200  hover:opacity-25 Htransition-opacity duration-300 justify-center align-center flex items-center">
<lable class="absolute z-20"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg>
</lable>
  <input
    type="file"
    name="images[]"
    multiple
    accept="image/*"
    id="inputimg"
       onchange="previewImage(this, 'preview3')"
    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20  "
  />

  <img
    id="preview3"
    src="./Assets/prescription.png"
    alt="Image preview"
    class="absolute inset-0 w-full h-full object-cover z-10 rounded"
  />
</div>


    <div class="relative w-1/6 h-1/2 rounded-lg overflow-hidden m-4 h-24 :hover bg-gray-200  hover:opacity-25 Htransition-opacity duration-300 justify-center align-center flex items-center">
<lable class="absolute z-20"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg>
</lable>
  <input
    type="file"
    name="images[]"
    multiple
    accept="image/*"
       onchange="previewImage(this, 'preview4')"
    id="inputimg"
    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20  "
  />

  <img
    id="preview4"
    src="./Assets/prescription.png"
    alt="Image preview"
    class="absolute inset-0 w-full h-full object-cover z-10 rounded"
  />
</div>


    <div class="relative w-1/6 h-1/2 rounded-lg overflow-hidden m-4 h-24 :hover bg-gray-200  hover:opacity-25 Htransition-opacity duration-300 justify-center align-center flex items-center">
<lable class="absolute z-20"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-10">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg>
</lable>
  <input
    type="file"
    name="images[]"
    multiple
    
    accept="image/*"
    id="inputimg"
    onchange="previewImage(this, 'preview5')"
    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20  "
  />

  <img
    id="preview5"
    src="./Assets/prescription.png"
    alt="Image preview"
    class="absolute inset-0 w-full h-full object-cover z-10 rounded"
  />
</div>

</div>
            
            </div>

            <div>
              <label class="block text-sm font-medium my-2">Delivery Address</label>
              <input type="text"  class="w-full bg-white bg-opacity-75  px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="address" />
            </div>

            <div>
              <label class="block text-sm font-medium my-2">Select Delivery Time Slot</label>
              <select  class=" bg-white bg-opacity-75  px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="timeslot">
                <option>08:00 AM - 10:00 AM</option>
                <option>10:00 AM - 12:00 PM</option>
                <option>12:00 PM - 02:00 PM</option>
                <option>02:00 PM - 04:00 PM</option>
                <option>04:00 PM - 06:00 PM</option>
                <option>06:00 PM - 08:00 PM</option>
              </select>
            </div>

             <div>
              <label class="block text-sm font-medium my-2">Pharmacies</label>
              <select  class="w-full bg-white bg-opacity-75  px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="pharmacyID">
                <?php foreach ($pharmacies as $pharmacy): ?>
                  <option value="<?php echo $pharmacy['id']; ?>"><?php echo $pharmacy['name']; ?></option>
                <?php endforeach; ?>
               
              </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Submit
            </button>
          </form>
        </div>
      </main>
      
    </div>
  </div>
<script>
  function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    if (file && preview) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }
</script>

</body>
</html>
