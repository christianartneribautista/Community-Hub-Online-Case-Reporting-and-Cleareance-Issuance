<?php
session_start();
// role secure

if ($_SESSION['type'] != "administrator") {
    header("Location:  ../clearance/clearance.php");
    exit();
}

?>


<!DOCTYPE html>
<html>

<head>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap 4 JS (after jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    if (!isset($_SESSION['role'])) {
        header("Location: ../../login.php");
        exit;
    }
    include('../head_css.php');
    include("../connection.php");

    // Initialize variables
    $lowCount = 0;
    $mediumCount = 0;
    $highCount = 0;
    $monthlyCounts = array_fill(0, 12, 0); // Create an array for monthly counts
    $solvedCount = 0;
    $unsolvedCount = 0;

    try {
        // Database Connection with PDO
        $pdo = new PDO('mysql:host=localhost;dbname=db_barangay', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get Complaint Categories Count
        $sql = "
            SELECT
                SUM(CASE WHEN complaint = 'Debt' THEN 1 ELSE 0 END) AS low_count,
                SUM(CASE WHEN complaint = 'Robbery' THEN 1 ELSE 0 END) AS medium_count,
                SUM(CASE WHEN complaint = 'Assault' THEN 1 ELSE 0 END) AS high_count
            FROM tblblotter
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $lowCount = (int) $result['low_count'];
        $mediumCount = (int) $result['medium_count'];
        $highCount = (int) $result['high_count'];

        // Get Cases by Month
        $sqlMonthly = "SELECT MONTH(dateRecorded) AS month, COUNT(*) AS count FROM tblblotter GROUP BY MONTH(dateRecorded)";
        $stmtMonthly = $pdo->prepare($sqlMonthly);
        $stmtMonthly->execute();
        while ($row = $stmtMonthly->fetch(PDO::FETCH_ASSOC)) {
            $monthlyCounts[(int) $row['month'] - 1] = (int) $row['count'];
        }

        // Get Solved and Unsolved Cases
        $solvedQuery = "SELECT COUNT(*) AS count FROM tblblotter WHERE sStatus = 'Solved'";
        $unsolvedQuery = "SELECT COUNT(*) AS count FROM tblblotter WHERE sStatus = 'Unsolved'";
        $stmtSolved = $pdo->prepare($solvedQuery);
        $stmtSolved->execute();
        $solvedResult = $stmtSolved->fetch(PDO::FETCH_ASSOC);
        $solvedCount = (int) $solvedResult['count'];
        $stmtUnsolved = $pdo->prepare($unsolvedQuery);
        $stmtUnsolved->execute();
        $unsolvedResult = $stmtUnsolved->fetch(PDO::FETCH_ASSOC);
        $unsolvedCount = (int) $unsolvedResult['count'];

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    ?>

    <style>
        .info-box-number {
            font-size: 14px;
            /* Adjust as needed */
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            /* Optional padding for spacing */
        }

        #casesChartByCategory,
        #casesChartByMonth,
        #casesSolvedVsUnsolved {
            max-width: 100%;
            /* Make sure it scales down on smaller screens */
            height: auto;
            /* Keep height auto to maintain aspect ratio */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="skin-black">
    <?php include('../header.php'); ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
            </section>

            <section class="content">
                <div class="row">
                    <!-- First row: three boxes per row -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="box">
                            <div class="info-box">
                                <a href="../resident/resident.php">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Number of <br> Registered Resident</span>
                                    <span class="info-box-number">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM tblresident WHERE ustatus = 'approved'");
                                        echo mysqli_num_rows($q);
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="box">
                            <div class="info-box">
                                <a href="../clearance/clearance.php">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Number of <br> Clearance Approved</span>
                                    <span class="info-box-number">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM tblclearance WHERE status = 'Approved' OR status = 'Issued'");
                                        echo mysqli_num_rows($q);
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="box">
                            <div class="info-box">
                                <a href="../clearance/clearance.php">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Number of <br> Clearance Issued</span>
                                    <span class="info-box-number">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM tblclearance WHERE status = 'Issued'");
                                        echo mysqli_num_rows($q);
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="box">
                            <div class="info-box">
                                <a href="../blotter/blotter.php">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Number of <br> Case Solved</span>
                                    <span class="info-box-number">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM tblblotter WHERE sStatus = 'Solved'");
                                        echo mysqli_num_rows($q);
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="box">
                            <div class="info-box">
                                <a href="../clearance/clearance.php">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Number of <br> Case Unsolved</span>
                                    <span class="info-box-number">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM tblblotter WHERE sStatus = 'Unsolved'");
                                        echo mysqli_num_rows($q);
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- New row for solved vs unsolved chart and cases per month -->
                    <div class="row">
                        <!-- Pie chart: Solved vs Unsolved -->
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="casesSolvedVsUnsolved" width="400" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Line chart: Cases per month -->
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="casesChartByMonth" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

            </section>
        </aside>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Scripts -->
    <script type="text/javascript">
        var monthlyData = <?php echo json_encode($monthlyCounts); ?>;
        var solvedCount = <?php echo $solvedCount; ?>;
        var unsolvedCount = <?php echo $unsolvedCount; ?>;





        var ctx2 = document.getElementById('casesChartByMonth').getContext('2d');
        var casesChartByMonth = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Number of Cases per Month',
                    data: monthlyData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            }
        });




        var ctx3 = document.getElementById('casesSolvedVsUnsolved').getContext('2d');
        var casesSolvedVsUnsolved = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: ['Solved', 'Unsolved'],
                datasets: [{
                    data: [solvedCount, unsolvedCount],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' cases';
                            }
                        }
                    }
                },
                maintainAspectRatio: false // Optional, can allow resizing based on canvas size
            }
        });


    </script>
</body>

</html>