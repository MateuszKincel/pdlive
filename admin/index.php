
<?php
//handler_admin.php, which contains functions and/or variables that are used later in the script. The script also creates a new object called $object of class Handler.
include('../class/handler_admin.php');

$object = new Handler;

if ($object->is_login()) {
    if ($_SESSION['type'] == 'Admin') {
        header("location:".$object->admin_url."");
    } else if ($_SESSION['type'] == 'Promotor') {
        header("location:".$object->base_url."/admin/praca.php");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>System Rezerwacji i Prowadzenia Prac Dyplomowych</title>
    <title>E-Praca</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>

    <style>
    html,
    body {
      height: 100%;
    }

    body {
      display: flex;
      align-items: center;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }

    .form-signin {
      width: 100%;
      max-width: 330px;
      padding: 15px;
      margin: auto;
    }
    .form-signin .checkbox {
      font-weight: 400;
    }
    .form-signin .form-control {
      position: relative;
      box-sizing: border-box;
      height: auto;
      padding: 10px;
      font-size: 16px;
    }
    .form-signin .form-control:focus {
      z-index: 2;
    }
    .form-signin input[type="email"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
      margin-bottom: 10px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
    }
    </style>

</head>
    <body class="text-center">
        <main class="form-signin">
            <form method="post" id="login_form">
                <h1 class="h3 mb-3 fw-normal">System Rezerwacji i Prowadzenia Pracy Dyplomowych</h1>
                <span id="error"></span>
                <div class="form-group">
                    <input type="text" name="admin_adres_email" id="admin_adres_email" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" placeholder="Wpisz Adres Email..." />
                </div>
                <div class="form-group">
                    <input type="password" name="admin_haslo" id="admin_haslo" class="form-control" required  data-parsley-trigger="keyup" placeholder="Hasło..." />
                </div>
                <div class="form-group">
                    <button type="submit" name="login_button" id="login_button" class="btn btn-primary btn-user btn-block">Zaloguj</button>
                </div>
            </form>
        </main>



    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script type="text/javascript" src="../vendor/parsley/dist/parsley.min.js"></script>

</body>

</html>

<script>
//The script then checks whether the user is logged in by calling the is_login() method on the $object. If the user is logged in, 
//they are redirected to the base URL of the website.
$(document).ready(function(){

    $('#login_form').parsley();

    $('#login_form').on('submit', function(event){
        event.preventDefault();
        if($('#login_form').parsley().isValid())
        {       
            $.ajax({
                url:"login_akcja.php",
                method:"POST",
                data:$(this).serialize(),
                dataType:'json',
                beforeSend:function()
                {
                    $('#login_button').attr('disabled', 'disabled');
                    $('#login_button').val('czekaj...');
                },
                success:function(data)
                {
                    $('#login_button').attr('disabled', false);
                    if(data.error != '')
                    {
                        $('#error').html(data.error);
                        $('#login_button').val('Login');
                    }
                    else
                    {
                        window.location.href = data.url;
                    }
                }
            })
        }
    });

});


</script>

 <!-- index.php

The script then includes various files for styling the login form, including custom fonts and a stylesheet called sb-admin-2.min.css.
It also includes a file called parsley.css, which is a stylesheet for a JavaScript library called Parsley that is used for form validation.

The script then defines some inline styles for the login form, including the size, padding, and background 
color of the form, as well as the font size and appearance of the input fields.
The script then includes the jQuery library, the Bootstrap JavaScript library, the jQuery Easing library,
the SB Admin 2 JavaScript library, and the Parsley JavaScript library.

The script then displays the login form, which consists of a form element with an ID of "login_form" and a method of "post". 
The form includes a heading, a form group for the email input field, a form group for the password input field, and a button to submit the form. 
The email and password fields also have the required attribute, which means that they must be filled out before the form can be submitted. 
The email field also has the data-parsley-type attribute set to "email", which means that it will be validated as an email address using the Parsley library. 
The password field has a data-parsley-trigger attribute set to "keyup", which means that the form will be validated whenever a 
key is released while the password field is in focus. The submit button has an ID of "login_button" and a name of "login_button".


index.php

Ten skrypt następnie uwzględnia różne pliki do stylizowania formularza logowania, w tym niestandardowe czcionki i arkusz stylów o nazwie sb-admin-2.min.css. 
Zawiera również plik o nazwie parsley.css, który jest arkuszem stylów dla biblioteki JavaScript o nazwie Parsley, która jest używana do walidacji formularzy. 
Skrypt definiuje również niektóre style wbudowane dla formularza logowania, w tym rozmiar, odstępy i kolor tła formularza, a także rozmiar czcionki i 
wygląd pól wejściowych. 

Skrypt następnie uwzględnia bibliotekę jQuery, bibliotekę JavaScript Bootstrap, bibliotekę jQuery Easing, bibliotekę JavaScript SB Admin 2 oraz 
bibliotekę JavaScript Parsley. Skrypt wyświetla formularz logowania, który składa się z elementu formularza o ID "login_form" i metodzie "post". 

Formularz zawiera nagłówek, grupę formularzy dla pola wejściowego adresu e-mail, grupę formularzy dla pola hasła i przycisk do przesyłania formularza. 
Pola e-mail i hasło mają również atrybut wymagany, co oznacza, że muszą być wypełnione przed przesłaniem formularza. 
Pole e-mail ma również atrybut data-parsley-type ustawiony na "email", co oznacza, że zostanie zweryfikowane jako adres e-mail za pomocą biblioteki Parsley. 
Pole hasła ma atrybut data-parsley-trigger ustawiony na "keyup", co oznacza, że formularz zostanie zweryfikowany za każdym razem, 
gdy klawisz jest wypuszczany podczas skupienia się na polu hasła. Przycisk przesyłania ma ID "login_button" i nazwę "login_button". -->

