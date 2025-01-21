    <?php
    session_start(); // Start the session at the beginning of the file

    // Error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include('../head_css.php'); 
    ?>
    <body class="skin-black">
        <?php 
        ob_start(); // Start output buffering
        include "../connection.php"; 
        include('../header.php'); 
        ?>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php include('../sidebar-left.php'); ?>

            <aside class="right-side">
                <section class="content">
                    <?php
                    // Database configuration
                    $host = 'localhost';
                    $user = 'root';
                    $password = '';
                    $dbname = 'db_barangay';

                    // Initialize messages
                    $message = "";
                    $restoreMessage = ""; // Initialize restoreMessage

                    // Check if the backup button was clicked
                    if (isset($_POST['backup'])) {
                        $con = new mysqli($host, $user, $password, $dbname);
                        if ($con->connect_error) {
                            $message = "Connection failed: " . $con->connect_error;
                        } else {
                            backupDatabase($con, $dbname); // Call the function to serve the backup
                        }
                    }

                    // Check if the restore button was clicked
                    if (isset($_POST['restore']) && isset($_FILES['backup_file'])) {
                        $file = $_FILES['backup_file']['tmp_name'];

                        // Make sure the file exists
                        if (file_exists($file)) {
                            $restoreMessage = restoreDatabase($file, $con); // Call the restore function
                        } else {
                            $restoreMessage = "Please upload a valid SQL file.";
                        }
                    }

                    // Function to create a backup of the database
                    function backupDatabase($con, $dbname) {
                        $tables = [];
                        $result = $con->query("SHOW TABLES");
                        while ($row = $result->fetch_array()) {
                            $tables[] = $row[0];
                        }

                        // Start building the SQL backup content
                        $sqlBackup = "-- Backup of database: $dbname\n";
                        $sqlBackup .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

                        foreach ($tables as $table) {
                            // Drop the table if it exists
                            $sqlBackup .= "DROP TABLE IF EXISTS `$table`;\n";
                            
                            // Get the CREATE TABLE statement
                            $createTableResult = $con->query("SHOW CREATE TABLE `$table`");
                            $createTableRow = $createTableResult->fetch_array();
                            $sqlBackup .= $createTableRow[1] . ";\n\n";

                            // Get the data from the table
                            $dataResult = $con->query("SELECT * FROM `$table`");
                            while ($dataRow = $dataResult->fetch_assoc()) {
                                // Escape values to prevent SQL injection
                                $escapedValues = array_map(function($value) use ($con) {
                                    return "'" . $con->real_escape_string($value) . "'";
                                }, array_values($dataRow));

                                // Create the INSERT statement
                                $sqlBackup .= "INSERT INTO `$table` ("; 
                                $sqlBackup .= implode(", ", array_keys($dataRow)) . ") VALUES ("; 
                                $sqlBackup .= implode(", ", $escapedValues) . ");\n";
                            }
                            $sqlBackup .= "\n\n";
                        }

                        // Serve the backup file for download
                        header('Content-Type: application/sql');
                        header('Content-Disposition: attachment; filename="backup-' . date('Y-m-d-H-i-s') . '.sql"');
                        header('Content-Length: ' . strlen($sqlBackup));
                        echo $sqlBackup; // Output the SQL backup content
                        exit; // Stop further execution
                    }

                    // Function to restore the database from an uploaded SQL file
                    function restoreDatabase($file, $con) {
                        $sql = file_get_contents($file); // Read the SQL file contents

                        // Check if the file is empty
                        if (empty($sql)) {
                            return "The uploaded file is empty.";
                        }

                        // Ensure that the file contains SQL commands by checking for common keywords
                        if (strpos($sql, 'CREATE TABLE') === false && strpos($sql, 'INSERT INTO') === false) {
                            return "The uploaded file does not contain valid SQL commands.";
                        }

                        // Attempt to execute the SQL queries
                        if ($con->multi_query($sql)) {
                            return "Database successfully restored.";
                        } else {
                            return "Error restoring database: " . $con->error;
                        }
                    }
                    ?>
</head>
<body>
                    <div class="container-fluid">
                        <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
                            <h2>Backup Database</h2>
                            <form method="post">
                                <button type="submit" name="backup" class="btn btn-primary">Backup Database</button>
                            </form>
                            <div class="mt-3">
                                <?php if ($message): ?>
                                    <div class="alert alert-info">
                                        <?php echo htmlspecialchars($message); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <h2 class="mt-5">Restore Database</h2>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="backup_file">Upload SQL Backup File:</label>
                                    <input type="file" class="form-control" name="backup_file" accept=".sql" required>
                                </div>
                                <button type="submit" name="restore" class="btn btn-success">Restore Database</button>
                            </form>
                            <div class="mt-3">
                                <?php if ($restoreMessage): ?>
                                    <div class="alert alert-info">
                                        <?php echo htmlspecialchars($restoreMessage); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </main>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <?php include "../footer.php"; ?>
    </body>
    </html>