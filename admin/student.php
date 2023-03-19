<?php

//student.php

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
                    <h1 class="h3 mb-4 text-gray-800">Student</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Lista Studentów</h6>
                                    
                            	</div>
                            	<div class="col" align="right">
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="student" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Imię i Nazwisko</th>
                                            <th>Semestr</th>
                                            <th>Grupa</th>
                                            <th>Podgrupa</th>
                                            <th>Adres Email</th>
                                            <th>Nr. Telefonu</th>
                                            <th>Weryfikacja Email</th>
                                            <th>Akcja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>


                <?php
                include('footer.php');
                ?>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Dane Studenta</h4>
                <button type="button" class="close" vlaue= "sendMail" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="student_details">
                
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>


	
    $(document).ready(function(){
        $('#emailButton').click(function(){
            $.ajax({
                url:"student_akcja.php",
                method:"POST",
                data:{action:'sendMail'},
                success:function(data)
                {
                    $('#message').html(data);
                }
            });
        });
    });

	$('#mailForm').on('submit', function(event){
	sendEmailRegister(event, this);
});




$(document).ready(function(){

	var dataTable = $('#student').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"student_akcja.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[7],
				"orderable":false,
			},
		],
	});

    $(document).on('click', '.view_button', function(){

        var student_id = $(this).data('id');

        $.ajax({

            url:"student_akcja.php",

            method:"POST",

            data:{student_id:student_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><th width="40%" class="text-right">Adres Email</th><td width="60%">'+data.student_adres_email+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Hasło</th><td width="60%">'+data.student_haslo+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Imię i Nazwisko</th><td width="60%">'+data.student_imie+' '+data.student_nazwisko+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Nr Indeksu</th><td width="60%">'+data.student_nr_indeksu+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Semestr</th><td width="60%">'+data.student_semestr+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Wydział</th><td width="60%">'+data.student_wydzial+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Grupa</th><td width="60%">'+data.student_gr_dziek+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Podgrupa</th><td width="60%">'+data.student_podgrupa+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Nr. Telefonu</th><td width="60%">'+data.student_telefon+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Adres</th><td width="60%">'+data.student_adres+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Data Urodzenia</th><td width="60%">'+data.student_data_urodzenia+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Weryfikacji Email</th><td width="60%">'+data.weryfikacja_email+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#student_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Czy na pewno usunąć studenta?"))
    	{

      		$.ajax({

        		url:"student_akcja.php",

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