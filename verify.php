<?php

//verify.php

include('header.php');

include('class/handler.php');

$object = new Handler;

if(isset($_GET["code"]))
{
	$object->query = "
	UPDATE student 
	SET weryfikacja_email = 'Tak' 
	WHERE student_kod_weryfikacji = '".$_GET["code"]."'
	";

	$object->execute();

	$_SESSION['success_message'] = '<div class="alert alert-success">E-mail został pomyślnie zweryfikowany. Możesz zalogować się do serwisu</div>';

	header('location:login.php');
}

include('footer.php');

?>