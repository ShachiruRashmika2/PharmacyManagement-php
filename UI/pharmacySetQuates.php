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

if (isset($_GET['id'])) {
    $priscpID= $_GET['id'];
   
} else {
    echo "No prescription ID provided.";
    exit();
}



$stmt = $db->prepare("SELECT * FROM prescription WHERE prescriptionID = $priscpID AND pharmacyID = $pharmacy[id]");
$result = $stmt->execute();
$order=$result->fetchArray(SQLITE3_ASSOC);
if (!$order) {
    echo "No order found for the given prescription ID.";
    exit();
}
$stmt->close();





$stmt = $db->prepare("SELECT * FROM drugs WHERE pharmacyID = $pharmacy[id]");
$result = $stmt->execute();
$drugs = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $drugs[] = $row;
}   
$stmt->close();
if (empty($drugs)) {
    $drugs = [];
}

$stmt = $db->prepare("SELECT * FROM prescriptionImage WHERE prescriptionID = $priscpID");
$result = $stmt->execute();
$images = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $images[] = $row;
}
$stmt->close();
if (empty($images)) {
    $images = [];
}

 if($_SERVER['REQUEST_METHOD'] === 'POST') {

   header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
 

$drugIDArray = $data['drugIdArray'] ?? [];
$drugQtyArray = $data['drugQtyArray'] ?? [];
$csv = $data['csv'] ?? '';
$total = $data['total'] ?? 0;

$stmt = $db->prepare("INSERT INTO quotes (prescriptionID, pharmacyID, customerID, total, csv) VALUES (?, ?, ?, ?, ?)");
$stmt->bindValue(1, $priscpID, SQLITE3_INTEGER);
$stmt->bindValue(2, $pharmacy['id'], SQLITE3_INTEGER);
$stmt->bindValue(3, $order['customerID'], SQLITE3_INTEGER);
$stmt->bindValue(4, $total, SQLITE3_FLOAT);
$stmt->bindValue(5, $csv, SQLITE3_TEXT);
if ($stmt->execute()) {
    $quoteID = $db->lastInsertRowID();
    
    $insertQuery = "INSERT INTO quoteItems (quoteID, drugID, quantity) VALUES ";
    $values = [];
    
    foreach ($drugIDArray as $index => $drugID) {
        if (isset($drugQtyArray[$index])) {
            $quantity = $drugQtyArray[$index];
            $values[] = "($quoteID, '$drugID', $quantity)";
        }
    }
    
    if (!empty($values)) {
        $insertQuery .= implode(", ", $values);
        $db->exec($insertQuery);
    }
    
    $stmt->close();

    
$_SESSION['quoteID'] = $quoteID;
$_SESSION['csv'] = $csv;
   echo json_encode(['success' => true, 'message' => 'Quote created successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating quote: ' . $db->lastErrorMsg()]);
}








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
    <a href="pahrmacyDashboard.php" class="block px-6 py-2 text-slate-600 hover:bg-blue-100 hover:text-blue-900 rounded-full">
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

     
       <main class="h-full p-6 overflow-y-scroll">
         <header class="bg-white bg-opacity-75  shadow-lg p-4 px-8 flex justify-between items-center rounded-t-lg">
        <h1 class="text-xl font-semibold ">Prescription</h1>

        
      </header>
    <div class="bg-white bg-opacity-75 rounded-b-lg shadow p-6 h-full">
      <div class="overflow-x-auto h-full">
        <div class="flex flex-col h-full  w-full  gap-3">
         <div class="w-full h-[90%] flex flex-row gap-4">

                 <div class="w-2/5 grid grid-cols-4 grid-rows-5 gap-4 bg-white bg-opacity-75 rounded-lg  p-4">

            <div class="  h-full flex items-center justify-center col-span-4  row-span-4 "  >
               <img src="<?php echo $images[4]['ImagePath']; ?>" alt="Prescription Image" id="mainImg" class="w-full h-full object-contain rounded-[24px]">

            </div>
            <div class=" h-full flex items-center justify-center cursor-pointer 
            hover:opacity-75 
            active:scale-95 
            transition 
            duration-300   " onClick="changeImage('img1')"> <img src="<?php echo $images[0]['ImagePath']; ?>" alt="Prescription Image" id="img1" class="w-full h-full object-contain rounded"></div>
            <div class=" h-full flex items-center justify-center cursor-pointer 
            hover:opacity-75 
            active:scale-95 
            transition 
            duration-300 " onClick="changeImage('img2')">
                <img src="<?php echo $images[1]['ImagePath']; ?>" alt="Prescription Image" id="img2" class="w-full h-full object-contain rounded">
            </div>
            <div class=" h-full flex items-center justify-center cursor-pointer 
            hover:opacity-75 
            active:scale-95 
            transition 
            duration-300  " onClick="changeImage('img3')">
                <img src="<?php echo $images[2]['ImagePath']; ?>" alt="Prescription Image" id="img3" class="w-full h-full object-contain rounded">
            </div>
            <div class=" h-full flex items-center justify-center cursor-pointer 
            hover:opacity-75 
            active:scale-95 
            transition 
            duration-300 " onClick="changeImage('img4') ">
                <img src="<?php echo $images[3]['ImagePath']; ?>" alt="Prescription Image" id="img4" class="w-full h-full object-contain ">
            </div>
          </div>


            <div class="w-3/5 grid grid-cols-4 grid-rows-5 gap-4  p-4">

            <div class=" h-full overflow-y-scroll flex items-center justify-center col-span-4  row-span-3">

<table class="w-full h-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow">
  <!-- Header -->
  <thead class="bg-blue-600 text-white">
    <tr>
      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Drug</th>
      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Quantity</th>
      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
    </tr>
  </thead>


  <tbody class="bg-white divide-y divide-gray-100" id="drugTableBody">
  
      
   
  </tbody>


  <tfoot class="bg-gray-100">
    <tr>
      <td colspan="3" class="px-6 py-3 text-sm text-gray-700 text-right font-medium">
        Total:Rs. <span id="totalAmount"></span>
      </td>
    </tr>
  </tfoot>
</table>


            </div>
            <div class=" h-full flex flex-row items-center justify-end col-span-4  row-span-2 ">
                
            <div class=" h-full w-2/3 flex-column p-4 ">

            <div class="w-full h-1/3 flex items-center justify-end gap-4">Drug 
                <select class="border border-gray-300 rounded p-2 w-1/3" id="drugName">
                    <option value="" disabled selected>Select Drug</option>
                    <?php foreach ($drugs as $drug): ?>
                        <option value="<?php echo $drug['name'];?>" data-price="<?php echo $drug["price"]?>"
                        data-id="<?php echo $drug['drugID']; ?>"
                        > <?php echo $drug['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
             <div class=" w-full h-1/3 flex items-center justify-end gap-4">Quantity 
                <input type="text" class="border border-gray-300 rounded p-2 w-1/3" placeholder="Enter Quantity" id="quantityInput">
            </div>
             <div class="w-full h-1/3 flex items-center justify-end gap-4">
                <button onclick="addDrug()" class="bg-blue-500 text-white px-4 py-2 rounded">Add Drug</button>
            </div>
                 </div>
           
            </div>
            


          </div>
        
         </div>
     
          <div class="w-full  h-[10%] flex items-center justify-end">  <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="exportTable()">Send Quatation</button></div>
        </div>
      </div>
    </div>
  </main>
      
    </div>
  </div>



<script>
     let total = 0;
      let drugIDs = [];
      let drugQuantities = [];
     
     

     document.getElementById('totalAmount').innerText = total;
  function addDrug() {
 const drugSelect = document.getElementById('drugName');
const selectedOption = drugSelect.options[drugSelect.selectedIndex];

const drug = drugSelect.value.trim();
const quantity = parseInt(document.getElementById('quantityInput').value.trim());
const amount = parseFloat(selectedOption.getAttribute('data-price'));
const drugID = parseInt(selectedOption.getAttribute('data-id'));
   const tbody = document.getElementById('drugTableBody');
console.log(amount, drug, quantity);

    if (!drug || !quantity || !amount || isNaN(amount)) {
      alert('Please enter valid values.');
      return;
    }
     total = total + (amount * quantity);
    drugIDs.push(drugID);
    drugQuantities.push(quantity);

      console.log(drugIDs, drugQuantities);
   

    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">${drug}</td>
      <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">${quantity}</td>
      <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900">${amount}</td>
    `;
    tbody.appendChild(row);



 
  document.getElementById('totalAmount').innerText = total.toFixed(2);

    document.getElementById('drugName').value = '';
    document.getElementById('quantityInput').value = '';
  
    
  }

 


  function exportTable() {






    const rows = document.querySelectorAll("table tr");
    const csv = [];

    rows.forEach(row => {
      const cells = row.querySelectorAll("th, td");
      const rowData = Array.from(cells).map(cell => `"${cell.innerText}"`);
      csv.push(rowData.join(","));
    });

    const csvString = csv.join("\n");
   
const prescriptionID = <?php echo json_encode($priscpID); ?>;

fetch(`pharmacySetQuates.php?id=${prescriptionID}`, {
  method: 'POST',
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify({
    drugIdArray:drugIDs ,
    drugQtyArray: drugQuantities,
    csv:csvString,
    total: total,
  })
})
.then(res => res.json())
.then(data => {
  if (data.success) {
    alert(data.message); 

    window.location.href = "../PHP/sendQuoteMail.php";
    
  } else {
    alert("Failed: " + data.message);
    window.location.href = `pahrmacyDashboard.php`;
  }
})




 
  }

  function changeImage(imgId) {
    const mainImg = document.getElementById('mainImg');
    const img = document.getElementById(imgId);
   const tempSrc = mainImg.src;
  mainImg.src = img.src;
  img.src = tempSrc;
  }
</script>





</body>
</html>
