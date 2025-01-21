<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../login.php");
    exit(); // Always call exit() after header redirection to prevent further code execution
} else {
    ob_start();
    include('../head_css.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editable Table for Clearances and Certificates</title>
    <style>
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .editable {
            background-color: #ffffe0;
        }
        .btn {
            padding: 5px 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        // Show success notification if the URL contains 'status=success'
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status') && urlParams.get('status') === 'success') {
                alert("Data saved successfully!");
            }
        };

    // Function to show a prompt when a clearance is approved
    function showApprovalNotification(clearanceNo, amount) {
        alert("Clearance #" + clearanceNo + " has been approved with an amount of ₱" + amount);
    }

    // If any approval action occurs, you can call showApprovalNotification with appropriate data.
    // For example, if you trigger approval through a button click:
    document.querySelectorAll('.btn-approve').forEach(button => {
        button.addEventListener('click', function() {
            const clearanceNo = this.getAttribute('data-clearanceNo');
            const amount = this.getAttribute('data-amount');
            showApprovalNotification(clearanceNo, amount);
        });
    });


    </script>
</head>
<body class="skin-black">
    <?php 
    include "../connection.php";
    ?>
    <?php include('../header.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <section class="content-header">
                <h1>
                    Blotter
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <h2 style="text-align: center;">Clearances and Certificates Table</h2>

                <!-- Start Form -->
                <form action="save_data.php" method="POST">
                    <table id="clearanceTable">
                        <thead>
                            <tr>
                                <th>Clearance/Certificate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data from the database
                            $query = "SELECT * FROM clearances_and_certificates";
                            $result = mysqli_query($con, $query);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr data-id='{$row['id']}'>
                                              <td><input type='text' name='clearance_certificate[{$row['id']}]' value='{$row['clearance_certificate']}' /></td>
                                              <td><input type='text' name='amount[{$row['id']}]' value='{$row['amount']}' /></td>
                                          </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <button type="submit" class="btn">Save Data</button>
                </form>
                <!-- End Form -->

            </section>
        </aside>
    </div>
</body>
</html>
<?php
} // End of else block
?>
