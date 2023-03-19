<?php
//get_messages.php
include('class/handler.php');

$object = new Handler;

// Initialize the $result variable to an empty result
$result = null;


// Get the selected recipient ID
$recipient_id = $_POST['recipient_id'];

// Get the student's ID from the session
$student_id = $_SESSION['student_id'];



// Query the chat table to retrieve the messages between the student and the selected promotor, ordered by the timestamp column in ascending order
$object->query = "SELECT c.message, c.timestamp, c.file_name, c.sender_id, s.student_imie AS sender_name, p.promotor_nazwa AS recipient_name
FROM chat c
LEFT JOIN student s ON c.sender_id = s.student_id
LEFT JOIN promotor p ON c.sender_id = p.promotor_id
WHERE (c.sender_id = :student_id AND c.recipient_id = :recipient_id) OR (c.sender_id = :recipient_id AND c.recipient_id = :student_id)
ORDER BY c.timestamp ASC";
$data = array(':student_id' => $student_id, ':recipient_id' => $recipient_id);
$object->execute($data);
$messages = $object->statement->fetchAll(PDO::FETCH_ASSOC);

// Return the messages in a table
echo '<html>';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="/css/sb-admin-2.css">';
echo '</head>';
echo '<body>';
echo '<table id="messages-table" style="width: 100%;">';
echo '<tr>';
echo '<th>Nadawaca:</th>';
echo '<th>Wiadomość:</th>';
echo '<th style="text-align: right; padding-right: 150px">Data:</th>';
echo '</tr>';
foreach ($messages as $message) {
  if ($message['sender_id'] == $student_id) {
    // The sender is a student
    $class = 'student';
    $name = $message['sender_name'];
  } else {
    // The sender is a promotor
    $class = 'promotor';
    $name = $message['recipient_name'];
  }
  echo '<tr class="' . $class . '" style="border-bottom: 1px solid #ddd;border-left: 1px solid #ddd;border-right: 1px solid #ddd; border-top: 1px solid #ddd;">';
echo '<td style="padding-left: 10px; font-weight: bold;">' . $name . ' :</td>';
if ($message['file_name']) {
    // The message is a file
    echo '<td style="padding-left: 20px;"> <a href="'.$object->get_file_path($message['file_name']).'" target="_blank">'.$message['file_name'].'</a> </td>';
} else {
    // The message is a regular message
    echo '<td style="padding-left: 20px;">' . $message['message'] . '</td>';
}
echo '<td style="text-align: right; padding-right: 10px;">' . $message['timestamp'] . '</td>';
echo '</tr>';
}
echo '</table>';

?>







