<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//chat.php
include('../class/handler_admin.php');

$object = new Handler;

include('header.php');

// Connect to the pd_db database
$db = mysqli_connect("localhost", "u829025220_root", "J2zgffghh!", "u829025220_DB");



// Initialize the $result variable to an empty result
$result = null;

$messages = array();
 // Get the student's ID and name from the session
  $promotor_id = $_SESSION['admin_id'];
  $query = "SELECT promotor_nazwa FROM promotor WHERE promotor_id = $promotor_id";
  $result = mysqli_query($db, $query);
  $promotor_name = mysqli_fetch_assoc($result)['promotor_nazwa'];

  


// Check if the form has been submitted

  // Get the selected recipient ID
  if (isset($_POST['recipient_id'])) {
    $recipient_id = $_POST['recipient_id'];
  } else {
    // If the "recipient_id" key is not defined in the $_POST array, set the value to 0
    $recipient_id = 0;
  }
$recipients = array();
 
 // Get the promotor's name from the promotor
  $query = "SELECT promotor_id, promotor_nazwa FROM promotor";
  $result = mysqli_query($db, $query);
  while ($row = mysqli_fetch_assoc($result)) {
    $recipients[] = $row;
  }
 
$promotor_id = $_SESSION['admin_id'];


  $query = "SELECT student_imie FROM student WHERE student_id = student_id";
  $result = mysqli_query($db, $query);
  $student_name = mysqli_fetch_assoc($result)['student_imie'];


  // Query the chat table to retrieve the messages between the student and the selected promotor, ordered by the timestamp column in ascending order
$query = "SELECT c.message, c.timestamp, c.file_name, s.student_imie, p.promotor_nazwa AS sender_name, s.student_imie AS recipient_name
      FROM chat c
      INNER JOIN promotor p ON c.sender_id = p.promotor_id
      INNER JOIN student s ON c.recipient_id = s.student_id
      WHERE (c.sender_id = $promotor_id AND c.recipient_id = $recipient_id) OR (c.sender_id = $recipient_id AND c.recipient_id = $promotor_id)
      ORDER BY c.timestamp ASC";
      $result = mysqli_query($db, $query);
      $messages = array();
      while ($row = mysqli_fetch_assoc($result)) 
        $messages[] = $row;



  // Query the chat table to retrieve the sender's name
$sender_id = $_SESSION['admin_id'];
$query = "SELECT DISTINCT s.student_id, CONCAT(s.student_imie, ' ', s.student_nazwisko, ' ', s.student_nr_indeksu) AS full_name
FROM pd pt
INNER JOIN student s ON pt.student_id = s.student_id
INNER JOIN pd pd ON pt.pd_id = pd.pd_id
WHERE pt.promotor_id = $sender_id AND pd.promotor_id = $sender_id";
$result = mysqli_query($db, $query);
$recipients = array();
while ($row = mysqli_fetch_assoc($result)) {
  $recipients[] = $row;

}




?>

<div class="card">
  <div class="card-header"><h4>Chat ze studentem</h4></div>
    <div class="card-body">
      <div id="chat-section" style="height: 300px; overflow-y: scroll; width: 100%;">
      <!-- The messages will be inserted here by the AJAX request -->
      <?php foreach ($messages as $message): ?>
    <?php if ($message['sender_name'] == $promotor_name): ?>
      <!-- If the sender's name is the student's name, add the "sender" class to the message div -->
      <div style="background-color: #333;" class="message sender"><?php echo $message['message']; ?></div>
    <?php else: ?>
      <!-- If the sender's name is not the student's name, do not add the "sender" class to the message div -->
      <div class="message"><?php echo $message['message']; ?></div>
    <?php endif; ?>
  <?php endforeach; ?>
    </div>
    <form method="post" action="get_messages.php" id="chat-form" data-recipient-id="<?php echo $recipient_id; ?>">
      <label for="recipient">Wybierz Studenta:</label><br>
      <select  name="recipient_id" id="recipient">
        <option value="0">Wybierz Studenta</option>
        <?php foreach ($recipients as $recipient): ?>
          <option value="<?php echo $recipient['student_id']; ?>"><?php echo $recipient['full_name']; ?></option>
        <?php endforeach; ?>
        </select>
        <br>
        <input type="hidden" name="promotor_id" value="<?php echo $promotor_id; ?>">
        <label for="message">Wpisz wiadomość:</label><br>
        <div>
          <input class="form-control" name="message" id="message" style="width: 80%; height: 30px; margin-bottom: 10px;" placeholder="Wpisz wiadomość...">
          <br>
          <input type="file" name="file" id="file-input">
          <br>
          <button type="submit" name="submit" id="send-btn" class="btn btn-primary">Wyślij</button>
        </div>
      </form>
    </div>
  </div>
</div>
        </div>





<script>
  $(document).ready(function() {
  $("#recipient").change(function() {
    // Get the selected promotor id
    var recipientId = $(this).val();
    
    // Send an AJAX request to the server to retrieve the messages for the selected promotor
    $.ajax({
      url: "get_messages.php", // the URL of the PHP file that will handle the request
      type: "POST", // the type of request
      data: { recipient_id: recipientId }, // the data to send to the server
      success: function(response) { // a function to be called if the request succeeds
        // Update the chat-section div with the response
        $("#chat-section").html(response);
        $("#chat-section").scrollTop($("#chat-section")[0].scrollHeight);

      }
    });
  });
});
</script>

<script>
  $(document).ready(function() {

    $('#file-input').change(function() {
        $('#message').attr('disabled', true);
      });

    // Handle the form submit event
    $("#chat-form").submit(function(event) {
      // Prevent the form from being submitted
      event.preventDefault();
      // Get the selected recipient ID from the recipient select element
      var recipientId = $("#recipient").val();
      // Get the message from the textarea
      var message = $("#message").val();

      // Get the file input element
      const fileInput = document.getElementById('file-input');

      // Re-enable the message text input after the form is submitted
      $('#message').attr('disabled', false);

      // Get the file from the input element
      const file = fileInput.files[0];

      // Create a new FormData object to send the file and message to the server
      const formData = new FormData();
      formData.append('file', file);
      formData.append('recipient_id', recipientId);

      // Send the FormData object to the server using an AJAX request
      $.ajax({
        type: 'POST',
        url: 'insert_message.php',
        data:formData,
        processData: false,  // Tell jQuery not to process the data
        contentType: false 
          // Tell jQuery not to set the content type        
});



      if (recipientId == 0) {
        alert("Najpierw wybierz promotora.");
        return;
      }
      // Send an AJAX request to the server to insert the message into the chat table
      $.ajax({
        url: "insert_message.php", // the URL of the PHP file that will handle the request
        type: "POST", // the type of request
        data: {
          promotor_id: <?php echo $_SESSION['admin_id']; ?>,
          recipient_id: recipientId,
          message: message
        }, // the data to send to the server
        success: function(response) { // a function to be called if the request succeeds
          // Send an AJAX request to the get_messages.php script to retrieve the updated chat messages
          $.ajax({
            url: "get_messages.php",
            type: "POST",
            data: { recipient_id: recipientId },
            success: function(response) {
              // Update the chat-section div with the response
              $("#chat-section").html(response);
              $("#message").focus();
              $("#chat-section").scrollTop($("#chat-section")[0].scrollHeight);
            }
          });
        }
      });
    });

    // Add a keydown event listener to the textarea element
    $("#message").keydown(function(event) {
      // Check if the key pressed is the Enter key
      if (event.keyCode == 13) {
        event.preventDefault();
        // Trigger the submit event on the form element
        $("#chat-form").submit();
        $("#message").val("");
      }
    });
  });
</script>

<script>
  $(document).ready(function() {

  // Code to retrieve the messages and update the chat section goes here

  // Update the messages every 5 seconds
  setInterval(function() {
    // Get the selected recipient ID from the recipient select element
    var recipientId = $("#recipient").val();
    // Send an AJAX request to the get_messages.php script to retrieve the updated chat messages
    $.ajax({
      url: "get_messages.php",
      type: "POST",
      data: { recipient_id: recipientId },
      success: function(response) {
        // Update the chat-section div with the response
        $("#chat-section").html(response);
        $("#chat-section").scrollTop($("#chat-section")[0].scrollHeight);
      }
    });
  }, 5000);
});
</script>


<?php include('footer.php'); ?>







