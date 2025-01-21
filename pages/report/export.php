<?php
if (isset($_POST['export'])) {
    include "connection.php"; // This will include your connection settings

    // Existing SQL queries
    $SQL1 = "SELECT count(*) as NumberofResident, round(monthlyincome,-1) as Income FROM tblresident GROUP BY monthlyincome";
    $SQL2 = "SELECT count(*) as NumberofResident, Zone FROM tblresident r GROUP BY r.zone";
    $SQL3 = "SELECT COUNT(*) as NumberofResident, Age FROM tblresident GROUP BY age";
    $SQL4 = "SELECT count(*) as NumberofResident, HighestEducationalAttainment FROM tblresident GROUP BY highesteducationalattainment";
    
    // New SQL query for clearances issued
    $SQL5 = "SELECT type_of_clearance, recordedBy, COUNT(*) as NumberOfClearances FROM tblclearance GROUP BY type_of_clearance, recordedBy";

    // Array of SQL queries and their corresponding headers
    $arrsql = array($SQL1, $SQL2, $SQL3, $SQL4, $SQL5);
    $arrhead = array("Resident Income Level", "Population per Zone", "Age", "Resident Educational Attainment", "Clearances Issued by Type and Requester");

    foreach (array_combine($arrsql, $arrhead) as $value => $headers) {
        $header = "$headers\n";
        $result = '';

        // Execute the query
        $exportData = mysqli_query($con, $value) or die("SQL error: " . mysqli_error($con));

        // Get the number of fields
        $fields = mysqli_num_fields($exportData);

        // Fetch field names
        for ($i = 0; $i < $fields; $i++) {
            $header .= mysqli_fetch_field_direct($exportData, $i)->name . "\t";
        }

        // Fetch rows
        while ($row = mysqli_fetch_row($exportData)) {
            $line = '';
            foreach ($row as $value) {
                if ((!isset($value)) || ($value == "")) {
                    $value = "\t";
                } else {
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                $line .= $value;
            }
            $result .= trim($line) . "\n";
        }
        $result = str_replace("\r", "", $result);

        if ($result == "") {
            $result = "\nNo Record(s) Found!\n";
        }

        // Set headers for download
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=export.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        print "$header\n$result\n\n";

        // Free result set
        mysqli_free_result($exportData);
    }

    // Close the connection
    mysqli_close($con);
}
?>