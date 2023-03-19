<?php

//praca.php



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
		<div class="card-header"><h4>Status Pracy Dyplomowej</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="pd_list_table">
		      			<thead>
			      			<tr>
			      				<th>Temat Pracy</th> 
			      				<th>Promotor</th> 
			      				<th>E-mail Promotora</th>
								<th>Komentarz Promotora</th>
								<th>Data zł. Dokumentów</th>  
			      				<th>Postęp Pracy</th> 
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



<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="view_praca_form">
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


 $('#view_praca_form').parsley();

    $('#view_praca_form').on('submit', function(event){
        event.preventDefault();
        if($('#view_praca_form').parsley().isValid())
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


  

$(document).ready(function(){

	var dataTable = $('#pd_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"akcja.php",
			type:"POST",
			data:{action:'fetch_praca'}
		},
		"columnDefs":[
			{
                "targets":[4,5],				
				"orderable":false,
				
			},
		],
	});

	$(document).on('click', '.cancel_praca', function(){
		var pd_id = $(this).data('id');
		if(confirm("Na pewno chcesz anulować wizytę?"))
		{
			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:{pd_id:pd_id, action:'cancel_praca'},
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

	$(document).on('click', '.download_praca', function(){
		var pd_id = $(this).data('id');
		{
			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:{pd_id:pd_id, action:'download_praca'},
				success:function(data)
				{
					$('#hidden_praca_id').val(pd_id);
					var file_name = JSON.parse(data).file_name;
					var download_url = "downloads/" + file_name;
					window.open(download_url, "_blank");

				}
			})
		}
	});
	



$(document).on('click', '.delete_praca', function(){
		var pd_id = $(this).data('id');
		if(confirm("Na pewno chcesz usunąć rezerwacje pracy?"))
		{
			$.ajax({
				url:"akcja.php",
				method:"POST",
				data:{pd_id:pd_id, action:'delete_praca'},
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

$(document).on('click', '.view_praca', function(){

        var pd_id = $(this).data('id');

        $.ajax({

            url:"akcja.php",

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

</script>