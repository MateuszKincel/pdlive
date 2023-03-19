<?php

//promotor.php

include('../class/handler_admin.php');

$object = new Handler;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Harmonogram Promotora</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Lista Harmonogramów</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_exam" id="add_promotor_schedule" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="promotor_harmonogram" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php
                                            if($_SESSION['type'] == 'Admin')
                                            {
                                            ?>
                                            <th>Imię i Nazwisko Promotora</th>
                                            <?php
                                            }
                                            ?>
											
                                            <th>Data złożenia dokumentów </th>                                       
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

<div id="promotor_scheduleModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="promotor_schedule_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Dodaj Harmonogram Promotora</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <?php
                    if($_SESSION['type'] == 'Admin')
                    {
                    ?>
                    <div class="form-group">
                        <label>Wybierz Promotora</label>
                        <select name="promotor_id" id="promotor_id" class="form-control" required>
                            <option value="">Wybierz Promotora</option>
                            <?php
                            $object->query = "
                            SELECT * FROM promotor 
                            WHERE promotor_status = 'Aktywny' 
                            ORDER BY promotor_nazwa ASC;
                            ";

                            $result = $object->get_result();

                            foreach($result as $row)
                            {
                                echo '
                                <option value="'.$row["promotor_id"].'">'.$row["promotor_nazwa"].'</option>
                                ';
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <label>Data Harmonogramu</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" name="promotor_harmonogram_data" id="promotor_harmonogram_data" class="form-control" required readonly />
                        </div>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Dodaj" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

<script>
$(document).ready(function(){

	var dataTable = $('#promotor_harmonogram').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"promotor_harmonogram_akcja.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                "targets":[1, 3],
                <?php
                }
                else
                {
                ?>
                "targets":[1, 2],
                <?php
                }
                ?>
				
				"orderable":false,
			},
		],
	});

    var date = new Date();
    date.setDate(date.getDate());

    $('#promotor_harmonogram_data').datepicker({
        language: "pl",
        startDate: date,
        format: "yyyy-mm-dd",
        autoclose: true
    });

    // $('#promotor_harmonogram_start_czas').datetimepicker({
    //     format: 'HH:mm'
    // });

    // $('#promotor_harmonogram_koniec_czas').datetimepicker({
    //     useCurrent: false,
    //     format: 'HH:mm'
    // });

    // $("#promotor_harmonogram_start_czas").on("change.datetimepicker", function (e) {
    //     console.log('test');
    //     $('#promotor_harmonogram_koniec_czas').datetimepicker('minDate', e.date);
    // });

    // $("#promotor_harmonogram_koniec_czas").on("change.datetimepicker", function (e) {
    //     $('#promotor_harmonogram_start_czas').datetimepicker('maxDate', e.date);
    // });

	$('#add_promotor_schedule').click(function(){
		
		$('#promotor_schedule_form')[0].reset();

		$('#promotor_schedule_form').parsley().reset();

    	$('#modal_title').text('Dodaj Harmonogram Promotora');

    	$('#action').val('Add');

    	$('#submit_button').val('Dodaj');

    	$('#promotor_scheduleModal').modal('show');

    	$('#form_message').html('');

	});

	$('#promotor_schedule_form').parsley();

	$('#promotor_schedule_form').on('submit', function(event){
		event.preventDefault();
		if($('#promotor_schedule_form').parsley().isValid())
		{		
			$.ajax({
				url:"promotor_harmonogram_akcja.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
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
						$('#submit_button').val('Dodaj');
					}
					else
					{
						$('#promotor_scheduleModal').modal('hide');
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

		var promotor_harmonogram_id = $(this).data('id');

		$('#promotor_schedule_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"promotor_harmonogram_akcja.php",

	      	method:"POST",

	      	data:{promotor_harmonogram_id:promotor_harmonogram_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                $('#promotor_id').val(data.promotor_id);
                <?php
                }
                ?>
	        	$('#promotor_harmonogram_data').val(data.promotor_harmonogram_data);


	        	$('#modal_title').text('Edytuj Harmonogram Promotora');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edytuj');

	        	$('#promotor_scheduleModal').modal('show');

	        	$('#hidden_id').val(promotor_harmonogram_id);

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
		if(confirm("Czy na pewno zmienić status?"))
    	{

      		$.ajax({

        		url:"promotor_harmonogram_akcja.php",

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

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Usunąć harmonogram dokumentów?"))
    	{

      		$.ajax({

        		url:"promotor_harmonogram_akcja.php",

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