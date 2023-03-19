<?php

//praca_akcja.php.php

include('../class/handler_admin.php');

$object = new Handler;



if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('pd.numer_pracy', 'student.student_imie', 'promotor.promotor_nazwa', 'pd.status');
			$main_query = "
			SELECT * FROM pd  
			INNER JOIN promotor 
			ON promotor.promotor_id = pd.promotor_id 
			INNER JOIN student 
			ON student.student_id = pd.student_id 
			";

			$search_query = '';

			
				$search_query .= 'WHERE ';
			

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'pd.numer_pracy LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR student.student_imie LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR student.student_nazwisko LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR promotor.promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR pd.status LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			
		}
		else
		{
			$order_column = array('pd.numer_pracy', 'student.student_imie', 'pd.status');

			$main_query = "
			SELECT * FROM pd 
			INNER JOIN student 
			ON student.student_id = pd.student_id 
			";

			$search_query = '
			WHERE pd.promotor_id = "'.$_SESSION["admin_id"].'" 
			';

			

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'AND (pd.numer_pracy LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR student.student_imie LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR student.student_nazwisko LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR pd.temat_pracy LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR pd.status LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = "ORDER BY ".$order_column[$_POST['order']['0']['column']]." ".$_POST['order']['0']['dir']." ";
		}
		else
		{
			$order_query = 'ORDER BY pd.pd_id DESC ';
		}

		$limit_query = "";

		if($_POST["length"] != -1)
		{
			$limit_query .= "LIMIT " . $_POST['start'] . ", " . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["numer_pracy"];

			$sub_array[] = $row["student_imie"] . ' ' . $row["student_nazwisko"];

			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = $row["promotor_nazwa"];
			}
			$sub_array[] = $row["temat_pracy"];


			$status = '';

			if($row["status"] == 'Zakończono')
			{
				$status = '<span class="badge badge-success">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Oczekiwanie na akceptacje')
			{
				$status = '<span class="badge badge-primary">' . $row["status"] . '</span>';
			}

			if($row["status"] == '0 %')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
			}
			if($row["status"] == '20 %')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
			}

			if($row["status"] == '40 %')
			{
				$status = '<span class="badge badge-warning">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Odrzucono')
			{
				$status = '<span class="badge badge-warning">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Anulowano')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
			}
			if($row["status"] == '60 %')
			{
				$status = '<span class="badge badge-warning">' . $row["status"] . '</span>';
			}
			if($row["status"] == '80 %')
			{
				$status = '<span class="badge badge-primary">' . $row["status"] . '</span>';
			}
			if($row["status"] == '100 %')
			{
				$status = '<span class="badge badge-success">' . $row["status"] . '</span>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["pd_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["pd_id"].'" title="Podgląd prośby"><i class="fas fa-eye"></i></button>
			<button type="button" name="reject_button" class="btn btn-danger btn-circle btn-sm reject_button" data-id="'.$row["pd_id"].'" data-action="reject-pd" title="Odrzuć prośbę"><i class="fas fa-thumbs-down"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["pd_id"].'" data-action="delete-pd" title="Usuń prośbę"><i class="fas fa-trash"></i></button>
			<button type="button" name="email_button" class="btn btn-primary btn-circle btn-sm email_button" data-id="'.$row["pd_id"].'" data-action="notify-pd" title="Powiadom za pomocą email"><i class="fa-solid fa-envelope"></i></button>
			</div>
			';
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
		SELECT * FROM pd 
		WHERE pd_id = '".$_POST["pd_id"]."'
		";

		$praca_data = $object->get_result();

		foreach($praca_data as $praca_row)
		{

			$object->query = "
			SELECT * FROM student 
			WHERE student_id = '".$praca_row["student_id"]."'
			";

			$student_data = $object->get_result();

			$object->query = "
			SELECT * FROM promotor 
			WHERE promotor_id = '".$praca_row["promotor_id"]."'
			";

			$promotor_schedule_data = $object->get_result();

			$html = '
			<h4 class="text-center">Dane Studenta</h4>
			<table class="table">
			';

			foreach($student_data as $student_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Imię i Nazwisko</th>
					<td>'.$student_row["student_imie"].' '.$student_row["student_nazwisko"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Nr Indeksu</th>
					<td>'.$student_row["student_nr_indeksu"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Wydział</th>
					<td>'.$student_row["student_wydzial"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Grupa</th>
					<td>'.$student_row["student_gr_dziek"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Podgrupa</th>
					<td>'.$student_row["student_podgrupa"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Nr. Telefonu</th>
					<td>'.$student_row["student_telefon"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">E-mail</th>
					<td>'.$student_row["student_adres_email"].'</td>
				</tr>
				';
			}

			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Dane Pracy</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Nr. Pracy</th>
					<td>'.$praca_row["numer_pracy"].'</td>
				</tr>
			';
			foreach($promotor_schedule_data as $promotor_schedule_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Promotor</th>
					<td>'.$promotor_schedule_row["promotor_nazwa"].'</td>
				</tr>
				
				
				';
			}

			$html .= '
				
				<tr>
					<th width="40%" class="text-right">Temat Pracy</th>
					<td>'.$praca_row["temat_pracy"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Cel i Zakres</th>
					<td>'.$praca_row["cel_zakres_pracy"].'</td>
				</tr>
			';

			

			if($praca_row["status"] != 0)
			{
				if($_SESSION['type'] == 'Promotor')
				{
					if($praca_row['student_akceptacja'] == 'Tak')
					{
						echo '<script>document.getElementById("action").value="change_praca_status"</script>';
							$html .= '
								<tr>
									<th width="40%" class="text-right">Zmień postęp pracy</th>
									<td>
									<select name="postep" id="postep">
										<option class="badge badge-danger  id="0">0 %</option>
										<option class="badge badge-danger  id="20">20 %</option>
										<option class="badge badge-warning  id="40">40 %</option>
										<option class="badge badge-warning  id="60">60 %</option>
										<option class="badge badge-primary  id="80">80 %</option>
										<option class="badge badge-success  id="100">100 %</option>
									</select>
									</td>
								</tr>
								<tr>
									<th width="40%" class="text-right">Komentarz Promotora</th>
									<td>
										<textarea name="komentarz_promotora" id="komentarz_promotora" class="form-control" rows="8" >'.$praca_row["komentarz_promotora"].'</textarea>
									</td>
								</tr
							';
							if(isset($_POST["postep"])) 
							{
						
						$praca_row["status"] = $_POST["postep"];
						}
					}
				}
	

				if($_SESSION['type'] == 'Promotor')
				{
					if($praca_row["student_akceptacja"] == 'Nie')
					{
						if($praca_row["status"] == 'Oczekiwanie na akceptacje')
						{
							 echo '<script>document.getElementById("action").value="akceptuj_prace"</script>';
							$html .= '
								<tr>
									<th width="40%" class="text-right">Akceptuj Dyplomanta?</th>
									<td>
										<select name="student_akceptacja" id="student_akceptacja" class="form-control" required>
											<option value="">Select</option>
											<option value="Nie">Nie</option>
											<option value="Tak">Tak</option>
										</select>
									</td>
								<tr>
									<th width="40%" class="text-right">Komentarz Promotora</th>
									<td>
										<textarea name="komentarz_promotora" id="komentarz_promotora" class="form-control" rows="8" >'.$praca_row["komentarz_promotora"].'</textarea>
									</td>
								</tr>
							';
						}

					}
				}
			
			}

			$html .= '
			</table>
			';
		}

		echo $html;
	}


	if($_POST["action"] == 'fetch_edit')
	{
		$object->query = "
		SELECT * FROM pd 
		WHERE pd_id = '".$_POST["pd_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['temat_pracy'] = $row['temat_pracy'];
			$data['temat_pracy_ang'] = $row['temat_pracy_ang'];
			$data['cel_zakres_pracy'] = $row['cel_zakres_pracy'];

		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
{
    $error = '';
    $success = '';
    $data = array(':pd_id' => $_POST['hidden_id']);

    $object->query = "SELECT * FROM pd WHERE pd_id = :pd_id";
    $object->execute($data);

    if($error == '')
    {
        $data = array(
            ':temat_pracy' 				=> $object->clean_input($_POST["temat_pracy"]),
            ':temat_pracy_ang' 			=> $object->clean_input($_POST["temat_pracy_ang"]),
            ':cel_zakres_pracy' 		=> $object->clean_input($_POST["cel_zakres_pracy"]),
            ':rezencent' 				=> $object->clean_input($_POST["recenzent"])
        );

        $object->query = "UPDATE pd SET temat_pracy = :temat_pracy, temat_pracy_ang = :temat_pracy_ang, cel_zakres_pracy = :cel_zakres_pracy, recenzent = :rezencent WHERE pd_id = '".$_POST['hidden_id']."'";

        $object->execute($data);

        $success = '<div class="alert alert-success">Zmodyfikowano dane pracy.</div>';
        
        if($object) {
            $object->query = "SELECT * FROM student JOIN pd ON student.student_id = pd.student_id WHERE pd.pd_id = '".$_POST['hidden_id']."'";
            $student_email = $object->get_result();
            $student_email_row = $student_email->fetch();
            $student_email = $student_email_row['student_adres_email'];

            $repplyTo = "testprojekt636@gmail.com";
            $message = "Dokonano zmian w pracy dyplomowej, prosimy o zapoznanie się z aktualnymi danymi.";
            $recipient = $student_email;
            $subject = 'Aktualizacja pracy dyplomowej';
            $message_body = '<p>Szanowny Dyplomancie,</p>
            <p>Dokonano zmian w pracy dyplomowej, prosimy o zapoznanie się z aktualnymi danymi.</p>
            <p>  </p>
            <p>  </p>
            <p>Z poważaniem</p>
            <p><b>E-Praca.pl</b></p>';

            $object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
            
            if($object) {
                $success .= '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
            } else {
                $error = '<div class="alert alert-danger">Nie udało się wysłać powiadomienia E-mail</div>';
            }
        } else {
            $error .= '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
        }
    }			
    
    $output = array(
        'error' => $error,
        'success' => $success
    );

    echo json_encode($output);
}

	if($_POST['action'] == 'akceptuj_prace')
	{
		if($_SESSION['type'] == 'Promotor') {

			$data = array(
				':student_akceptacja'			=>	$_POST["student_akceptacja"],
				':status'						=>	'0 %',
				':komentarz_promotora'			=>	$_POST['komentarz_promotora'],
				':pd_id'						=>	$_POST['hidden_praca_id'],
			);

			$object->query = "
			UPDATE pd 
			SET student_akceptacja = :student_akceptacja,
			status = :status,   
			komentarz_promotora = :komentarz_promotora
			WHERE pd_id = :pd_id
			";
			$object->execute($data);
			
			$result = $object->statement_result();
			if(!empty($result))
			{
				echo 'Dyplomant został zatwierdzony';
			}

			if(empty($_POST['komentarz_promotora'])) {
				 $message = 'Twoj promotor nie zostawił żadnego komentarza';
			} else {
				$message ='Komentarz promotora: ' . $_POST['komentarz_promotora'];
			}	


			$object->query = "
			SELECT * FROM promotor
			WHERE promotor_id = '".$_SESSION['admin_id']."' 
			";

			$promotor_nazwa = $object->get_result();
			$promotor_nazwa_row = $promotor_nazwa->fetch();
			$promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

			$promotor_adres_email = $object->get_result();
			$promotor_email_row = $promotor_adres_email->fetch();
			$promotor_adres_email = $promotor_email_row['promotor_adres_email'];

			$object->query = 
			"SELECT student_adres_email FROM student
			JOIN pd ON student.student_id = pd.student_id
			WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

			$student_email = $object->get_result();
			$student_email_row = $student_email->fetch();
			$student_email = $student_email_row['student_adres_email'];

			$repplyTo = $promotor_adres_email;
			$message = $_POST['komentarz_promotora'];
			$recipient = $student_email;
			$subject = 'Twoja praca została zaakceptowana na portalu E-Praca.pl';
			$message_body = '<p>Twoja praca została zaakceptowana przez: '.$promotor_nazwa.'</p>
			<p>'.$message.'</p>
			<p>  </p>
			<p>  </p>
			<p>Wiadomość wygenerowana automatycznie, prosimy na nią nie odpowiadać.</p>
			<p>Z poważaniem</p>
			<p><b>E-Praca.pl</b></p>';


			$object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
			

			if($object) {
				echo '<div class="alert alert-success">Pomyślnie zaakceptowano pracę.</div>';
				echo '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
			} else {
				echo '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}
		}
}

	if($_POST['action'] == 'change_praca_status')
	{
		if($_SESSION['type'] == 'Promotor') {

			$data = array(
				':status'						=>	$_POST["postep"],
				':komentarz_promotora'			=>	$_POST['komentarz_promotora'],
				':pd_id'						=>	$_POST['hidden_praca_id'],
			);

			$object->query = "
			UPDATE pd 
			SET status = :status,  
			komentarz_promotora = :komentarz_promotora
			WHERE pd_id = :pd_id
			";
			$object->execute($data);
			
			echo '<div class="alert alert-success">Status wizyty zmieniony na '.$_POST["postep"].'</div>';

			if(empty($_POST['komentarz_promotora'])) {
				 $message = 'Twoj promotor nie zostawił żadnego komentarza';
			} else {
				$message ='Komentarz promotora: ' . $_POST['komentarz_promotora'];
			}	

			$object->query = "
			SELECT * FROM promotor
			WHERE promotor_id = '".$_SESSION['admin_id']."' 
			";

			$promotor_nazwa = $object->get_result();
			$promotor_nazwa_row = $promotor_nazwa->fetch();
			$promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

			$promotor_adres_email = $object->get_result();
			$promotor_email_row = $promotor_adres_email->fetch();
			$promotor_adres_email = $promotor_email_row['promotor_adres_email'];

			$object->query = 
			"SELECT student_adres_email FROM student
			JOIN pd ON student.student_id = pd.student_id
			WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

			$student_email = $object->get_result();
			$student_email_row = $student_email->fetch();
			$student_email = $student_email_row['student_adres_email'];

			$repplyTo = $promotor_adres_email;
			$message = $_POST['komentarz_promotora'];
			$recipient = $student_email;
			$subject = 'Zmiana statusu pracy na portalu E-Praca.pl';
			$message_body = '<p>Twoja praca właśnie zmieniła status na: '.$_POST["postep"].' ukończenia.</p>
			<p>'.$message.'</p>
			<p>  </p>
			<p>  </p>
			<p>Wiadomość wygenerowana automatycznie, prosimy na nią nie odpowiadać.</p>
			<p>Z poważaniem</p>
			<p><b>E-Praca.pl</b></p>';


			$object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
			

			if($object) {
				echo '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
			} else {
				echo '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}	
		
}

		
	

	 if($_POST['action'] == 'change_praca_status')
	{
		if($_SESSION['type'] == 'Promotor') {
					if(isset($_POST['student_dokumenty'])) {
						if($_POST['student_dokumenty'] == 'Tak') {

			$data = array(
				':status'						=>	'40 %',
				':student_akceptacja'			=>	'Tak',
				':student_dokumenty'			=>	'Tak',
				':pd_id'						=>	$_POST['hidden_praca_id']
			);

			$object->query = "
			UPDATE pd 
			SET status = :status,
			student_dokumenty = :student_dokumenty, 
			student_akceptacja = :student_akceptacja
			WHERE pd_id = :pd_id
			";
			$object->execute($data);
			
			echo '<div class="alert alert-success">Status wizyty zmieniony na 40 %</div>';
			}
		}
	}
}
		
	
	// Update the status to "Odrzucono" when the promotor selects "Nie" for student_akceptacja
if($_SESSION['type'] == 'Promotor' && isset($_POST['student_akceptacja']) && $_POST['student_akceptacja'] == 'Nie')
{
	$data = array(
		':status'						=>	'Odrzucono',
		':komentarz_promotora'			=>	$_POST['komentarz_promotora'],
		':student_akceptacja'			=>	'Nie',
		':pd_id'					=>	$_POST['hidden_praca_id']
	);

	$object->query = "
	UPDATE pd 
	SET status = :status, 
	komentarz_promotora = :komentarz_promotora,
	student_akceptacja = :student_akceptacja
	WHERE pd_id = :pd_id
	";

	$object->execute($data);

		// Refresh the pd datatable
		echo '<script>dataTable.ajax.reload();</script>';
		echo '<div class="alert alert-success">Status wizyty zmieniony na "Odrzucono"</div>';
	}
}



if($_POST["action"] == 'notify-pd')
{
    $output = '';
    if($_SESSION['type'] == 'Promotor') {

        $data = array(
            ':wiadomosc_promotora' 			=> $_POST['wiadomosc_promotora'],
            ':pd_id'						=>	$_POST['hidden_praca_id'],
        );

        $object->query = "SELECT * FROM promotor WHERE promotor_id = '".$_SESSION['admin_id']."'";
        $promotor_nazwa = $object->get_result();
        $promotor_nazwa_row = $promotor_nazwa->fetch();
        $promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

        $promotor_adres_email = $object->get_result();
        $promotor_email_row = $promotor_adres_email->fetch();
        $promotor_adres_email = $promotor_email_row['promotor_adres_email'];

        $object->query = "SELECT student_adres_email FROM student
                           JOIN pd ON student.student_id = pd.student_id
                           WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

        $student_email = $object->get_result();
        $student_email_row = $student_email->fetch();
        $student_email = $student_email_row['student_adres_email'];

        $repplyTo = $promotor_adres_email;
        $message = $_POST['wiadomosc_promotora'];
        $recipient = $student_email;
        $subject = 'Powiadomienie od promotora!';
        $message_body = '<p>Twoj promotor '.$promotor_nazwa.'  wysłał do ciebie wiadomość:</p>
        <p>'.$message.'</p>
        <p>  </p>
        <p>  </p>
        <p>Odpowiedź na tą wiadomość zostanie wysłana na e-mail: '.$promotor_adres_email.'</p>
        <p>Z poważaniem</p>
        <p><b>E-Praca.pl</b></p>';

        $object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);

        if($object) {
            $output .= '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
        } else {
            $output .= '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
        }
    }
    echo $output;
}

	

	if($_POST["action"] == 'delete-pd')
		if($_SESSION['type'] == 'Promotor') {
		$data = array(
				':wiadomosc_promotora' 			=> $_POST['wiadomosc_promotora'],
				':pd_id'						=>	$_POST['hidden_praca_id'],
			);

		{
			$object->query = "
			DELETE FROM pd 
			WHERE pd_id = '{$_POST['hidden_praca_id']}'
			";

			$object->execute();
			$object->query = "
			SELECT * FROM promotor
			WHERE promotor_id = '".$_SESSION['admin_id']."' 
			";

			$promotor_nazwa = $object->get_result();
			$promotor_nazwa_row = $promotor_nazwa->fetch();
			$promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

			$promotor_adres_email = $object->get_result();
			$promotor_email_row = $promotor_adres_email->fetch();
			$promotor_adres_email = $promotor_email_row['promotor_adres_email'];

			$object->query = 
			"SELECT student_adres_email FROM student
			JOIN pd ON student.student_id = pd.student_id
			WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

			$student_email = $object->get_result();
			$student_email_row = $student_email->fetch();
			$student_email = $student_email_row['student_adres_email'];

			$repplyTo = $promotor_adres_email;
			$message = $_POST['wiadomosc_promotora'];
			$recipient = $student_email;
			$subject = 'Twoja praca została została usunięta z portalu E-Praca.pl';
			$message_body = '<p>Twoja praca została usunięta przez: '.$promotor_nazwa.'</p>
			<p>Powód:'.$message.'</p>
			<p>  </p>
			<p>  </p>
			<p>Wiadomość wygenerowana automatycznie, prosimy na nią nie odpowiadać.</p>
			<p>Z poważaniem</p>
			<p><b>E-Praca.pl</b></p>';


			$object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
			

				if($object) {
				echo '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
			} else {
				echo '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}	
		}
}

	if($_POST["action"] == 'reject-pd')
		if($_SESSION['type'] == 'Promotor') {
		$data = array(
				':wiadomosc_promotora' 			=> $_POST['wiadomosc_promotora'],
				':pd_id'						=>	$_POST['hidden_praca_id'],
			);

		{
			
			$object->query = "
			UPDATE pd
			SET status = 'odrzucono'
			WHERE pd_id = '{$_POST['hidden_praca_id']}'
			";

    		$object->execute();

			$object->query = "
			SELECT * FROM promotor
			WHERE promotor_id = '".$_SESSION['admin_id']."' 
			";

			$promotor_nazwa = $object->get_result();
			$promotor_nazwa_row = $promotor_nazwa->fetch();
			$promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

			$promotor_adres_email = $object->get_result();
			$promotor_email_row = $promotor_adres_email->fetch();
			$promotor_adres_email = $promotor_email_row['promotor_adres_email'];

			$object->query = 
			"SELECT student_adres_email FROM student
			JOIN pd ON student.student_id = pd.student_id
			WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

			$student_email = $object->get_result();
			$student_email_row = $student_email->fetch();
			$student_email = $student_email_row['student_adres_email'];

			$repplyTo = $promotor_adres_email;
			$message = $_POST['wiadomosc_promotora'];
			$recipient = $student_email;
			$subject = 'Twoja praca została została odrzucona z portalu E-Praca.pl';
			$message_body = '<p>Twoja praca została odrzucona przez: '.$promotor_nazwa.'</p>
			<p>Powód:'.$message.'</p>
			<p>  </p>
			<p>  </p>
			<p>Wiadomość wygenerowana automatycznie, prosimy na nią nie odpowiadać.</p>
			<p>Z poważaniem</p>
			<p><b>E-Praca.pl</b></p>';


			$object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
			

				if($object) {
				echo '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
			} else {
				echo '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}	
		}
	}

	if($_POST["action"] == 'reject-pd')
		if($_SESSION['type'] == 'Promotor') {
		$data = array(
				':wiadomosc_promotora' 			=> $_POST['wiadomosc_promotora'],
				':pd_id'						=>	$_POST['hidden_praca_id'],
			);

		{
			
			$object->query = "
			UPDATE pd
			SET status = 'odrzucono'
			WHERE pd_id = '{$_POST['hidden_praca_id']}'
			";

    		$object->execute();

			$object->query = "
			SELECT * FROM promotor
			WHERE promotor_id = '".$_SESSION['admin_id']."' 
			";

			$promotor_nazwa = $object->get_result();
			$promotor_nazwa_row = $promotor_nazwa->fetch();
			$promotor_nazwa = $promotor_nazwa_row['promotor_nazwa'];

			$promotor_adres_email = $object->get_result();
			$promotor_email_row = $promotor_adres_email->fetch();
			$promotor_adres_email = $promotor_email_row['promotor_adres_email'];

			$object->query = 
			"SELECT student_adres_email FROM student
			JOIN pd ON student.student_id = pd.student_id
			WHERE pd.pd_id = '{$_POST['hidden_praca_id']}'";

			$student_email = $object->get_result();
			$student_email_row = $student_email->fetch();
			$student_email = $student_email_row['student_adres_email'];

			$repplyTo = $promotor_adres_email;
			$message = $_POST['wiadomosc_promotora'];
			$recipient = $student_email;
			$subject = 'Twoja praca została została odrzucona z portalu E-Praca.pl';
			$message_body = '<p>Twoja praca została odrzucona przez: '.$promotor_nazwa.'</p>
			<p>Powód:'.$message.'</p>
			<p>  </p>
			<p>  </p>
			<p>Wiadomość wygenerowana automatycznie, prosimy na nią nie odpowiadać.</p>
			<p>Z poważaniem</p>
			<p><b>E-Praca.pl</b></p>';


			$object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
			

				if($object) {
				echo '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
			} else {
				echo '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}	
		}
	}
}
?>