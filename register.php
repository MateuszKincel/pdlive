<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Rejestracja</div>
				<div class="card-body">
					<form method="post" id="student_register_form">
						<div class="form-group">
							<label>Adres Email<span class="text-danger">*</span></label>
							<input type="text" name="student_adres_email" id="student_adres_email" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>
						<div class="form-group">
							<label>Hasło<span class="text-danger">*</span></label>
							<input type="password" name="student_haslo" id="student_haslo" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Imię<span class="text-danger">*</span></label>
									<input type="text" name="student_imie" id="student_imie" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nazwisko<span class="text-danger">*</span></label>
									<input type="text" name="student_nazwisko" id="student_nazwisko" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						
						<div class="col-md-6">
								<div class="form-group">
									<label>Nr Indeksu<span class="text-danger">*</span></label>
									<input type="text" name="student_nr_indeksu" id="student_nr_indeksu" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Grupa<span class="text-danger">*</span></label>
									<input type="text" name="student_gr_dziek" id="student_gr_dziek" class="form-control" placeholder="np. 11 INF-NP / 41 INF-ISM-NP" required  data-parsley-trigger="keyup" />
									<div id="error-message" style="display: none; color: red; font-size: 13px;"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Data Urodzenia<span class="text-danger">*</span></label>
									<input type="text" name="student_data_urodzenia" id="student_data_urodzenia" class="form-control" required  data-parsley-trigger="keyup" readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Podgrupa<span class="text-danger">*</span></label>
									<input type="text" name="student_podgrupa" id="student_podgrupa" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nr. Telefonu</label>
									<input type="text" name="student_telefon" id="student_telefon" class="form-control"  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Wydział<span class="text-danger">*</span></label>
									<input type="text" name="student_wydzial" id="student_wydzial" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="student_semestr" style="display: block;">Semestr<span class="text-danger">*</span></label>
							<select name="student_semestr" id="student_semestr"<span>*</span>
								<option value="semestr letni 2022/2023">semestr letni 2022/2023</option>
								<option value="semestr zimowy 2022/2023">semestr zimowy 2022/2023</option>
							</select>
						</div>
						<div class="form-group">
							<label>Adres Zamieszkania</label>
							<textarea name="student_adres" id="student_adres" class="form-control" data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="student_register" />
							<input type="submit" name="student_register_button" id="student_register_button" class="btn btn-primary" value="Zarejestruj" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Zaloguj</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

const pattern = /^\d{2}\s*[A-Z]{3}-[A-Z]{3}-[A-Z]{2}$/
const pattern2 = /^\d{2}\s*[A-Z]{3}-[A-Z]{2}$/


// function to register the student form
$(document).ready(function(){

	$('#student_data_urodzenia').datepicker({
		language: "pl",
        format: "yyyy-mm-dd",
        autoclose: true
    });

	$('#student_register_form').parsley();

	$('#student_register_form').on('submit', function(event){
	event.preventDefault()
	checkStudentGrDziek(event, this);
});

function checkStudentGrDziek(event, form) {
  const student_gr_dziek_value = $("#student_gr_dziek").val();
  if(!pattern.test(student_gr_dziek_value) && !pattern2.test(student_gr_dziek_value)){
    //show error message
    $("#error-message").show();
    $("#error-message").text("To pole wymaga specjalnego formatu np. '41 INF-ISM-NP' lub '11 INF-NP'");
    event.preventDefault();
	return false;
  } else {
    $("#error-message").hide();
    //form validation passed
	 if($(form).parsley().isValid())
	{
		$.ajax({
			url:"akcja.php",
			method:"POST",
			data:$(form).serialize(),
			dataType:'json',
			beforeSend:function(){
				$('#student_register_button').attr('disabled', 'disabled');
			},
			success:function(data)
			{
				$('#student_register_button').attr('disabled', false);
				if(data.error !== '')
				{
					$('#message').html(data.error);
				}
				if(data.success != '')
				{
					$('#message').html(data.success);
					$error = '<div class="alert alert-danger">Coś poszło nie tak :(</div>';
				}
			}
		});
	}
  }
}
  });
</script>