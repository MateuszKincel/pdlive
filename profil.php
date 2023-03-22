<?php

//profil.php



include('class/handler.php');

$object = new Handler;

$object->query = "
SELECT * FROM student 
WHERE student_id = '".$_SESSION["student_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid">
	<?php include('navbar.php'); ?>

	<div class="row justify-content-md-center">
  <div class="col-md-4">
    <br />
    <?php
    if(isset($_GET['action']) && $_GET['action'] == 'edit')
    {
    ?>
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            Edytuj Dane Profilu
          </div>
          <div class="col-md-6 text-right">
            <a href="profil.php" class="btn btn-secondary btn-sm">Podlgąd</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form method="post" id="edit_profile_form">
          <div class="form-group row">
            <label for="student_imie" class="col-md-3 col-form-label col-form-label-sm">Imię:<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_imie" id="student_imie" class="form-control" required data-parsley-trigger="keyup" />
            </div>
          </div>
          <div class="form-group row">
            <label for="student_nazwisko" class="col-md-3 col-form-label">Nazwisko:<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_nazwisko" id="student_nazwisko" class="form-control" required data-parsley-trigger="keyup" />
            </div>
          </div>
          <div class="form-group row">
            <label for="student_adres_email" class="col-md-3 col-form-label">Adres Email:<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="readonly" name="student_adres_email" id="student_adres_email" class="form-control" required="false" autofocus data-parsley-type="email" data-parsley-trigger="keyup" readonly />
            </div>
          </div>
          <div class="form-group row">
            <label for="student_haslo" class="col-md-3 col-form-label">Hasło:<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="password" name="student_haslo" id="student_haslo" class="form-control" required data-parsley-trigger="keyup" />
            </div>
          </div>
          <div class="form-group row">
            <label for="student_data_urodzenia" class="col-md-3 col-form-label">Data Urodzenia:<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_data_urodzenia" id="student_data_urodzenia" class="form-control" required data-parsley-trigger="keyup" readonly />
            </div>
          </div>
          <div class="form-group row">
            <label for="student_nr_indeksu" class="col-md-3 col-form-label">Nr Indeksu<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_nr_indeksu" id="student_nr_indeksu" class="form-control" required data-parsley-trigger="keyup" />
			</div>
		   </div>
		  <div class="form-group row">
            <label for="student_semestr" class="col-md-3 col-form-label">Semestr<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_semestr" id="student_semestr" class="form-control" required data-parsley-trigger="keyup" />
			 </div>
		    </div>
		   <div class="form-group row">
            <label for="student_gr_dziek" class="col-md-3 col-form-label">Grupa<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_gr_dziek" id="student_gr_dziek" class="form-control" required data-parsley-trigger="keyup" />
			 </div>
		    </div>
		   <div class="form-group row">
            <label for="student_gr_dziek" class="col-md-3 col-form-label">Grupa<span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" name="student_gr_dziek" id="student_gr_dziek" class="form-control" required data-parsley-trigger="keyup" />
			</div>
		   </div>
		  <div class="form-group row">
			<label for="student_podgrupa" class="col-md-3 col-form-label">Podgrupa<span class="text-danger">*</span></label>
			<div class="col-md-8">
			  <input type="text" name="student_podgrupa" id="student_podgrupa" class="form-control" required data-parsley-trigger="keyup" />
			  </div>
			</div>
		  <div class="form-group row">
			<label for="student_wydzial" class="col-md-3 col-form-label">Wydział<span class="text-danger">*</span></label>
			<div class="col-md-8">
			  <input type="text" name="student_wydzial" id="student_wydzial" class="form-control" required data-parsley-trigger="keyup" />
			  </div>
			 </div>
		   <div class="form-group row">
			<label for="student_telefon" class="col-md-3 col-form-label">Telefon</label>
			<div class="col-md-8">
			  <input type="text" name="student_telefon" id="student_telefon" class="form-control" required data-parsley-trigger="keyup" />
			  </div>
			</div>
		   <div class="form-group row">
			<label for="student_adres" class="col-md-3 col-form-label">Adres</label>
			<div class="col-md-8">
			  <input type="text" name="student_adres" id="student_adres" class="form-control" required data-parsley-trigger="keyup" />
			   </div>
			 </div>
				<div class="form-group text-center">
					<input type="hidden" name="action" value="edit_profile" />
					<input type="submit" name="edit_profile_button" id="edit_profile_button" class="btn btn-primary" value="Edytuj" />
				</div>
			</form>
		</div>
	</div>
			

			<br />
			<br />
			

			<?php
			}
			else
			{

				if(isset($_SESSION['success_message']))
				{
					echo $_SESSION['success_message'];
					unset($_SESSION['success_message']);
				}
			?>

			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Dane Profilu
						</div>
						<div class="col-md-6 text-right">
							<a href="profil.php?action=edit" class="btn btn-secondary btn-sm">Edytuj</a>
						</div>
				<div class="card-body ">
					<table class="table table-striped">
						<?php
						foreach($result as $row)
						{
						?>
						<tr>
							<th class="text-right" width="40%">Imię i Nazwisko:</th>
							<td><?php echo $row["student_imie"] . ' ' . $row["student_nazwisko"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Adres Email:</th>
							<td><?php echo $row["student_adres_email"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Nr Indeksu:</th>
							<td><?php echo $row["student_nr_indeksu"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Semestr</th>
							<td><?php echo $row["student_semestr"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Grupa:</th>
							<td><?php echo $row["student_gr_dziek"]; ?></td>
						</tr>
						
						<tr>
							<th class="text-right" width="40%">Podgrupa dziek.:</th>
							<td><?php echo $row["student_podgrupa"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Wydział:</th>
							<td><?php echo $row["student_wydzial"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Hasło:</th>
							<td>*****</td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Adres:</th>
							<td><?php echo $row["student_adres"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Nr. Telefonu:</th>
							<td><?php echo $row["student_telefon"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Data Urodzenia:</th>
							<td><?php echo $row["student_data_urodzenia"]; ?></td>
						</tr>
						<?php
						}
						?>	
					</table>					
				</div>
			</div>
			<br />
			<br />
			<?php
			}
			?>
		</div>
	</div>
</div>
</div>

<?php

include('footer.php');


?>

<script>

$(document).ready(function(){

	$('#student_data_urodzenia').datepicker({
		language: "pl",
        format: "yyyy-mm-dd",
        autoclose: true
    });

<?php
	foreach($result as $row)
	{

?>
		$('#student_adres_email').val("<?php echo $row['student_adres_email']; ?>");
		$('#student_haslo').val("<?php echo $row['student_haslo']; ?>");
		$('#student_nr_indeksu').val("<?php echo $row['student_nr_indeksu']; ?>");
		$('#student_semestr').val("<?php echo $row['student_semestr']; ?>");
		$('#student_gr_dziek').val("<?php echo $row['student_gr_dziek']; ?>");
		$('#student_podgrupa').val("<?php echo $row['student_podgrupa']; ?>");
		$('#student_wydzial').val("<?php echo $row['student_wydzial']; ?>");
		$('#student_imie').val("<?php echo $row['student_imie']; ?>");
		$('#student_nazwisko').val("<?php echo $row['student_nazwisko']; ?>");
		$('#student_data_urodzenia').val("<?php echo $row['student_data_urodzenia']; ?>");
		$('#student_telefon').val("<?php echo $row['student_telefon']; ?>");
		$('#student_adres').val("<?php echo $row['student_adres']; ?>");

<?php

	}

?>

	$('#edit_profile_form').parsley();

	$('#edit_profile_form').on('submit', function(event){

		event.preventDefault();

		if($('#edit_profile_form').parsley().isValid())
		{
			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function()
				{
					$('#edit_profile_button').attr('disabled', 'disabled');
					$('#edit_profile_button').val('czekaj...');
				},
				success:function(data)
				{
					window.location.href = "profil.php";
				}
			})
		}

	});

});

</script>