<?php

//panel.php



include('class/handler.php');

$object = new Handler;

include('header.php');

?>

<div class="container-fluid">
	<?php
	include('navbar.php');
	?>
	<br />
	<div class="card">
		<div class="card-header"><h4>Zarezerwuj swój własny tematu u wybranego promotora.</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="pd_list_table">
		      			<thead>
			      			<tr>
			      				<th>Promotor</th> 
								<th>Wydział</th>
								<th>Specjalizacja</th> 
								<th>Telefon</th> 
			      				<th>E-mail</th> 
			      				<th>Status</th> 
			      				<th>Akcja</th>
			      			</tr>
			      		</thead>
			      		<tbody></tbody>
			      	</table>
			    </div>
			</div>
		</div>
	</div>

</div>

<?php

include('footer.php');

?>

<div id="pracaModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="praca_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Rezerwuj Promotora</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div id="praca_detail"></div>
                    <div class="form-group">
                    	<label><b>Proponowany Temat Pracy</b></label>
                    	<textarea name="temat_pracy" id="temat_pracy" class="form-control" required rows="1"></textarea>
                    </div>
					<div class="form-group">
                    	<label><b>Temat Pracy w Języku Angielskim</b></label>
                    	<textarea name="temat_pracy_ang" id="temat_pracy_ang" class="form-control" required rows="1"></textarea>
                    </div>
					<div class="form-group">
                    	<label><b>Cel i Zakres Pracy</b></label>
                    	<textarea name="cel_zakres_pracy" id="cel_zakres_pracy" class="form-control" required rows="1"></textarea>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="promotor_id_ukryte" id="promotor_id_ukryte" />
          			<input type="hidden" name="action" id="action" value="rezerwuj_prace" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Zarezerwuj" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<script>

$(document).ready(function(){

	var dataTable = $('#pd_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"akcja.php",
			type:"POST",
			data:{action:'fetch_schedule'}
		},
		"columnDefs":[
			{
                "targets":[5],				
				"orderable":false,
			},
		],
	});

	$(document).on('click', '.get_praca', function(){

		var promotor_status = $(this).data('promotor_status');
		var promotor_id = $(this).data('promotor_id');
		var id = $(this).data('id');

		$.ajax({
			url:"akcja.php",
			method:"POST",
			data:{action:'pd_rezerw', promotor_status:promotor_status, promotor_id:promotor_id},
			success:function(data)
			{
				$('#pracaModal').modal('show');
				$('#promotor_id_ukryte').val(promotor_id);
				$('#id').val(id);
				$('#praca_detail').html(data);
			}
		});

	});

	$('#praca_form').parsley();

	$('#praca_form').on('submit', function(event){

		event.preventDefault();

		if($('#praca_form').parsley().isValid())
		{

			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('czekaj...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					$('#submit_button').val('Zarezerwuj');
					if(data.error != '')
					{
						$('#form_message').html(data.error);
					}
					else
					{	
						window.location.href="praca.php";
					}
				}
			})

		}

	})

});

</script>