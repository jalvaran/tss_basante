<?php
include("inc/jpgraph.php");
include("inc/jpgraph_bar.php");
include("inc/jpgraph_pie.php");
$ydata = array(11, 20, 8, 12, 5, 1, 9, 13, 5, 7);
$graph = new Graph(350, 250, "auto");    
$graph->SetScale("textlin");

$graph->img->SetMargin(40, 20, 20, 40);
$graph->title->Set("Empleados");
$graph->xaxis->title->Set("Mes" );
$graph->yaxis->title->Set("Dinero" );

$barplot =new BarPlot($ydata);
$barplot->SetColor("orange");

$graph->Add($barplot);
$graph->Stroke();






// Se define el array de valores y el array de la leyenda
$datos = array(40,60,21,33);
$leyenda = array("Morenas","Rubias","Pelirrojas","Otras");

//Se define el grafico
$grafico = new PieGraph(450,300);

//Definimos el titulo
$grafico->title->Set("Mi primer grafico de tarta");
$grafico->title->SetFont(FF_FONT1,FS_BOLD);

//Añadimos el titulo y la leyenda
$p1 = new PiePlot($datos);
$p1->SetLegends($leyenda);
$p1->SetCenter(0.4);

//Se muestra el grafico
$grafico->Add($p1);
$grafico->Stroke();


?>