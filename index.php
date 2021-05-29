<?php

require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf(); // instantiate and use the dompdf class
$dompdf->loadHtml('hello world');
$dompdf->setPaper('A4', 'landscape'); // (Optional) Setup the paper size and orientation
$dompdf->render(); // Render the HTML as PDF
$dompdf->stream(); // Output the generated PDF to Browser