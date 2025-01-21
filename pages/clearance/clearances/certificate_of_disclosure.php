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
    <title>Certificate of Indi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .printable-area {
            padding: 20px;
            margin: 0;
            border: none;
            background-color: #ffffff;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .printable-area, .printable-area * {
                visibility: visible;
            }
            @page {
                margin: 10mm; /* Adjust page margins */
            }
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .council-section {
            border-right: 1px solid #ccc; /* Adding a right border for separation */
            padding-right: 20px;
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
        }
        .qr-section {
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-full mx-auto border border-gray-300 printable-area">
        <div class="header">
            <div class="flex justify-between items-center mb-2">
                <img alt="Barangay Taboc Logo" class="w-20 h-20" src="../cimg/logo.jfif" />
                <div>
                    <h1 class="text-lg font-bold">Republic of the Philippines</h1>
                    <h2 class="text-md">Province of La Union</h2>
                    <h2 class="text-md">Municipality of San Juan</h2>
                    <h2 class="text-md font-bold">Barangay Taboc</h2>
                </div>
                <img alt="San Juan La Union Logo" class="w-20 h-20" src="../cimg/sanjuan_logo.jpg" />
            </div>
        </div>
        <div class="flex">
            <div class="w-1/3 council-section">
                <h2 class="text-md font-bold council-title">Barangay Taboc Councils</h2>
                <p class="font-bold">Punong Barangay</p>
                <p>Teofilo C. Cabiladas</p>
                <p>Over-all Chairman</p>
                
                <p class="font-bold council-title">Barangay Kagawads</p>
                <p>Florante Q. Cabagbag</p>
                <p>Rosita S. Nillo</p>
                <p>Arnelis G. Corpuz</p>
                <p>Manuel King Q. Gaerlan</p>
                <p>Estanislao A. Costales Jr.</p>
                <p>Romel M. Saranquin</p>
                <p>Gina G. Lucena</p>
                
                <p class="font-bold council-title">SK Chairperson</p>
                <p>Jayson G. Adierto</p>
                <p class="font-bold council-title">Barangay Secretary</p>
                <p>Ray-An G. Costales</p>
                <p class="font-bold council-title">Barangay Treasurer</p>
                <p>Natalia J. Martinez</p>
            </div>
            <div class="w-2/3 pl-4">
                <div class="text-center mb-4">
                    <h2 class="text-lg font-bold">OFFICE OF THE PUNONG BARANGAY</h2>
                    <h1 class="text-xl font-bold text-blue-600">BARANGAY CLEARANCE</h1>
                </div>
                <div class="mb-4">
                    <p class="text-lg">TO WHOM IT MAY CONCERN:</p>
                    <p class="mt-2 text-lg">
                        This is to certify that
                        <span class="underline font-bold" style="text-transform: uppercase;"><?php echo $requesteeName; ?></span>
                        a resident of barangay Taboc, San Juan, La Union, is known to me personally to be a person of good moral character, comes from a family of good standing and reputation in the community.
                    </p>
                    <p class="mt-2 text-lg">
                        This certification is issued for all legal intents and purposes.
                    </p>
                    <p class="mt-2 text-lg">
                        Issued this 
                        <span class="underline"><?php echo $issuedDay; ?></span> day of 
                        <span class="underline"><?php echo $issuedMonth; ?></span>, 
                        <span class="underline"><?php echo $issuedYear; ?></span>.
                    </p>
                </div>
                <div class="signature-section">
                    <p class="text-lg font-bold">TEOFILO C. CABILDAS</p>
                    <p class="text-lg">Punong Barangay</p>
                </div>
                <div class="border-t border-gray-300 pt-4">
                    <p class="text-lg">DOCUMENTARY STAMP TAX PAID</p>
                    <p class="text-lg">OR #: <span class="underline"><?php echo $orNumber; ?></span></p>
                    <p class="text-lg">Cert. Fee: <span class="underline"><?php echo $certFee; ?></span></p>
                    <p class="text-lg">DST: <span class="underline">30.00</span></p>
                    <p class="text-lg">Date: <span class="underline"><?php echo $documentDate; ?></span></p>
                </div>
                <div class="footer">Taboc, San Juan, La Union</div>

                <!-- QR Code Section -->
                <div id="qrcode" class="qr-section text-center"></div>
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
                width: 80,  // Adjust size for fitting
                height: 80 // Adjust size for fitting
            });
        });
    </script>
    
</body>
</html>