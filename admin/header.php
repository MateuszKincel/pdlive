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

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    

    <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>

    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-select/bootstrap-select.min.css"/>

    <link rel="stylesheet" type="text/css" href="../vendor/datepicker/bootstrap-datepicker.css"/>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="panel_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <?php
            if($_SESSION['type'] == 'Admin')
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="panel_admin.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Panel</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="promotor.php">
                    <i class="fas fa-regular fa-id-card"></i>
                    <span>Promotor</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student.php">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Student</span></a>
            </li>
            <?php
            }
            ?>
            <li class="nav-item">
                <a class="nav-link" href="promotor_harmonogram.php">
                    <i class="fas fa-user-clock"></i>
                    <span>Harmonogram Promotora</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="praca.php">
                    <i class="fas fa-solid fa-scroll"></i>
                    <span>Prace</span></a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="temat.php">
                    <i class="fas fa-solid fa-list-ul"></i>
                    <span>Tematy</span></a>
            </li>
            <?php
            if($_SESSION["type"] == 'Admin')
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="profil.php">
                    <i class="far fa-id-card"></i>
                    <span>Profil</span></a>
            </li>
            <?php
            } 
            else
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="promotor_profil.php">
                    <i class="far fa-id-card"></i>
                    <span>Profil</span></a>
            </li>
            <?php
            }
            ?>
            <?php
            if($_SESSION['type'] == 'Promotor')
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="chat.php">
                    <i class="far fa-id-card"></i>
                    <span>Chat</span></a>
            </li>
            
            <?php
            }
            ?>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <?php
                        $user_name = '';
                        $user_profile_image = '';

                        if($_SESSION['type'] == 'Admin')
                        {
                            $object->query = "
                            SELECT * FROM admin 
                            WHERE admin_id = '".$_SESSION['admin_id']."'
                            ";

                            $user_result = $object->get_result();

                            foreach($user_result as $row)
                            {
                                $user_name = $row['admin_nazwa'];
                                $user_profile_image = '../img/undraw_profile.svg';
                            }
                        }

                        if($_SESSION['type'] == 'Promotor')
                        {
                            $object->query = "
                            SELECT * FROM promotor 
                            WHERE promotor_id = '".$_SESSION['admin_id']."'
                            ";

                            $user_result = $object->get_result();
                            
                            foreach($user_result as $row)
                            {
                                $user_name = $row['promotor_nazwa'];
                                $user_profile_image = '../img/undraw_profile.svg';
                            }
                        }

                        
                        ?>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="user_profile_name"><?php echo $user_name; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo $user_profile_image; ?>" id="user_profile_image">
                            </a>
                            <!-- Dropdown - User Information -->
                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Wyloguj
                                </a>
                            </div>
                            <?php
                            }
                            if($_SESSION['type'] == 'Promotor')
                            {
                            ?>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="promotor_akcja.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Wyloguj
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

