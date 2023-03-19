<?php

//promotor.php

include('../class/handler_admin.php');

$object = new Handler;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Zarządzanie Promotorami</h1>

                    <!-- DataTables Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Lista Promotory</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_promotor" id="add_promotor" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="promotor" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Adres Email</th>
                                            <th>Imię i Nazwisko</th>
                                            <th>Nr. Telefonu Promotora</th>
											<th>Wydział</th>
                                            <th>Specjalizacja</th>
                                            <th>Status</th>
                                            <th>Akcja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="promotorModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="promotor_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Dodaj Promotora</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Adres Email <span class="text-danger">*</span></label>
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
                                <label>Wydział <span class="text-danger">*</span></label>
                                <input type="text" name="promotor_wydzial" id="promotor_wydzial" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
							<div class="col-md-6">
                                <label>Specjalizacja <span class="text-danger">*</span></label>
                                <input type="text" name="promotor_specjalizacja" id="promotor_specjalizacja" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Dodaj" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Podgląd Danych Promotora</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="promotor_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#promotor').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"promotor_akcja.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[5,6],
				"orderable":false,
			},
		],
	});

    $('#promotor_data_urodzenia').datepicker({
		language: "pl",
        format: "yyyy-mm-dd",
        autoclose: true
    });

	$('#add_promotor').click(function(){
		
		$('#promotor_form')[0].reset();

		$('#promotor_form').parsley().reset();

    	$('#modal_title').text('Dodaj Promotora');

    	$('#action').val('Add');

    	$('#submit_button').val('Dodaj');

    	$('#promotorModal').modal('show');

    	$('#form_message').html('');

	});

	$('#promotor_form').parsley();

	$('#promotor_form').on('submit', function(event){
		event.preventDefault();
		if($('#promotor_form').parsley().isValid())
		{		
			$.ajax({
				url:"promotor_akcja.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('czekaj...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#promotorModal').modal('hide');
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

	$(document).on('click', '.edit_button', function(){

		var promotor_id = $(this).data('id');

		$('#promotor_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"promotor_akcja.php",

	      	method:"POST",

	      	data:{promotor_id:promotor_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

                $('#promotor_adres_email').val(data.promotor_adres_email);
                $('#promotor_haslo').val(data.promotor_haslo);
                $('#promotor_nazwa').val(data.promotor_nazwa);
                $('#promotor_telefon').val(data.promotor_telefon);
                $('#promotor_data_urodzenia').val(data.promotor_data_urodzenia);
                $('#promotor_wydzial').val(data.promotor_wydzial);
				$('#promotor_specjalizacja').val(data.promotor_specjalizacja);

	        	$('#modal_title').text('Edytuj Dane Promotora');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edytuj');

	        	$('#promotorModal').modal('show');

	        	$('#hidden_id').val(promotor_id);

	      	}

	    })

	});

	$(document).on('click', '.status_button', function(){
		var id = $(this).data('id');
    	var status = $(this).data('status');
		var next_status = 'Aktywny';
		if(status == 'Aktywny')
		{
			next_status = 'Nie Aktywny';
		}
		if(confirm("Czy na pewno chcesz zmienić status?"))
    	{

      		$.ajax({

        		url:"promotor_akcja.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}
	});

    $(document).on('click', '.view_button', function(){
        var promotor_id = $(this).data('id');

        $.ajax({

            url:"promotor_akcja.php",

            method:"POST",

            data:{promotor_id:promotor_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><th width="40%" class="text-right">Adres Email</th><td width="60%">'+data.promotor_adres_email+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Imię i Nazwisko</th><td width="60%">'+data.promotor_nazwa+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Nr. Telefonu</th><td width="60%">'+data.promotor_telefon+'</td></tr>';

				html += '<tr><th width="40%" class="text-right">Wydział</th><td width="60%">'+data.promotor_wydzial+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Specjalizacja</th><td width="60%">'+data.promotor_specjalizacja+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#promotor_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Czy na pewno chcesz usunąć?"))
    	{

      		$.ajax({

        		url:"promotor_akcja.php",

        		method:"POST",

        		data:{id:id, action:'delete'},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}

  	});



});
</script>