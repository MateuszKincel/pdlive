<?php
include('class/handler.php');
//insert_message.php


$object = new Handler;



$student_id = $_SESSION['student_id'];
$object->query = "SELECT student_imie FROM student WHERE student_id = :student_id";
$data = array(':student_id' => $student_id);
$object->execute($data);
$result = $object->statement->fetchAll(PDO::FETCH_ASSOC);
$student_name = $result[0]['student_imie'];
  
$sender_id = $student_id;
// Initialize the $result variable to an empty result
$result = null;

// Get the selected recipient ID
if (isset($_POST['recipient_id'])) {
    $recipient_id = $_POST['recipient_id'];
  } else {
    // If the "recipient_id" key is not defined in the $_POST array, set the value to 0
    $recipient_id = 0;
  }

 // Check if a file was uploaded
    if (isset($_FILES['file'])) {
        // Get the file from the $_FILES array
        $file = $_FILES['file'];

        // Get the file name and extension
        $fileName = $file['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Set the allowed file extensions
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx');

        // Check if the file extension is allowed
        if (in_array($fileExt, $allowed)) {
            // Set the file destination
            $fileDestination = 'uploads/' . $fileName;

            // Move the file to the uploads folder
            move_uploaded_file($file['tmp_name'], $fileDestination);

            // Insert the message and file path into the chat table
            $student_id = $_SESSION['student_id'];
            $recipient_id = $_POST['recipient_id'];
            $message = $fileName;
            $timestamp = time();
            $object->query = "INSERT INTO chat (sender_id, recipient_id, message, timestamp, file_name) VALUES (:student_id, :recipient_id, :message, NOW(), :fileName)";
            $data = array(':student_id' => $student_id, ':recipient_id' => $recipient_id, ':message' => $message, ':fileName' => $fileName);
            $object->execute($data);
        }
    }
    // No file was uploaded, insert the message into the chat table
    if (!empty($_POST['message'])) {
      $student_id = $_SESSION['student_id'];
      $recipient_id = $_POST['recipient_id'];
      $message = $_POST['message'];
      $object->query = "INSERT INTO chat (sender_id, recipient_id, message, timestamp) VALUES (:student_id, :recipient_id, :message, NOW())";
      $data = array(':student_id' => $student_id, ':recipient_id' => $recipient_id, ':message' => $message);
      $object->execute($data);
}


