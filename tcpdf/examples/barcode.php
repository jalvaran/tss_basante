<?php


// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('JULIAN ALVARAN');
$pdf->SetTitle('BARCODE');
$pdf->SetSubject('BARCODE');
$pdf->SetKeywords('TECHNO, SOLUCIONES');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 027', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set a barcode on the page footer
$pdf->setBarcode(date('Y-m-d H:i:s'));

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// print a message
//$txt = "You can also export 1D barcodes in other formats (PNG, SVG, HTML). Check the examples inside the barcodes directory.\n";
//$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);
//$pdf->SetY(30);

// -----------------------------------------------------------------------------

$pdf->SetFont('helvetica', '', 10);

// define barcode style
$style = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => true,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);



// Standard 2 of 5
//$pdf->Cell(0, 0, 'PUNTO MODA', 0, 1);
//$pdf->write1DBarcode('TECHNO', 'S25', '', '', '', 18, 0.4, $style, 'N');

//$pdf->Ln();


// set a background color
//$style['bgcolor'] = array(255,255,240);
//$style['fgcolor'] = array(127,0,0);
$pdf->Cell(1, 1, 'PUNTO MODA', 0, 2,'L');


$style['position'] = 'L';
$pdf->write1DBarcode('LEFT', 'C128A', 10, '', '', 20, 0.4, $style, 'N');
//$pdf->Ln();
//$pdf->Cell(1, 1, 'PUNTO MODA', 0, 2,'L');

//$style['position'] = 'L';
//$pdf->write1DBarcode('LEFT', 'C128A', 100, '', '', 20, 0.4, $style, 'N');






// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_027.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
