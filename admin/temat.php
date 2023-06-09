<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//tematy_admin.php

include('../class/handler_admin.php');

$object = new Handler;



if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if($_SESSION['type'] != 'Admin' && $_SESSION['type'] != 'Promotor')
{
    header("location:".$object->base_url."");
}


include('header.php');

?>

                    <!-- Page Heading -->
                    
					<?php if($_SESSION["type"] == "Admin") {?>
								<div class="col">
                            		<h1 class="h3 mb-4 text-gray-800">Przydzielanie liczby tematów</h1>
                            	</div>
								<?php } else if ($_SESSION["type"] == "Promotor") { ?>
                            	<div class="col">
                            		<h1 class="h3 mb-4 text-gray-800">Tematy prac dyplomowych</h1>
                            	</div>
								<?php } ?>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
								<?php if($_SESSION["type"] == "Admin") {?>
								<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Przydzielone tematy: <span id="header"></span></h6>
                            	</div>
								<?php } else if ($_SESSION["type"] == "Promotor") { ?>
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Lista Tematów</h6>
                            	</div>
								<?php } ?>
                            	<div class="col" align="right">
									<?php if($_SESSION["type"] == "Admin") { ?>
										<label style="padding-right: 10px;">Przydziel liczbę tematów:</label>
										<button type="button" name="przydzial_button" id="przydzial_button" class="btn btn-danger btn-sm mr-2"><i class="fas fa-plus"></i></button>
									<?php } ?> <?php if($_SESSION["type"] == "Promotor") { ?>
									<label style="padding-right: 10px;">Dodaj temat:</label>
									<button type="button" name="add_temat" id="add_temat" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
									<?php } ?>									
								</div> 
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="temat_table" class="display compact" width="display" cellspacing="0">
                                    <thead>
                                        <tr>
											<?php if($_SESSION["type"] == "Admin") {?>
											<th>Promotor</th>
                                            <th>Przydzielona liczba tematów</th>
											<th>Akcja</th>
											<?php } else if ($_SESSION["type"] == "Promotor") { ?>

											<th>Grupa</th>
                                            <th>Temat</th>
											<th>Cel i Zakres</th>
											<th>Semestr</th>
											<th>Dostępność</th>
											<th>Akcja</th>
											<?php } ?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>


<?php if($_SESSION["type"] == "Admin" ) { ?>
<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Podgląd Tematów</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="temat_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>
</div>
<?php } ?>

<?php if($_SESSION["type"] == "Admin" ) { ?>
<div id="przydzialModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="przydzial_form">
      		<div class="modal-content">
        		<div class="modal-header">
					<div class="header-group">
						<h4 class="modal-title" id="modal_title">Przydział liczby tematów</h4>
						<h6 class="modal-summary" id="modal_summary">Podziel liczbę tematów na liczbę promotorów.</h6>
					</div>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
                        <div class="row">
                           <div class="col-md-12">
								<label>Liczba tematów <span class="text-danger">*</span></label>
								<input type="number" name="liczba_tematow" id="liczba_tematow" class="form-control" />
							</div>
                        </div>
					</div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Przydziel" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Przydziel" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>
<?php } ?>


<?php if($_SESSION["type"] == "Admin" ) { ?>
<div id="adminTematModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="temat_form_admin">
      		<div class="modal-content">
        		<div class="modal-header">
          			<div class="header-group">
						<h4 class="modal-title" id="modal_title">Dodaj tematy</h4>
						<h6 class='modal-summary' id='modal_summary'></h6>
					</div>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Semestr<span class="text-danger" >*</span></label>
                                <input type="text" name="temat_semestr" id="temat_semestr" class="form-control" placeholder="np: semestr zimowy 2022/2023 " />
                            </div>
							<div class="col-md-12">
                                <label>Grupa<span class="text-danger" >*</span></label>
                                <input type="text" name="temat_grupa" id="temat_grupa" class="form-control" placeholder="np: 41 INF-ISM-NP / 11 INF-SP..." />
                            </div>
							<div class="col-md-12">
                                <label>Temat Pracy <span class="text-danger">*</span></label>
                                <input type="text" name="temat" id="temat" class="form-control" />
                            </div>
							<div class="col-md-12">
                                <label>Temat Pracy j. ang. <span class="text-danger">*</span></label>
                                <input type="text" name="temat_ang" id="temat_ang" class="form-control" />
                            </div>
							<div class="col-md-12">
                                <label>Cel i Zakres Pracy <span class="text-danger">*</span></label>
                                <input type="text" name="cel_zakres" id="cel_zakres" class="form-control" />
                            </div>
                        </div>
        		<div class="modal-footer">
          			<input type="hidden" name="promotor_id" id="promotor_id" value="" />
					<input type="hidden" name="action" id="action" value="Add_admin" />
					<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Zapisz" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
  		</div>
	</form>
</div>
</div>
</div>
<?php } ?>

<?php if($_SESSION["type"] == "Admin" ) { ?>
<div id="editModal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="edit_form">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title">Edytuj Liczbę Tematów</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <span id="form_message"></span>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <label>Liczba tematów <span class="text-danger">*</span></label>
                <input type="number" name="promotor_liczba_tematow" id="promotor_liczba_tematow" class="form-control" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="action" id="action" value="update_single" />
          <input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Zapisz" />
          <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php } ?>

<script>




//updateModalSummary() function
function updateModalSummary() {
	$.ajax({
		url: 'topics_left.php',
		type: 'GET',
		success: function(result) {
			console.log(result);
			
			var data = JSON.parse(result)
			// var data = result;
    		if (data) {
				console.log(typeof data.promotor_liczba_tematow);
                console.log(typeof data.dodane_tematy);
        		$('#modal_summary').text("Ilość tematów do dodania: " + (parseInt(data.promotor_liczba_tematow) - parseInt(data.dodane_tematy)) + "");
    		} else {
        		console.log("error");
    		}
		}
	});
}

function updateTopicsTotal() {
  $.ajax({
    type: "GET",
    url: "topics_total.php",
    success: function(data) {
      $("#header").text(data);
    }
  });
}



$(document).ready(function(){
	updateTopicsTotal();
	var dataTable = $('#temat_table').DataTable({
			"processing" : true,
			"serverSide" : true,
			"order" : [],
			"ajax" : {
				url:"temat_akcja.php",
				type:"POST",
				data:{action:'fetch'}
				
			},
			"columnDefs":[
				{
					"targets":[],
					"orderable":false,
				},
			],
			
		});

$(document).on('click', '.view_button', function(){
    var promotor_id = $(this).data('id');

    $.ajax({
        url:"temat_akcja.php",
        method:"POST",
        data:{promotor_id:promotor_id, action:'fetch_single'},
        dataType:'JSON',
        success:function(data)
        {
            var html = '<div class="table-responsive">';
            html += '<table class="table">';

            for(var i = 0; i < data.length; i++) {
                html += '<tr><th width="40%" class="text-right">Temat '+(i+1)+':</th><td width="60%">'+data[i].temat+'</td>' +
                '<td><button type="button" action="delete_temat" name="delete_button" class="btn btn-danger btn-circle delete_button" data-id="'+data[i].temat_id+'" title="Usuń"><i class="fa fa-times"></i></button></td></tr>' +
                '<tr><th width="40%" class="text-right">Semestr tematu '+(i+1)+':</th><td width="60%">'+data[i].temat_semestr+'</td></tr>';
            }

            html += '</table></div>';

            $('#viewModal').modal('show');

            $('#temat_details').html(html);
        }
    });
});

$(document).on('click', '.delete_button', function(){
    var id = $(this).data('id');
    if(confirm("Usunąć temat?"))
    {
        $.ajax({
            url:"temat_akcja.php",
            method:"POST",
            data: {id: id, action: 'delete_temat'},
            success:function(data)
            {
                $('#message').html(data);
                $('#viewModal').modal('hide'); // Hide the modal
                dataTable.ajax.reload();
                setTimeout(function(){
                    $('#message').html('');
                }, 5000);
            }
        })
    }
});
	
   $('#add_temat').click(function(){
		updateModalSummary();

		// $('#modal_summary').text("Możesz dodać jeszcze " + (result.promotor_liczba_tematow - result.dodane_tematy) + " temat/ów");

		$('#temat_form')[0].reset();

		$('#temat_form').parsley().reset();

    	$('#modal_title').text('Dodaj Temat');

    	$('#action').val('Add');

    	$('#submit_button').val('Dodaj');

    	$('#tematModal').modal('show');

    	$('#form_message').html('');


	});
    $('#temat_form').on('submit', function(event){
		event.preventDefault();
		if($('#temat_form').parsley().isValid())
		{		
			$.ajax({
				url:"temat_akcja.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('Czek..');
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
						$('#tematModal').modal('hide');
						$('#adminTematModal').modal('hide');
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


// When the add_topic_button is clicked
$(document).on('click', '.add_topic_button', function(){
	// Get the promotor_id value from the button
	var promotor_id = $(this).data('id');
	
	// Set the value of the hidden promotor_id input
	$('#promotor_id').val(promotor_id);
	
	// Reset the form and display the modal
	$('#temat_form_admin')[0].reset();
	$('#temat_form_admin').parsley().reset();
	$('#modal_title').text('Dodaj tematy');
	$('#submit_button').val('Zapisz');
	$('#adminTematModal').modal('show');
	$('#form_message').html('');
});

$('#temat_form_admin').on('submit', function(event){
	event.preventDefault();
	
	// Get the form data
	var formData = $(this).serialize();
	
	// Send an AJAX request to the server
	$.ajax({
		url: 'temat_akcja.php',
		type: 'POST',
		dataType: 'json',
		data: formData,
		beforeSend: function() {
			$('#submit_button').attr('disabled', 'disabled');
			$('#submit_button').val('Czekaj...');
		},
		success: function(response) {
			if (response.error) {
				$('#form_message').html(response.error);
				$('#submit_button').removeAttr('disabled');
				$('#submit_button').val('Zapisz');
			} else {
				$('#temat_form_admin')[0].reset();
				$('#temat_form_admin').parsley().reset();
				$('#adminTematModal').modal('hide');
				$('#message').html(response.success);
				dataTable.ajax.reload();

				setTimeout(function(){
		            $('#message').html('');
		        }, 5000);
			}
		},
		error: function(xhr, status, error) {
			console.log(xhr.responseText);
			console.log(status);
			console.log(error);
		},
		complete: function() {
			$('#submit_button').removeAttr('disabled');
			$('#submit_button').val('Zapisz');
		}
	});
});



$(document).on('click', '.edit_button', function(){
    var promotor_id = $(this).data("id");
    $('#form_message').html('');
    $.ajax({
        url:"temat_akcja.php",
        method:"POST",
        data:{promotor_id:promotor_id, action:'edit_single'},
        dataType:"json",
        success:function(data)
        {
            $('#editModal').modal('show');
            $('#promotor_liczba_tematow').val(data.promotor_liczba_tematow);
            $('#promotor_id').val(promotor_id);
            $('#modal_title').text('Edytuj Liczbę Tematów');
            $('#action').val('update_single');
            $('#submit_button').val('Zapisz');
			$('#form_message').html('');
        }
    })
});

$(document).on('submit', '#edit_form', function(event){
    event.preventDefault();

    var formData = $(this).serializeArray();
    formData.push({name: "promotor_id", value: $('#promotor_id').val()});

    $.ajax({
        url:"temat_akcja.php",
        method:"POST",
        data: formData,
        dataType:"json",
        success:function(data)
        {
            if(data.success)
            {
                $('#editModal').modal('hide');
                $('#form_message').html(data.success);
                setTimeout(function(){
                    $('#form_message').html('');
                }, 5000);
                // Reload the DataTable
                dataTable.ajax.reload();
				updateTopicsTotal();
            }
            else
            {
                
				$('#form_message').html(data.error);
            }
        }
    })
});


$('#przydzial_button').click(function(){
		console.log("przydzialModal")
		$('#przydzial_form')[0].reset();

		$('#przydzial_form').parsley().reset();

    	$('#modal_title').text('Przydziel liczbę tematów');

    	$('#action').val('Przydziel');

    	$('#submit_button').val('Przydziel');

    	$('#przydzialModal').modal('show');

    	$('#form_message').html('');

	});
    $('#przydzial_form').on('submit', function(event){
		event.preventDefault();
		if($('#przydzial_form').parsley().isValid())
		{		
			$.ajax({
				url:"temat_akcja.php",
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
					updateTopicsTotal();
					$('#submit_button').attr('disabled', false);
					$('#submit_button').val('Przydziel');
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Przydziel');
					}
					else
					{
						$('#przydzialModal').modal('hide');
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




});

</script>