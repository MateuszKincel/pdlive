                                                        Documentation index.phpFormularz

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


                                                        Dokumentacja index.php

Skrypt następnie zawiera różne pliki do stylizacji formularza logowania, w tym niestandardowe czcionki i arkusz stylów o nazwie sb-admin-2.min.css.
Zawiera również plik o nazwie parsley.css, który jest arkuszem stylów dla biblioteki JavaScript o nazwie Parsley, która jest używana do walidacji formularzy.
Skrypt definiuje niektóre wewnętrzne style dla formularza logowania, w tym rozmiar, odstępy i kolor tła formularza, a także rozmiar czcionki i wygląd pól wejściowych.
Skrypt następnie zawiera bibliotekę jQuery, bibliotekę JavaScript Bootstrap, bibliotekę jQuery Easing, bibliotekę JavaScript SB Admin 2 oraz bibliotekę JavaScript Parsley.
Skrypt wyświetla formularz logowania, który składa się z elementu formularza o ID "login_form" i metodzie "post".
Formularz zawiera nagłówek, grupę formularzy dla pola do wprowadzenia adresu e-mail, grupę formularzy dla pola hasła i przycisk do wysłania formularza. 
Pola e-mail i hasło również mają wymagane atrybuty, co oznacza, że ​​muszą zostać wypełnione przed wysłaniem formularza. 
Pole e-mail ma również ustawiony atrybut data-parsley-type na "email", co oznacza, że ​​zostanie zweryfikowane jako adres e-mail za pomocą biblioteki Parsley. 
Pole hasła ma ustawiony atrybut data-parsley-trigger na "keyup", co oznacza, że ​​formularz będzie sprawdzany po każdym zwolnieniu klawisza podczas kiedy pole hasła jest aktywne. 
Przycisk wyślij ma ID "login_button" i nazwę "login_button".



 <!-- login.php 

This script is a PHP file that handles the login process for an application. It includes the 'handler.php' file, which contains a class called 'Handler'. 
An object of the 'Handler' class is created and stored in the variable '$object'.

The script then checks if the 'admin_adres_email' field has been set in the POST request. If it has, it enters the if block. 
Inside the block, the script first initializes the variables '$error', '$url', and '$data'. '$error' is used to store any error messages that may occur during 
the login process. '$url' is used to store the URL that the user should be redirected to after a successful login. '$data' is an array that stores 
the 'admin_adres_email' field from the POST request as a key-value pair.

The script then sets the 'query' property of the '$object' object to a SELECT statement that retrieves all rows from the 'admin' where 
the 'admin_adres_email' column is equal to the 'admin_adres_email' field from the POST request. The script then calls the 'execute()' method of 
the '$object' object and passes the '$data' array as an argument. This executes the SELECT statement with the provided email address.

The script then gets the number of rows returned by the SELECT statement and stores it in the '$total_row' variable. If '$total_row' is equal to 0, 
it means that there is no matching email address in the 'admin'. The script then sets the 'query' property of the '$object' object to a SELECT 
statement that retrieves all rows from the 'promotor_table' where the 'promotor_adres_email' column is equal to the 'admin_adres_email' field from the POST 
request. It then calls the 'execute()' method of the '$object' object and passes the '$data' array as an argument. This executes the SELECT statement with 
the provided email address.

The script then gets the number of rows returned by the SELECT statement and checks if it is equal to 0. If it is, it means that there is no matching 
email address in the 'promotor_table' either. The script sets the '$error' variable to a string containing an error message indicating that the provided 
email address is incorrect. If the number of rows returned by the SELECT statement is not 0, it means that there is a matching email address in 
the 'promotor_table'. The script gets the result of the SELECT statement and stores it in the '$result' variable. It then iterates over each row in 
the '$result' array and checks the value of the 'promotor_status' column. If it is equal to 'Nie Aktywny', it means that the account is disabled. 
The script sets the '$error' variable to a string containing an error message indicating that the account has been disabled and the user should contact 
the administrator. If the 'promotor_status' column is not 'Nie Aktywny', the script checks if the 'admin_haslo' field from the POST request is equal to 
the 'promotor_haslo' column of the current row. If it is, it means that the login was successful. The script sets the 'admin_id' session variable to the id of 
the admin that is logging in. It then sets the 'type' session variable to 'Promotor_table'. It then sets the '$url' variable to the URL of the 'promotor_harmonogram.php'
file. If the 'admin_haslo' field from the POST request is not equal to the 'promotor_haslo' column of the current row, it means that the password is incorrect.
The script sets the '$error' variable to a string containing an error message indicating that the password is incorrect.


login.php

Ten skrypt jest plikiem PHP, który obsługuje proces logowania dla aplikacji. Zawiera plik 'handler.php', który zawiera klasę o nazwie 'Handler'.
Tworzony jest obiekt klasy 'Handler' i przechowywany w zmiennej '$object'.

Następnie skrypt sprawdza, czy pole 'admin_adres_email' zostało ustawione w żądaniu POST. Jeśli tak, wejdzie do bloku if. Wewnątrz bloku skrypt najpierw inicjalizuje
zmienne '$error', '$url' i '$data'. '$error' jest używane do przechowywania komunikatów o błędach, które mogą wystąpić podczas procesu logowania. '$url' jest
używana do przechowywania adresu URL, do którego użytkownik powinien być przekierowany po pomyślnym zalogowaniu. '$data' to tablica, która przechowuje pole
'admin_adres_email' z żądania POST jako para klucz-wartość.

Następnie skrypt ustawia właściwość 'query' obiektu '$object' na polecenie SELECT, które pobiera wszystkie wiersze z tabeli 'admin', gdzie kolumna
'admin_adres_email' jest równa polu 'admin_adres_email' z żądania POST. Następnie skrypt wywołuje metodę 'execute()' obiektu '$object' i przekazuje tablicę '$data'
jako argument. W ten sposób wykonywane jest polecenie SELECT z podanym adresem e-mail.

Skrypt następnie pobiera liczbę zwróconych przez zapytanie SELECT wierszy i zapisuje ją w zmiennej '$total_row'. Jeśli '$total_row' jest 
równy 0, oznacza to, że nie ma pasującego adresu e-mail w tabeli 'admin'. Wtedy skrypt ustawia właściwość 'query' obiektu '$object' na 
polecenie SELECT, które pobiera wszystkie wiersze z tabeli 'promotor_table', gdzie kolumna 'promotor_adres_email' jest równa polu 'admin_adres_email' z 
żądania POST. Następnie wywołuje metodę 'execute' obiektu '$object' i przekazuje tablicę '$data' jako argument. 
W ten sposób wykonuje zapytanie SELECT z podanym adresem e-mail.

Skrypt sprawdza liczbę zwracanych wierszy przez zapytanie SELECT i sprawdza, czy jest równa 0. Jeśli tak, oznacza to, 
że nie ma pasującego adresu email w tabeli 'promotor_table'. Skrypt ustawia zmienną '$error' na ciąg zawierający komunikat o błędzie wskazujący, 
że podany adres email jest nieprawidłowy. Jeśli liczba zwracanych wierszy przez zapytanie SELECT nie jest równa 0, oznacza to, 
że istnieje pasujący adres email w tabeli 'promotor_table'. Skrypt pobiera wynik zapytania SELECT i przechowuje go w 
zmiennej '$result'. Następnie iteruje przez każdy wiersz w tablicy '$result' i sprawdza wartość kolumny 'promotor_status'. 
Jeśli jest równa 'Nie Aktywny', oznacza to, że konto jest wyłączone. Skrypt ustawia zmienną '$error' na ciąg zawierający komunikat o 
błędzie wskazujący, że konto zostało wyłączone i użytkownik powinien skontaktować się z administratorem. Jeśli kolumna 'promotor_status' 
nie jest równa 'Nie Aktywny', skrypt sprawdza, czy pole 'admin_haslo' z żądania POST jest -->

/* footer.php 
Ten plik HTML zawiera stopkę strony oraz odwołania do plików JavaScript i CSS, które są wymagane do poprawnego działania strony. 
Stopka zawiera informacje o prawach autorskich.
 Pliki JavaScript używane są do obsługi tabel, walidacji formularzy oraz wyboru daty za pomocą kalendarza. 
 Pliki CSS służą do stylizacji elementów strony.
*/

<!-- /* footer.php admin
Ten plik HTML zawiera stopkę strony oraz odwołania do plików JavaScript i CSS, które są wymagane do poprawnego działania strony. 
Stopka zawiera informacje o prawach autorskich oraz przycisk umożliwiający wylogowanie się z aplikacji.
 Pliki JavaScript używane są do obsługi tabel, walidacji formularzy oraz wyboru daty za pomocą kalendarza. 
 Pliki CSS służą do stylizacji elementów strony.
*/ -->


<!-- 
/* get_messages.php 
Ten plik PHP jest odpowiedzialny za pobieranie wiadomości wysłanych między promotorem a studentem. 
Wcześniej, użytkownik wybiera adresata, którego ma wyświetlić. Po pobraniu ID adresata z tablicy POST, 
zapytanie SELECT jest tworzone i wykonywane, aby pobrać wiadomości z tabeli 'chat' między wybranym adresatem (studentem) 
a bieżącym promotorem. Zapytanie zostaje posortowane według kolumny 'timestamp' w kolejności rosnącej. Wynik jest przechowywany w 
tablicy '$messages'. Następnie, dla każdego rekordu w tablicy '$messages', sprawdzane jest, czy nadawcą jest promotor, czy student. 
W zależności od nadawcy, klasa CSS jest ustawiana odpowiednio na 'student' lub 'promotor'. Nadawca i wiadomość są następnie wyświetlane 
w tabeli HTML z odpowiednią klasą CSS.
*/ -->


handler.php

// It looks like this is a class file for a PHP project. The class has a constructor that is setting up a connection to a MySQL database 
// using PDO (PHP Data Objects). The class also has several methods, such as execute, row_count, statement_result, get_result, is_login, is_master_user, 
// and clean_input.

// The execute method takes an optional parameter, $data, which is an array of values to bind to the prepared statement. The row_count method 
// returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement executed by the execute method. The statement_result method 
// returns an array containing all of the rows from the result set returned by the execute method. The get_result method returns a result set as an 
// associative array.

// The is_login method returns true if the admin_id key is defined in the $_SESSION superglobal, and false otherwise. The is_master_user method returns 
// true if the user_type key in the $_SESSION superglobal is equal to "Master", and false otherwise. The clean_input method takes a string as a parameter 
// and sanitizes it by removing leading and trailing whitespace, removing backslashes, and converting special characters to HTML entities. 


/* insert_message.php 

This file is a PHP script that inserts a message into the chat table of a database. 
The script is used to add a new message to a conversation between a promotor and a student in a system for managing and reserving diploma theses.

The script begins by including the Handler class file, which contains functions for interacting with the database. 
It then instantiates an object of the Handler class and connects to the pd_db database using MySQLi.

Next, the script gets the ID of the selected recipient (student) and the ID of the logged-in promotor from the POST request and the session, 
respectively. It also gets the message and the current timestamp from the POST request.

Finally, the script constructs an INSERT query to insert the message, sender ID, recipient ID, and timestamp into the chat table. 
The query is executed using the mysqli_query() function and the result is not stored in a variable.

*/



<!-- /* chat.php 
Plik ten to skrypt PHP, który obsługuje proces czatu między prowadzącym a studentami. W pliku została dołączona biblioteka 'handler.php', 
która zawiera klasę 'Handler'. Tworzony jest obiekt klasy 'Handler', który jest przechowywany w zmiennej '$object'. Następnie plik zawiera nagłówek 
strony internetowej.

Następnie plik łączy się z bazą danych o nazwie 'pd_db'. Zmienna '$result' jest inicjalizowana jako pusty wynik. Zmienna '$messages' jest 
inicjalizowana jako tablica. Zmienna sesyjna 'admin_id' jest używana do pobrania ID prowadzącego, a następnie do pobrania jego nazwy z tabeli 'promotor_table'.

Następnie sprawdzane jest, czy formularz został wysłany. Jeśli tak, to ID odbiorcy jest pobierane z tablicy POST. W przeciwnym razie, 
ID odbiorcy jest ustawione na 0. Następnie pobierana jest tablica '$recipients', zawierająca ID i nazwy wszystkich prowadzących z tabeli 'promotor_table'.

Następnie tworzone jest zapytanie SELECT do tabeli 'chat', które pobiera wiadomości między prowadzącym a wybranym studentem, posortowane 
według kolumny 'timestamp' w kolejności rosnącej. Wynik zapytania jest przechowywany w tablicy '$messages'.

Następnie tworzone jest zapytanie SELECT do tabeli 'chat', które pobiera nazwy nadawców (promotorów) dla danego studenta. 
Wynik zapytania jest przechowywany w tablicy '$recipients'.

Po tym, znajdujemy się w głównym bloku skryptu PHP. W tym bloku, skrypt pobiera ID studenta i nazwę z sesji i używa ich do wygenerowania listy studentów, 
z którymi dany prowadzący może prowadzić rozmowę. Skrypt sprawdza również, czy formularz został przesłany, a jeśli tak, to pobiera ID odbiorcy ze zmiennej POST. 
Następnie, skrypt tworzy zapytanie SELECT do tabeli 'chat', które pobiera wiadomości między studentem a wybranym prowadzącym, posortowane według 
kolumny 'timestamp' w kolejności rosnącej. Wynik zapytania jest przechowywany w tablicy '$messages'.

Następnie, skrypt używa AJAX do wysłania żądania do pliku 'chat_action.php' co sekundę. Jeśli żądanie zostanie zrealizowane pomyślnie, skrypt używa danych
 zwrotnych do wyświetlenia wiadomości na stronie.

Na końcu, skrypt wyświetla formularz, w którym użytkownik może wprowadzić wiadomość i wysłać ją do wybranego studenta. Po wysłaniu formularza, skrypt 
wyśle żądanie POST do pliku 'chat_action.php', aby zapisać wiadomość w bazie danych.


*/ -->


<!-- /* header.php   
This file is a PHP script that generates an HTML page for the admin panel of a system for managing and reserving diploma theses. 
The page displays a list of students who have reserved a diploma thesis with the logged-in promotor.

The script starts by including the Handler class file, which contains functions for interacting with the database. 
It then instantiates an object of the Handler class and connects to the pd_db database using MySQLi.

Next, a SELECT query is executed on the chat table to retrieve the names of the senders (students) for a given promotor. 
The result of the query is stored in the $recipients array.

The HTML structure for the page is then generated, including a navigation bar and a table to display the list of students. 
The table is populated with data from the $recipients array using a foreach loop.

Finally, the script includes several JavaScript and CSS files for styling and functionality, including the DataTables plugin, 
the Parsley form validation plugin, the Bootstrap Select plugin, and the Bootstrap Datepicker plugin. The page is closed with the </body> and </html> tags.


header.php 

Ten plik to skrypt PHP, który generuje stronę HTML dla panelu administracyjnego systemu do zarządzania i rezerwowania prac dyplomowych.
Strona wyświetla listę studentów, którzy zarezerwowali pracę dyplomową z zalogowanym promotorem.

Skrypt rozpoczyna się od dołączenia pliku z klasą Handler, który zawiera funkcje do interakcji z bazą danych.
Następnie tworzy się obiekt klasy Handler i łączy z bazą danych pd_db za pomocą MySQLi.

Następnie wykonywane jest zapytanie SELECT do tabeli "chat", aby pobrać nazwy nadawców (studentów) dla danego promotora.
Wynik zapytania jest przechowywany w tablicy $recipients.

Następnie generowana jest struktura HTML dla strony, w tym pasek nawigacyjny i tabelę do wyświetlania listy studentów.
Tabela jest uzupełniana danymi z tablicy $recipients za pomocą pętli foreach.

Na końcu skrypt dołącza kilka plików JavaScript i CSS do stylizacji i funkcjonalności, w tym wtyczkę DataTables,
wtyczkę do walidacji formularzy Parsley, wtyczkę Bootstrap Select oraz wtyczkę Bootstrap Datepicker. Strona jest zamykana tagami </body> i </html>.
*/ -->




done:
podzielić system na semestry (chyba dodać do bazy tematów semestr zimowy i semestr letni i wtedy w liście tematów wyświetlać tylko te z danego semestru
dodano wyświetlanie semestru do listy studentów i w wyświetlaniu szczegołów studenta
skopiowano plik handler.php w celu otwarcia nowej sesji dla admina
Sortowanie tabeli tematów w zależności jaką student ma grupę (w zależności na jakim semestrze jest student)
w bazie tematów ma być przypisana grupa studentów którzy mogą dany temat zarezerwować (w tabelce powinna wyświetlać sie grupa)
przypisywanie studenta do tematu już zrobione ale w liście tematów dodać opcje zarezerwuj ten temat wtedy automatycznie sprawdzany 
jest promotor_id i wysyłana jest Rezerwacja
przydzielony już temat nie ma wyświetlać statusu tylko mają wyświetlać się dostępne tematy a wykorzystane przechodzić do archiwum
powiadomienia email od admin do promotora i recenzenta treść wpisywana w inpucie
generacja przydziału ilości tematów na promotora np: wszystkich tematów 40, 10 na promotorów podzielić
dodać semestr letni zimowy nie wiem jeszcze jak
dodac procentowy postęp pracy nie musi być ładnie może być po prostu napisane np: 50% postępu pracy w jakimś kolorze
generowanie karty pracy jako pdf żeby sie wypełniał (łatwiej zrobić wypełnianie w latexie podobno jest łatwiej jak w html)
if studen akceptacja != tak nie pokazuj postępu
przycisk podglądu tematów promotora temat_akcja
promotor ma określoną liczbe tematów które może dodać stworzyć modal w którym będzie napisane ile jeszcze tematów może dodać z blokadą po przekroczeniu
admin widzi ile temaów przydzielił
Promotor ma możliwość edycji danych takich jak temat pracy temat pracy po angielsku i cel i zakres pracy







to be done: 


powiadomienia dla studenta co 2 tygodnie o postępach pracy automatyczne -------- wymaga ustawienie crona na linuxie lub uruchamianie zadania 
czasowego na windowsie które uruchomi skrypt
<<<<<<< HEAD
=======

stworzono to topics_total.php 
dorobić funkcje w tematy.php tak jak topics_left i wyświetlić w headerze 
>>>>>>> 116fcae485da34d438a5d3bc05d47ec455ec01d3














register.php działa 

<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Rejestracja</div>
				<div class="card-body">
					<form method="post" id="student_register_form">
						<div class="form-group">
							<label>Adres Email<span class="text-danger">*</span></label>
							<input type="text" name="student_adres_email" id="student_adres_email" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>
						<div class="form-group">
							<label>Hasło<span class="text-danger">*</span></label>
							<input type="password" name="student_haslo" id="student_haslo" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Imię<span class="text-danger">*</span></label>
									<input type="text" name="student_imie" id="student_imie" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nazwisko<span class="text-danger">*</span></label>
									<input type="text" name="student_nazwisko" id="student_nazwisko" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						
						<div class="col-md-6">
								<div class="form-group">
									<label>Nr Indeksu<span class="text-danger">*</span></label>
									<input type="text" name="student_nr_indeksu" id="student_nr_indeksu" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Grupa<span class="text-danger">*</span></label>
									<input type="text" name="student_gr_dziek" id="student_gr_dziek" class="form-control" placeholder="np. 11 INF-NP / 41 INF-ISM-NP" required  data-parsley-trigger="keyup" />
									<div id="error-message" style="display: none; color: red; font-size: 13px;"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Data Urodzenia<span class="text-danger">*</span></label>
									<input type="text" name="student_data_urodzenia" id="student_data_urodzenia" class="form-control" required  data-parsley-trigger="keyup" readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Podgrupa<span class="text-danger">*</span></label>
									<input type="text" name="student_podgrupa" id="student_podgrupa" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Nr. Telefonu</label>
									<input type="text" name="student_telefon" id="student_telefon" class="form-control"  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Wydział<span class="text-danger">*</span></label>
									<input type="text" name="student_wydzial" id="student_wydzial" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="student_semestr" style="display: block;">Semestr<span class="text-danger">*</span></label>
							<select name="student_semestr" id="student_semestr"<span>*</span>
								<option value="semestr letni 2022/2023">semestr letni 2022/2023</option>
								<option value="semestr zimowy 2022/2023">semestr zimowy 2022/2023</option>
							</select>
						</div>
						<div class="form-group">
							<label>Adres Zamieszkania</label>
							<textarea name="student_adres" id="student_adres" class="form-control" data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="student_register" />
							<input type="submit" name="student_register_button" id="student_register_button" class="btn btn-primary" value="Zarejestruj" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Zaloguj</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

// // regex pattern to check the student group
// var pattern1 = /^\d{2}\s*[A-Z]{3}-[A-Z]{3}-[A-Z]{2}$/
// var pattern2 = /^\d{2}\s*[A-Z]{3}-[A-Z]{2}$/
// var errorMessage = document.getElementById("error-message");

// document.getElementById("student_gr_dziek").addEventListener("input", function(keyup) {
//   if (!pattern1.test(this.value) && !pattern2.test(this.value)) {
//     errorMessage.innerHTML = "To pole wymaga specjalnego formatu np. '41 INF-ISM-NP' lub '11 INF-NP'";
//     errorMessage.style.display = "block";
//     $('#student_register_button').attr('disabled', true);
//   } else {
//     errorMessage.innerHTML = "";
//     errorMessage.style.display = "none";
//     $('#student_register_button').attr('disabled', false);
//   }
// });


// function to register the student form
$(document).ready(function(){

	$('#student_data_urodzenia').datepicker({
		language: "pl",
        format: "yyyy-mm-dd",
        autoclose: true
    });

	$('#student_register_form').parsley();

	$('#student_register_form').on('submit', function(event){


		event.preventDefault();

		 if($('#student_register_form').parsley().isValid())
	{
		$.ajax({
			url:"akcja.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:'json',
			beforeSend:function(){
				$('#student_register_button').attr('disabled', 'disabled');
			},
			success:function(data)
			{
				$('#student_register_button').attr('disabled', false);
				$('#student_register_form')[0].reset();
				if(data.error !== '')
				{
					$('#message').html(data.error);
				}
				if(data.success != '')
				{
					$('#message').html(data.success);
				}
			}
		});
	}
});
});

</script>









// // regex pattern to check the student group
// var pattern1 = /^\d{2}\s*[A-Z]{3}-[A-Z]{3}-[A-Z]{2}$/
// var pattern2 = /^\d{2}\s*[A-Z]{3}-[A-Z]{2}$/
// var errorMessage = document.getElementById("error-message");

// document.getElementById("student_gr_dziek").addEventListener("input", function(keyup) {
//   if (!pattern1.test(this.value) && !pattern2.test(this.value)) {
//     errorMessage.innerHTML = "To pole wymaga specjalnego formatu np. '41 INF-ISM-NP' lub '11 INF-NP'";
//     errorMessage.style.display = "block";
//     $('#student_register_button').attr('disabled', true);
//   } else {
//     errorMessage.innerHTML = "";
//     errorMessage.style.display = "none";
//     $('#student_register_button').attr('disabled', false);
//   }
// });