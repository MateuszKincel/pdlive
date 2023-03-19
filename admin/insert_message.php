<?php
include('../class/handler_admin.php');
//insert_message.php


$object = new Handler;

// Connect to the pd_db database
$db = mysqli_connect("localhost", "u829025220_root", "J2zgffghh!", "u829025220_DB");



$promotor_id = $_SESSION['admin_id'];
  $query = "SELECT promotor_nazwa FROM promotor WHERE promotor_id = $promotor_id";
  $result = mysqli_query($db, $query);
$sender_id = $promotor_id;
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
            $fileDestination = '../uploads/' . $fileName;

            // Move the file to the uploads folder
            move_uploaded_file($file['tmp_name'], $fileDestination);

            // Insert the message and file path into the chat table
            $promotor_id = $_SESSION['admin_id'];
            $recipient_id = $_POST['recipient_id'];
            $message = $fileName;
            $timestamp = time();
            $query = "INSERT INTO chat (sender_id, recipient_id, message, timestamp, file_name) VALUES ($sender_id, $recipient_id, '$message',DATE_FORMAT($timestamp, '%Y-%m-%d %H:%i:%s'), '$fileName' )";
            mysqli_query($db, $query);
        }
    }
    // No file was uploaded, insert the message into the chat table
    if (!empty($_POST['message'])) {
        $promotor_id = $_SESSION['student_id'];
        $recipient_id = $_POST['recipient_id'];
        $message = $_POST['message'];
        $timestamp = time();
        $query = "INSERT INTO chat (sender_id, recipient_id, message, timestamp) VALUES ($sender_id, $recipient_id, '$message', DATE_FORMAT($timestamp, '%Y-%m-%d %H:%i:%s'))";
        mysqli_query($db, $query);
    }


