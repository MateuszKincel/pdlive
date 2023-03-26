                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Prawa &copy; Mateusz Kincel 41 INF-ISM NP & Uniwersytet Zielonogórski <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Czy na pewno chcesz się wylogować?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Wybierz "Wyloguj" aby zakończyć tą sesję.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>
                    <a class="btn btn-primary" href="/admin/logout.php">Wyloguj</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="../vendor/parsley/dist/parsley.min.js"></script>
    <script type="text/javascript" src="../vendor/bootstrap-select/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="../vendor/datepicker/bootstrap-datepicker.js"></script>

</body>

</html>

<!-- header.php -->
<!DOCTYPE html>
<html>
<head>
    <!-- Other head elements like title, meta tags, stylesheets, etc. -->
</head>
<body>
    <!-- Your website's header content -->

    <script>
        let timeout; // Timeout variable to store the setTimeout function

        function resetTimeout() {
            clearTimeout(timeout); // Clear previous timeout if any
            // Set the timeout to 1 hour (3600000 milliseconds)
            timeout = setTimeout(() => window.location.href = "/logout.php", 3600000);
        }

        // Reset the timeout on any user action (mousemove, keydown, or touchstart)
        window.addEventListener('mousemove', resetTimeout);
        window.addEventListener('keydown', resetTimeout);
        window.addEventListener('touchstart', resetTimeout);

        // Initialize the timeout when the script is loaded
        resetTimeout();
    </script>