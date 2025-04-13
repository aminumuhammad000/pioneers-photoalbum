<?php
include "header.php";

$messege = '';
$reg_number = $_GET['reg_number'] ?? ''; // unique identifier for the student

// Fetch existing student data
if ($reg_number !== '') {
    $sql = "SELECT * FROM student_table WHERE nin = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $reg_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    die("No registration number provided.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST['fname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $nin = $_POST['nin'];
    $age = $_POST['age'];
    $special_need = $_POST['special_need'];
    $employment_status = $_POST['employment_status'];
    $course = $_POST['course'];

    $target_file = $student['passport_path']; // default to old image

    // Handle image upload if new one provided
    if (!empty($_FILES['passport']['name'])) {
        $target_dir = "images-folder/";
        $passport = $_FILES['passport'];
        $imageFileType = strtolower(pathinfo($passport["name"], PATHINFO_EXTENSION));

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'jfif'])) {
            die("Invalid file type.");
        }

        $target_file = $target_dir . basename($passport["name"]);
        move_uploaded_file($passport["tmp_name"], $target_file);
    }

    // Update record
    $sql = "UPDATE student_table SET fname=?, gender=?, email=?, phone=?, nin=?, age=?, special_need=?, employment_status=?, course=?, passport_path=? WHERE reg_number=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $fname, $gender, $email, $phone, $nin, $age, $special_need, $employment_status, $course, $target_file, $reg_number);

    if (mysqli_stmt_execute($stmt)) {
        $messege = "Student record updated successfully!";
    } else {
        $messege = "Failed to update record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<main class="register-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>Update Student's Record</h1>
        <img src="images/logo.png" alt="Logo" id="logo">
        <p style="color:green;"><?php echo $messege; ?></p>

        <input type="text" placeholder="Full Name" required name="fname" value="<?php echo htmlspecialchars($student['fname']); ?>">

        <select name="gender" required>
            <option disabled>Select Gender</option>
            <option value="male" <?php if ($student['gender'] === 'male') echo 'selected'; ?>>Male</option>
            <option value="female" <?php if ($student['gender'] === 'female') echo 'selected'; ?>>Female</option>
        </select>

        <input type="text" name="reg_number" value="<?php echo htmlspecialchars($student['reg_number']); ?>" disabled>

        <input type="email" placeholder="Email Address" required name="email" value="<?php echo htmlspecialchars($student['email']); ?>">

        <input type="text" placeholder="Phone Number" required name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">

        <input type="number" placeholder="NIN Number" required name="nin" value="<?php echo htmlspecialchars($student['nin']); ?>">

        <input type="number" placeholder="Age" required name="age" min="15" value="<?php echo htmlspecialchars($student['age']); ?>">

        <select name="course" required>
            <option disabled>Choose Your Course</option>
            <option value="Web Design" <?php if ($student['course'] === 'Web Design') echo 'selected'; ?>>Web Design</option>
            <option value="Product Branding" <?php if ($student['course'] === 'Product Branding') echo 'selected'; ?>>Product Branding</option>
            <option value="UI/UX" <?php if ($student['course'] === 'UI/UX') echo 'selected'; ?>>UI/UX</option>
            <option value="SEO" <?php if ($student['course'] === 'SEO') echo 'selected'; ?>>SEO</option>
        </select>

        <select name="special_need" required>
            <option disabled>Do You Have Any Special Need?</option>
            <option value="yes" <?php if ($student['special_need'] === 'yes') echo 'selected'; ?>>Yes</option>
            <option value="no" <?php if ($student['special_need'] === 'no') echo 'selected'; ?>>No</option>
        </select>

        <select name="employment_status" required>
            <option disabled>Employment Status</option>
            <option value="self-employed" <?php if ($student['employment_status'] === 'self-employed') echo 'selected'; ?>>Self-Employed</option>
            <option value="employed" <?php if ($student['employment_status'] === 'employed') echo 'selected'; ?>>Employed</option>
            <option value="unemployed" <?php if ($student['employment_status'] === 'unemployed') echo 'selected'; ?>>Unemployed</option>
        </select>

        <label>Current Passport:</label><br>
        <img src="<?php echo $student['passport_path']; ?>" width="100"><br><br>
        <label>Upload New Passport (optional)</label>
        <input type="file" accept="image/*" name="passport">

        <input type="submit" value="Update">
    </form>
</main>

<?php include "footer.php"; ?>
</body>
</html>
