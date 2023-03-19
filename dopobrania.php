<?php

//dopobrania.php



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
    <div class="card-header"><h4>Ważne dokumenty do pobrania</h4></div>
      <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="file_list_table">
              <thead>
                <tr>
                  <th>Nazwa pliku </th>
                  <th> Pobierz </th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Get the list of files in the "pliki do pobrania" folder
                $folder = "pliki do pobrania/";
                $files = array_diff(scandir($folder), array('.', '..'));
                foreach ($files as $file) {
                  echo "<tr>";
                  echo "<td>" . $file . "</td>";
                  echo "<td><a href='" . $folder . $file . "' download><button class='btn btn-primary'> Pobierz </button></a></td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php

include('footer.php');

?>


