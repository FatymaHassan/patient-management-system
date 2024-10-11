<?php
  session_start();
  include('assets/inc/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid = $_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $lab_id = $_GET['lab_id'];
        $lab_number = $_GET['lab_number'];
        $ret = "SELECT * FROM his_laboratory WHERE lab_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $lab_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        //$cnt=1;
        while ($row = $res->fetch_object()) {
            $mysqlDateTime = $row->lab_date_rec;
        ?>

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">View Lab Record</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- Add Print and Export Buttons -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <button class="btn btn-primary" onclick="printReport()">Print Report</button>
                                <button class="btn btn-success" id="exportCsv">Export CSV</button>
                               
                            </div>
                        </div>
                        <!-- End Print and Export Buttons -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box" id="reportContent">
                                    <div class="row">
                                        <div class="col-xl-5">
                                            <div class="tab-content pt-0">
                                                <div class="tab-pane active show" id="product-1-item">
                                                    <img src="assets/images/medical_record.png" alt="" class="img-fluid mx-auto d-block rounded">
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-xl-7">
                                            <div class="pl-xl-3 mt-3 mt-xl-0">
                                                <h2 class="mb-3">Patient's Name : <?php echo $row->lab_pat_name; ?></h2>
                                                <hr>
                                                <h3 class="text-danger ">Patient Number : <?php echo $row->lab_pat_number; ?></h3>
                                                <hr>
                                                <h3 class="text-danger ">Patient Ailment : <?php echo $row->lab_pat_ailment; ?></h3>
                                                <hr>
                                                <h3 class="text-danger ">Date Recorded : <?php echo date("d/m/Y - h:m:s", strtotime($mysqlDateTime)); ?></h3>
                                                <hr>
                                                <h2 class="align-centre">Laboratory Test</h2>
                                                <hr>
                                                <p class="text-muted mb-4">
                                                    <?php echo $row->lab_pat_tests; ?>
                                                </p>
                                                <hr>
                                                <h2 class="align-centre">Laboratory Result</h2>
                                                <p class="text-muted mb-4">
                                                    <?php echo $row->lab_pat_results; ?>
                                                </p>
                                                <hr>
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row -->
                                </div> <!-- end card-box -->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php'); ?>
                <!-- end Footer -->

            </div>
        <?php } ?>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

    <!-- JavaScript to handle Print, CSV, and PDF functionality -->
    <script type="text/javascript">
        function printReport() {
            var printContents = document.getElementById('reportContent').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        // Export report to CSV
        document.getElementById('exportCsv').addEventListener('click', function() {
            var csv = [];
            var rows = document.querySelectorAll("#reportContent div, #reportContent p, #reportContent h2, #reportContent h3");

            for (var i = 0; i < rows.length; i++) {
                var row = rows[i].innerText;
                csv.push(row);        
            }

            // Download CSV
            downloadCSV(csv.join("\n"), 'patient_report.csv');
        });

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], {type: "text/csv"});

            // Create download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide link
            downloadLink.style.display = "none";

            // Add the link to the document
            document.body.appendChild(downloadLink);

            // Click the link
            downloadLink.click();
        }

        // Export report to PDF
       
    </script>

</body>

</html>
