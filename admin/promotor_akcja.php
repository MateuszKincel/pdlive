<?php

//promotor_akcja.php

include('../class/handler_admin.php');

$object = new Handler;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('promotor_nazwa','promotor_email', 'promotor_status','promotor_telefon','promotor_wydzial','promotor_specjalizacja',);

		$output = array();

		$main_query = "
		SELECT * FROM promotor ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE promotor_adres_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor_telefon LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor_wydzial LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor_status LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR promotor_specjalizacja LIKE "%'.$_POST["search"]["value"].'%" ';

		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY promotor_id DESC ';
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
			$sub_array[] = $row["promotor_adres_email"];
			$sub_array[] = $row["promotor_nazwa"];
			$sub_array[] = $row["promotor_telefon"];
			$sub_array[] = $row["promotor_wydzial"];
			$sub_array[] = $row["promotor_specjalizacja"];
			$status = '';
			if($row["promotor_status"] == 'Aktywny')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["promotor_id"].'" data-status="'.$row["promotor_status"].'">Aktywny</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["promotor_id"].'" data-status="'.$row["promotor_status"].'">Nie Aktywny</button>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["promotor_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["promotor_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["promotor_id"].'"><i class="fas fa-times"></i></button>
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

	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':promotor_adres_email'	=>	$_POST["promotor_adres_email"]
		);

		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor_adres_email = :promotor_adres_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email już istnieje!</div>';
		}
		

			if($error == '')
			{
				$data = array(
					':promotor_adres_email'					=>	$object->clean_input($_POST["promotor_adres_email"]),
					':promotor_haslo'						=>	$_POST["promotor_haslo"],
					':promotor_nazwa'						=>	$object->clean_input($_POST["promotor_nazwa"]),
					':promotor_telefon'						=>	$object->clean_input($_POST["promotor_telefon"]),
					':promotor_wydzial'						=>	$object->clean_input($_POST["promotor_wydzial"]),
					':promotor_specjalizacja'				=>	$object->clean_input($_POST["promotor_specjalizacja"]),
					':promotor_status'						=>	'Aktywny'
				);

				$object->query = "
				INSERT INTO promotor 
				(promotor_adres_email, promotor_haslo, promotor_nazwa, promotor_telefon,  promotor_wydzial,promotor_specjalizacja, promotor_status) 
				VALUES (:promotor_adres_email, :promotor_haslo, :promotor_nazwa, :promotor_telefon, :promotor_wydzial, :promotor_specjalizacja, :promotor_status)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Promotor Dodany</div>';
			}
	

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}
}


	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor_id = '".$_POST["promotor_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['promotor_adres_email'] = $row['promotor_adres_email'];
			$data['promotor_haslo'] = $row['promotor_haslo'];
			$data['promotor_nazwa'] = $row['promotor_nazwa'];
			$data['promotor_telefon'] = $row['promotor_telefon'];
			$data['promotor_wydzial'] = $row['promotor_wydzial'];
			$data['promotor_specjalizacja'] = $row['promotor_specjalizacja'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':promotor_adres_email'	=>	$_POST["promotor_adres_email"],
			':promotor_id'			=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * FROM promotor 
		WHERE promotor_adres_email = :promotor_adres_email 
		AND promotor_id != :promotor_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Adres E-Mail już istnieje</div>';
		}
		
			

			if($error == '')
			{
				$data = array(
					':promotor_adres_email'			=>	$object->clean_input($_POST["promotor_adres_email"]),
					':promotor_haslo'				=>	$_POST["promotor_haslo"],
					':promotor_nazwa'				=>	$object->clean_input($_POST["promotor_nazwa"]),
					':promotor_telefon'				=>	$object->clean_input($_POST["promotor_telefon"]),
					':promotor_wydzial'				=>	$object->clean_input($_POST["promotor_wydzial"]),
					':promotor_specjalizacja'		=>	$object->clean_input($_POST["promotor_specjalizacja"])
				);

				$object->query = "
				UPDATE promotor  
				SET promotor_adres_email = :promotor_adres_email, 
				promotor_haslo = :promotor_haslo, 
				promotor_nazwa = :promotor_nazwa, 
				promotor_telefon = :promotor_telefon, 
				promotor_wydzial = :promotor_wydzial,
				promotor_specjalizacja = :promotor_specjalizacja  
				WHERE promotor_id = '".$_POST['hidden_id']."'
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Zmodyfikowano dane promotora.</div>';
			}			
		

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':promotor_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE promotor 
		SET promotor_status = :promotor_status 
		WHERE promotor_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Zmieniono status.</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM promotor 
		WHERE promotor_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Usunięto dane promotora.</div>';
	}

?>