<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $faculty_id = $_POST['faculty_id'];
    $file = $_FILES['image'];
    
    if ($file['error'] == 0) {
        // Define the upload directory
        $upload_dir = 'uploads/faculty_images/';
        $file_name = $faculty_id . '_' . basename($file['name']);
        $file_path = $upload_dir . $file_name;

        // Ensure the upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file to the directory
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Update the faculty record with the image path
            $stmt = $conn->prepare("UPDATE faculty SET image = ? WHERE id = ?");
            $stmt->bind_param('si', $file_path, $faculty_id);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
?>
