<?php

//praca.php

include('../class/handler_admin.php');

$object = new Handler;

if(!isset($_SESSION['admin_id']))
{
    header('location:'.$object->base_url.'');
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Prace Dyplomowe</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col-sm-6">
                            		<h6 class="m-0 font-weight-bold text-primary">Lista Prac</h6>
                            	</div>
                            	<div class="col-sm-6" align="right">
                                    <div class="row">
                                        <div class="col-md-9">
                                        </div>
                                    </div>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="pd">
                                    <thead>
                                        <tr>
                                            <th>Nr. Pracy</th>
                                            <th>Student</th>
                                            <?php
                                            if($_SESSION['type'] == 'Admin')
                                            {
                                            ?>
                                            <th>Promotor</th>
                                            <?php
                                            }
                                            ?>
                                            <th>Temat Pracy</th>
                                            <th>Status</th>
                                            <th>Akcja</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="edit_praca_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Podgląd Pracy</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="praca_details"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_praca_id" id="hidden_praca_id" />
                    <input type="hidden" name="hidden_student_id" id="hidden_praca_id" />
                    <input type="hidden" name="action" id="action" value="change_praca_status" />
                      <?php
                            if($_SESSION['type'] == 'Promotor')
                            {
                         ?>
                         <input type="submit" name="save_praca" id="save_praca" class="btn btn-primary"  value="Zapisz" />
                        <?php
                            }
                         ?>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="edit_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Edytuj Dane Pracy</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Temat Pracy <span class="text-danger">*</span></label>
                                <input type="text" name="temat_pracy" id="temat_pracy" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                            <div class="col-md-6">
                                <label>Temat Pracy (Ang.) <span class="text-danger">*</span></label>
                                <input type="text" name="temat_pracy_ang" id="temat_pracy_ang" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
		          		</div>
		          	</div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Cel i Zakres <span class="text-danger">*</span></label>
                                <input type="text" name="cel_zakres_pracy" id="cel_zakres_pracy" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Recenzent</label>
                                <input type="text" name="recenzent" id="recenzent" class="form-control" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
                    <input type="hidden" name="action" id="action" class="btn btn-success" value="Edit" />
          			<input type="submit" name="submit" id="submit_button_edit" class="btn btn-success" value="" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<div id="multiUseModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="multiuseform">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Wyslij Powiadomienie</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea name="wiadomosc_promotora" id="wiadomosc_promotora" rows="3" class="form-control" required ></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_praca_id" id="hidden_praca_id" />
                    <input type="hidden" name="action" id="action" value="send_email" />
                    <input type="submit" name="submit_button" id="submit_button" class="btn btn-primary" value="Wyslij" />
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

	 dataTable = $('#pd').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"praca_akcja.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
				"targets":[5],
                <?php
               }
               else
               {
                ?>
                "targets":[4],
                <?php
               }
                ?>
				"orderable":false,
			},
		],
	});

   //przesłanie formularza 
    $('#edit_form').parsley();

	$('#edit_form').on('submit', function(event){
		event.preventDefault();
		if($('#edit_form').parsley().isValid())
		{		
			$.ajax({
				url:"praca_akcja.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button_edit').attr('disabled', 'disabled');
					$('#submit_button_edit').val('czekaj...');
				},
				success:function(data)
				{
					$('#submit_button_edit').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button_edit').val('Edit');
					}
					else
					{
						$('#editModal').modal('hide');
						$('#message').html(data.success);
						dataTable.ajax.reload();

						setTimeout(function(){

				            $('#message').html('');

				        }, 5000);
					}
				}
			})
		}
	});
    //po kliknięciu edytuj
    $(document).on('click', '.edit_button', function(){

		var pd_id = $(this).data('id');

		$('#edit_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"praca_akcja.php",

	      	method:"POST",

	      	data:{pd_id:pd_id, action:'fetch_edit'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

                $('#temat_pracy').val(data.temat_pracy);
                $('#temat_pracy_ang').val(data.temat_pracy_ang);
                $('#cel_zakres_pracy').val(data.cel_zakres_pracy);
                $('#recenzent').val(data.cel_zakres_pracy);
                

	        	$('#action').val('Edit');

	        	$('#submit_button_edit').val('Edytuj');

	        	$('#editModal').modal('show');

	        	$('#hidden_id').val(pd_id);

	      	}

	    })

	});

    $(document).on('click', '.view_button', function(){

        var pd_id = $(this).data('id');

        $.ajax({

            url:"praca_akcja.php",

            method:"POST",

            data:{pd_id:pd_id, action:'fetch_single'},

            success:function(data)
            {
                $('#viewModal').modal('show');

                $('#praca_details').html(data);

                $('#hidden_praca_id').val(pd_id);

            }

        })
    });

    


   $('#refresh').click(function(){
    $('#pd').DataTable().ajax.reload();
    });

    $('#edit_praca_form').parsley();

    $('#edit_praca_form').on('submit', function(event){
        event.preventDefault();
        if($('#edit_praca_form').parsley().isValid())
        {       
            $.ajax({
                url:"praca_akcja.php",
                method:"POST",
                data: $(this).serialize(),
                beforeSend:function()
                {
                    $('#save_praca').attr('disabled', 'disabled');
                    $('#save_praca').val('czekaj...');
                },
                success:function(data)
                {
                    $('#save_praca').attr('disabled', false);
                    $('#save_praca').val('Zapisz');
                    $('#viewModal').modal('hide');
                    $('#message').html(data);
                    $('#pd').DataTable().ajax.reload();
                    
                    setTimeout(function(){
                        $('#message').html('');
                    }, 5000);
                }
            })
        }
    });

});

$(document).on('click', '.delete_button, .email_button, .reject_button', function(){
    var pd_id = $(this).data('id');
    var action = $(this).data('action');

    $('#hidden_praca_id').val(pd_id);
    $('#action').val(action);

    switch(action) {
        case 'delete-pd':
            var confirm_message = "Czy na pewno usunąć pracę?";
            break;
        case 'reject-pd':
            var confirm_message = "Czy na pewno odrzucić pracę?";
            break;
        case 'notify-pd':
            var confirm_message = "Czy na pewno wysłać powiadomienie?";
            break;
        case 'edit-pd':
            var confirm_message = "Czy na pewno edytować pracę?";
            break;temat_pracy_ang
    }

    if(confirm(confirm_message)){
        $('#multiUseModal').modal('show');
    }
});

$('#multiuseform').on('submit', function(event){
    event.preventDefault(); // prevent default form submission
    var pd_id = $('#hidden_praca_id').val(); // retrieve value of hidden input
    var wiadomosc_promotora = $('#wiadomosc_promotora').val(); // retrieve value of textarea
    var action = $('#action').val();

    $.ajax({
        url: "praca_akcja.php",
        method: "POST",
        data:{hidden_praca_id:pd_id,wiadomosc_promotora:wiadomosc_promotora, action: action},
        beforeSend:function()
                {
                    $('#submit_button').attr('disabled', 'disabled');
                    $('#submit_button').val('czekaj...');
                },
        success: function(data){
                    $('#submit_button').attr('disabled', false);
                    $('#submit_button').val('Zapisz');
                    $('#multiUseModal').modal('hide');
                    $('#message').html(data);
                    $('#pd').DataTable().ajax.reload();
            setTimeout(function(){
                $('#message').html('');
            }, 5000);
        }
    });
});

</script>