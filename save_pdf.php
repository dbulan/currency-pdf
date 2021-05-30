<?php

use Dompdf\Dompdf;

require __DIR__ . '/vendor/autoload.php';

$dompdf = new Dompdf(); // instantiate and use the dompdf class

//

$result_one = @$_COOKIE['result-1'];
$result_two = @$_COOKIE['result-2'];

$result = $result_one.'<br>'.$result_two;
//

$dompdf->loadHtml($result);

$dompdf->setPaper('A4', 'landscape'); // (Optional) Setup the paper size and orientation
$dompdf->render(); // Render the HTML as PDF
$dompdf->stream(); // Output the generated PDF to Browser