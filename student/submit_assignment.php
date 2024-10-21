<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assessment_id = $_POST['assessment_id'];
    $user_id = $_SESSION['user_id'];
    
    // Handling file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["assignment_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid type
    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        $_SESSION['message'] = "Sorry, only PDF, DOC, and DOCX files are allowed.";
        $_SESSION['message_type'] = 'error';
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
        // Store assignment submission details in the database
        $sql = "INSERT INTO submissions (user_id, assessment_id, file_path) VALUES ('$user_id', '$assessment_id', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Assignment submitted successfully!";
            $_SESSION['message_type'] = 'success';
            header("Location: assignments.php");
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            $_SESSION['message_type'] = 'error';
            header("Location: assignments.php");
        }
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
        $_SESSION['message_type'] = 'error';
        header("Location: assignments.php");
    }

    $conn->close();
    exit;
}
?>
