<?php include "header.php" ?>
<?php include "index_configuration.php" ?>

<form method="GET" action="index.php" class="header-form">
    <input type="text" id="search" name="search" placeholder="Search by name, NIN" value="<?= htmlspecialchars($search) ?>">
        <select name="filter">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
                <option value="web design" <?= $filter === 'web design' ? 'selected' : '' ?>>Web Design</option>
                <option value="seo" <?= $filter === 'seo' ? 'selected' : '' ?>>SEO</option>
                <option value="uiux" <?= $filter === 'uiux' ? 'selected' : '' ?>>UI/UX</option>
                <option value="product branding" <?= $filter === 'product branding' ? 'selected' : '' ?>>Product Branding</option>
                <option value="special need" <?= $filter === 'special need' ? 'selected' : '' ?>>Special Need</option>
                <option value="Male" <?= $filter === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $filter === 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
    <button class="btn" type="submit">Filter Result</button>
</form>

<div id="main-menu" class="main-menu"> 
    <?php
    // Generate and echo the HTML content
    echo generateHTML($dataArray);
    ?>
</div>

<?php
include "database.php";

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


<?php include "footer.php" ?>

<script defer>
  document.querySelectorAll(".menu").forEach(menu => {
    menu.addEventListener('click', (e) => {
        const menuNav = e.target.nextElementSibling;
        menuNav.classList.toggle('active');
    });
});
</script>
</body>
</html>
