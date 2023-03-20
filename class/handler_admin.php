<?php

//handler.php

require '../includes/PHPMailer.php';
require '../includes/SMTP.php';
require '../includes/Exception.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

class Handler
{
	public $base_url = 'https://epraca.site';
	public $admin_url = 'https://epraca.site/admin/panel_admin.php';
	public $connect;
	public $query;
	public $statement;
	public $now;

	public function __construct()
	{
		$this->connect = new PDO("mysql:host=localhost;dbname=u829025220_DB", "u829025220_root", "J2zgffghh!");

		date_default_timezone_set('Europe/Warsaw');

		session_name("admin_session");
		session_start();

		$this->now = date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
	}

	


	function send_mail($message, $subject, $message_body, $recipient, $repplyTo=null) {
    $mail = new PHPMailer(true);
    // $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.titan.email';
    $mail->Port = '587';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@epraca.site';
    $mail->Password = 'J2zgffghh!';
    $mail->SMTPSecure = 'tls';
    $mail->From = 'info@epraca.site';
    $mail->FromName = 'E-Praca';
    $mail->addAddress($recipient);
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    $mail->addReplyTo($repplyTo);
    $mail->Subject = $subject;
    $message_body = $message_body;
    $mail->Body = $message_body;
    
    try {
        if($mail->Send()) {
            $success = '<div class="alert alert-success">Pomyślnie wysłano powiadomienie.</div>';
            return $success;
        } else {
            $error = '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
            $error .= '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
            return $error;
        }
    } catch (Exception $e) {
        $error = '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
        $error .= '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
        return $error;
    }
}



	function updateTematDostepny($temat_pracy, $temat, $object) {
    // Check if temat_pracy exists in pd
    $object->query = "SELECT * FROM pd WHERE temat_pracy = :temat_pracy";
    $data = array(':temat_pracy' => $temat_pracy);
    $object->execute($data);

    if($object->row_count() > 0) {
        // Update temat_dostepny to "Nie" in temat
        $object->query = "UPDATE temat SET temat_dostepny = 'Nie' WHERE temat = :temat";
        $data = array(':temat' => $temat);
        $object->execute($data);
    } else {
        // Update temat_dostepny to "Tak" in temat
        $object->query = "UPDATE temat SET temat_dostepny = 'Tak' WHERE temat = :temat";
        $data = array(':temat' => $temat);
        $object->execute($data);
    }
}

	function check_temat_pracy($temat_pracy){
		$query = "SELECT COUNT(*) FROM pd WHERE temat_pracy = :temat_pracy";
		$statement = $this->connect->prepare($query);
		$statement->execute([':temat_pracy' => $temat_pracy]);
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if($row['COUNT(*)'] == 0){
			return true;
		}
		else{
			return false;
		}
	}


	public function get_temat_result($temat_id)
{
    $stmt = $this->pdo->prepare($this->query);
    $stmt->bindValue(1, $temat_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}


	
	function execute($data = null)
	{
		$this->statement = $this->connect->prepare($this->query);
		if($data)
		{
			$this->statement->execute($data);
		}
		else
		{
			$this->statement->execute();
		}		
	}

	function fetch()
	{
    return $this->statement->fetch(PDO::FETCH_ASSOC);
	}

	function row_count()
	{
		return $this->statement->rowCount();
	}

	function statement_result()
	{
		return $this->statement->fetchAll();
	}

	function get_result()
	{
		return $this->connect->query($this->query, PDO::FETCH_ASSOC);
	}
	

	function is_login()
	{
		if(isset($_SESSION['admin_id']))
		{
			return true;
		}
		return false;
	}

	function is_master_user()
	{
		if(isset($_SESSION['user_type']))
		{
			if($_SESSION["user_type"] == 'Master')
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function clean_input($string)
	{
	  	$string = trim($string);
	  	$string = stripslashes($string);
	  	$string = htmlspecialchars($string);
	  	return $string;
	}

	function Generate_praca_no()
	{
		$this->query = "
		SELECT MAX(numer_pracy) as numer_pracy FROM pd 
		";

		$result = $this->get_result();

		$numer_pracy = 0;

		foreach($result as $row)
		{
			$numer_pracy = $row["numer_pracy"];
		}

		if($numer_pracy > 0)
		{
			return $numer_pracy + 1;
		}
		else
		{
			return '1';
		}
	}

function topics_left() {
    //query to get the number of topics that the promotor is allowed to insert
    $object->query = "SELECT promotor_liczba_tematow FROM promotor WHERE promotor_id = :promotor_id";
    $object->execute(array(':promotor_id' => $_SESSION['promotor_id']));
    $promotor_liczba_tematow = $object->fetch()['promotor_liczba_tematow'];
    
    //query to get the number of topics that the promotor has already inserted
    $object->query = "SELECT COUNT(*) as count FROM temat WHERE promotor_id = :promotor_id";
    $object->execute(array(':promotor_id' => $_SESSION['promotor_id']));
    $dodane_tematy = $object->fetch()['count'];	
	global $pozostalo;
    $pozostalo = $promotor_liczba_tematow - $dodane_tematy;
	return  $pozostalo;

}


	function bindParam($param, $value, $type = null)
{
    if (is_null($type)) {
        switch (true) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
    }
    $this->statement->bindParam($param, $value, $type);
}

	function getPromotorTematy() {
		$this->query = "SELECT promotor_liczba_tematow FROM promotor";
		$this->execute();
		$result = $this->get_result();
		$sum = 0;
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		if (is_array($row) && array_key_exists('promotor_liczba_tematow', $row)) {
			$sum += $row['promotor_liczba_tematow'];
			}
		}
		return $sum;
	}


	function prace_zarezerwowane()
	{
		$this->query = "
		SELECT * FROM pd
		WHERE status = 'Zarezerwowano' 
		";
		$this->execute();
		return $this->row_count();
	}

	function prace_wszystkie()
	{
		$this->query = "
		SELECT * FROM pd 
		";
		$this->execute();
		return $this->row_count();
	}

	function studenci_wszyscy()
	{
		$this->query = "
		SELECT * FROM student 
		";
		$this->execute();
		return $this->row_count();
	}

	function doktoranci_wszyscy()
	{
		$this->query = "
		SELECT student.*
		FROM student
		INNER JOIN pd ON student.student_id = pd.student_id
		WHERE pd.status = 'Zakończono'; 
		";
		$this->execute();
		return $this->row_count();
	}

  function get_file_path($file_name) {
    $upload_dir = 'uploads/';
    return $upload_dir . $file_name;
  }
  function admin_get_file_path($file_name) {
    $upload_dir = '../uploads/';
    return $upload_dir . $file_name;
  }



	
	function promotorzy_wszyscy()
	{
		$this->query = "
		SELECT * FROM promotor
		";
		$this->execute();
		return $this->row_count();
	}

}

?>






