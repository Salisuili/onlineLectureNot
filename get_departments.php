<?php
include 'database.php';

if (isset($_GET['faculty_id'])) {
    $faculty_id = intval($_GET['faculty_id']);
    
    $query = "SELECT department_id, department_name FROM departments WHERE faculty_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $departments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare the SQL statement.']);
        exit;
    }
    
    if (empty($departments)) {
        echo json_encode(['error' => 'No departments found for this faculty.']);
    } else {
        echo json_encode($departments);
    }
} else {
    echo json_encode(['error' => 'Faculty ID not provided.']);
}

$conn->close();
?>
