<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $title = $_POST['title'];
    $assessment_type = $_POST['assessment_type'];
    $description = $_POST['description'];
    $total_marks = $_POST['total_marks'];
    $due_date = $_POST['due_date'];
    

    $sql = "INSERT INTO assessments (course_name, assessment_type, title, description, total_marks, due_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $course_name, $assessment_type, $title, $description, $total_marks, $due_date);
 
    if ($stmt->execute()) {
        header('location: assignments.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
