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
		<a class="navbar-brand ml-2" href="#"><?php echo $_SESSION['student_name']; ?></a>
        <a class="nav-link btn btn-outline-primary text-white" href="profil.php"><i class="fas fa-user-edit mr-2"></i>Profil</a>
      </li>
      <li class="nav-item mr-2">
        <a class="nav-link btn btn-outline-danger text-white" href="logout.php">Wyloguj</a>
      </li>
    </ul>
  </div>
</nav>
