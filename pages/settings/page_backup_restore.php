<?php 
include('../head_css.php'); 
?>
<body class="skin-black">
    <!-- header logo: style can be found in header.less -->
    <?php 
    session_start();

    include "../connection.php"; 
    include('../header.php'); 
    include('backup_restore.php'); // Include the backup and restore logic
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php include('../sidebar-left.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
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
                </div>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->
    <!-- jQuery 2.0.2 -->
    <?php include "../footer.php"; ?>
</body>
</html>