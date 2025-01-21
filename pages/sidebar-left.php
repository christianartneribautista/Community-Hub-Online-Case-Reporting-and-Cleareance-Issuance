<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
        .treeview-menu {
            display: none;
            margin-left: 20px;
        }
        .treeview.active .treeview-menu {
            display: block;
        }
    </style>
</head>
<body>

<?php
echo ' 
<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left info">
                <h4 class="font-normal text-gray-100" style="font-size: 19px;">Welcome, <br>' . $_SESSION['role'] . '</h4>
            </div>
        </div>';

if ($_SESSION['role'] == "Administrator") {
    echo ' 
    <ul class="sidebar-menu">
        <li><a href="../dashboard/dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li><a href="../officials/officials.php"><i class="fa fa-user"></i> <span>Barangay Officials</span></a></li>
        <li><a href="../resident/resident.php"><i class="fa fa-users"></i> <span>Resident</span></a></li>
        <li><a href="../blotter/blotter.php"><i class="fa fa-users"></i> <span>Blotter</span></a></li>
        <li><a href="../clearance/clearance.php"><i class="fa fa-file"></i> <span>Clearance</span></a></li>
        <li><a href="../clearance/clearanceandcertificates.php"><i class="fa fa-file"></i> <span>Clearances and Certificates</span></a></li>
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Settings</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
                <li><a href="../settings/logs.php"><i class="fa fa-circle-o"></i> Audit Trail</a></li>
                <li><a href="../settings/system_backup_restore.php"><i class="fa fa-circle-o"></i> System Backup & Restore</a></li>
            </ul>
        </li>
    </ul>';
} elseif ($_SESSION['role'] == "Staff") {
    echo ' 
    <ul class="sidebar-menu">
        <li><a href="../clearance/clearance.php"><i class="fa fa-file"></i> <span>Clearance</span></a></li>
        <li><a href="../blotter/blotter.php"><i class="fa fa-users"></i> <span>Blotter</span></a></li>
    </ul>';
} else {
    echo ' 
    <ul class="sidebar-menu">
        <li><a href="../clearance/clearance.php"><i class="fa fa-file"></i> <span>Clearance</span></a></li>
        <li><a href="../blotter/res_blotter.php"><i class="fa fa-users"></i> <span>Blotter</span></a></li>
    </ul>';
}

echo '
    </section>
</aside>';
?>


<!-- Bootstrap JS, Popper JS (for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Custom JS to toggle dropdown -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.querySelector('.treeview > a');
        dropdown.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default behavior (e.g., navigating to #)
            var menu = this.nextElementSibling;
            menu.classList.toggle('active'); // Toggle visibility of the dropdown menu
        });
    });
    $(document).ready(function() {
    // Initialize the treeview
    $('.treeview').click(function() {
        $(this).children('.treeview-menu').toggle();
    });
});

</script>


</body>
</html>
