<?php

$limit = 15; 

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// Initialize variables for search and filter
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

// Build the SQL query dynamically
$sql = "SELECT fname, gender, reg_number, email, phone, nin, age, special_need, employment_status, passport_path, course FROM student_table WHERE 1=1";

// Add search criteria if provided
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (fname LIKE '%$search%' OR nin LIKE '%$search%' OR phone LIKE '%$search%' OR reg_number LIKE '%$search%')";
}

// Add filter criteria if provided
if ($filter !== 'all') {
    $filter = $conn->real_escape_string($filter);
    if (in_array($filter, ['Male', 'Female'])) {
        $sql .= " AND gender = '$filter'";
    } elseif ($filter === 'special need') {
        $sql .= " AND special_need != ''";
    } else {
        $sql .= " AND course = '$filter'";
    }
}

$sql .= " LIMIT $limit OFFSET $start";

// Fetch data from the database
$result = $conn->query($sql);

// Check if data exists
if ($result->num_rows > 0) {
    $dataArray = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $dataArray = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reg_number'])) {
    $deleteReg = $conn->real_escape_string($_POST['delete_reg_number']);
    $deleteSql = "DELETE FROM student_table WHERE nin = '$deleteReg'";
    $conn->query($deleteSql);
}

// Close the database connection
$conn->close();

// Generate HTML dynamically
function generateHTML($dataArray) {
    $html = '';

    foreach ($dataArray as $data) {
        $html .= '
        <div class="container">
        <div class="menu">&#8942;</div>

            <div class="menu-nav">
                <form method="get" action="update.php" style="display:inline;">
                    <input type="hidden" name="reg_number" value="' . htmlspecialchars($data["nin"]) . '">
                    <button type="submit">Update</button>
                </form>
                <form method="post" action="" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this record?\');">
                    <input type="hidden" name="delete_reg_number" value="' . htmlspecialchars($data["nin"]) . '">
                    <button type="submit" id="delete">Delete</button>
                </form>
            </div>

            <div class="head">
                <img src="images/amee.jpg" alt="">
            </div>

            <div class="text">
                <div class="table">
                
                <table>
                    <tr><td id="bold">Gender:</td><td>' . htmlspecialchars($data["gender"]) . '</td></tr>
                    <tr><td id="bold">Age:</td><td>' . htmlspecialchars($data["age"]) . '</td></tr>
                    <tr><td id="bold">Phone:</td><td>' . htmlspecialchars($data["phone"]) . '</td></tr>  
                    <tr><td id="bold">NIN:</td><td>' . htmlspecialchars($data["nin"]) . '</td></tr>   
                    <tr><td id="bold">Employment Status:</td><td>' . htmlspecialchars($data["employment_status"]) . '</td></tr>
                    <tr><td id="bold">Special Need:</td><td>' . htmlspecialchars($data["special_need"]) . '</td></tr>      
                </table>
                </div>    
            </div>
           
        <div class="footer">
                   <div class="name"> <h1>' . htmlspecialchars($data["fname"]) . '</h1>
                    <h5>' . htmlspecialchars($data["email"]) . '</h5>
                </div>
                <div class="reg">
                    <h4>Course: ' . htmlspecialchars($data["course"]) . '</h4>
                    <h6 id="reg"> <span>Reg No: </span>' . htmlspecialchars($data["reg_number"]) . '</h6>
                </div>
        </div> 

        </div>';
    }

    return $html;
}
?>