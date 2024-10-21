<?php
require_once('database.php');

$query = "SELECT lecturer_id, password FROM lecturers";
$result = $conn->query($query);

while($row = $result->fetch_assoc()) {
    $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
    $update_query = "UPDATE lecturers SET password = '$hashed_password' WHERE lecturer_id = ".$row['lecturer_id'];
    $conn->query($update_query);
}

echo "Passwords have been updated.";
?>
