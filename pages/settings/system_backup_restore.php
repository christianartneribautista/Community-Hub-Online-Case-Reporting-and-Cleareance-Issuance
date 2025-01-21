<?php 
session_start(); // Start the session at the beginning of the file

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'db_barangay';

// Initialize messages
$message = "";
$restoreMessage = "";

// Check if the backup button was clicked
if (isset($_POST['backup'])) {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        // Call the backup function directly to output the file
        backupDatabase($conn, $dbname);
    }
}

// Check if the restore button was clicked
if (isset($_POST['restore'])) {
    if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == 0) {
        $filePath = $_FILES['backup_file']['tmp_name'];
        $restoreMessage = restoreDatabase($host, $user, $password, $dbname, $filePath);
    } else {
        $restoreMessage = "Please upload a valid SQL file.";
    }
}

// Function to create a backup of the database
function backupDatabase($conn, $dbname) {
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }

    $sqlBackup = "";
    foreach ($tables as $table) {
        $sqlBackup .= "DROP TABLE IF EXISTS `$table`;\n";
        $createTableResult = $conn->query("SHOW CREATE TABLE `$table`");
        $createTableRow = $createTableResult->fetch_array();
        $sqlBackup .= $createTableRow[1] . ";\n\n";

        $dataResult = $conn->query("SELECT * FROM `$table`");
        while ($dataRow = $dataResult->fetch_assoc()) {
            $sqlBackup .= "INSERT INTO `$table` (";
            $sqlBackup .= implode(", ", array_keys($dataRow)) . ") VALUES (";
            $sqlBackup .= "'" . implode("', '", array_map('addslashes', array_values($dataRow))) . "');\n";
        }
        $sqlBackup .= "\n\n";
    }

    // Set headers to prompt download
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="backup-' . date('Y-m-d-H-i-s') . '.sql"');
    header('Content-Length: ' . strlen($sqlBackup));
    
    // Output the SQL backup
    echo $sqlBackup;
    exit; // Stop further execution
}

// Function to restore the database from a SQL file
function restoreDatabase($host, $user, $password, $dbname, $filePath) {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $sql = file_get_contents($filePath);
    if ($conn->multi_query($sql)) {
        return "Database restored successfully!";
    } else {
        return "Error restoring database: " . $conn->error;
    }

    $conn->close();
}

// Include other files after handling backup and restore
include('../head_css.php'); 
include "../connection.php"; 
include('../header.php'); 
?>

<body class="skin-black">
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../sidebar-left.php'); ?>

        <aside class="right-side">
            <section class="content">
                <div class="container-fluid">
                    <main class="col-md-9 ml-sm-auto col-lg-10 px-4">
                        <h2>Backup Database</h2>
                        <form method="post">
                            <button type="submit" name="backup" class="btn btn-primary">Backup Database</button>
                        </form>
                        <div class="mt-3">
                            <?php if ($message ): ?>
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
            </section>
        </aside>
    </div>
    <?php include "../footer.php"; ?>
</body>
</html>