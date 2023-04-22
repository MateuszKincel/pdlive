<?php
			
//akcja.php

include('class/handler.php');
require 'vendor/autoload.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';

	use Dompdf\Dompdf; 
	use PhpOffice\PhpWord\Shared\Html as HtmlHelper;
	use PhpOffice\PhpWord\IOFactory;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
$object = new Handler;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'check_login')
	{
		if(isset($_SESSION['student_id']))
		{
			echo 'panel.php';
		}
		else
		{
			echo 'login.php';
		}
	}

	if($_POST['action'] == 'student_register')
	{
		$error = '';

		$success = '';

		$data = array(
			':student_adres_email'	=>	$_POST["student_adres_email"]
		);

		$object->query = "
		SELECT * FROM student 
		WHERE student_adres_email = :student_adres_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Adres Email już istnieje!</div>';
		}
		else
		{
			$student_kod_weryfikacji = md5(uniqid());
			$data = array(
				':student_adres_email'					=>	$object->clean_input($_POST["student_adres_email"]),
				':student_haslo'						=>	$_POST["student_haslo"],
				':student_imie'							=>	$object->clean_input($_POST["student_imie"]),
				':student_nazwisko'						=>	$object->clean_input($_POST["student_nazwisko"]),
				':student_nr_indeksu'					=>	$object->clean_input($_POST["student_nr_indeksu"]),
				':student_gr_dziek'						=>	$object->clean_input($_POST["student_gr_dziek"]),
				':student_podgrupa'						=>	$object->clean_input($_POST["student_podgrupa"]),
				':student_wydzial'						=>	$object->clean_input($_POST["student_wydzial"]),
				':student_data_urodzenia'				=>	$object->clean_input($_POST["student_data_urodzenia"]),
				':student_semestr'						=>	$object->clean_input($_POST["student_semestr"]),
				':student_adres'						=>	$object->clean_input($_POST["student_adres"]),
				':student_telefon'						=>	$object->clean_input($_POST["student_telefon"]),
				':student_dodany'						=>	$object->now,
				':student_kod_weryfikacji'				=>	$student_kod_weryfikacji,
				':weryfikacja_email'					=>	'Nie'
			);

			$object->query = "
			INSERT INTO student 
			(student_adres_email, student_haslo, student_imie, student_nazwisko,student_nr_indeksu,student_gr_dziek,student_podgrupa,student_wydzial, student_data_urodzenia, student_semestr, student_adres, student_telefon, student_dodany, student_kod_weryfikacji, weryfikacja_email) 
			VALUES (:student_adres_email, :student_haslo, :student_imie, :student_nazwisko, :student_nr_indeksu, :student_gr_dziek, :student_podgrupa, :student_wydzial, :student_data_urodzenia, :student_semestr, :student_adres, :student_telefon, :student_dodany, :student_kod_weryfikacji, :weryfikacja_email)
			";

			$object->execute($data);

			
		
			$mail = new PHPMailer(true);
			$mail->SMTPDebug = 2;
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
			$mail->AddAddress($_POST["student_adres_email"]);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Kod weryfikacjny do portalu E-Praca';

			
			$message_body = '
			<p>Aby zweryfikować email kliknij tu <a href="'.$object->base_url.'verify.php?code='.$student_kod_weryfikacji.'"><b>link</b></a>.</p>
			<p>Z poważaniem</p>
			<p>E-Praca</p>
			';
			$mail->Body = $message_body;

			if($mail->Send())
			{
				$success = '<div class="alert alert-success">Na ten adres został wysłany kod do weryfikacji.</div>';
			}
			else
			{
				$error = '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
				$error = '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if($_POST['action'] == 'student_login')
	{
		$error = '';

		$data = array(
			':student_adres_email'	=>	$_POST["student_adres_email"]
		);

		$object->query = "
		SELECT * FROM student 
		WHERE student_adres_email = :student_adres_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{

			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["weryfikacja_email"] == 'Tak')
				{
					if($row["student_haslo"] == $_POST["student_haslo"])
					{
						$_SESSION['student_id'] = $row['student_id'];
						$_SESSION['student_name'] = $row['student_imie'] . ' ' . $row['student_nazwisko'] . ' ' . $row['student_nr_indeksu'];
						
					}
					else
					{
						$error = '<div class="alert alert-danger">Błędne hasło!</div>';
					}
				}
				else
				{
					$error = '<div class="alert alert-danger">Najpierw Zweryfikuj Adres Email</div>';
				}
			}
		}
		else
		{
			$error = '<div class="alert alert-danger">Błędny Adres Email</div>';
		}

		$output = array(
			'error'		=>	$error
		);

		echo json_encode($output);

	}

	function getStudentName($object, $student_id) {
    $data = array(
        ':student_id' => $student_id
    );

    $object->query = "
    SELECT student_imie, student_nazwisko, student_nr_indeksu FROM student 
    WHERE student_id = :student_id
    ";

    $object->execute($data);

    if ($object->row_count() > 0) {
        $result = $object->statement_result();
        foreach ($result as $row) {
            return $row['student_imie'] . ' ' . $row['student_nazwisko'] . ' ' . $row['student_nr_indeksu'];
        }
    } else {
        return '';
    }
}


	if($_POST['action'] == 'fetch_schedule')
	{
		$output = array();

		$order_column = array('promotor.promotor_nazwa','promotor.promotor_specjalizacja', 'promotor.promotor_wydzial', 'promotor.promotor_telefon', 'promotor.promotor_adres_email', 'promotor_harmonogram.promotor_status');
		
		$main_query = "
		SELECT  DISTINCT * FROM promotor_harmonogram
		INNER JOIN promotor
		ON promotor.promotor_id=promotor_harmonogram.promotor_id
		WHERE promotor.promotor_status = 'Aktywny' AND promotor_harmonogram.promotor_harmonogram_status = 'Aktywny'
		";

		$search_query = '
		
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( promotor.promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor.promotor_wydzial LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor.promotor_telefon LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor.promotor_adres_email LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY promotor.promotor_nazwa ASC  ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			
			$sub_array[] = $row["promotor_nazwa"];

			$sub_array[] = $row["promotor_wydzial"];

			$sub_array[] = $row["promotor_specjalizacja"];

			$sub_array[] = $row["promotor_telefon"];

			$sub_array[] = $row["promotor_adres_email"];

			$sub_array[] = $row["promotor_harmonogram_status"];


			$sub_array[] = '
			<div align="center">
			<button type="button" name="get_praca" class="btn btn-primary btn-sm get_praca" data-promotor_id="'.$row["promotor_id"].'" >Rezerwuj</button
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

	if($_POST['action'] == 'edit_profile')
	{
		$data = array(
			':student_adres_email'		=>	$_POST["student_adres_email"],
			':student_haslo'			=>	$_POST["student_haslo"],
			':student_imie'				=>	$_POST["student_imie"],
			':student_nazwisko'			=>	$_POST["student_nazwisko"],
			':student_gr_dziek'			=>	$_POST["student_gr_dziek"],
			':student_podgrupa'			=>	$_POST["student_podgrupa"],
			':student_data_urodzenia'	=>	$_POST["student_data_urodzenia"],
			':student_nr_indeksu'		=>	$_POST["student_nr_indeksu"],
			':student_adres'			=>	$_POST["student_adres"],
			':student_telefon'			=>	$_POST["student_telefon"]

		);

		$object->query = "
		UPDATE student  
		SET student_haslo = :student_haslo, 
		student_imie = :student_imie, 
		student_adres_email = :student_adres_email, 
		student_nazwisko = :student_nazwisko, 
		student_gr_dziek = :student_gr_dziek,
		student_podgrupa = :student_podgrupa, 
		student_data_urodzenia = :student_data_urodzenia, 
		student_nr_indeksu = :student_nr_indeksu, 
		student_adres = :student_adres, 
		student_telefon = :student_telefon
		WHERE student_id = '".$_SESSION['student_id']."'
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Dane Profilu Zaktualizowane</div>';

		echo 'done';
	}

	if($_POST['action'] == 'pd_rezerw') 
	{
		//Fetch student data
		$object->query = "
		SELECT * FROM student 
		WHERE student_id = '".$_SESSION["student_id"]."'
		";

		$student_data = $object->get_result();
		

		//Fetch topic data
		$object->query = "
		SELECT temat, temat_grupa, temat_semestr, cel_zakres, promotor_id FROM temat 
		";

		$temat_data = $object->get_result();

		
		
		$promotor_id = $_POST['promotor_id'];
		

		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor.promotor_id = '$promotor_id'
		AND promotor.promotor_status = 'Aktywny'
		";

		$promotor_data = $object->get_result();


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
		<h4 class="text-center">Dane Promotora</h4>
		<table class="table">
		';
		foreach($promotor_data as $promotor_pd_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">Promotor</th>
				<td>'.$promotor_pd_row["promotor_nazwa"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Wydział</th>
				<td>'.$promotor_pd_row["promotor_wydzial"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Specjalizacja</th>
				<td>'.$promotor_pd_row["promotor_specjalizacja"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Nr. Telefonu</th>
				<td>'.$promotor_pd_row["promotor_telefon"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">E-mail</th>
				<td>'.$promotor_pd_row["promotor_adres_email"].'</td>
			</tr>
			';
			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Dane Pracy</h4>
			<table class="table">
			';
		}
			
		$html .= '
		</table>';
		echo $html;
	}


	if($_POST['action'] == 'temat_rezerw') 
	{
		//Fetch student data
		$object->query = "
		SELECT * FROM student 
		WHERE student_id = '".$_SESSION["student_id"]."'
		";

		$student_data = $object->get_result();
		

		//Fetch topic data
		$object->query = "
		SELECT temat, temat_ang, temat_grupa, temat_semestr, cel_zakres, promotor_id FROM temat 
		WHERE temat_id = '".$_POST["temat_id"]."'
		AND promotor_id = '".$_POST["promotor_id"]."'
		";

		$temat_data = $object->get_result();

		
		
		$promotor_id = $_POST['promotor_id'];
		

		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor.promotor_id = '$promotor_id'
		AND promotor.promotor_status = 'Aktywny'
		";

		$promotor_data = $object->get_result();

		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor.promotor_id = '$promotor_id'
		AND promotor.promotor_status = 'Aktywny'
		";

		$promotor_data = $object->get_result();


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
		<h4 class="text-center">Dane Promotora</h4>
		<table class="table">
		';
		foreach($promotor_data as $promotor_pd_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">Promotor</th>
				<td>'.$promotor_pd_row["promotor_nazwa"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Wydział</th>
				<td>'.$promotor_pd_row["promotor_wydzial"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Specjalizacja</th>
				<td>'.$promotor_pd_row["promotor_specjalizacja"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Nr. Telefonu</th>
				<td>'.$promotor_pd_row["promotor_telefon"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">E-mail</th>
				<td>'.$promotor_pd_row["promotor_adres_email"].'</td>
			</tr>
			';
			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Dane Pracy</h4>
			<table class="table">
			';
			foreach($temat_data as $temat_row)
			{
			$html .= '	
			<tr>
				<th width="40%" class="text-right">Temat</th>
				<td>'.$temat_row["temat"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Temat ang.</th>
				<td>'.$temat_row["temat_ang"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Cel i zakres</th>
				<td>'.$temat_row["cel_zakres"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Semestr</th>
				<td>'.$temat_row["temat_semestr"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Grupa</th>
				<td>'.$temat_row["temat_grupa"].'</td>
			</tr>
			';
			}
		}
			
		$html .= '
		</table>';
		echo $html;
	}

	if($_POST['action'] == 'rezerwuj_temat')
	{
		$object->query = "SELECT temat FROM temat WHERE temat_id = :temat_id";
		$object->execute(array(':temat_id' => $_POST['temat_id']));
		$object->temat = $object->statement->fetchColumn();

		$object->query = "SELECT cel_zakres FROM temat WHERE temat_id = :temat_id";
		$object->execute(array(':temat_id' => $_POST['temat_id']));
		$object->cel_zakres = $object->statement->fetchColumn();

		$object->query = "SELECT temat_ang FROM temat WHERE temat_id = :temat_id";
		$object->execute(array(':temat_id' => $_POST['temat_id']));
		$object->temat_ang = $object->statement->fetchColumn();

		
		$error = '';
		$data = array(
			':student_id'			=>	$_SESSION['student_id'],
			':promotor_id'			=>	$_POST['promotor_id_ukryte'],
			
			
		);

		$object->query = "
		SELECT * FROM pd 
		WHERE student_id = :student_id 
		AND promotor_id = :promotor_id
		";
		
			$object->execute($data);
		

		if($object->row_count() >= 3 )
		{
			$error = '<div class="alert alert-danger">Zbyt wiele prób rezerwacji. </div>';
		}
		else
		{
			$object->query = "
			SELECT * FROM promotor 
			WHERE promotor_id = '".$_POST['promotor_id_ukryte']."'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(pd_id) AS total FROM pd 
			WHERE promotor_id = '".$_POST['promotor_id_ukryte']."' 
			";

			$praca_data = $object->get_result();
			
			$wszystkie_prace = 0;

			foreach($praca_data as $praca_row)
			{
				$wszystkie_prace = $praca_row["total"];
			}

			$status = '';

			$numer_pracy = $object->Generate_praca_no();

			{
				$status = '0 %';
			}
			
			$data = array(
			':promotor_id' 				=> $_POST['promotor_id_ukryte'],
			':student_id' 				=> $_SESSION['student_id'],
			':numer_pracy' 				=> $numer_pracy,
			':temat_pracy' 				=> $object->temat,
			':cel_zakres_pracy' 		=> $object->cel_zakres,
			':temat_ang' 				=> $object->temat_ang,
			':status' 					=> 'Oczekiwanie na akceptacje'
			);
			
				$object->query = "INSERT INTO pd (temat_pracy, temat_pracy_ang, numer_pracy, promotor_id, student_id, cel_zakres_pracy, status) VALUES (:temat_pracy, :temat_ang, :numer_pracy, :promotor_id, :student_id, :cel_zakres_pracy, :status)";
				$object->execute($data);

				//update temat_dostepny status to "Nie" in temat
				$object->query = "UPDATE temat SET temat_dostepny = 'Nie' WHERE temat_id = :temat_id";
				$object->execute(array(':temat_id' => $_POST['temat_id']));
			

			$_SESSION['praca_message'] = '<div class="alert alert-success">Twoja praca zmieniła status na <b>'.$status.'/a</b> Nr. Pracy <b>'.$numer_pracy.'</b></div>';
		}
		echo json_encode(['error' => $error]);
	}


	if($_POST['action'] == 'rezerwuj_prace')

	
	{
		$error = '';
		$data = array(
			':student_id'			=>	$_SESSION['student_id'],
			':promotor_id'			=>	$_POST['promotor_id_ukryte'],
			
		);

		$object->query = "
		SELECT * FROM pd 
		WHERE student_id = :student_id 
		AND promotor_id = :promotor_id
		";
		
			$object->execute($data);
		


		if($object->row_count() >= 3 )
		{
			$error = '<div class="alert alert-danger">Zbyt wiele prób rezerwacji. </div>';
		}
		else
		{
			$object->query = "
			SELECT * FROM promotor 
			WHERE promotor_id = '".$_POST['promotor_id_ukryte']."'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(pd_id) AS total FROM pd 
			WHERE promotor_id = '".$_POST['promotor_id_ukryte']."' 
			";

			$praca_data = $object->get_result();
			
			$wszystkie_prace = 0;

			foreach($praca_data as $praca_row)
			{
				$wszystkie_prace = $praca_row["total"];
			}


			$status = '';

			$numer_pracy = $object->Generate_praca_no();


			if($data = array(
			':student_id'			=>	$_SESSION['student_id'],
			':promotor_id'			=>	$_POST['promotor_id_ukryte']
			
				)
			)

			{
				$status = 'Oczekiwanie na akceptacje';
			}
			
			$data = array(
				':promotor_id'					=>	$_POST['promotor_id_ukryte'],
				':student_id'					=>	$_SESSION['student_id'],
				':numer_pracy'					=>	$numer_pracy,
				':temat_pracy'					=>	$_POST['temat_pracy'],
				':temat_pracy_ang'					=>	$_POST['temat_pracy_ang'],
				':cel_zakres_pracy'				=>	$_POST['cel_zakres_pracy'],	

				':status'						=>	'Oczekiwanie na akceptacje'
			);
			if($object->check_temat_pracy($_POST['temat_pracy'])){
				$object->query = "INSERT INTO pd (numer_pracy, promotor_id, student_id, temat_pracy,temat_pracy_ang, cel_zakres_pracy, status) VALUES (:numer_pracy, :promotor_id, :student_id,  :temat_pracy,:temat_pracy_ang, :cel_zakres_pracy, :status)";
				$object->execute($data);

				//update temat_dostepny status to "Nie" in temat
				$object->query = "UPDATE temat SET temat_dostepny = 'Nie' WHERE temat = :temat_pracy";
				$data = array(':temat_pracy' => $_POST['temat_pracy']);
				$object->execute($data);
			} else {
				// Display error message or redirect user to an error page
				$error = '<div class="alert alert-danger">Temat pracy jest zajęty</div>';
			}

			$_SESSION['praca_message'] = '<div class="alert alert-success">Twoja praca zmieniła status na <b>'.$status.'/a</b> Nr. Pracy <b>'.$numer_pracy.'</b></div>';
		}
		echo json_encode(['error' => $error]);
	}


	if($_POST['action'] == 'fetch_praca')
	{
		$output = array();

		$order_column = array('pd.temat_pracy','promotor.promotor_nazwa', 'promotor.promotor_adres_email', 'pd.status', 'promotor_harmonogram.promotor_harmonogram_data');
		
		$main_query = "
		SELECT * FROM pd  
		INNER JOIN promotor 
		ON promotor.promotor_id = pd.promotor_id
		INNER JOIN student 
		ON student.student_id = pd.student_id 
		INNER JOIN promotor_harmonogram
		ON promotor_harmonogram.promotor_id = pd.promotor_id
		
		";

		$search_query = '
		WHERE pd.student_id = '.$_SESSION['student_id'].'
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( pd.temat_pracy LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor.promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR pd.status LIKE "%'.$_POST["search"]["value"].'%") ';
			
		}


		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY pd.pd_id ASC  ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["temat_pracy"];

			$sub_array[] = $row["promotor_nazwa"];
		
			$sub_array[] = $row["promotor_adres_email"];

			$sub_array[] = $row["komentarz_promotora"];

			$sub_array[] = $row["promotor_harmonogram_data"];


			$status = '';

			if($row["status"] == 'Oczekiwanie na akceptacje')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
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
			if($row["status"] == 'Oczekiwanie na akceptacje') {

				$sub_array[] = '<button type="button"   name="cancel_praca" class="btn btn-danger btn-sm cancel_praca" data-id="'.$row["pd_id"].'" title="Anuluj"><i class="fas fa-times"></i></button> 
				<button type="button"   name="view_praca" class="btn btn-primary btn-sm view_praca" data-id="'.$row["pd_id"].'" title="Podgląd"><i class="fas fa-eye"></i></button>';
							
			} else if ($row["status"] == 'Odrzucono') {

			$sub_array[] = '<button type="button" disabled  name="cancel_praca" class="btn btn-danger btn-sm cancel_praca" data-id="'.$row["pd_id"].'"title="Anuluj"><i class="fas fa-times"></i></button> 
			<button type="button"  name="delete_praca" class="btn btn-danger btn-sm delete_praca" data-id="'.$row["pd_id"].'" title="Usuń"><i class="fas fa-trash"></i></button> 
			<button type="button"   name="view_praca" class="btn btn-primary btn-sm view_praca" data-id="'.$row["pd_id"].'" title="Podgląd"><i class="fas fa-eye"></i></button>';
				
			} else if($row["status"] == 'Anulowano')

			{
				$sub_array[] = '<button type="button" disabled  name="cancel_praca" class="btn btn-danger btn-sm cancel_praca" data-id="'.$row["pd_id"].'" title="Anuluj"><i class="fas fa-times"></i></button> 
				<button type="button"   name="delete_praca" class="btn btn-danger btn-sm delete_praca" data-id="'.$row["pd_id"].'" title="Usuń"><i class="fas fa-trash"></i></button> 
				<button type="button"   name="view_praca" class="btn btn-primary btn-sm view_praca" data-id="'.$row["pd_id"].'" title="Podgląd"><i class="fas fa-eye"></i></button>';
			}

			else {
				$sub_array[] = '<button type="button" disabled  name="cancel_praca" class="btn btn-danger btn-sm cancel_praca" data-id="'.$row["pd_id"].'" title="Anuluj"><i class="fas fa-times"></i></button> 
				<button type="button"   name="view_praca" class="btn btn-primary btn-sm view_praca" data-id="'.$row["pd_id"].'" title="Podgląd"><i class="fas fa-eye"></i></button>
				<button type="button" name="download_praca" class="btn btn-info btn-circle btn-sm download_praca" data-id="'.$row["pd_id"].'" title="Pobierz kartę pracy"><i class="fas fa-file"></i></button>';
			}
			

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
}


if($_POST['action'] == 'fetch_temat')
	{ 

		$search_query = '';
		$output = array();

		$order_column = array('temat.temat', 'temat.cel_zakres','temat.temat_grupa',  'temat.temat_semestr', 'temat.temat_dostepny');
		
		$main_query = "
		SELECT * FROM temat 
		WHERE temat_dostepny = 'Tak'
		";

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= " AND ( temat.temat LIKE '%".$_POST["search"]["value"]."%' 
				OR temat.temat_dostepny LIKE '%".$_POST["search"]["value"]."%' 
				OR temat.cel_zakres LIKE '%".$_POST["search"]["value"]."%' 
				OR temat.temat_semestr LIKE '%".$_POST["search"]["value"]."%'
				OR temat.temat_grupa LIKE '%".$_POST["search"]["value"]."%')";
				// OR temat.temat_grupa LIKE '%".$_POST["search"]["value"]."%' 
				// OR s.student_gr_dziek LIKE '%".$_POST["search"]["value"]."%'
				// OR student.student_semestr LIKE '%".$_POST["search"]["value"]."%' )";

		
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY temat.temat_id ASC  ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$query = $main_query . $search_query . $order_query . $limit_query;
        $object->query = $query;
        $object->execute();
        $filtered_rows = $object->row_count();

        $result = $object->get_result();

       
        $total_rows = $object->row_count();
		
		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["temat"];
			$sub_array[] = $row["cel_zakres"];
			$sub_array[] = $row["temat_grupa"];
			$sub_array[] = $row["temat_semestr"];
			
			$status = '';
			if($row["temat_dostepny"] == 'Tak')
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
			<button type="button" name="get_praca" class="btn btn-primary btn-sm get_praca" data-temat_id="'.$row["temat_id"].'" data-promotor_id="'.$row["promotor_id"].'">Rezerwuj</button> <button type="button"   name="view_temat" class="btn btn-danger btn-sm view_temat" data-temat_id="'.$row["temat_id"].'" title="Podgląd">Podgląd</i></button>
			</div>';
			
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$total_rows,
			"data"    			=> 	$data
		);

		echo json_encode($output);
	}


	if($_POST['action'] == 'cancel_praca')

	{
		$data = array(
			':status'			=>	'Anulowano',
			':pd_id'	=>	$_POST['pd_id']
		);
		$object->query = "
		UPDATE pd 
		SET status = :status 
		WHERE pd_id = :pd_id
		";
		$object->execute($data);
		echo '<div class="alert alert-danger">Rezerwacja została anulowana.</div>';
	}


	if($_POST['action'] == 'download_praca')
	{

	$data = array(
			':pd_id'	=>	$_POST['pd_id']
		);

	$file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/downloads/kartapracy.html');

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

			$promotor_data = $object->get_result();
		

		foreach($student_data as $student_row) {

			$student_row["student_imie"].' '.$student_row["student_nazwisko"];
			$student_row["student_kierunek"];
			$student_row["student_specjal"];
			$student_row["student_stopien"];
			$student_row["student_nr_indeksu"];

			$file = str_replace("[kierunek studiów]", $student_row["student_kierunek"], $file);
			$file = str_replace("[specjalność]", $student_row["student_specjal"], $file);
			$file = str_replace("[pierwszy/drugi]", $student_row["student_stopien"], $file);
			$file = str_replace("[IMIĘ I NAZWISKO (W NAWIASIE NR ALBUMU]", $student_row["student_imie"].' '.$student_row["student_nazwisko"].' ('.$student_row["student_nr_indeksu"].')', $file);

		}
		foreach($promotor_data as $promotor_row) {
			$promotor_row["promotor_nazwa"];
			$file = str_replace("[STOPIEŃ I/LUB TYTUŁ IMIĘ I NAZWISKO]", $promotor_row["promotor_nazwa"], $file);

		}
		$praca_row["temat_pracy"];
		$praca_row["temat_pracy_ang"];
		$praca_row["cel_zakres_pracy"];

		$file = str_replace("[TEMAT PRACY W JĘZYKU POLSKIM]", $praca_row["temat_pracy"], $file);
		$file = str_replace("[TEMAT PRACY W JĘZYKU ANGIELSKIM]", $praca_row["temat_pracy_ang"], $file);
		$cel_zakres_pracy = $praca_row["cel_zakres_pracy"];

        $cel_zakres_pracy = $praca_row["cel_zakres_pracy"];

// Match "Zakres pracy" with optional colon and case-insensitive
if(preg_match('/zakres\s*pracy\s*:?/i', $cel_zakres_pracy)) {
    // Move "Zakres pracy" to a new line
    $cel_zakres_pracy = preg_replace('/zakres\s*pracy\s*:/i', '<br>Zakres pracy:', $cel_zakres_pracy);
} else {
    // Find the last period in the string
    $last_period_pos = strrpos($cel_zakres_pracy, ".");
    if($last_period_pos !== false) {
        // Start a new line after the last period
        $cel_zakres_pracy = substr_replace($cel_zakres_pracy, "<br>", $last_period_pos + 1, 0);
    }
}

$file = str_replace("[Wpisz cel i zakres pracy]", $cel_zakres_pracy, $file);
	}

	$file_new_name = $student_row["student_nr_indeksu"].'_kartapracy';
	$upload_path = 'downloads/' . basename($file_new_name);
	// $file_utf8 = mb_convert_encoding($file,'HTML-ENTITIES','UTF-8');
	// file_put_contents($upload_path, $file);

	$dompdf = new Dompdf();
	// $dompdf->set_option('defaultFont', 'DejaVu Serif');
	$dompdf->loadHtml($file);
	$dompdf->render();
	$output = $dompdf->output();
	file_put_contents($upload_path . '.pdf', $output);
	// $dompdf->stream($file_new_name . '.pdf', array('Attachment'=>0));


	$file_new_name_pdf = $student_row["student_nr_indeksu"].'_kartapracy.pdf';
	die(json_encode(array("file_name" => $file_new_name_pdf)));
	$file_path = 'downloads/'.$file_new_name_pdf;

	header("Content-Disposition: attachment; filename='$file_new_name_pdf'");
	header('Content-Type: application/pdf');
	header('Content-Length: ' . filesize($file_path));
	readfile($file_path);
}


	if($_POST["action"] == 'delete_praca')
		{
			$object->query = "SELECT temat_pracy FROM pd WHERE pd_id = :pd_id";
			$object->execute(array(':pd_id' => $_POST['pd_id']));
			$temat_pracy = $object->statement->fetchColumn();
			
			$object->query = "
			DELETE FROM pd 
			WHERE pd_id = :pd_id
			";
			$object->execute(array(':pd_id' => $_POST['pd_id']));
			
			$object->query = "UPDATE temat SET temat_dostepny = 'Tak' WHERE temat = :temat_pracy";
			$object->execute(array(':temat_pracy' => $temat_pracy));
			
			echo '<div class="alert alert-danger">Usunięto prośbę rezerwacji.</div>';
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
			<h4 class="text-center">Dane Promotora</h4>
			<table class="table">
			';
			foreach($promotor_schedule_data as $promotor_schedule_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Promotor</th>
					<td>'.$promotor_schedule_row["promotor_nazwa"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">E-mail</th>
					<td>'.$promotor_schedule_row["promotor_adres_email"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Wydział</th>
					<td>'.$promotor_schedule_row["promotor_wydzial"].'</td>
				</tr>
				
				
				';
			}

			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Dane Pracy</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Temat</th>
					<td>'.$praca_row["temat_pracy"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Temat ang.</th>
					<td>'.$praca_row["temat_pracy_ang"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Cel i zakres</th>
					<td>'.$praca_row["cel_zakres_pracy"].'</td>
				</tr>

			';
	
		

			$html .= '
			</table>
			';
		}

		echo $html;
	}


if($_POST["action"] == 'fetch_single_temat')
	{
		$object->query = "
		SELECT temat, temat_grupa, temat_semestr, cel_zakres, promotor_id FROM temat 
		WHERE temat_id = '".$_POST["temat_id"]."'
		";

		$temat_data = $object->get_result();

		foreach($temat_data as $temat_row)
		{
			$object->query = "
			SELECT promotor_nazwa, promotor_adres_email, promotor_wydzial FROM promotor 
			WHERE promotor_id = '".$temat_row["promotor_id"]."'
			";

			$promotor_data = $object->get_result();

			$html = '

			<h4 class="text-center">Dane Promotora</h4>
			<table class="table">
			';
			foreach($promotor_data as $promotor_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Promotor</th>
					<td>'.$promotor_row["promotor_nazwa"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">E-mail</th>
					<td>'.$promotor_row["promotor_adres_email"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Wydział</th>
					<td>'.$promotor_row["promotor_wydzial"].'</td>
				</tr>
				';
			}
			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Dane Pracy</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Temat Pracy</th>
					<td>'.$temat_row["temat"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Grupa</th>
					<td>'.$temat_row["temat_grupa"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Semestr</th>
					<td>'.$temat_row["temat_semestr"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Cel i Zakres Pracy</th>
					<td>'.$temat_row["cel_zakres"].'</td>
				</tr>
			</table>
			';
		}

		echo $html;
	}





?>