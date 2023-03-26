<<<<<<< HEAD
<?php 
function getStudentName($object, $student_id) {
    $data = array(
        ':student_id' => $student_id
    );

    $object->query = "
    SELECT student_imie, student_nazwisko, student_nr_indeksu FROM student 
    WHERE student_id = :student_id
    ";

    $object->execute($data);

    if ($object->row_count() > 0) {
        $result = $object->statement_result();
        foreach ($result as $row) {
            return $row['student_imie'] . ' ' . $row['student_nazwisko'] . ' ' . $row['student_nr_indeksu'];
        }
    } else {
        return '';
    }
}

?>

=======
>>>>>>> 4e1a8393c0e1d565459a54a2d57be4d485bc40d6
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-primary text-white" href="tematy.php">Tematy</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-primary text-white" href="panel.php">Rezerwuj</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-primary text-white" href="praca.php">Moja Praca</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-primary text-white" href="dopobrania.php">Do Pobrania</a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn btn-outline-primary text-white" href="chat.php">Chat</a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item d-flex align-items-center mr-2">
<<<<<<< HEAD
		    <a class="navbar-brand ml-2" href="#"><?php echo getStudentName($object, $_SESSION['student_id']); ?></a>
=======
		<a class="navbar-brand ml-2" href="#"><?php echo $_SESSION['student_name']; ?></a>
>>>>>>> 4e1a8393c0e1d565459a54a2d57be4d485bc40d6
        <a class="nav-link btn btn-outline-primary text-white" href="profil.php"><i class="fas fa-user-edit mr-2"></i>Profil</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-danger text-white" href="logout.php">Wyloguj</a>
      </li>
    </ul>
  </div>
</nav>
