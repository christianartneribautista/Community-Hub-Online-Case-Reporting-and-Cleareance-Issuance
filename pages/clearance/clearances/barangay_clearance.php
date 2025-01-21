<?php
// Retrieve parameters from the URL
$requesteeName = isset($_GET['requesteeName']) ? $_GET['requesteeName'] : '';
$orNumber = isset($_GET['orNumber']) ? $_GET['orNumber'] : '';
$certFee = isset($_GET['certFee']) ? $_GET['certFee'] : '';
$issuedDay = isset($_GET['issuedDay']) ? $_GET['issuedDay'] : '';
$issuedMonth = isset($_GET['issuedMonth']) ? $_GET['issuedMonth'] : '';
$issuedYear = isset($_GET['issuedYear']) ? $_GET['issuedYear'] : '';
$documentDate = isset($_GET['documentDate']) ? $_GET['documentDate'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Clearance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <style>
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    position: relative; /* Required for positioning the pseudo-element */
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('../cimg/image_bg.png');
    background-size: 63%; /* Adjust the size of the image */
    background-position: right; /* Position the image to the right and 20% down from the top */
    background-repeat: no-repeat;
    filter: grayscale(100%); /* Make the background image black and white */
    z-index: -1; /* Ensure it is behind other content */
    border-radius: %; /* This will not have an effect on the body element */
}

.printable-area {
    padding: 20px;
    margin: 0;
    border: none;
    background-color: rgba(255, 255, 255, 0.9);
}

@media print {
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('../cimg/image_bg.png');
    background-size: 63%; /* Increase the size of the image */
    background-position: right;
    background-repeat: no-repeat;
    z-index: -1; /* Ensure it is behind other content */
    border-radius: 100%;
    filter: grayscale(100%); /* Make the image black and white */
    opacity: 0.2; /* Adjust the transparency (0 is fully transparent, 1 is fully opaque) */
}

    .printable-area, .printable-area * {
        visibility: visible !important;
        background-color: transparent !important;
    }

    .footer {
        visibility: visible !important;
    }

    @page {
        margin: 10mm;
    }
}
/* Rest of your existing styles remain unchanged */
.header {
    text-align: center;
    margin-bottom: 20px;
}

.council-section {
    position: relative;
    padding: 20px;
    font-size: 0.600rem;
    text-align: center;
    background-color: white;
    border-radius: 4px;
    margin-bottom: 20px;
    z-index: 1;
}

/* ... rest of your styles ... */
    .council-section::before {
        content: '';
        position: absolute;
        top: 30px;
        left: -6px;
        right: -6px;
        bottom: -4px;
        border: 2px solid #C04040;
        border-radius: 4px;
        z-index: -1;
    }
    .council-section::after {
        content: '';
        position: absolute;
        top: 24.5px;
        left: -12px;
        right: -12px;
        bottom: -8px;
        border: 4px solid #C04040;
        border-radius: 4px;
        z-index: -2;
    }
    .council-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .signature-section {
        text-align: right;
        margin-top: 30px;
    }
    .footer {
        text-align: center;
        margin-top: 30px;
        font-weight: bold;
        background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
    .qr-section {
        margin-top: 10px;
    }
    .indent {
        text-indent: 20px;
    }
    .small-text {
        font-size: 1rem;
    }
    .head {
        font-family: 'Times New Roman', Times, serif;
    }
    .taboc-logo {
        width: 100px; /* Adjust width as needed */
        height: 100px; /* Adjust height as needed */
        border-radius: 100px; /* This will make the image circular */
        margin-left: 60px;
    }
</style>
</head>
<body class="bg-gray-100">
    <div class="max-w-full mx-auto border border-gray-300 printable-area">
        <div class="header">
            <div class="flex justify-between items-center mb-2">
                <img alt="Barangay Taboc Logo" class="taboc-logo" src="../cimg/barangay_taboc.jpg" />
                <div class="head">
                    <h1 class="text-lg">Republic of the Philippines</h1>
                    <h2 class="text-md">Province of La Union</h2>
                    <h2 class="text-md">Municipality of San Juan</h2>
                    <h2 class="text-md font-bold">Barangay Taboc</h2>
                </div>
                <img alt="San Juan La Union Logo" src="../cimg/bagong_pilipinas.jpg" style="width: 125px; height: 125px; margin-right: 60px;"  />
            </div>
        </div>
        <div class="flex">
            <div class="w-1/3 council-section">
                <br>
                <br>
                <h2 class="text-md font-bold council-title">Barangay Taboc Councils</h2>
                <br>
                <p class="font-bold">Punong Barangay</p>
                <br>
                <p class="font-bold">Noland C. Atejira</p>
                <p>Over-all Chairman</p>
                <br>
                <p class="font-bold council-title"> SANGGUNIANG BARANGAY <br> MEMBERS</p>
                <br>
                <p class="font-bold">Albert S. Catbagan</p>
                <p>Committee Chairperson on Appropriation</p>
                <br>
                <p class="font-bold">Mariano Lague Jr.</p>
                <p>Committee Chairperson on Peace &amp; Order</p>
                <br>
                <p class="font-bold">Ray An G. Costales</p>
                <p>Committee Chairperson on Cooperative/Agriculture/Environmental Protection</p>
                <br>
                <p class="font-bold">Manuel King G. Gaerlan</p>
                <p>Committee Chairperson on Public Works/Infrastructure</p>
                <br>
                <p class="font-bold">Gina G. Lucena</p>
                <p>Committee Chairperson on Health &amp; Sanitation</p>
                <br>
                <p class="font-bold">Wenzsi Alain Ross L. Gaerlan</p>
                <p>Committee Chairperson on Education/Culture</p>
                <br>
                <p class="font-bold">Rosita S. Nillo</p>
                <p>Committee Chairperson on Women/Welfare/Rights/Privileges &amp; VAWC</p>
                <br>
                <p class="font-bold">SK Chairperson</p>
                <p class="font-bold">Natalie Nicole C. Martinez</p>
                <br>
                <p>Committee Chairperson on Sports</p>
                <br>
                <p class="font-bold">Barangay Secretary</p>
                <p>Mark Anthony C. Garcia</p>
                <br>
                <p class="font-bold">Barangay Treasurer</p>
                <p>Natalia S. Martinez</p>
            </div>
            <div class="w-2/3 ml-5">
                <div class="text-center mb-4">
                    <br>
                    <h3 class="text-lg font-bold small-text" style="font-size: 0.9rem;">OFFICE OF THE PUNONG BARANGAY</h3>
                    <br>
                    <h1 class="text-xl font-bold text-green-600 small-text" style="font-size: 1.5rem;">CERTIFICATE OF INDIGENCY</h1>
                    <br>
                </div>
                <div class="mb-4">
                    <p class="small-text"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO WHOM IT MAY CONCERN:</p>
                    <br>
                    <p class="mt-2 small-text indent" style="text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;This is to certify that family of 
                        <span class="underline font-bold" style="text-transform: uppercase;"><?php echo $requesteeName; ?></span>
                        , lives on the poverty line and is one of the indigent families living in our barangay.
                    </p>
                    <br>
                    <p class="mt-2 small-text indent" style="text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the aboved-named person for all legal purposes and lawful intents this may serve him/her.
                    </p>
                    <br>
                    <p class="mt-2 small-text">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    Issued this 
                        <span class="underline font-bold"><?php echo $issuedDay; ?></span> day of 
                        <span class="underline font-bold"><?php echo $issuedMonth; ?></span>, 
                        <span class="underline font-bold"><?php echo $issuedYear; ?></span>.
                    </p>
                </div>
                <br>
                <div class="signature-section">
                    <p class="small-text font-bold">NOLAND C. ATEJIRA</p>
                    <p class="small-text">Punong Barangay</p>
                </div>
                <br>
                <div class="pt-3 flex justify-between items-start" style="margin-top: 1px;">
                    <div>
                        <p class="small-text font-bold" style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENTARY STAMP TAX PAID</p>

                        <p class="small-text font-bold" style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OR #: <span class="underline"><?php echo $orNumber; ?></span></p>

                        <p class="small-text font-bold" style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cert. Fee: <span class="underline"><?php echo $certFee; ?></span></p>

                        <p class="small-text font-bold" style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DST: <span class="underline">30.00</span></p>

                        <p class="small-text font-bold" style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: <span class="underline"><?php echo $documentDate; ?></span></p>

                    </div>
                    <div id="qrcode" class="qr-section text-center ml-4"></div> <!-- Added margin-left for spacing -->
                </div>
                <div class="footer small-text" style="background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 27px;">
                    Taboc, San Juan, La Union
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Get the OR number and requestee name from PHP
            var orNumber = "<?php echo $orNumber; ?>";
            var requesteeName = "<?php echo $requesteeName; ?>";

            // Generate the QR code
            $('#qrcode').qrcode({
                text : 'OR Number: ' + orNumber + '\nRequestee Name: ' + requesteeName,
                width: 70,  // Adjust size for fitting
                height: 70 // Adjust size for fitting
            });
        });
    </script>
    
</body>
</html>