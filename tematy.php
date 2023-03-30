<?php
//tematy.php

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
		<span id="message"></span>
		<div class="card-header"><h4>Zerezerwuj temat udostępniony przez promotorów.</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="temat_list_table">
		      			<thead>
			      			<tr>
			      				<th>Temat Pracy</th>
								<th>Cel i Zakres</th>
								<th>Grupa</th>
								<th>Semestr</th>
								<th>Dostępność</th>
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

<div id="pracaModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="temat_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Rezerwuj Temat</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div id="praca_detail"></div>
					<!-- <div class="form-group">
						<label><b>Temat Pracy</b></label>
						<input id="temat_pracy" class="form-control" required readonly>
					</div> -->
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="temat_id" id="temat_id" value="" />
					<input type="hidden" name="temat_pracy" id="temat_pracy" />
					<input type="hidden" name="cel_zakres_pracy" id="cel_zakres_pracy" />
					<input type="hidden" name="promotor_id_ukryte" id="promotor_id_ukryte" />
          			<input type="hidden" name="action" id="action" value="rezerwuj_temat" />
          			<input type="submit" name="submit" id="temat_submit" class="btn btn-success" value="Zarezerwuj" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="view_temat_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Dane tematu</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="praca_details"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="temat_id_ukryte" id="temat_id_ukryte" />
					<input type="hidden" name="promotor_id_ukryte" id="promotor_id_ukryte" />
                    <input type="hidden" name="action" value="change_praca_status" />
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php

include('footer.php');

?>


<script>


$(document).ready(function(){
	
	var dataTable = $('#temat_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"akcja.php",
			type:"POST",
			data:{action:'fetch_temat'},
		},
		success: function(data){
console.log(data);
},
		"columnDefs":[
			{
                "targets":[0],				
				"orderable":true,
				
			},
			
		],
	});

 });


	$('#temat_form').parsley();

	$('#temat_form').on('submit', function(event){

		event.preventDefault();

		if($('#temat_form').parsley().isValid())
		{

			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#temat_submit').attr('disabled', 'disabled');
					$('#temat_submit').val('czekaj...');
				},
				success:function(data)
				{
					$('#temat_submit').attr('disabled', false);
					$('#temat_submit').val('Zarezerwuj');
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



	 $('#view_temat_form').parsley();

    $('#view_temat_form').on('submit', function(event){
        event.preventDefault();
        if($('#view_temat_form').parsley().isValid())
        {       
            $.ajax({
                url:"akcja.php",
                method:"POST",
                data: $(this).serialize(),
                
                success:function(data)
                {
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

	$(document).on('click', '.view_temat', function(){

        var temat_id = $(this).data('temat_id');

        $.ajax({

            url:"akcja.php",

            method:"POST",

            data:{ action:'fetch_single_temat',temat_id:temat_id },

            success:function(data)
            {
                $('#viewModal').modal('show');

                $('#praca_details').html(data);

                $('#temat_id_ukryte').val(temat_id);

            }

        })
    });




	$(document).on('click', '.get_praca', function(){

		var promotor_status = $(this).data('promotor_status');
		var promotor_id = $(this).data('promotor_id');
		var temat_id = $(this).data('temat_id');

		$.ajax({
			url:"akcja.php",
			method:"POST",
			data:{action:'temat_rezerw',  temat_id:temat_id, promotor_status:promotor_status, promotor_id:promotor_id},
			success:function(data)
			{
				$('#pracaModal').modal('show');
				$('#promotor_id_ukryte').val(promotor_id);
				$('#temat_id').val(temat_id);
				$('#praca_detail').html(data);
			}
		});

	});





</script>