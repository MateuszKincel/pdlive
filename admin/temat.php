<?php

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
									<?php
									if($_SESSION["type"] == "Admin") {?>
									<label style="padding-right: 10px">Przydziel liczbę tematów:  </label>
									<button type="button" name="przydzial_button" id="przydzial_button" class="btn btn-danger btn-square "><i class="fas fa-plus"></i></button>

									<?php } else if ($_SESSION["type"] == "Promotor") { 
									?>
									<label style="padding-right: 10px">Dodaj temat:  </label>
									<button type="button" name="add_temat" id="add_temat" class="btn btn-success btn-square btn-lg"><i class="fas fa-plus"></i></button>
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

<div id="editModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Edycja Liczby Tematów</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="temat_liczba">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>


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
        		<div class="modal-footer">
          			<input type="hidden" name="action" id="action" value="Przydziel" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Przydziel" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>
<?php } ?>

<?php if($_SESSION["type"] == "Promotor" ) {  ?>
<div id="tematModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="temat_form">
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
                                <label>Cel i Zakres Pracy <span class="text-danger">*</span></label>
                                <input type="text" name="cel_zakres" id="cel_zakres" class="form-control" />
                            </div>
                        </div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" value="<?= $_SESSION['admin_id'] ?>"/>
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Dodaj" />
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
		},
		error: function(xhr, status, error){
		console.log(xhr);
		console.log(status);
		console.log(error);
		}
	});
}

function updateTopicsTotal() {
  $.ajax({
    type: "GET",
    url: "topics_total.php",
    success: function(data) {
      $("#header").text(data);
	  console.log(data);
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
	
		
	



$(document).on('click', '.edit_button', function(){
    var promotor_id = $(this).data('id');

    $.ajax({
        url:"temat_akcja.php",
        method:"POST",
        data:{promotor_id:promotor_id, action:'edit_single'},
        dataType:'JSON',
        success:function(data)
        {
            var html = '<div class="table-responsive">';
            html += '<table class="table">';
            html += '<tr><th width="40%" class="text-right">Temat '+data.promotor_liczba_tematow+':</th><td width="60%"><input type="text" id="temat_input" value="'+data.promotor_liczba_tematow+'"></td></tr>';
            html += '</table></div>';

            $('#editModal').modal('show');
            $('#temat_details').html(html);
        }
    });
});


$(document).on('click', '.edit_button', function(){
    var promotor_id = $(this).data('id');

    $.ajax({
        url:"temat_akcja.php",
        method:"POST",
        data:{promotor_id:promotor_id, action:'edit_single'},
        dataType:'JSON',
        success:function(data)
        {
			console.log(data)
            var html = '<div class="table-responsive">';
            html += '<table class="table">';
            html += '<tr><th width="40%" class="text-right">Temat '+data.promotor_liczba_tematow+':</th><td width="60%"><input type="text" id="temat_input" value="'+data.promotor_liczba_tematow+'"></td></tr>';
            html += '</table></div>';

            $('#editModal').modal('show');
            $('#temat_liczba').html(html);

            // Add submit button and bind event handler
            var submitBtn = '<button type="button" class="btn btn-primary" id="submit_btn">Zapisz</button>';
            $('.modal-footer').append(submitBtn);
            $('#submit_btn').on('click', function() {
                var newTematValue = $('#temat_input').val();
                updateTematValue(promotor_id, newTematValue);
            });
        }
    });
});

function updateTematValue(promotor_id, newTematValue) {
    $.ajax({
        url: "temat_akcja.php",
        method: "POST",
        data: {promotor_id: promotor_id, new_temat_value: newTematValue, action: 'update_single'},
        dataType: 'JSON',
        success: function(data) {
            // Handle success here
            $('#editModal').modal('hide');
        }
    });
}




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
					$('#submit_button').val('wait...');
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



$('#przydzial_button').click(function(){
  console.log("przydzialModal");
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
  console.log("Submitting form...");

  if($('#przydzial_form').parsley().isValid()) {
    $.ajax({
      url: "temat_akcja.php",
      method: "POST",
      data: new FormData(this),
      dataType: "json",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        console.log("Before send...");
        $("#submit_button").attr("disabled", "disabled");
        $("#submit_button").val("czekaj...");
      },
      success: function(data) {
        console.log("Success...");
        console.log(data);
        $("#submit_button").prop("disabled", false);
        $("#submit_button").val("Przydziel");

        if(data.success) {
          console.log("Before hiding modal...");
          $("#przydzialModal").modal("hide");
          console.log("After hiding modal...");

          $("#message").html(data.success);
          dataTable.ajax.reload();

		  setTimeout(function() {
            $("#message").html("");
          }, 5000);
        } else {
          console.log("przydzialModal ERROR");
          $("#form_message").html(data.error);
          $("#submit_button").val("Przydziel");
        }
      }
    });
  }
});

}); // datatable end bracket
</script>


