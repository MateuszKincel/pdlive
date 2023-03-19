<?php

//promotor_harmonogram_akcja.php

include('../class/handler_admin.php');

$object = new Handler;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('promotor.promotor_nazwa', 'promotor_harmonogram.promotor_harmonogram_data');
			$main_query = "
			SELECT * FROM promotor_harmonogram 
			INNER JOIN promotor 
			ON promotor.promotor_id = promotor_harmonogram.promotor_id 
			";

			$search_query = '';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'WHERE promotor.promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
				
			}
		}
		else
		{
			$order_column = array('promotor_harmonogram.promotor_harmonogram_data');
			$main_query = "
			SELECT * FROM promotor_harmonogram 
			INNER JOIN promotor
			ON promotor.promotor_id = promotor_harmonogram.promotor_id
			
			";

			$search_query = '
			WHERE promotor.promotor_id = "'.$_SESSION["admin_id"].'" AND 
			';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= '(promotor_harmonogram_data LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY promotor_harmonogram.promotor_harmonogram_status = "Nie Aktywny" ';
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
			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = html_entity_decode($row["promotor_nazwa"]);
			}
			$sub_array[] = $row["promotor_harmonogram_data"];
			


			$status = '';
			if($row["promotor_harmonogram_status"] == 'Aktywny')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["promotor_harmonogram_id"].'" data-status="'.$row["promotor_harmonogram_status"].'">Aktywny</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["promotor_harmonogram_id"].'" data-status="'.$row["promotor_harmonogram_status"].'">Nie Aktywny</button>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["promotor_harmonogram_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["promotor_harmonogram_id"].'"><i class="fas fa-times"></i></button>
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

		$promotor_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$promotor_id = $_POST["admin_id"];
		}

		if($_SESSION['type'] == 'Promotor')
		{
			$promotor_id = $_SESSION['admin_id'];
		}

		$data = array(
			':promotor_id'							=>  $promotor_id,
			':promotor_harmonogram_data'			=>	date("Y-m-d", strtotime($_POST["promotor_harmonogram_data"])),
			
		);

	 //check if the promotor id already exists
    $check_query = "SELECT * FROM promotor_harmonogram WHERE promotor_id = :promotor_id";
    $object->query = $check_query;
    $object->execute(array(':promotor_id' => $promotor_id));
    if($object->row_count() > 0){
        $error = '<div class="alert alert-danger">Harmonogram już istnieje. Maksymalna liczba harmonogramów (1/1)</div>';
    } else {
        $insert_query = "INSERT INTO promotor_harmonogram (promotor_id, promotor_harmonogram_data) VALUES (:promotor_id, :promotor_harmonogram_data)";
        $object->query = $insert_query;
        $object->execute($data);
        $success = '<div class="alert alert-success">Dodano harmonogram promotora.</div>';
    }
    $output = array(
        'error'		=>	$error,
        'success'	=>	$success
    );
    echo json_encode($output);

}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM promotor_harmonogram 
		WHERE promotor_harmonogram_id = '".$_POST["promotor_harmonogram_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['promotor_id'] = $row['promotor_id'];
			$data['promotor_harmonogram_data'] = $row['promotor_harmonogram_data'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$promotor_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$promotor_id = $_POST["promotor_id"];
		}

		if($_SESSION['type'] == 'Promotor')
		{
			$promotor_id = $_SESSION['admin_id'];
		}

		$data = array(
			':promotor_id'					=>	$promotor_id,
			':promotor_harmonogram_data'			=>	$_POST["promotor_harmonogram_data"],
			// ':promotor_harmonogram_start_czas'	=>	$_POST["promotor_harmonogram_start_czas"],
			// ':promotor_harmonogram_koniec_czas'		=>	$_POST["promotor_harmonogram_koniec_czas"],
			// ':sredni_czas_wizyty'		=>	$_POST["sredni_czas_wizyty"]
		);

		$object->query = "
		UPDATE promotor_harmonogram 
		SET promotor_id = :promotor_id, 
		promotor_harmonogram_data = :promotor_harmonogram_data
		WHERE promotor_harmonogram_id = '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Zmodyfikowano harmonogram promotora</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':promotor_harmonogram_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE promotor_harmonogram 
		SET promotor_harmonogram_status = :promotor_harmonogram_status 
		WHERE promotor_harmonogram_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Zmieniono status.</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM promotor_harmonogram 
		WHERE promotor_harmonogram_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Usunięto harmonogram promotora.</div>';
	}
}

?>