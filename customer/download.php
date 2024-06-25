<?php
ob_start();
include('data_pesanan.php'); // Replace with the path to your PHP page
$htmlContent = ob_get_clean();

// Include autoloader
require '../script/library/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
// use Dompdf\Options;
$dompdf = new Dompdf();
// $options = new Options();

// $options->setIsRemoteEnabled(true);
$dompdf->loadHtml(''.$htmlContent);

// Render
$dompdf->render();

//Output
$dompdf->stream('document', array('Attachment' => 0));

?>