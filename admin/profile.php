<?php

include('../class/handler_admin.php');

$object = new Handler;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}


$object->query = "
SELECT * FROM admin
WHERE admin_id = '".$_SESSION["admin_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Profil</h1>

                    <!-- DataTales Example -->
                    
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-8"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary">Dane Profilu</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="admin_profile" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edytuj</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<div class="row">
                                    <div class="col-md-6">!-->
                                        <div class="form-group">
                                            <label>Imię i Nazwisko</label>
                                            <input type="text" name="admin_nazwa" id="admin_nazwa" class="form-control" required data-parsley-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-maxlength="175" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Adres Email</label>
                                            <input type="text" name="admin_adres_email" id="admin_adres_email" class="form-control" required  data-parsley-type="email" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Hasło</label>
                                            <input type="password" name="admin_haslo" id="admin_haslo" class="form-control" required data-parsley-maxlength="16" data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Nazwa Przychodni</label>
                                            <input type="text" name="nazwa_przychodni" id="nazwa_przychodni" class="form-control" required  data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Adres Przychodni</label>
                                            <textarea name="adres_przychodni" id="adres_przychodni" class="form-control" required ></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Nr. Telefonu Przychodni</label>
                                            <input type="text" name="telefon_przychodni" id="telefon_przychodni" class="form-control" required  data-parsley-trigger="keyup" />
                                        </div>
                                        <div class="form-group">
                                            <label>Logo Przychodni</label><br />
                                            <input type="file" name="logo_przychodni" id="logo_przychodni" />
                                            <span id="uploaded_hospital_logo"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></div></div>
                    </form>
                <?php
                include('footer.php');
                ?>

<script>
$(document).ready(function(){

    <?php
    foreach($result as $row)
    {
    ?>
    $('#admin_adres_email').val("<?php echo $row['admin_adres_email']; ?>");
    $('#admin_haslo').val("<?php echo $row['admin_haslo']; ?>");
    $('#admin_nazwa').val("<?php echo $row['admin_nazwa']; ?>");
    $('#nazwa_przychodni').val("<?php echo $row['nazwa_przychodni']; ?>");
    $('#adres_przychodni').val("<?php echo $row['adres_przychodni']; ?>");
    $('#telefon_przychodni').val("<?php echo $row['telefon_przychodni']; ?>");
    <?php
        if($row['logo_przychodni'] != '')
        {
    ?>
    $("#uploaded_hospital_logo").html("<img src='<?php echo $row["logo_przychodni"]; ?>' class='img-thumbnail' width='100' /><input type='hidden' name='hidden_hospital_logo' value='<?php echo $row['logo_przychodni']; ?>' />");

    <?php
        }
        else
        {
    ?>
    $("#uploaded_hospital_logo").html("<input type='hidden' name='hidden_hospital_logo' value='' />");
    <?php
        }
    }
    ?>

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
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    if(data.error != '')
                    {
                        $('#message').html(data.error);
                    }
                    else
                    {

                        $('#admin_adres_email').val(data.admin_adres_email);
                        $('#admin_haslo').val(data.admin_haslo);
                        $('#admin_nazwa').val(data.admin_nazwa);

                        $('#nazwa_przychodni').val(data.nazwa_przychodni);
                        $('#adres_przychodni').val(data.adres_przychodni);
                        $('#telefon_przychodni').val(data.telefon_przychodni);

                        if(data.logo_przychodni != '')
                        {
                            $("#uploaded_hospital_logo").html("<img src='"+data.logo_przychodni+"' class='img-thumbnail' width='100' /><input type='hidden' name='hidden_hospital_logo' value='"+data.logo_przychodni+"'");
                        }
                        else
                        {
                            $("#uploaded_hospital_logo").html("<input type='hidden' name='hidden_hospital_logo' value='"+data.logo_przychodni+"'");
                        }

                        $('#message').html(data.success);

    					setTimeout(function(){

    				        $('#message').html('');

    				    }, 5000);
                    }
				}
			})
		}
	});

});
</script>