<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    
    $sql = "DELETE FROM lecturers WHERE lecturer_id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Lecturer deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: lecturers.php");
    exit();
}
?>
