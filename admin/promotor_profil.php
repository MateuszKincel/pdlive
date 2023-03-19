<?php

include('../class/handler_admin.php');

$object = new Handler;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Promotor')
{
    header("location:".$object->base_url."");
}

$object->query = "
    SELECT * FROM promotor
    WHERE promotor_id = '".$_SESSION["admin_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Profil</h1>

                    <!-- DataTales Example -->
                    
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-10"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary">Profil</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="promotor_profile" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edytuj</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<div class="row">
                                    <div class="col-md-6">!-->
                                        <span id="form_message"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Adres Email<span class="text-danger">*</span></label>
                                                    <input type="text" name="promotor_adres_email" id="promotor_adres_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Hasło <span class="text-danger">*</span></label>
                                                    <input type="password" name="promotor_haslo" id="promotor_haslo" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Imię i Nazwisko <span class="text-danger">*</span></label>
                                                    <input type="text" name="promotor_nazwa" id="promotor_nazwa" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Nr. Telefonu <span class="text-danger">*</span></label>
                                                    <input type="text" name="promotor_telefon" id="promotor_telefon" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <label>Specjalizacja <span class="text-danger">*</span></label>
                                                    <input type="text" name="promotor_wydzial" id="promotor_wydzial" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                    <!--</div>
                                </div>!-->
                            </div>
                        </div></div></div>
                    </form>
                <?php
                include('footer.php');
                ?>

<script>
$(document).ready(function(){

    $('#promotor_data_urodzenia').datepicker({
        language: "pl",
        format: "yyyy-mm-dd",
        autoclose: true
    });

    <?php
    foreach($result as $row)
    {
    ?>
    $('#hidden_id').val("<?php echo $row['promotor_id']; ?>");
    $('#promotor_adres_email').val("<?php echo $row['promotor_adres_email']; ?>");
    $('#promotor_haslo').val("<?php echo $row['promotor_haslo']; ?>");
    $('#promotor_nazwa').val("<?php echo $row['promotor_nazwa']; ?>");
    $('#promotor_telefon').val("<?php echo $row['promotor_telefon']; ?>");
    $('#promotor_wydzial').val("<?php echo $row['promotor_wydzial']; ?>");
    <?php
    }
    ?>

    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
		{		
			$.ajax({
				url:"profil_akcja.php",
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('czekaj...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', true);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#promotor_adres_email').val(data.promotor_adres_email);
                    $('#promotor_haslo').val(data.promotor_haslo);
                    $('#promotor_nazwa').val(data.promotor_nazwa);
                    $('#promotor_telefon').val(data.promotor_telefon);
                    $('#promotor_wydzial').text(data.promotor_wydzial);

                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

</script>