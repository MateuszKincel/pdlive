<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<?php

//chat.php
include('class/handler.php');
// Connect to the pd_db database
$object = new Handler;

include('header.php');





// Initialize the $result and $message variables
$result = null;
$messages = array();

 // Get the student's ID and name from the session
$student_id = $_SESSION['student_id'];
$query = "SELECT student_imie FROM student WHERE student_id = :student_id";
$data = array(':student_id' => $student_id);
$object->query = $query;
$object->execute($data);
$result = $object->statement->fetchAll(PDO::FETCH_ASSOC);
$student_name = $result[0]['student_imie'];

  




  // Get the selected recipient ID
$recipient_id = 0;
if (isset($_POST['recipient_id'])) {
    $recipient_id = $_POST['recipient_id'];
}

$object->query = "SELECT promotor_id, promotor_nazwa FROM promotor";
$object->execute();
$recipients = $object->statement->fetchAll(PDO::FETCH_ASSOC);
 

  // Query the chat table to retrieve the messages between the student and the selected promotor, ordered by the timestamp column in ascending order
$object->query = "SELECT c.message, c.timestamp, c.file_name, s.student_imie AS sender_name, p.promotor_nazwa AS recipient_name
FROM chat c
INNER JOIN student s ON c.sender_id = s.student_id
INNER JOIN promotor p ON c.recipient_id = p.promotor_id
WHERE (c.sender_id = :student_id AND c.recipient_id = :recipient_id) OR (c.sender_id = :recipient_id AND c.recipient_id = :student_id)
    ORDER BY c.timestamp ASC";
$data = array(':student_id' => $student_id, ':recipient_id' => $recipient_id);
$object->execute($data);
$messages = $object->statement->fetchAll(PDO::FETCH_ASSOC);




$sender_id = $_SESSION['student_id'];

$object->query = "SELECT DISTINCT p.promotor_id, p.promotor_nazwa
FROM pd pt
INNER JOIN promotor p ON pt.promotor_id = p.promotor_id
INNER JOIN pd pd ON pt.pd_id = pd.pd_id
WHERE pt.student_id = :sender_id AND pd.student_id = :sender_id";
$data = array(':sender_id' => $sender_id);
$object->execute($data);
$recipients = $object->statement->fetchAll(PDO::FETCH_ASSOC);




?>

<div class="container-fluid">
  <?php include('navbar.php'); ?>
  <br />
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header"><h4>Chat z promotorem</h4></div>
        <div class="card-body">
          <div id="chat-section" style="height: 300px; overflow-y: scroll; width: 100%;">
            <!-- The messages will be inserted here by the AJAX request -->
            <?php foreach ($messages as $message): ?>
          <?php if ($message['sender_name'] == $student_name): ?>
            <!-- If the sender's name is the student's name, add the "sender" class to the message div -->
            <div style="background-color: #333;" class="message sender"><?php echo $message['message']; ?></div>
          <?php else: ?>
            <!-- If the sender's name is not the student's name, do not add the "sender" class to the message div -->
            <div class="message"><?php echo $message['message']; ?></div>
          <?php endif; ?>
        <?php endforeach; ?>
          </div>
          <form method="post" action="get_messages.php" id="chat-form" data-recipient-id="<?php echo $recipient_id; ?>">
            <label for="recipient">Wybierz promotora:</label><br>
            <select  name="recipient_id" id="recipient">
              <option value="0">Wybierz Promotora</option>
              <?php foreach ($recipients as $recipient): ?>
                <option value="<?php echo $recipient['promotor_id']; ?>"><?php echo $recipient['promotor_nazwa']; ?></option>
              <?php endforeach; ?>
              </select>
              <br>
              <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
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
  const messageInput = document.querySelector('#message');
  const sendButton = document.querySelector('#send-btn');
  
  sendButton.addEventListener('click', () => {
    messageInput.value = ''; // Clear the message input field
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
          student_id: <?php echo $_SESSION['student_id']; ?>,
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

<

<?php include('footer.php'); ?>