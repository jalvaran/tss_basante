<?php
include("../../modelo/php_conexion.php");
/**
 * Control de Mantenimiento
 */

$idFormatoCalidad=3;

$Documento="<strong>CONTROL DE MANTENIMIENTO</strong>";
require_once('Encabezado.php');

// IMPORTANT: disable font subsetting to allow users editing the document
$pdf->setFontSubsetting(false);


/*
It is possible to create text fields, combo boxes, check boxes and buttons.
Fields are created at the current position and are given a name.
This name allows to manipulate them via JavaScript in order to perform some validation for instance.
*/

// set default form properties
$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));

//$pdf->SetFont('helvetica', 'BI', 18);
//$pdf->Cell(0, 5, 'Example of Form', 0, 1, 'C');
//$pdf->Ln(10);

$pdf->SetFont('helvetica', '', 12);

// First name
$pdf->Cell(35, 5, 'Cliente:');
$pdf->TextField('firstname', 50, 5);
//$pdf->Ln(6);

// Last name
$pdf->Cell(35, 5, 'NIT:');
$pdf->TextField('lastname', 200, 5);
$pdf->Ln(6);

// Gender
$pdf->Cell(35, 5, 'Gender:');
$pdf->ComboBox('gender', 30, 5, array(array('', '-'), array('M', 'Male'), array('F', 'Female')));
$pdf->Ln(6);

// Drink
$pdf->Cell(35, 5, 'Drink:');
//$pdf->RadioButton('drink', 5, array('readonly' => 'true'), array(), 'Water');
$pdf->RadioButton('drink', 5, array(), array(), 'Water');
$pdf->Cell(35, 5, 'Water');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Beer', true);
$pdf->Cell(35, 5, 'Beer');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Wine');
$pdf->Cell(35, 5, 'Wine');
$pdf->Ln(6);
$pdf->Cell(35, 5, '');
$pdf->RadioButton('drink', 5, array(), array(), 'Milk');
$pdf->Cell(35, 5, 'Milk');
$pdf->Ln(10);

// Newsletter
$pdf->Cell(35, 5, 'Newsletter:');
$pdf->CheckBox('newsletter', 5, true, array(), array(), 'OK');

$pdf->Ln(10);
// Adress
$pdf->Cell(35, 5, 'Address:');
$pdf->TextField('address', 60, 18, array('multiline'=>true, 'lineWidth'=>0, 'borderStyle'=>'none'), array('v'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'dv'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'));
$pdf->Ln(19);

// Listbox
$pdf->Cell(35, 5, 'List:');
$pdf->ListBox('listbox', 60, 15, array('', 'item1', 'item2', 'item3', 'item4', 'item5', 'item6', 'item7'), array('multipleSelection'=>'true'));
$pdf->Ln(20);

// E-mail
$pdf->Cell(35, 5, 'E-mail:');
$pdf->TextField('email', 50, 5);
$pdf->Ln(6);

// Date of the day
$pdf->Cell(35, 5, 'Date:');
$pdf->TextField('date', 30, 5, array(), array('v'=>date('Y-m-d'), 'dv'=>date('Y-m-d')));
$pdf->Ln(10);

$pdf->SetX(50);

// Button to validate and print
$pdf->Button('print', 30, 10, 'Print', 'Print()', array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Reset Button
$pdf->Button('reset', 30, 10, 'Reset', array('S'=>'ResetForm'), array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Submit Button
$pdf->Button('submit', 30, 10, 'Submit', array('S'=>'SubmitForm', 'F'=>'http://localhost/printvars.php', 'Flags'=>array('ExportFormat')), array('lineWidth'=>2, 'borderStyle'=>'beveled', 'fillColor'=>array(128, 196, 255), 'strokeColor'=>array(64, 64, 64)));

// Form validation functions
$js = <<<EOD
function CheckField(name,message) {
	var f = getField(name);
	if(f.value == '') {
	    app.alert(message);
	    f.setFocus();
	    return false;
	}
	return true;
}
function Print() {
	if(!CheckField('firstname','First name is mandatory')) {return;}
	if(!CheckField('lastname','Last name is mandatory')) {return;}
	if(!CheckField('gender','Gender is mandatory')) {return;}
	if(!CheckField('address','Address is mandatory')) {return;}
	print();
}
EOD;

// Add Javascript code
$pdf->IncludeJS($js);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_014.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
