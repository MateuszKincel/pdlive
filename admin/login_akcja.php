<?php

//login_akcja.php

include('../class/handler_admin.php');

$object = new Handler;

if(isset($_POST["admin_adres_email"]))
{
	sleep(2);
	$error = '';
	$url = '';
	$data = array(
		':admin_adres_email'	=>	$_POST["admin_adres_email"]
	);

	$object->query = "
		SELECT * FROM admin 
		WHERE admin_adres_email = :admin_adres_email
	";

	$object->execute($data);

	$total_row = $object->row_count();

	if($total_row == 0)
	{
		$object->query = "
			SELECT * FROM promotor 
			WHERE promotor_adres_email = :admin_adres_email
		";
		$object->execute($data);

		if($object->row_count() == 0)
		{
			$error = '<div class="alert alert-danger">Wrong Email Address</div>';
		}
		else
		{
			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["promotor_status"] == 'Nie Aktywny')
				{
					$error = '<div class="alert alert-danger">Konto wyłączone, skontaktuj się z adminstratorem.</div>';
				}
				else
				{
					if($_POST["admin_haslo"] == $row["promotor_haslo"])
					{
						$_SESSION['admin_id'] = $row['promotor_id'];
						$_SESSION['type'] = 'Promotor';
						$url = $object->base_url . '/admin/promotor_harmonogram.php';
					}
					else
					{
						$error = '<div class="alert alert-danger">Błędne Hasło!</div>';
					}
				}
			}
		}
	}
	else
	{
	

		$result = $object->statement_result();

		foreach($result as $row)
		{
			if($_POST["admin_haslo"] == $row["admin_haslo"])
			{
				$_SESSION['admin_id'] = $row['admin_id'];
				$_SESSION['type'] = 'Admin';
				$url = $object->base_url . '/admin/panel_admin.php';
			}
			else
			{
				$error = '<div class="alert alert-danger">Wrong Password</div>';
			}
		}
	}

	$output = array(
		'error'		=>	$error,
		'url'		=>	$url
	);

	echo json_encode($output);
}

?>


