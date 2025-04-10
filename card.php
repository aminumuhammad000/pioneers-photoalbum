<?php

$limit = 15; // records per page

// Get current page from URL (default to 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// Fetch records
$sql = "SELECT * FROM student_table LIMIT $start, $limit";
$result = $conn->query($sql);

?>

<?php while($row = $result->fetch_assoc()): ?>
    <!-- cad start here  -->
    <div class="card">
    
    <div class="img-container">
        <img src="images/profile.jpg" alt="">
    </div>

    <!-- details start here  -->
    <div class="details">
        <table>
            <tr>
            <td id="bold">Name:</td>
            <td><?= $row['fname'] ?></td>
            </tr>
            <tr>
            <td id="bold">Gender:</td>
            <td><?= $row['gender'] ?></td>
            </tr>
            <tr>
            <td id="bold">Course:</td>
            <td><?= $row['course'] ?></td>
            </tr>
            <tr>
            <td id="bold">Age:</td>
            <td><?= $row['age'] ?></td>
            </tr>
            <tr>
            <td id="bold">Phone:</td>
            <td><?= $row['phone'] ?></td>
            </tr>
        </table>
    </div>
    <!-- detial end here  -->

    </div>
    <!-- card end here  -->
<?php endwhile; ?>;
