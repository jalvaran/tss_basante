<?php
$host="localhost";
$user="root";
$pw="pirlo1985";
$db="softcontech_v4";

$servi='localhost';
$baseDatos='softcontech_v4';
$usuario='root';
$passsword='pirlo1985';	

$COMPrinter="COM6";

class conexion {
  private $server;
  private $database;
  private $usuario;
  private $pass;
  
  public function __construct($server,$database, $usuario, $pass)
  {
    $this->server=$server;
	$this->database=$database;
	$this->usuario=$usuario;
	$this->pass=$pass;
	
  }
  public function conectar()
  {
    $con=mysql_connect($this->server,$this->usuario,$this->pass) or die("problemas con el servidor");
	mysql_select_db($this->database,$con) or die("la base de datos no abre clase");
	return($con);
  }
  function desconectar() {
	##############################
	
		mysql_close();
		print("desconectado");

	}
  
}


?>