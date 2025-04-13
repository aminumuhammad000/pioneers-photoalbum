<?php
include "header.php";

$messege = '';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $fname = $_POST['fname'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $reg_number = $_POST['reg_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $nin = $_POST['nin'] ?? '';
    $age = $_POST['age'] ?? '';
    $special_need = $_POST['special_need'] ?? '';
    $employment_status = $_POST['employment_status'] ?? '';
    $course = $_POST['course'] ?? '';

    // Handle image upload
    $target_dir = "images-folder/";
    $passport = $_FILES['passport'] ?? null;

    if ($passport && $passport['error'] === UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($passport["name"]);

        // Validate image file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif', 'jfif'])) {
            die("Invalid file type. Only JPG, JPEG, PNG, jfif, and GIF are allowed.");
        }

        // Move the uploaded file
        if (move_uploaded_file($passport["tmp_name"], $target_file)) {
            // Insert data into database
            $sql = "INSERT INTO student_table (fname, gender, reg_number, email, phone, nin, age, special_need, employment_status, passport_path, course) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssssssssss", $fname, $gender, $reg_number, $email, $phone, $nin, $age, $special_need, $employment_status, $target_file, $course);

                if ($stmt->execute()) {
                     header("Location: success.php");
                    exit();
                } else {
                    header("Location: fail.php");
                    exit();
                }

                $stmt->close();
            } else {
                die("SQL preparation failed: " . $conn->error);
            }
        } else {
            die("Failed to upload the image. Check folder permissions.");
        }
    } else {
        die("File upload error: " . $passport['error']);
    }
}

$conn->close();
?>

    <main class="register-container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
    <h1>Registration Form</h1>
    <img src="images/logo.png" alt="Logo" id="logo">
    
    <input type="text" placeholder="Full Name" required name="fname">
    
    <select name="gender" required>
        <option value="" disabled selected>Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
    
    <input type="text" placeholder="Registration Number" required name="reg_number">
    
    <input type="email" placeholder="Email Address" required name="email">
    
    <input type="text" placeholder="Phone Number" required name="phone">
    
    <input type="number" placeholder="NIN Number" required name="nin" maxlength="12" minlength="9">
    
    <input type="number" placeholder="Age" required name="age" min="15">
    
    <select name="course" required>
        <option value="" disabled selected>Choose Your Course</option>
        <option value="Web Design">Web Design</option>
        <option value="Product Branding">Product Branding</option>
        <option value="UI/UX">UI/UX</option>
        <option value="SEO">SEO</option>
    </select>

    <select name="special_need" required>
        <option value="" disabled selected>Do You Have Any Special Need?</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select>
    
    <select name="employment_status" required>
        <option value="" disabled selected>Employment Status</option>
        <option value="self-employed">Self-Employed</option>
        <option value="employed">Employed</option>
        <option value="unemployed">Unemployed</option>
    </select>

    <input type="file" accept="image/*" name="passport" required>  
    <input type="submit" value="Send">
    </form>
    </main>

    <?php include "footer.php" ?>
</body>
</html>