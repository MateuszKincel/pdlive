<?php

//promotor_akcja.php

include('../class/handler_admin.php');

$object = new Handler;













if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('student_imie', 'student_nazwisko','student_nr_indeksu','student_gr_dziek','student_podgrupa', 'student_adres_email', 'student_telefon', 'weryfikacja_email');

		$output = array();

		$main_query = "
		SELECT * FROM student ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE student_imie LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_nazwisko LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_adres_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_nr_indeksu LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_gr_dziek LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_semestr LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_podgrupa LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_telefon LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR weryfikacja_email LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY student_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["student_imie"].' '.$row["student_nazwisko"].' '.$row["student_nr_indeksu"];
			$sub_array[] = $row["student_semestr"];
			$sub_array[] = $row["student_gr_dziek"];
			$sub_array[] = $row["student_podgrupa"];
			$sub_array[] = $row["student_adres_email"];
			$sub_array[] = $row["student_telefon"];
			$status = '';
			if($row["weryfikacja_email"] == 'Tak')
			{
				$status = '<span class="badge badge-success">Tak</span>';
			}
			else
			{
				$status = '<span class="badge badge-danger">Nie</span>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["student_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["student_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$sub_array[] = ""; // Add an empty element to the array
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM student 
		WHERE student_id = '".$_POST["student_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['student_adres_email'] = $row['student_adres_email'];
			$data['student_haslo'] = $row['student_haslo'];
			$data['student_imie'] = $row['student_imie'];
			$data['student_nazwisko'] = $row['student_nazwisko'];
			$data['student_nr_indeksu'] = $row['student_nr_indeksu'];
			$data['student_semestr'] = $row['student_semestr'];
			$data['student_wydzial'] = $row['student_wydzial'];
			$data['student_gr_dziek'] = $row['student_gr_dziek'];
			$data['student_data_urodzenia'] = $row['student_data_urodzenia'];
			$data['student_podgrupa'] = $row['student_podgrupa'];
			$data['student_adres'] = $row['student_adres'];
			$data['student_telefon'] = $row['student_telefon'];
			if($row['weryfikacja_email'] == 'Tak')
			{
				$data['weryfikacja_email'] = '<span class="badge badge-success">Tak</span>';
			}
			else
			{
				$data['weryfikacja_email'] = '<span class="badge badge-danger">Nie</span>';
			}
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM student 
		WHERE student_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Student usunięty!</div>';
	}
}


if($_POST['action'] == 'sendMail') {
	
    $mail = new PHPMailer(true);
	// $mail->SMTPDebug = 4;
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->SMTPAuth = true;
    $mail->Username = 'testprojekt636@gmail.com';
    $mail->Password = 'hvgbwkzlnizlqmtu';
    $mail->SMTPSecure = 'ssl';
    $mail->From = 'testprojekt636@gmail.com';
    $mail->FromName = 'E-Praca';
    $mail->addAddress('testprojekt636@gmail.com');
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    $mail->Subject = 'Kod weryfikacjny do portalu E-Praca';
    $message_body = '<p>TEST sendMail</p><p>Z poważaniem</p><p>E-Praca</p>';
    $mail->Body = $message_body;
    if($mail->Send()) {
        $success = '<div class="alert alert-success">Na ten adres został wysłany kod do weryfikacji.</div>';
    } else {
        $error = '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
		$error = '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
    }

}

	

?>