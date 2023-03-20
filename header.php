<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>System Rezerwacji i Prowadzenia Prac Dyplomowych</title>
		<title>E-Praca</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>

	    <link rel="stylesheet" type="text/css" href="vendor/datepicker/bootstrap-datepicker.css"/>

	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	    <style>
	    	.border-top { border-top: 1px solid #e5e5e5; }
			.border-bottom { border-bottom: 1px solid #e5e5e5; }

			.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
	    </style>
		
		<style>#chat-section {
		width: 100%;
		height: 100px;
		} 

		
		</style>


	</head>
	<body>
		<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
			<div class="col">
		    	<h5 class="my-0 mr-md-auto font-weight-normal"><a href="index.php">epraca.site</a></h5>
		    </div>
		    <?php
		    if(!isset($_SESSION['student_id']))
		    {
		    ?>
		    <div class="col text-right">
				<a type="submit" name="student_login_button" id="student_login_button" class="btn btn-primary" href="login.php" value="Zaloguj">Zaloguj</a>
				<a type="submit" name="student_register_button" id="student_register_button" class="btn btn-danger" href="register.php" value="Rejestracja">Rejestracja</a>
			</div>
		   	<?php
		   	}
		   	?>
		    
	    </div>

	    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
	      	<h1 class="display-4">System Rezerwacji i Prowadzenia Prac Dyplomowych</h1>
			<h1 class="display-4">E-Praca</h1>
	    </div>
	    <br />
	    <br />
	    <div class="container-fluid">