<?php
    // <?php include 'header.php'; ?
 include 'connection.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="printable.css">
    <title>pioneers | photo album printable </title>
</head>
<body>
    <!-- header start here  -->
    <?php include "header.php" ?>
    <!-- header end here  -->



    <!-- main menu of the photo album  -->
    <main>
        <div class="heading">
            <h1 id="title">
                Photo Album
            </h1>

            <button class="btn" onclick="window.print()">print photo album</button>
        </div>
        
        <!-- photo album container  -->
        <div class="photo-container">
        <?php include "card.php" ?>
        </div>
<?php
// Get total records
$total_result = $conn->query("SELECT COUNT(*) AS total FROM student_table");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);


// Display page numbers
echo "<div class='pagination'>";
?>

<?php if ($page > 1): ?>

 <a href="?page=<?= $page - 1 ?>">&laquo;</a>

<?php endif; ?>

<?php

for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}
?>
 
<?php if ($page < $total_pages): ?>
    <a href="?page=<?= $page + 1 ?>">&raquo;</a>
  <?php endif; ?> 
  </div>  
    </main>

    <footer>
        <div class="img-container">
            <img src="images/logo.png" alt="">
        </div>

        <p>&copy; 2024/2025 | All Rights Reserved.</p>
            <div class="contact">
                <div class="facebook"><img src="images/facebook.png" alt="facebook icon"></div>
                <div class="twitter"><img src="images/twitter.png" alt="twitter icon"></div>
                <div class="linkedin"><img src="images/instagram.png" alt="instagram icon"></div>
            </div>
    </footer>
</body>
</html>