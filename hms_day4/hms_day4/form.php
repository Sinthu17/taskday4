<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Validation
    if (empty($name) || empty($email) || empty($phone)) {
        die("<script>alert('⚠️ All fields are required!'); window.location='form.html';</script>");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<script>alert('❌ Invalid email format!'); window.location='form.html';</script>");
    }
    if (!is_numeric($phone) || strlen($phone) != 10) {
        die("<script>alert('❌ Phone must be 10 digits!'); window.location='form.html';</script>");
    }

    // Prevent duplicate email
    $sql_check = "SELECT * FROM entries WHERE email='$email'";
    $result = $conn->query($sql_check);
    if ($result->num_rows > 0) {
        die("<script>alert('⚠️ Email already exists!'); window.location='form.html';</script>");
    }

    // Insert into database
    $sql = "INSERT INTO entries (name,email,phone) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $phone);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Patient Registered Successfully!'); window.location='form.html';</script>";
    } else {
        die("<script>alert('❌ Error: " . $stmt->error . "'); window.location='form.html';</script>");
    }

    $stmt->close();
}
$conn->close();
?>
