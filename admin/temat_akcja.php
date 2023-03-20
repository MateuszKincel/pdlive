<?php

//temat_akcja.php

include('../class/handler_admin.php');

$object = new Handler;


if(isset($_POST["action"]))
{
    if($_POST["action"] == 'fetch')
    {
		// $promotor_id = $_SESSION['admin_id'];
        
        $output = array();
		if($_SESSION["type"] == "Promotor" ) {
		$order_column = array('promotor_id','temat','temat_grupa','temat_semestr', 'temat_dostepny');
		$main_query = "SELECT *  FROM temat WHERE promotor_id = '".$_SESSION["admin_id"]."' ";
		} else {
		$order_column = array('promotor_id','promotor_nazwa','temat','temat_grupa','temat_semestr', 'temat_dostepny');
        $main_query = "SELECT DISTINCT * FROM promotor ";
		}
        $search_query = '';

        if(isset($_POST["search"]["value"]))
        {
            if($_SESSION["type"] == "Promotor") {
				$search_query .= 'AND (temat LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR temat_grupa LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR temat_semestr LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR temat_dostepny LIKE "%'.$_POST["search"]["value"].'%") ';
			} else {
				$search_query .= ' WHERE promotor.promotor_liczba_tematow LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR promotor.promotor_nazwa LIKE "%'.$_POST["search"]["value"].'%" ';
			}
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
			if($_SESSION["type"] == "Admin") {
			$sub_array[] = $row["promotor_nazwa"];
			$sub_array[] = $row["promotor_liczba_tematow"];
			$sub_array[] = '<button type="button"   name="view_button" class="btn btn-info btn-circle view_button" data-id="'.$row["promotor_id"].'" title="Podgląd tematów"><i class="fa-sharp fa-solid fa-eye"></i></button>';
		} else if ($_SESSION["type"] == "Promotor") {
			$sub_array[] = $row["temat_grupa"];
			$sub_array[] = $row["temat"];
			$sub_array[] = $row["cel_zakres"];
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
			$sub_array[] = '<button type="button" action="delete_temat   name="delete_button" class="btn btn-danger btn-circle delete_button" data-id="'.$row["temat_id"].'" title="Usuń"><i class="fa-sharp fa-solid fa-times"></i></button>';
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

if($_POST["action"] == 'delete_temat')
		{
		$object->query = "
		DELETE FROM temat 
		WHERE temat_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-danger">Usunięto temat.</div>';
	}


if($_POST["action"] == 'fetch_single')
{
    $object->query = "SELECT * FROM temat WHERE promotor_id = '".$_POST["promotor_id"]."'";

    $result = $object->get_result();

    $data = array();

    foreach($result as $row)
    {
        array_push($data, $row);
    }

    echo json_encode($data);
}


// if($_POST["action"] == 'fetch_single')
// {
//     $object->query = "
//     SELECT * FROM temat 
//     WHERE promotor_id = '".$_POST["promotor_id"]."'
//     ";

//     $result = $object->get_result();

//     $data = array();

// 	$object->query = "SELECT promotor_liczba_tematow FROM promotor WHERE promotor_id = '".$_POST["promotor_id"]."'";
//      // get the number of rows from the promotor_liczba_tematow column
// 	$promotor_liczba_tematow = $object->get_result()->fetchColumn();
	

//     for($i = 0; $i < $promotor_liczba_tematow; $i++)
//     {
//         $data[$i]['temat'] = $result[$i]['temat_name'];
//     }

//     echo json_encode($data);
// }



// if ($_POST["action"] == 'Przydziel') {
//     $error = '';
//     $success = '';

//     // Get number of topics and number of promotors
//     $liczba_tematow = $_POST["liczba_tematow"];
//     $object->query = "SELECT * FROM promotor";
//     $result = $object->get_result();
//     $liczba_promotorow = $result->rowCount();

//     // Divide the number of topics by the number of promotors
//     $temat_na_promotora = floor($liczba_tematow / $liczba_promotorow);
//     $remaining_topics = $liczba_tematow % $liczba_promotorow;

//     // Prepare the update query and bind the parameter for the topic count
//     $object->query = "UPDATE promotor SET promotor_liczba_tematow = :topics WHERE promotor_id = :id";
//     $object->statement = $object->connect->prepare($object->query);

//     // Loop through the result and update each row
//     foreach ($result as $row) {
//         $promotor_id = $row['promotor_id'];
//         $promotor_email = $row['promotor_adres_email'];
//         if ($remaining_topics > 0) {
//             $object->statement->bindValue(':topics', $temat_na_promotora + 1);
//             $remaining_topics--;
//         } else {
//             $object->statement->bindValue(':topics', $temat_na_promotora);
//         }
//         $object->statement->bindValue(':id', $promotor_id);
//         $object->statement->execute();

//         // Send an email to the promotor
//         $admin_name = $_SESSION['admin_id'];
//         $message = "Your administrator, $admin_name, assigned you $temat_na_promotora topics.";
//         $object->send_mail($message,$subject = 'Powiadomienie od administratora!',$message_body = '<p>Twoj promotor '.$admin_name.'  wysłał do ciebie wiadomość:</p>',$promotor_email,'kincelmateusz5@gmail.com');
//     }

//     $success = '<div class="alert alert-success">Assigned number of topics.</div>';

//     $output = array(
//         'error' => $error,
//         'success' => $success
//     );

//     echo json_encode($output);
// }


		



if($_POST["action"] == 'Przydziel')
{
	if(isset($_POST["action"]) && $_POST["action"] === 'Przydziel') {
	$success = '';
	// Get number of topics and number of promotors
	$liczba_tematow = intval($_POST["liczba_tematow"]);
	$object->query = "SELECT * FROM promotor";
	$result = $object->get_result();
	$liczba_promotorow = $result->rowCount();

	// Divide the number of topics by the number of promotors
$temat_na_promotora = floor($liczba_tematow / $liczba_promotorow);
$remaining_topics = $liczba_tematow % $liczba_promotorow;

// Prepare the update query and bind the parameter for the topic count
$object->query = "UPDATE promotor SET promotor_liczba_tematow = :topics WHERE promotor_id = :id";
$object->statement = $object->connect->prepare($object->query);
$object->statement->bindParam(':topics', $temat_na_promotora, PDO::PARAM_INT);
$object->statement->bindParam(':id', $promotor_id, PDO::PARAM_INT);

// Loop through the result and update each row
foreach ($result as $row) {
    $promotor_id = $row['promotor_id'];
    if ($remaining_topics > 0) {
        $object->statement->bindValue(':topics', $temat_na_promotora + 1, PDO::PARAM_INT);
        $remaining_topics--;
    } else {
        $object->statement->bindValue(':topics', $temat_na_promotora, PDO::PARAM_INT);
    }
    $object->statement->execute();
}

$object->query = "SELECT promotor_id, promotor_nazwa, promotor_liczba_tematow FROM promotor";
$statement = $object->execute();
$promotors = $object->statement_result();
$success = '<div class="alert alert-success">Przydzielono liczbę tematów.</div>';
	}

    $object->query = "SELECT promotor_id, promotor_nazwa, promotor_liczba_tematow FROM promotor";
    $statement = $object->execute();
	$promotors = $object->statement_result();
	$success = '<div class="alert alert-success">Przydzielono liczbę tematów.</div>';
	
			$object->query = "SELECT * FROM promotor";
			$promotor_emails = $object->get_result();

			$admin_nazwa = "";
			$admin_email = "";

			$object->query = "SELECT * FROM admin WHERE admin_id = '".$_SESSION['admin_id']."'";
			$admin_data = $object->get_result();
			$admin_data_row = $admin_data->fetch();

			$admin_nazwa = $admin_data_row['admin_nazwa'];
			$admin_email = $admin_data_row['admin_adres_email'];
			$repplyTo = $admin_email;
foreach ($promotor_emails as $promotor_email) {
    $promotor_adres_email = $promotor_email['promotor_adres_email'];
    $recipient = $promotor_adres_email;

    $message = 'Administrator '.$admin_nazwa.'  zmienił liczbę twoich tematów na: '. $temat_na_promotora.'';
    $subject = 'Powiadomienie od administratora!';
    $message_body = '<p>Twoj administrator '.$admin_nazwa.'  wysłał do ciebie wiadomość:</p>
    <p>'.$message.'</p>
    <p>  </p>
    <p>  </p>
    <p>Odpowiedź na tą wiadomość zostanie wysłana na e-mail: '.$admin_email.'</p>
    <p></p>
    <p>Z poważaniem</p>
    <p><b>epraca.site</b></p>';

    $object->send_mail($message, $subject, $message_body, $recipient, $repplyTo);
}

if($object) {
    $success .= '<div class="alert alert-success">Wysłano powiadomienie E-mail</div>';
} else {
    $error .= '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
}

$output = array(
    'error'     =>  $error,
    'success'   =>  $success
    
);

echo json_encode($output);
			}
	
	




if($_SESSION['type'] == 'Promotor')
		{
			
		
	if($_POST["action"] == 'Add')


		
	{
		
		$error = '';

		$success = '';

		$data = array(
			':temat'						=>	$object->clean_input($_POST["temat"]),
		);

		$object->query = "
		SELECT * FROM temat 
		WHERE temat = :temat
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Temat już istnieje!</div>';
		}
		
		
		

			if($error == '')
			{
				$data = array(
					':temat'						=>	$object->clean_input($_POST["temat"]),
					':temat_dostepny'				=>	'Tak',
					':cel_zakres'					=>	$object->clean_input($_POST["cel_zakres"]),
					':temat_semestr'				=>	$object->clean_input($_POST["temat_semestr"]),
					':temat_grupa'					=>	$object->clean_input($_POST["temat_grupa"]),
					':promotor_id'					=> 	$_POST['hidden_id']	
				);

				$object->query = "SELECT COUNT(*) as temat_count FROM temat WHERE promotor_id = :promotor_id";
				$object->execute(array(':promotor_id' => $_POST['hidden_id']));
				$temat_count = $object->fetch()['temat_count'];

				$object->query = "SELECT promotor_liczba_tematow FROM promotor WHERE promotor_id = :promotor_id";
				$object->execute(array(':promotor_id' => $_POST['hidden_id']));
				$promotor_temat_limit = $object->fetch()['promotor_liczba_tematow'];

				if($temat_count >= $promotor_temat_limit) {
					$error = '<div class="alert alert-danger">Dodałeś już wszystkie tematy!</div>';
				} else {
			// Continue with insert statement

				$object->query = "
				INSERT INTO temat 
				(temat_grupa,promotor_id, temat, temat_semestr,cel_zakres, temat_dostepny) 
				VALUES (:temat_grupa, :promotor_id, :temat, :temat_semestr,:cel_zakres, :temat_dostepny)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Temat Dodany</div>';
				}
			}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

		}
	}
?>