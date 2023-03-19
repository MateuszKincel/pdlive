<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//index.php
include('class/handler.php');

$object = new Handler;

if(isset($_SESSION['student_id']))
{
	header('location:panel.php');
}

		$object->query = "
		SELECT * FROM promotor_harmonogram
		INNER JOIN promotor
		ON promotor.promotor_id=promotor_harmonogram.promotor_id 
		";

$result = $object->get_result();

include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header"><h3><b>Dostępni Promotorzy</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>Promotor</th> 
		      							<th>Wydział</th> 
		      							<th>Telefon</th> 
		      							<th>E-Mail</th> 
										<th>Status</th> 
		      							<th>Akcja</th>
		      						</tr>
		      						<?php
		      						foreach($result as $row)
		      						{
		      							echo '
		      							<tr>
		      								<td>'.$row["promotor_nazwa"].'</td>
		      								<td>'.$row["promotor_wydzial"].'</td>
		      								<td>'.$row["promotor_telefon"].'</td>
		      								<td>'.$row["promotor_adres_email"].'</td>
											<td>'.$row["promotor_harmonogram_status"].'</td>
		      								<td><button type="button" name="get_praca" class="btn btn-primary btn-sm get_praca" data-id="'.$row["promotor_id"].'">Rezerwuj</button></td>
		      							</tr>
		      							';
		      						}
		      						?>
		      					</table>
		      				</div>
		      			</div>
		      		</form>
		      	</div>
		    

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){
	$(document).on('click', '.get_praca', function(){
		var action = 'check_login';
		var promotor_id = $(this).data('id');
		var promotor_status = $(this).data('promotor_status');
		$.ajax({
			url:"akcja.php",
			method:"POST",
			data:{action:action,promotor_status:promotor_status, promotor_id:promotor_id},
			success:function(data)
			{
				window.location.href=data;
			}
		})
	});
});

</script>