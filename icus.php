<?php
require_once 'inc.php';
require_once 'table_head.php';
?>

    <body id="page-top">
<?php require_once 'sidebar.php';?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
<?php require_once 'header.php';?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">ICUs</h3>
                <div class="row mb-3">

                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold">ICU Data</p>
                                        <?php
                                        if (isset($_GET['success']) && $_GET['success'] == 'Y') {
                                            ?>
                                            <p style="text-align: center;color: limegreen;"><?php echo $_SESSION['add_new_icu_success']; ?></p>
                                        <?php } ?>
                                        <p id="error"></p>
                                    </div>
                                    <div style="float: left;margin-left: 85%;">
                                        <a href="add_icu.php" id="add-new-butt"class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Add New ICU</a>
                                    </div>
                                    <div class="card-body">


                                        <div class="progress" style="height: 2px;">
                                            <div class="progress-bar line" role="progressbar" style="width: 0%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <div class="row new-data animate__animated animate__backInRight animate__delay-1s">
                                            <div class="col col-lg-12">
                                                <nav class="navbar icon-search navbar-light">

                                                </nav>
                                                <div class="table-responsive">
                                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap data-table" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>ICU Name</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>

                                                    </table>
                                                </div>
                                            </div>





                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
<?php require_once 'footer.php'?>

            <script src="assets/js/jquery.dataTables.min.js"></script>
            <script src="assets/js/dataTables.bootstrap4.min.js"></script>
            <script src="assets/js/dataTables.responsive.js"></script>
            <script src="assets/js/dataTables.buttons.min.js"></script>
            <script src="assets/js/jszip.min.js"></script>
            <script src="assets/js/pdfmake.min.js"></script>
            <script src="assets/js/vfs_fonts.js"></script>
            <script src="assets/js/buttons.html5.min.js"></script>
            <script src="assets/js/buttons.print.min.js"></script>
            <script src="assets/js/buttons.bootstrap4.min.js"></script>
            <script src="assets/js/jszip.min.js"></script>
            <script src="assets/js/pdfmake.min.js"></script>
            <script src="assets/js/vfs_fonts.js"></script>
            <script src="assets/js/buttons.html5.min.js"></script>
            <script src="assets/js/buttons.print.min.js"></script>
            <script src="assets/js/buttons.colVis.min.js"></script>
            <script src="assets/js/datatable_input_pagination.js"></script>
            <script>
                $(document).ready(function() {
                    var table = $('#example').DataTable( {
                        "pagingType": "input",
                        "lengthChange": false,
                        "ajax": 'tables_data/icuss_data.php',
                        "deferRender": true,
                        "processing": true,
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel'
                        ]
                    } );

                    table.buttons().container()
                        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
                } );
            </script>
    </body>

</html>
