<?php

include('../class/handler_admin.php');

$object = new Handler;

if($_POST["action"] == 'promotor_profile')
{
	sleep(2);

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
	else
	{
	

		if($error == '')
		{
			$data = array(
				':promotor_adres_email'			=>	$object->clean_input($_POST["promotor_adres_email"]),
				':promotor_haslo'				=>	$_POST["promotor_haslo"],
				':promotor_nazwa'					=>	$object->clean_input($_POST["promotor_nazwa"]),
				':promotor_telefon'				=>	$object->clean_input($_POST["promotor_telefon"]),
				':promotor_wydzial'				=>	$object->clean_input($_POST["promotor_wydzial"])
			);

			$object->query = "
			UPDATE promotor  
			SET promotor_adres_email = :promotor_adres_email, 
			promotor_haslo = :promotor_haslo, 
			promotor_nazwa = :promotor_nazwa, 
			promotor_telefon = :promotor_telefon, 
			promotor_wydzial = :promotor_wydzial 
			WHERE promotor_id = '".$_POST['hidden_id']."'
			";
			$object->execute($data);

			$success = '<div class="alert alert-success">Dane Promotora Zaktualizowane</div>';
		}			
	}

	$output = array(
		'error'					=>	$error,
		'success'				=>	$success,
		'promotor_adres_email'	=>	$_POST["promotor_adres_email"],
		'promotor_haslo'		=>	$_POST["promotor_haslo"],
		'promotor_nazwa'			=>	$_POST["promotor_nazwa"],
		'promotor_telefon'		=>	$_POST["promotor_telefon"],
		'promotor_wydzial'		=>	$_POST["promotor_wydzial"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';


	if($error == '')
	{
		$data = array(
			':admin_adres_email'			=>	$object->clean_input($_POST["admin_adres_email"]),
			':admin_haslo'				=>	$_POST["admin_haslo"],
			':admin_nazwa'					=>	$object->clean_input($_POST["admin_nazwa"])

		);

		$object->query = "
		UPDATE admin  
		SET admin_adres_email = :admin_adres_email, 
		admin_haslo = :admin_haslo, 
		admin_nazwa = :admin_nazwa 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Dane admina zaktualizowane</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_adres_email'	=>	$_POST["admin_adres_email"],
			'admin_haslo'		=>	$_POST["admin_haslo"],
			'admin_nazwa'			=>	$_POST["admin_nazwa"], 
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>