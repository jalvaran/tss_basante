-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `centrocosto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `EmpresaPro` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `cierres_contables` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `idUsuario` bigint(20) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `ciuu` (
  `Codigo` int(11) NOT NULL,
  `Descripcion` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `clasecuenta` (
  `PUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Clase` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Valor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`PUC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `cod_municipios_dptos` (
  `ID` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Cod_mcipio` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Cod_Dpto` int(11) NOT NULL,
  `Departamento` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Ciudad` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `configuraciones_nombres_campos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `NombreDB` varchar(50) NOT NULL,
  `Visualiza` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `configuracion_general` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `Valor` text COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `cuentas` (
  `idPUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Valor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `GupoCuentas_PUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idPUC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `cuentasfrecuentes` (
  `CuentaPUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `ClaseCuenta` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `UsoFuturo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`CuentaPUC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `empresapro` (
  `idEmpresaPro` int(11) NOT NULL AUTO_INCREMENT,
  `RazonSocial` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NIT` bigint(20) DEFAULT NULL,
  `DV` int(11) NOT NULL,
  `CodigoPrestadora` bigint(20) NOT NULL,
  `Direccion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Celular` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Ciudad` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `CodigoDANE` int(11) NOT NULL,
  `ResolucionDian` text COLLATE utf8_spanish_ci NOT NULL,
  `Regimen` enum('SIMPLIFICADO','COMUN') COLLATE utf8_spanish_ci DEFAULT 'SIMPLIFICADO',
  `Email` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `WEB` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ObservacionesLegales` text COLLATE utf8_spanish_ci NOT NULL,
  `PuntoEquilibrio` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `DatosBancarios` text COLLATE utf8_spanish_ci NOT NULL,
  `RutaImagen` varchar(200) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'LogosEmpresas/logotipo1.png',
  `FacturaSinInventario` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `CXPAutomaticas` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'SI',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idEmpresaPro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `empresapro_regimenes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Regimen` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `empresa_pro_sucursales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Ciudad` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Direccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `idEmpresaPro` int(11) NOT NULL,
  `Visible` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `Actual` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `idServidor` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `estadosfinancieros_mayor_temporal` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FechaCorte` date NOT NULL,
  `Clase` int(11) NOT NULL,
  `CuentaPUC` bigint(20) NOT NULL,
  `NombreCuenta` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `SaldoAnterior` double NOT NULL,
  `Neto` double NOT NULL,
  `SaldoFinal` double NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `formatos_calidad` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `Version` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Codigo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Fecha` date NOT NULL,
  `CuerpoFormato` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `NotasPiePagina` text COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `gupocuentas` (
  `PUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Valor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ClaseCuenta_PUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`PUC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `librodiario` (
  `idLibroDiario` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Tipo_Documento_Intero` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Num_Documento_Interno` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Num_Documento_Externo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Tipo_Documento` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Tercero_Identificacion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Tercero_DV` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Primer_Apellido` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Segundo_Apellido` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Primer_Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Otros_Nombres` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Razon_Social` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Direccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Cod_Dpto` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Cod_Mcipio` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Tercero_Pais_Domicilio` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Concepto` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaPUC` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NombreCuenta` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Detalle` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Debito` double DEFAULT NULL,
  `Credito` double DEFAULT NULL,
  `Neto` double DEFAULT NULL,
  `Mayor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Esp` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idCentroCosto` int(11) NOT NULL,
  `idEmpresa` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `Estado` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idLibroDiario`),
  KEY `Tipo_Documento_Intero` (`Tipo_Documento_Intero`),
  KEY `Tercero_Identificacion` (`Tercero_Identificacion`),
  KEY `Num_Documento_Interno` (`Num_Documento_Interno`),
  KEY `CuentaPUC` (`CuentaPUC`),
  KEY `Fecha` (`Fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `libromayorbalances` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaInicial` date NOT NULL,
  `FechaFinal` date NOT NULL,
  `CuentaPUC` bigint(20) DEFAULT NULL,
  `NombreCuenta` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `SaldoAnterior` double NOT NULL,
  `Debito` double DEFAULT NULL,
  `Credito` double DEFAULT NULL,
  `NuevoSaldo` double NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `menu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `idCarpeta` int(11) NOT NULL,
  `Pagina` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `Target` varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT '_SELF',
  `Estado` int(1) NOT NULL DEFAULT '1',
  `Image` text COLLATE utf8_spanish_ci NOT NULL,
  `CSS_Clase` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Orden` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `menu_carpetas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ruta` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `menu_pestanas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idMenu` int(11) NOT NULL,
  `Orden` int(11) NOT NULL,
  `Estado` bit(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `menu_submenus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `idPestana` int(11) NOT NULL,
  `idCarpeta` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `TablaAsociada` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `TipoLink` int(1) NOT NULL,
  `JavaScript` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `Pagina` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Target` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` bit(1) NOT NULL,
  `Image` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Orden` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `paginas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `TipoPagina` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Visible` tinyint(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `paginas_bloques` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TipoUsuario` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Pagina` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Habilitado` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'SI',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `parametros_contables` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `CuentaPUC` bigint(20) NOT NULL,
  `NombreCuenta` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `parametros_generales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KardexCotizacion` bit(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `plataforma_tablas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `registra_ediciones` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Tabla` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Campo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ValorAnterior` text COLLATE utf8_spanish_ci NOT NULL,
  `ValorNuevo` text COLLATE utf8_spanish_ci NOT NULL,
  `ConsultaRealizada` text COLLATE utf8_spanish_ci NOT NULL,
  `idUsuario` bigint(20) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `registra_eliminaciones` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Campo` text COLLATE utf8_spanish_ci NOT NULL,
  `Valor` text COLLATE utf8_spanish_ci NOT NULL,
  `Causal` text COLLATE utf8_spanish_ci NOT NULL,
  `TablaOrigen` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `idTabla` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idItemEliminado` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUsuario` bigint(20) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `TablaOrigen` (`TablaOrigen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `registro_actualizacion_facturas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FacturaAnterior` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaNueva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `respuestas_condicional` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Valor` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_archivo_conceptos_glosas` (
  `id_concepto_glosa` int(20) NOT NULL AUTO_INCREMENT,
  `cod_glosa` int(3) NOT NULL COMMENT 'Codigo de glosa, Devolucion o Respuestas',
  `cod_concepto_general` int(1) NOT NULL COMMENT 'Concepto general de la glosa',
  `descripcion_concepto_general` text COLLATE utf8_spanish_ci NOT NULL,
  `tipo_concepto_general` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `aplicacion_concepto_general` text COLLATE utf8_spanish_ci NOT NULL,
  `cod_concepto_especifico` int(2) unsigned zerofill NOT NULL COMMENT 'Concepto especifico de la glosa',
  `descrpcion_concep_especifico` text CHARACTER SET utf8 NOT NULL COMMENT 'Descripción de la glosa ',
  `aplicacion` text CHARACTER SET utf8 NOT NULL COMMENT 'Aplica cuando:',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_concepto_glosa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT COMMENT='manual unico glosas,devoluciones y resp Ver Anexo tecn #6';


CREATE TABLE `salud_archivo_consultas` (
  `id_consultas` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_consulta` date NOT NULL COMMENT 'Fecha de la consulta " Ver Alineamientos tecnicos para ips ver pag 20"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_consulta` varchar(7) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código de la consulta " Ver Alineamientos tecnicos para ips ver pag 20"',
  `finalidad_consulta` enum('01','02','03','04','05','06','07','08','09','10') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Finalidad de la consulta " Ver Alineamientos tecnicos para ips ver pag 25"',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 26"',
  `cod_diagnostico_principal` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código del diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 26"',
  `cod_diagnostico_relacionado1` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 1 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `cod_diagnostico_relacionado2` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 2 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `cod_diagnostico_relacionado3` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 3 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `tipo_diagn_principal` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_consulta` double(15,2) NOT NULL COMMENT 'Valor de la consulta " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_cuota_moderadora` double(15,2) NOT NULL COMMENT 'Valor de la cuota moderadora " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_neto_pagar_consulta` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la consulta " Ver Alineamientos tecnicos para ips ver pag 28"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_consultas`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_ident_usuario` (`num_ident_usuario`),
  KEY `num_factura_2` (`num_factura`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de cosnultas AC';


CREATE TABLE `salud_archivo_consultas_temp` (
  `id_consultas` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_consulta` date NOT NULL COMMENT 'Fecha de la consulta " Ver Alineamientos tecnicos para ips ver pag 20"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_consulta` varchar(7) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código de la consulta " Ver Alineamientos tecnicos para ips ver pag 20"',
  `finalidad_consulta` enum('01','02','03','04','05','06','07','08','09','10') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Finalidad de la consulta " Ver Alineamientos tecnicos para ips ver pag 25"',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 26"',
  `cod_diagnostico_principal` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código del diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 26"',
  `cod_diagnostico_relacionado1` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 1 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `cod_diagnostico_relacionado2` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 2 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `cod_diagnostico_relacionado3` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado No. 3 " Ver Alineamientos tecnicos para ips ver pag 27"',
  `tipo_diagn_principal` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_consulta` double(15,2) NOT NULL COMMENT 'Valor de la consulta " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_cuota_moderadora` double(15,2) NOT NULL COMMENT 'Valor de la cuota moderadora " Ver Alineamientos tecnicos para ips ver pag 28"',
  `valor_neto_pagar_consulta` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la consulta " Ver Alineamientos tecnicos para ips ver pag 28"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_consultas` (`id_consultas`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de cosnultas AC';


CREATE TABLE `salud_archivo_control_glosas_respuestas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `idGlosa` bigint(20) NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `cod_glosa_general` int(1) NOT NULL COMMENT 'Código de la glosa general"',
  `cod_glosa_especifico` int(2) unsigned zerofill NOT NULL COMMENT 'Código de la glosa especifico&amp;quot;',
  `id_cod_glosa` bigint(12) NOT NULL COMMENT 'id del Código de la glosa"',
  `CodigoActividad` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Codigo de la actividad glosada&quot;',
  `DescripcionActividad` text COLLATE utf8_spanish_ci NOT NULL,
  `EstadoGlosa` int(2) NOT NULL,
  `FechaIPS` date NOT NULL COMMENT 'Fecha de expedición de la factura &quot; Ver Alineamientos tecnicos para ips ver pag 13&quot;',
  `FechaAuditoria` date NOT NULL COMMENT 'Fecha de expedición de la factura &amp;quot; Ver Alineamientos tecnicos para ips ver pag 13&amp;quot;',
  `valor_actividad` double NOT NULL COMMENT 'Valor total del pago compartido copago &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_glosado_eps` double NOT NULL COMMENT 'Valor de la comisión &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_levantado_eps` double NOT NULL COMMENT 'Valor total de descuentos &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_aceptado_ips` double NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `observacion_auditor` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `Soporte` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ruta de Archivo de comprobación de radicado',
  `fecha_registo` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `TipoArchivo` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Tratado` int(1) NOT NULL,
  `EstadoGlosaHistorico` int(3) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `num_factura` (`num_factura`),
  KEY `EstadoGlosa` (`EstadoGlosa`),
  KEY `CuentaGlobal` (`CuentaGlobal`),
  KEY `CuentaRIPS` (`CuentaRIPS`),
  KEY `idGlosa` (`idGlosa`),
  KEY `Tratado` (`Tratado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo Control de glosas y respuestas';


CREATE TABLE `salud_archivo_control_glosas_respuestas_temp` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `idGlosa` bigint(20) NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `cod_glosa_general` int(1) NOT NULL COMMENT 'Código de la glosa general"',
  `cod_glosa_especifico` int(2) unsigned zerofill NOT NULL COMMENT 'Código de la glosa especifico&amp;quot;',
  `id_cod_glosa` bigint(12) NOT NULL COMMENT 'id del Código de la glosa"',
  `CodigoActividad` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Codigo de la actividad glosada&quot;',
  `DescripcionActividad` text COLLATE utf8_spanish_ci NOT NULL,
  `EstadoGlosa` int(2) NOT NULL,
  `FechaIPS` date NOT NULL COMMENT 'Fecha de expedición de la factura &quot; Ver Alineamientos tecnicos para ips ver pag 13&quot;',
  `FechaAuditoria` date NOT NULL COMMENT 'Fecha de expedición de la factura &amp;quot; Ver Alineamientos tecnicos para ips ver pag 13&amp;quot;',
  `valor_actividad` double NOT NULL COMMENT 'Valor total del pago compartido copago &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_glosado_eps` double NOT NULL COMMENT 'Valor de la comisión &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_levantado_eps` double NOT NULL COMMENT 'Valor total de descuentos &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `valor_aceptado_ips` double NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante &quot; Ver Alineamientos tecnicos para ips ver pag 14&quot;',
  `observacion_auditor` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `Soporte` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ruta de Archivo de comprobación de radicado',
  `fecha_registo` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `TipoArchivo` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `num_factura` (`num_factura`),
  KEY `EstadoGlosa` (`EstadoGlosa`),
  KEY `CuentaGlobal` (`CuentaGlobal`),
  KEY `CuentaRIPS` (`CuentaRIPS`),
  KEY `idGlosa` (`idGlosa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo Control de glosas y respuestas';


CREATE TABLE `salud_archivo_ct` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `fecha_emision` date NOT NULL COMMENT 'Fecha de envio de datos " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_archivo` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el numero de cuenta asociado "Ver Alineamientos tecnicos para ips ver pag 12"',
  `total_registros` int(11) NOT NULL COMMENT 'cantidad de registros por acrhivo Ver Alineamientos tecnicos para ips ver pag 12',
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de control';


CREATE TABLE `salud_archivo_facturacion_mov_generados` (
  `id_fac_mov_generados` int(20) NOT NULL AUTO_INCREMENT,
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `razon_social` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Razón social o apellidos y nombre del prestado  " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_prest_servicio` enum('NI','CC','CE','PA') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_ident_prest_servicio` bigint(20) NOT NULL COMMENT 'Número de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `fecha_factura` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_final` date NOT NULL COMMENT 'Fecha final " Ver Alineamientos tecnicos para ips ver pag 13"',
  `cod_enti_administradora` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13"',
  `nom_enti_administradora` varchar(200) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `num_contrato` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número del contrato " Ver Alineamientos tecnicos para ips ver pag 13"',
  `plan_beneficios` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Plan de beneficios " Ver Alineamientos tecnicos para ips ver pag 13"',
  `num_poliza` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número de la póliza " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `valor_total_pago` double(15,2) NOT NULL COMMENT 'Valor total del pago compartido copago " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_comision` double(15,2) NOT NULL COMMENT 'Valor de la comisión " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_descuentos` double(15,2) NOT NULL COMMENT 'Valor total de descuentos " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_neto_pagar` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre con el que se hizo el cargue al sistema de cartera',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha Y Hora que se hizo el cargue',
  `idUser` int(11) NOT NULL,
  `eps_radicacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Eps en la que se radico la factura',
  `dias_pactados` int(2) DEFAULT NULL COMMENT 'Dias que se pactaron para el pago de la factura con eps',
  `fecha_radicado` date DEFAULT NULL COMMENT 'Fecha de la radicacion de la factura',
  `numero_radicado` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero con que se radico la factura',
  `Soporte` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ruta de Archivo de comprobación de radicado',
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Indica en que tabla esta el registro en un momento dado ',
  `EstadoCobro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Arma030Anterior` enum('S','N') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `Escenario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_fac_mov_generados`),
  UNIQUE KEY `num_factura` (`num_factura`),
  KEY `estado` (`estado`),
  KEY `tipo_negociacion` (`tipo_negociacion`),
  KEY `Updated` (`Updated`),
  KEY `EstadoCobro` (`EstadoCobro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de transacciones_AF_generadas';


CREATE TABLE `salud_archivo_facturacion_mov_pagados` (
  `id_pagados` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura ',
  `idEPS` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `nom_enti_administradora` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_pago_factura` date NOT NULL COMMENT 'Fecha de pago de la factura',
  `num_pago` int(10) NOT NULL COMMENT 'Número del comprobante del pago ',
  `valor_bruto_pagar` double(15,2) NOT NULL COMMENT 'Valor bruto a pagar',
  `valor_descuento` double(15,2) NOT NULL COMMENT 'Valor descuento',
  `valor_iva` double(15,2) NOT NULL COMMENT 'Valor iva',
  `valor_retefuente` double(15,2) NOT NULL COMMENT 'Valor retefuente',
  `valor_reteiva` double(15,2) NOT NULL COMMENT 'Valor reteiva',
  `valor_reteica` double(15,2) NOT NULL COMMENT 'Valor reteica',
  `valor_otrasretenciones` double(15,2) NOT NULL COMMENT 'Valor otras retenciones',
  `valor_cruces` double(15,2) NOT NULL COMMENT 'Valor de cruces posible glosas ',
  `valor_anticipos` double(15,2) NOT NULL COMMENT 'Valor de anticipos ',
  `valor_pagado` double(15,2) NOT NULL COMMENT 'Valor transferidoa banco ',
  `tipo_negociacion` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Proceso` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Arma030Anterior` enum('S','N') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `NumeroFacturaAdres` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `SubeDesde` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_pagados`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de transacciones_AF_pagadas';


CREATE TABLE `salud_archivo_facturacion_mov_pagados_temp` (
  `id_pagados` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura ',
  `fecha_pago_factura` date NOT NULL COMMENT 'Fecha de pago de la factura',
  `num_pago` int(10) NOT NULL COMMENT 'Número del comprobante del pago ',
  `valor_bruto_pagar` double(15,2) NOT NULL COMMENT 'Valor bruto a pagar',
  `valor_descuento` double(15,2) NOT NULL COMMENT 'Valor descuento',
  `valor_iva` double(15,2) NOT NULL COMMENT 'Valor iva',
  `valor_retefuente` double(15,2) NOT NULL COMMENT 'Valor retefuente',
  `valor_reteiva` double(15,2) NOT NULL COMMENT 'Valor reteiva',
  `valor_reteica` double(15,2) NOT NULL COMMENT 'Valor reteica',
  `valor_otrasretenciones` double(15,2) NOT NULL COMMENT 'Valor otras retenciones',
  `valor_cruces` double(15,2) NOT NULL COMMENT 'Valor de cruces posible glosas ',
  `valor_anticipos` double(15,2) NOT NULL COMMENT 'Valor de anticipos ',
  `valor_pagado` double(15,2) NOT NULL COMMENT 'Valor transferidoa banco ',
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Estado` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_temp_rips_pagados` (`id_pagados`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_factura` (`num_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo temporal de rips pagados';


CREATE TABLE `salud_archivo_hospitalizaciones` (
  `id_hospitalizacion` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `via_ingreso` enum('1','2','3','4') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Vía de ingreso a la institución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `fecha_ingreso_hospi` date NOT NULL COMMENT 'Fecha de ingreso del usuario a la institución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `hora_ingreso` time NOT NULL COMMENT 'Hora de ingreso del usuario a la\r\nInstitución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 32" ',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagn_princ_ingreso` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal de ingreso " Ver Alineamientos tecnicos para ips ver pag 36"',
  `diagn_princ_egreso` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac1_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 1 de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac2_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 2 de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac3_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 3 de egreso " Ver Alineamientos tecnicos para ips ver pag 38"',
  `diagn_complicacion` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico de la complicación " Ver Alineamientos tecnicos para ips ver pag 38"',
  `estado_salida_hospi` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Estado a la salida " Ver Alineamientos tecnicos para ips ver pag 38"',
  `diagn_causa_muerte` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico de la causa básica de muerte " Ver Alineamientos tecnicos para ips ver pag 38"',
  `fecha_salida_hospi` date NOT NULL COMMENT 'Fecha de egreso del usuario a la institución " Ver Alineamientos tecnicos para ips ver pag 38"',
  `hora_salida_hospi` time NOT NULL COMMENT 'Hora de egreso del usuario de la institución " Ver Alineamientos tecnicos para ips ver pag 38"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_hospitalizacion`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de hospitalización AH';


CREATE TABLE `salud_archivo_hospitalizaciones_temp` (
  `id_hospitalizacion` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `via_ingreso` enum('1','2','3','4') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Vía de ingreso a la institución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `fecha_ingreso_hospi` date NOT NULL COMMENT 'Fecha de ingreso del usuario a la institución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `hora_ingreso` time NOT NULL COMMENT 'Hora de ingreso del usuario a la\r\nInstitución " Ver Alineamientos tecnicos para ips ver pag 35"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 32" ',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagn_princ_ingreso` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal de ingreso " Ver Alineamientos tecnicos para ips ver pag 36"',
  `diagn_princ_egreso` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac1_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 1 de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac2_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 2 de egreso " Ver Alineamientos tecnicos para ips ver pag 37"',
  `diagn_relac3_egreso` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 3 de egreso " Ver Alineamientos tecnicos para ips ver pag 38"',
  `diagn_complicacion` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico de la complicación " Ver Alineamientos tecnicos para ips ver pag 38"',
  `estado_salida_hospi` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Estado a la salida " Ver Alineamientos tecnicos para ips ver pag 38"',
  `diagn_causa_muerte` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico de la causa básica de muerte " Ver Alineamientos tecnicos para ips ver pag 38"',
  `fecha_salida_hospi` date NOT NULL COMMENT 'Fecha de egreso del usuario a la institución " Ver Alineamientos tecnicos para ips ver pag 38"',
  `hora_salida_hospi` time NOT NULL COMMENT 'Hora de egreso del usuario de la institución " Ver Alineamientos tecnicos para ips ver pag 38"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_hospitalizacion` (`id_hospitalizacion`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de hospitalización AH';


CREATE TABLE `salud_archivo_medicamentos` (
  `id_medicamentos` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_medicamento` varchar(20) COLLATE utf8_spanish_ci DEFAULT '8' COMMENT 'Código del medicamento &quot; Ver Alineamientos tecnicos para ips ver pag 41&quot;',
  `tipo_medicamento` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de medicamento " Ver Alineamientos tecnicos para ips ver pag 41"',
  `forma_farmaceutica` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Forma farmacéutica" Ver Alineamientos tecnicos para ips ver pag 41"',
  `nom_medicamento` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre genérico del medicamento " Ver Alineamientos tecnicos para ips ver pag 41"',
  `concentracion_medic` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Concentración del medicamento" Ver Alineamientos tecnicos para ips ver pag 41"',
  `um_medicamento` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Unidad de medida del medicamento" Ver Alineamientos tecnicos para ips ver pag 41"',
  `num_und_medic` int(5) NOT NULL COMMENT 'Número de unidades" Ver Alineamientos tecnicos para ips ver pag 41"',
  `valor_unit_medic` double(15,2) NOT NULL COMMENT 'Valor unitario de medicamento " Ver Alineamientos tecnicos para ips ver pag 42"',
  `valor_total_medic` double(15,2) NOT NULL COMMENT 'Valor total del medicamento " Ver Alineamientos tecnicos para ips ver pag 42"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_medicamentos`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de medicamentos AM';


CREATE TABLE `salud_archivo_medicamentos_temp` (
  `id_medicamentos` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_medicamento` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del medicamento " Ver Alineamientos tecnicos para ips ver pag 41"',
  `tipo_medicamento` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de medicamento " Ver Alineamientos tecnicos para ips ver pag 41"',
  `forma_farmaceutica` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Forma farmacéutica" Ver Alineamientos tecnicos para ips ver pag 41"',
  `nom_medicamento` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre genérico del medicamento " Ver Alineamientos tecnicos para ips ver pag 41"',
  `concentracion_medic` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Concentración del medicamento" Ver Alineamientos tecnicos para ips ver pag 41"',
  `um_medicamento` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Unidad de medida del medicamento" Ver Alineamientos tecnicos para ips ver pag 41"',
  `num_und_medic` int(5) NOT NULL COMMENT 'Número de unidades" Ver Alineamientos tecnicos para ips ver pag 41"',
  `valor_unit_medic` double(15,2) NOT NULL COMMENT 'Valor unitario de medicamento " Ver Alineamientos tecnicos para ips ver pag 42"',
  `valor_total_medic` double(15,2) NOT NULL COMMENT 'Valor total del medicamento " Ver Alineamientos tecnicos para ips ver pag 42"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_medicamentos` (`id_medicamentos`),
  KEY `num_factura` (`num_factura`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `nom_cargue_2` (`nom_cargue`),
  KEY `num_factura_2` (`num_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de medicamentos AM';


CREATE TABLE `salud_archivo_nacidos` (
  `id_recien_nacido` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación de la madre " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación de la madre en el Sistema " Ver Alineamientos tecnicos para ips ver pag 39"',
  `fecha_nacimiento_rn` date NOT NULL COMMENT 'Fecha de nacimiento del recién nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `hora_nacimiento_rc` time NOT NULL COMMENT 'Hora de nacimiento " Ver Alineamientos tecnicos para ips ver pag 39"',
  `edad_gestacional` int(2) NOT NULL COMMENT 'Edad gestacional " Ver Alineamientos tecnicos para ips ver pag 39 "',
  `control_prenatal` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Control prenatal " Ver Alineamientos tecnicos para ips ver pag 39"',
  `sexo_rc` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Sexo del recien nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `peso_rc` int(4) NOT NULL COMMENT 'Peso del recien nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `diagn_rc` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico del recién nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `causa_muerte_rc` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Causa básica de muerte recien nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `fecha_muerte_rc` date NOT NULL COMMENT 'Fecha de muerte del recién nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `hora_muerte_rc` time NOT NULL COMMENT 'Hora de muerte del recién nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_recien_nacido`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de recien nacidos AN';


CREATE TABLE `salud_archivo_nacidos_temp` (
  `id_recien_nacido` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación de la madre " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación de la madre en el Sistema " Ver Alineamientos tecnicos para ips ver pag 39"',
  `fecha_nacimiento_rn` date NOT NULL COMMENT 'Fecha de nacimiento del recién nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `hora_nacimiento_rc` time NOT NULL COMMENT 'Hora de nacimiento " Ver Alineamientos tecnicos para ips ver pag 39"',
  `edad_gestacional` int(2) NOT NULL COMMENT 'Edad gestacional " Ver Alineamientos tecnicos para ips ver pag 39 "',
  `control_prenatal` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Control prenatal " Ver Alineamientos tecnicos para ips ver pag 39"',
  `sexo_rc` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Sexo del recien nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `peso_rc` int(4) NOT NULL COMMENT 'Peso del recien nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `diagn_rc` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico del recién nacido " Ver Alineamientos tecnicos para ips ver pag 39"',
  `causa_muerte_rc` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Causa básica de muerte recien nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `fecha_muerte_rc` date NOT NULL COMMENT 'Fecha de muerte del recién nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `hora_muerte_rc` time NOT NULL COMMENT 'Hora de muerte del recién nacido " Ver Alineamientos tecnicos para ips ver pag 40"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_recien_nacido` (`id_recien_nacido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de recien nacidos AN';


CREATE TABLE `salud_archivo_otros_servicios` (
  `id_otro_servicios` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `tipo_servicio` enum('1','2','3','4') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de servicio " Ver Alineamientos tecnicos para ips ver pag 43"',
  `cod_servicio` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del servicio " Ver Alineamientos tecnicos para ips ver pag 44"',
  `nom_servicio` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del servicio " Ver Alineamientos tecnicos para ips ver pag 44"',
  `cantidad` int(5) NOT NULL COMMENT 'Cantidad" Ver Alineamientos tecnicos para ips ver pag 44"',
  `valor_unit_material` double(15,2) NOT NULL COMMENT 'Valor unitario del material e insumo " Ver Alineamientos tecnicos para ips ver pag 45"',
  `valor_total_material` double(15,2) NOT NULL COMMENT 'Valor total del material e insumo " Ver Alineamientos tecnicos para ips ver pag 45"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_otro_servicios`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_factura` (`num_factura`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de otros servicios AT';


CREATE TABLE `salud_archivo_otros_servicios_temp` (
  `id_otro_servicios` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `tipo_servicio` enum('1','2','3','4') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de servicio " Ver Alineamientos tecnicos para ips ver pag 43"',
  `cod_servicio` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del servicio " Ver Alineamientos tecnicos para ips ver pag 44"',
  `nom_servicio` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del servicio " Ver Alineamientos tecnicos para ips ver pag 44"',
  `cantidad` int(5) NOT NULL COMMENT 'Cantidad" Ver Alineamientos tecnicos para ips ver pag 44"',
  `valor_unit_material` double(15,2) NOT NULL COMMENT 'Valor unitario del material e insumo " Ver Alineamientos tecnicos para ips ver pag 45"',
  `valor_total_material` double(15,2) NOT NULL COMMENT 'Valor total del material e insumo " Ver Alineamientos tecnicos para ips ver pag 45"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_otro_servicios` (`id_otro_servicios`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_factura` (`num_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de otros servicios AT';


CREATE TABLE `salud_archivo_procedimientos` (
  `id_procedimiento` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_procedimiento` date NOT NULL COMMENT 'Fecha del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_procedimiento` varchar(7) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `ambito_reali_procedimiento` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Ámbito de realización del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `finalidad_procedimiento` enum('1','2','3','4','5') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Finalidad del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `personal_atiende` enum('1','2','3','4','5') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Personal que atiende " Ver Alineamientos tecnicos para ips ver pag 29"',
  `cod_diagn_princ_procedimiento` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 29"',
  `cod_diagn_relac_procedimiento` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado " Ver Alineamientos tecnicos para ips ver pag 30"',
  `complicaciones` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Complicación " Ver Alineamientos tecnicos para ips ver pag 30"',
  `realizacion_quirurgico` enum('1','2','3','4','5') COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Forma de realización del acto quirúrgico " Ver Alineamientos tecnicos para ips ver pag 30"',
  `valor_procedimiento` double(15,2) DEFAULT NULL COMMENT 'Valor del procedimiento " Ver Alineamientos tecnicos para ips ver pag 31"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_procedimiento`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_factura` (`num_factura`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de procedimientos AP';


CREATE TABLE `salud_archivo_procedimientos_temp` (
  `id_procedimiento` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_procedimiento` date NOT NULL COMMENT 'Fecha del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 20" ',
  `cod_procedimiento` varchar(7) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `ambito_reali_procedimiento` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Ámbito de realización del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `finalidad_procedimiento` enum('1','2','3','4','5') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Finalidad del procedimiento " Ver Alineamientos tecnicos para ips ver pag 29"',
  `personal_atiende` enum('1','2','3','4','5') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Personal que atiende " Ver Alineamientos tecnicos para ips ver pag 29"',
  `cod_diagn_princ_procedimiento` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico principal " Ver Alineamientos tecnicos para ips ver pag 29"',
  `cod_diagn_relac_procedimiento` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Código del diagnóstico relacionado " Ver Alineamientos tecnicos para ips ver pag 30"',
  `complicaciones` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Complicación " Ver Alineamientos tecnicos para ips ver pag 30"',
  `realizacion_quirurgico` enum('1','2','3','4','5') COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Forma de realización del acto quirúrgico " Ver Alineamientos tecnicos para ips ver pag 30"',
  `valor_procedimiento` double(15,2) DEFAULT NULL COMMENT 'Valor del procedimiento " Ver Alineamientos tecnicos para ips ver pag 31"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_procedimiento` (`id_procedimiento`),
  KEY `nom_cargue` (`nom_cargue`),
  KEY `num_factura` (`num_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de procedimientos AP';


CREATE TABLE `salud_archivo_urgencias` (
  `id_urgencias` int(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de ingreso del usuario a observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `hora_ingreso` time NOT NULL COMMENT 'Hora de ingreso del usuario a observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 32" ',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagnostico_salida` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico a la salida " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagn_relac1_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 1 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `diagn_relac2_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 2 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `diagn_relac3_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 3 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `destino_usuario` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Destino del usuario a la salida de observacion " Ver Alineamientos tecnicos para ips ver pag 29"',
  `estado_salida` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Estado a la salida " Ver Alineamientos tecnicos para ips ver pag 34"',
  `causa_muerte` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Causa básica de muerte en urgencias " Ver Alineamientos tecnicos para ips ver pag 34"',
  `fecha_salida` date NOT NULL COMMENT 'Fecha de la salida del usuario en observación " Ver Alineamientos tecnicos para ips ver pag 34"',
  `hora_salida` time NOT NULL COMMENT 'Hora de la salida del usuario en observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `tipo_negociacion` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_urgencias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de urgencias AU';


CREATE TABLE `salud_archivo_urgencias_temp` (
  `id_urgencias` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `fecha_ingreso` date NOT NULL COMMENT 'Fecha de ingreso del usuario a observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `hora_ingreso` time NOT NULL COMMENT 'Hora de ingreso del usuario a observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `num_autorizacion` int(15) DEFAULT NULL COMMENT 'Número de autorización " Ver Alineamientos tecnicos para ips ver pag 32" ',
  `causa_externa` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Causa externa " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagnostico_salida` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnóstico a la salida " Ver Alineamientos tecnicos para ips ver pag 32"',
  `diagn_relac1_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 1 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `diagn_relac2_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 2 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `diagn_relac3_salida` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Diagnóstico relacionado Nro. 3 a la salida " Ver Alineamientos tecnicos para ips ver pag 33"',
  `destino_usuario` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Destino del usuario a la salida de observacion " Ver Alineamientos tecnicos para ips ver pag 29"',
  `estado_salida` enum('1','2') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Estado a la salida " Ver Alineamientos tecnicos para ips ver pag 34"',
  `causa_muerte` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Causa básica de muerte en urgencias " Ver Alineamientos tecnicos para ips ver pag 34"',
  `fecha_salida` date NOT NULL COMMENT 'Fecha de la salida del usuario en observación " Ver Alineamientos tecnicos para ips ver pag 34"',
  `hora_salida` time NOT NULL COMMENT 'Hora de la salida del usuario en observación " Ver Alineamientos tecnicos para ips ver pag 31"',
  `tipo_negociacion` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de urgencias AU';


CREATE TABLE `salud_archivo_usuarios` (
  `id_usuarios_salud` int(20) NOT NULL AUTO_INCREMENT,
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `cod_ident_adm_pb` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora de planes de beneficio (EPS) " Ver Alineamientos tecnicos para ips ver pag 16"',
  `tipo_usuario` enum('1','2','3','4','5','6','7','8') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de usuario " Ver Alineamientos tecnicos para ips ver pag 16"',
  `primer_ape_usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Primer apellido del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `segundo_ape_usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Segundo apellido del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `primer_nom_usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Primer nombre del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `segundo_nom_usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Segundo nombre del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `edad` int(3) NOT NULL COMMENT 'Edad " Ver Alineamientos tecnicos para ips ver pag 17 "',
  `unidad_medida_edad` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Unidad de medida de la edad " Ver Alineamientos tecnicos para ips ver pag 17"',
  `sexo` enum('M','F') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Sexo del usuario " Ver Alineamientos tecnicos para ips ver pag 18"',
  `cod_depa_residencial` int(2) NOT NULL COMMENT 'Código del departamento de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `cod_muni_residencial` int(3) NOT NULL COMMENT 'Código del municipio de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `zona_residencial` enum('U','R') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Zona de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_usuarios_salud`),
  KEY `num_ident_usuario` (`num_ident_usuario`),
  KEY `primer_ape_usuario` (`primer_ape_usuario`),
  KEY `segundo_ape_usuario` (`segundo_ape_usuario`),
  KEY `primer_nom_usuario` (`primer_nom_usuario`),
  KEY `segundo_nom_usuario` (`segundo_nom_usuario`),
  KEY `num_ident_usuario_2` (`num_ident_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT COMMENT='Archivo de usuarios US';


CREATE TABLE `salud_archivo_usuarios_temp` (
  `id_usuarios_salud` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `tipo_ident_usuario` enum('CC','CE','PA','RC','TI','AS','MS') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del usuario " Ver Alineamientos tecnicos para ips ver pag 14"',
  `num_ident_usuario` bigint(16) NOT NULL COMMENT 'Número de identificación del usuario del sistema " Ver Alineamientos tecnicos para ips ver pag 16"',
  `cod_ident_adm_pb` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora de planes de beneficio (EPS) " Ver Alineamientos tecnicos para ips ver pag 16"',
  `tipo_usuario` enum('1','2','3','4','5','6','7','8') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de usuario " Ver Alineamientos tecnicos para ips ver pag 16"',
  `primer_ape_usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Primer apellido del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `segundo_ape_usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Segundo apellido del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `primer_nom_usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Primer nombre del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `segundo_nom_usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Segundo nombre del usuario " Ver Alineamientos tecnicos para ips ver pag 17"',
  `edad` int(3) NOT NULL COMMENT 'Edad " Ver Alineamientos tecnicos para ips ver pag 17 "',
  `unidad_medida_edad` enum('1','2','3') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Unidad de medida de la edad " Ver Alineamientos tecnicos para ips ver pag 17"',
  `sexo` enum('M','F') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Sexo del usuario " Ver Alineamientos tecnicos para ips ver pag 18"',
  `cod_depa_residencial` int(2) NOT NULL COMMENT 'Código del departamento de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `cod_muni_residencial` int(3) NOT NULL COMMENT 'Código del municipio de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `zona_residencial` enum('U','R') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Zona de residencia habitual " Ver Alineamientos tecnicos para ips ver pag 18"',
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo con el que se carga',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha en que se carga',
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_usuarios_salud` (`id_usuarios_salud`),
  KEY `num_ident_usuario` (`num_ident_usuario`),
  KEY `primer_ape_usuario` (`primer_ape_usuario`),
  KEY `segundo_ape_usuario` (`segundo_ape_usuario`),
  KEY `primer_nom_usuario` (`primer_nom_usuario`),
  KEY `segundo_nom_usuario` (`segundo_nom_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci ROW_FORMAT=COMPACT COMMENT='Archivo de usuarios US';


CREATE TABLE `salud_bancos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `nit_banco` bigint(12) NOT NULL COMMENT 'NIT del banco',
  `banco_transaccion` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del banco donde entra la transaccion',
  `tipo_cuenta` enum('ahorros','corriente') COLLATE utf8_spanish_ci NOT NULL COMMENT 'tipo de cuenta',
  `num_cuenta_banco` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero de cuenta en la cual entra la transaccion',
  `observacion` text COLLATE utf8_spanish_ci COMMENT 'observaciones de diagnostico ',
  `fecha_hora_registro` datetime DEFAULT NULL COMMENT 'fecha y hora del registro',
  `user` int(2) DEFAULT NULL COMMENT 'usuario que registra',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de banco';


CREATE TABLE `salud_cartera_x_edades_temp` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `idEPS` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `RazonSocialEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Cantidad_1_30` int(11) NOT NULL,
  `Valor_1_30` double NOT NULL,
  `Cantidad_31_60` int(11) NOT NULL,
  `Valor_31_60` double NOT NULL,
  `Cantidad_61_90` int(11) NOT NULL,
  `Valor_61_90` double NOT NULL,
  `Cantidad_91_120` int(11) NOT NULL,
  `Valor_91_120` double NOT NULL,
  `Cantidad_121_180` int(11) NOT NULL,
  `Valor_121_180` double NOT NULL,
  `Cantidad_181_360` int(11) NOT NULL,
  `Valor_181_360` double NOT NULL,
  `Cantidad_360` int(11) NOT NULL,
  `Valor_360` double NOT NULL,
  `TotalFacturas` int(11) NOT NULL,
  `Total` double NOT NULL,
  `RegimenEPS` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `NIT_EPS` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_cie10` (
  `ID` int(20) NOT NULL,
  `codigo_sistema` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'SubCategoria de diagnostico ',
  `descripcion_cups` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Descrpcion de la Clasificacion internacional de Emfermedades ',
  `observacion` varchar(6) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'observaciones de CIE ',
  `fecha_hora_registro` datetime DEFAULT NULL COMMENT 'fecha y hora del registro',
  `user` int(2) DEFAULT NULL COMMENT 'usuario que registra',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de Clasificacion internacional de Emfermedades CIE10';


CREATE TABLE `salud_circular030_inicial` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `TipoRegistro` int(1) NOT NULL,
  `Consecutivo` int(10) NOT NULL,
  `tipo_ident_erp` enum('NI','MU','DE','DI') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de ERP Empresa responsable del pago" Ver circulra 030 anexo tecnico 2"',
  `num_ident_erp` bigint(12) NOT NULL COMMENT 'Número de identificación de ERP Empresa responsable del pago" Ver circulra 030 anexo tecnico 2"',
  `razon_social` varchar(250) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Razón social de ERP Empresa responsable del pago" Ver circulra 030 anexo tecnico 2"',
  `tipo_ident_ips` enum('NI') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación de ips " Ver circulra 030 anexo tecnico 2"',
  `num_ident_ips` bigint(12) NOT NULL COMMENT 'Número de identificación de ips " Ver circulra 030 anexo tecnico 2"',
  `tipo_cobro` enum('F','R') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de cobro " Ver circulra 030 anexo tecnico 2"',
  `pref_factura` varchar(6) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Prefijo factura o recobro " Ver circulra 030 anexo tecnico 2"',
  `num_factura` int(20) NOT NULL COMMENT 'Número de la factura o recobro " Ver circulra 030 anexo tecnico 2"',
  `indic_act_fact` enum('I','A','E') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Prefijo factura o recobro "Ver circulra 030 anexo tecnico 2"',
  `valor_factura` double(15,2) NOT NULL COMMENT 'Valor de la factura o recobro " Ver circulra 030 anexo tecnico 2"',
  `fecha_factura` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver circulra 030 anexo tecnico 2"',
  `fecha_radicado` date NOT NULL COMMENT 'Fecha de la radicacion de la factura " Ver circulra 030 anexo tecnico 2"',
  `fecha_devolucion` date DEFAULT NULL COMMENT 'Fecha de la devolucion de la factura " Ver circulra 030 anexo tecnico 2"',
  `valor_total_pagos` double(15,2) NOT NULL COMMENT 'Valor total de los pagos aplicados a la factura o recobro " Ver circulra 030 anexo tecnico 2"',
  `valor_glosa_acept` double(15,2) NOT NULL COMMENT 'Valor glosa aceptada de la factura o recobro segun la notificacion de la glosa de la ERP " Ver circulra 030 anexo tecnico 2"',
  `glosa_respondida` enum('SI','NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Glosa fue respondida " Ver circulra 030 anexo tecnico 2"',
  `saldo_factura` double(15,2) NOT NULL COMMENT 'Saldo pendiente de la cancelacion de la factura o recobro debe ser igual al valor de la factura o recobro menos la glosa aceptada y menos los pagos aplicados "Ver circulra 030 anexo tecnico 2"',
  `cobro_juridico` enum('SI','NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Factura o recobro se encuentra en cobro juridico" Ver circulra 030 anexo tecnico 2"',
  `etapa_proceso` enum('0','1','2','3','4','5','6','7','8') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Etapa en que se encuentra el proceso "Ver circulra 030 anexo tecnico 2"',
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `Cod_Entidad_Administradora` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Circular 030 inicial, esta debe ser otorgada por el cliente';


CREATE TABLE `salud_circular_030_control` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `TipoRegistro` int(1) NOT NULL,
  `TipoIdentificacion` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'NI',
  `NumIdentificacion` bigint(20) NOT NULL,
  `RazonSocial` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `FechaInicial` date NOT NULL,
  `FechaFinal` date NOT NULL,
  `NumRegistros` bigint(10) NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaGeneracion` datetime NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_cobros_prejuridicos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `TipoCobro` enum('1','2','3','4') COLLATE utf8_spanish_ci NOT NULL,
  `Fecha` date NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Soporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `Fecha` (`Fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_cobros_prejuridicos_relaciones` (
  `idCobroPrejuridico` int(11) NOT NULL,
  `num_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `idCobroPrejuridico` (`idCobroPrejuridico`),
  KEY `num_factura` (`num_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_conciliaciones_masivas_temp` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FechaConciliacion` date NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `num_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CodigoActividad` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `ValorLevantado` double NOT NULL,
  `ValorAceptado` double NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Conciliada` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_control_generacion_respuestas_excel` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `idEPS` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `num_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Generada` int(11) NOT NULL,
  `Soportes` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_control_glosas_masivas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `TipoArchivo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Analizado` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_cups` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(2) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Grupo de diagnostico ',
  `subgrupo` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'SubGrupo de diagnostico ',
  `categoria` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Categoria de diagnostico ',
  `subcategoria` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'SubCategoria de diagnostico ',
  `codigo_ley` varchar(9) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'SubCategoria de diagnostico ',
  `codigo_sistema` varchar(6) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'SubCategoria de diagnostico ',
  `descripcion_cups` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Descrpcion del C.U.P.S ',
  `Manual` int(11) NOT NULL DEFAULT '2',
  `observacion` varchar(6) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'observaciones de diagnostico ',
  `fecha_hora_registro` datetime DEFAULT NULL COMMENT 'fecha y hora del registro',
  `user` int(2) DEFAULT NULL COMMENT 'usuario que registra',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `codigo_sistema` (`codigo_sistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de cups';


CREATE TABLE `salud_dias_habiles` (
  `fecha_dia` date NOT NULL COMMENT 'fecha del dia',
  `dia` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo') COLLATE utf8_spanish_ci NOT NULL COMMENT 'dia de la semana',
  `tipo_dia` enum('festivo','dominical','sabatino','normal','') COLLATE utf8_spanish_ci NOT NULL COMMENT 'tipo de dia',
  `estado_dia` enum('habil','no habil','','') COLLATE utf8_spanish_ci NOT NULL COMMENT 'estado de dia es habil o no lo es',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla para controlar los días hábiles';


CREATE TABLE `salud_eps` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `cod_pagador_min` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Codigo del pagador ante el ministerio de salud ',
  `nit` bigint(20) NOT NULL,
  `sigla_nombre` varchar(120) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre corto del pagador del servicio salud',
  `nombre_completo` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre completo del pagador del servicio',
  `direccion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefonos` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_regimen` enum('SUBSIDIADO','CONTRIBUTIVO','REGIMEN ESPECIAL','ENTE TERRITORIAL','ENTE MUNICIPAL','OTRAS ENTIDADES','ENTIDAD EN LIQUIDACION') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de regimen',
  `dias_convenio` int(11) NOT NULL,
  `Nombre_gerente` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del gerente del pagador',
  `RepresentanteLegal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroRepresentanteLegal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Genera030` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  `Genera014` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  `Genera07` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `cod_pagador_min` (`cod_pagador_min`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='directorio de empresas promotoras de salud (EPS)';


CREATE TABLE `salud_estado_glosas` (
  `ID` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Id del estado glosa',
  `Estado_glosa` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Descripcion del estado',
  `idUser` int(11) NOT NULL COMMENT 'Usuario que registra el estado ',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha y Hora del registro',
  `Sync` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Ultima sincronizacion',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_facturas_radicacion_numero` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idFactura` bigint(20) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_glosas_iniciales` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaIPS` date NOT NULL,
  `FechaAuditoria` date NOT NULL,
  `FechaRegistro` date NOT NULL,
  `CodigoGlosa` int(3) NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CodigoActividad` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `EstadoGlosa` int(11) NOT NULL,
  `ValorActividad` double NOT NULL,
  `ValorGlosado` double NOT NULL,
  `ValorLevantado` double NOT NULL,
  `ValorAceptado` double NOT NULL,
  `ValorXConciliar` double NOT NULL,
  `ValorConciliado` double NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `num_factura` (`num_factura`),
  KEY `CodigoActividad` (`CodigoActividad`),
  KEY `CodigoGlosa` (`CodigoGlosa`),
  KEY `EstadoGlosa` (`EstadoGlosa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_glosas_iniciales_temp` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaIPS` date NOT NULL,
  `FechaAuditoria` date NOT NULL,
  `FechaRegistro` date NOT NULL,
  `CodigoGlosa` int(3) NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CodigoActividad` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `NombreActividad` text COLLATE utf8_spanish_ci NOT NULL,
  `EstadoGlosa` int(11) NOT NULL,
  `ValorActividad` double NOT NULL,
  `ValorGlosado` double NOT NULL,
  `ValorLevantado` double NOT NULL,
  `ValorAceptado` double NOT NULL,
  `ValorXConciliar` double NOT NULL,
  `ValorConciliado` double NOT NULL,
  `TipoArchivo` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `idArchivo` bigint(20) NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `num_factura` (`num_factura`),
  KEY `CodigoActividad` (`CodigoActividad`),
  KEY `CodigoGlosa` (`CodigoGlosa`),
  KEY `EstadoGlosa` (`EstadoGlosa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_glosas_masivas_temp` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaIPS` date NOT NULL,
  `FechaAuditoria` date NOT NULL,
  `ID_EPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NIT_EPS` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRips` int(6) unsigned zerofill NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CodigoActividad` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `ValorGlosado` double NOT NULL,
  `CodigoGlosa` int(4) NOT NULL,
  `CuentaGlobal` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Analizado` int(1) NOT NULL,
  `GlosaInicial` int(1) NOT NULL,
  `GlosaControlRespuestas` int(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_manuales_tarifarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_pagos_contributivo` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Proceso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NitEPS` bigint(20) NOT NULL,
  `CodigoEps` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaPago` date NOT NULL,
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `PrefijoFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaFactura` date NOT NULL,
  `FormaContratacion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ValorGiro` double NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Soporte` text COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `NitEPS` (`NitEPS`),
  KEY `numero_factura` (`numero_factura`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_pagos_contributivo_temp` (
  `ID` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `Proceso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NitEPS` bigint(20) NOT NULL,
  `CodigoEps` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaPago` date NOT NULL,
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `PrefijoFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaFactura` date NOT NULL,
  `FormaContratacion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ValorGiro` double NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Soporte` text COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `NitEPS` (`NitEPS`),
  KEY `numero_factura` (`numero_factura`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_pagos_temporal` (
  `id_temp_rips_generados` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `Proceso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CodigoEPS` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEPS` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `FormaContratacion` enum('Evento','Capitacion') COLLATE utf8_spanish_ci NOT NULL,
  `Departamento` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Municipio` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `FechaFactura` date NOT NULL,
  `PrefijoFactura` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroFactura` bigint(20) NOT NULL,
  `ValorGiro` double NOT NULL,
  `FechaPago` date NOT NULL,
  `NumeroGiro` bigint(20) NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_parametros_generales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `Valor` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_procesos_gerenciales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `IPS` int(11) NOT NULL,
  `EPS` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `NombreProceso` text COLLATE utf8_spanish_ci NOT NULL,
  `Concepto` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_procesos_gerenciales_archivos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idProceso` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_procesos_gerenciales_conceptos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Concepto` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_regimen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Regimen` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_registro_devoluciones_facturas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaDevolucion` date NOT NULL,
  `FechaReciboAuditoria` date NOT NULL,
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `CodGlosa` int(3) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `ValorFactura` double NOT NULL,
  `FechaRegistro` date NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_registro_glosas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_factura` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `PrefijoArchivo` enum('AC','AM','AP','AT','AF') COLLATE utf8_spanish_ci NOT NULL COMMENT 'AC, AM, AP, AT,AF',
  `idArchivo` bigint(20) NOT NULL COMMENT 'id de la tabla',
  `TipoGlosa` enum('1','2','3','4','5') COLLATE utf8_spanish_ci NOT NULL COMMENT '1 inicial, 2 levantada, 3 aceptada, 4 X Conciliar,5 Devuelta',
  `CodigoGlosa` int(11) NOT NULL COMMENT 'Codigo de la glosa',
  `FechaReporte` date NOT NULL COMMENT 'Fecha de reporte o gestion',
  `GlosaEPS` double NOT NULL COMMENT 'Valor que La EPS dice que hay Glosa',
  `GlosaAceptada` double NOT NULL COMMENT 'Valor que la IPS esta dispuesta a perder',
  `Soporte` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Documento que soporta la decision o gestion',
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Lo que el gestor gestionó',
  `cod_enti_administradora` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_factura` date NOT NULL,
  `idUser` int(11) NOT NULL COMMENT 'usuario que ingresó el registro',
  `TablaOrigen` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'tabla donde esta el archiv',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_rips_facturas_generadas_historico` (
  `id_fac_mov_generados` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `razon_social` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Razón social o apellidos y nombre del prestado  " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_prest_servicio` enum('NI','CC','CE','PA') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_ident_prest_servicio` bigint(20) NOT NULL COMMENT 'Número de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `fecha_factura` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_final` date NOT NULL COMMENT 'Fecha final " Ver Alineamientos tecnicos para ips ver pag 13"',
  `cod_enti_administradora` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13"',
  `nom_enti_administradora` varchar(200) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `num_contrato` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número del contrato " Ver Alineamientos tecnicos para ips ver pag 13"',
  `plan_beneficios` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Plan de beneficios " Ver Alineamientos tecnicos para ips ver pag 13"',
  `num_poliza` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número de la póliza " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `valor_total_pago` double(15,2) NOT NULL COMMENT 'Valor total del pago compartido copago " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_comision` double(15,2) NOT NULL COMMENT 'Valor de la comisión " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_descuentos` double(15,2) NOT NULL COMMENT 'Valor total de descuentos " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_neto_pagar` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre con el que se hizo el cargue al sistema de cartera',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha Y Hora que se hizo el cargue',
  `idUser` int(11) NOT NULL,
  `eps_radicacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Eps en la que se radico la factura',
  `dias_pactados` int(2) DEFAULT NULL COMMENT 'Dias que se pactaron para el pago de la factura con eps',
  `fecha_radicado` date DEFAULT NULL COMMENT 'Fecha de la radicacion de la factura',
  `numero_radicado` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero con que se radico la factura',
  `Soporte` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ruta de Archivo de comprobación de radicado',
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Indica en que tabla esta el registro en un momento dado ',
  `EstadoCobro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Arma030Anterior` enum('S','N') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `Escenario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `EstadoGlosa` int(2) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `estado` (`estado`),
  KEY `tipo_negociacion` (`tipo_negociacion`),
  KEY `Updated` (`Updated`),
  KEY `EstadoCobro` (`EstadoCobro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de transacciones_AF_generadas';


CREATE TABLE `salud_rips_facturas_generadas_temp` (
  `id_temp_rips_generados` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `razon_social` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Razón social o apellidos y nombre del prestado " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_prest_servicio` enum('NI','CC','CE','PA') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_ident_prest_servicio` bigint(20) NOT NULL COMMENT 'Número de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `fecha_factura` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_final` date NOT NULL COMMENT 'Fecha final " Ver Alineamientos tecnicos para ips ver pag 13"',
  `cod_enti_administradora` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13"',
  `nom_enti_administradora` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `num_contrato` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número del contrato " Ver Alineamientos tecnicos para ips ver pag 13"',
  `plan_beneficios` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Plan de beneficios " Ver Alineamientos tecnicos para ips ver pag 13"',
  `num_poliza` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número de la póliza " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `valor_total_pago` double(15,2) NOT NULL COMMENT 'Valor total del pago compartido copago " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_comision` double(15,2) NOT NULL COMMENT 'Valor de la comisión " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_descuentos` double(15,2) NOT NULL COMMENT 'Valor total de descuentos " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_neto_pagar` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `fecha_radicado` date NOT NULL,
  `numero_radicado` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Escenario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `LineaArchivo` int(11) NOT NULL,
  `dias_pactados` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `id_temp_rips_generados` (`id_temp_rips_generados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo temporal de rips generados';


CREATE TABLE `salud_subir_rips_pago_control` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ArchivoActual` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Separador` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `Destino` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `FechaGiro` date NOT NULL,
  `FechaCargue` datetime NOT NULL,
  `TipoGiro` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Leido` int(11) NOT NULL,
  `AnalizaFacturasPagas` int(11) NOT NULL,
  `AnalizaFacturasNoPagas` int(11) NOT NULL,
  `AnalizaFacturasConDiferencia` int(11) NOT NULL,
  `AnalizaInsercion` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_tesoreria` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `cod_enti_administradora` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad que paga',
  `nom_enti_administradora` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre entidad que paga',
  `fecha_transaccion` date NOT NULL COMMENT 'fecha entra el dinero al banco',
  `num_transaccion` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero de la transaccion con la cual entra al banco',
  `banco_transaccion` varchar(10) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del banco donde entra la transaccion',
  `num_cuenta_banco` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero de cuenta en la cual entra la transaccion',
  `valor_transaccion` double(15,2) NOT NULL COMMENT 'Valor de transaccion ',
  `valor_legalizado` double NOT NULL,
  `valor_legalizar` double NOT NULL,
  `Soporte` varchar(200) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Soporte que argumenta  o justifica el pago',
  `observacion` text COLLATE utf8_spanish_ci COMMENT 'observaciones de diagnostico ',
  `observaciones_cartera` text COLLATE utf8_spanish_ci NOT NULL,
  `legalizado` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_hora_registro` datetime DEFAULT NULL COMMENT 'fecha y hora del registro',
  `idUser` int(11) DEFAULT NULL COMMENT 'usuario que registra',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de tesoreria';


CREATE TABLE `salud_tipo_glosas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TipoGlosa` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_upload_control` (
  `id_upload_control` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_cargue` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `Analizado` bit(1) NOT NULL,
  `CargadoTemp` bit(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_upload_control`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `salud_upload_control_ct` (
  `id_upload_control` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_cargue` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `Analizado` bit(1) NOT NULL,
  `CargadoTemp` bit(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_upload_control`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `servidores` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Usuario` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `DataBase` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `subcuentas` (
  `PUC` int(11) NOT NULL,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Valor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Cuentas_idPUC` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`PUC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `tablas_campos_control` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `NombreTabla` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Campo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Visible` int(1) NOT NULL,
  `Editable` int(1) NOT NULL,
  `Habilitado` int(1) NOT NULL,
  `TipoUser` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `temporal_actualizacion_facturas` (
  `ID` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaAnterior` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaNueva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaRegistro` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `usuarios` (
  `idUsuarios` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Apellido` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Identificacion` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `Telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Login` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Password` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `TipoUser` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Role` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Email` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Habilitado` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'SI',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idUsuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `usuarios_tipo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `vista_af` (`id_fac_mov_generados` int(20), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `tipo_ident_prest_servicio` enum('NI','CC','CE','PA'), `num_ident_prest_servicio` bigint(20), `num_factura` varchar(20), `fecha_factura` date, `fecha_inicio` date, `fecha_final` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `num_contrato` varchar(15), `plan_beneficios` varchar(30), `num_poliza` varchar(10), `valor_total_pago` double(15,2), `valor_comision` double(15,2), `valor_descuentos` double(15,2), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `nom_cargue` varchar(20), `fecha_cargue` datetime, `idUser` int(11), `eps_radicacion` varchar(50), `dias_pactados` int(2), `fecha_radicado` date, `numero_radicado` varchar(20), `Soporte` varchar(200), `estado` varchar(50), `EstadoCobro` varchar(20), `Arma030Anterior` enum('S','N'), `Escenario` varchar(15), `CuentaGlobal` varchar(45), `CuentaRIPS` int(6) unsigned zerofill, `EstadoGlosa` int(2), `Updated` timestamp, `Sync` datetime, `GeneraCircular` varchar(1));


CREATE TABLE `vista_af_devueltos` (`ID` int(20), `num_factura` varchar(20), `fecha_factura` date, `LineaArchivo` int(11), `CuentaGlobal` varchar(45), `CuentaRIPS` int(6) unsigned zerofill, `EstadoGlosa` int(2), `fecha_cargue` datetime);


CREATE TABLE `vista_af_duplicados` (`ID` int(20), `num_factura` varchar(20), `fecha_factura` date, `LineaArchivo` int(11), `CuentaGlobal` varchar(45), `CuentaRIPS` int(6) unsigned zerofill, `EstadoGlosa` int(2), `fecha_cargue` datetime);


CREATE TABLE `vista_af_semaforo` (`id_fac_mov_generados` int(20), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `tipo_ident_prest_servicio` enum('NI','CC','CE','PA'), `num_ident_prest_servicio` bigint(20), `num_factura` varchar(20), `fecha_factura` date, `fecha_inicio` date, `fecha_final` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `num_contrato` varchar(15), `plan_beneficios` varchar(30), `num_poliza` varchar(10), `valor_total_pago` double(15,2), `valor_comision` double(15,2), `valor_descuentos` double(15,2), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `nom_cargue` varchar(20), `fecha_cargue` datetime, `idUser` int(11), `eps_radicacion` varchar(50), `dias_pactados` int(2), `fecha_radicado` date, `numero_radicado` varchar(20), `Soporte` varchar(200), `estado` varchar(50), `EstadoCobro` varchar(20), `Arma030Anterior` enum('S','N'), `Escenario` varchar(15), `CuentaGlobal` varchar(45), `CuentaRIPS` int(6) unsigned zerofill, `EstadoGlosa` int(2), `Updated` timestamp, `Sync` datetime, `Dias` bigint(11), `identificacion_usuario` bigint(20));


CREATE TABLE `vista_cartera_x_edades` (`RangoDias` varchar(7), `idEPS` varchar(6), `nom_enti_administradora` varchar(200), `TotalCartera` double(19,2), `TotalItems` bigint(21), `DiasPactados` int(11));


CREATE TABLE `vista_cartera_x_edades_organizada` (`CodEPS` varchar(6), `TotalCartera` double(19,2));


CREATE TABLE `vista_circular_07` (`id_factura_generada` int(20), `DiasMora` bigint(12), `CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_factura` varchar(20), `fecha_factura` date, `fecha_radicado` date, `numero_radicado` varchar(20), `FechaVencimiento` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `RegimenEPS` varchar(22), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `dias_pactados` int(2), `Soporte` varchar(200), `EstadoCobro` varchar(20), `ValorGlosaInicial` double, `ValorGlosaLevantada` double, `ValorGlosaAceptada` double, `ValorGlosaXConciliar` double, `TotalPagos` double(19,2), `SaldoFinalFactura` double);


CREATE TABLE `vista_glosas_iniciales` (`ID` bigint(20), `FechaIPS` date, `FechaAuditoria` date, `FechaRegistro` date, `CodigoGlosa` int(3), `num_factura` varchar(20), `CodigoActividad` varchar(20), `EstadoGlosa` int(11), `ValorActividad` double, `ValorGlosado` double, `ValorLevantado` double, `ValorAceptado` double, `ValorXConciliar` double, `ValorConciliado` double, `Updated` timestamp, `Sync` datetime, `DiasTranscurridos` int(7), `CuentaRIPS` bigint(10) unsigned);


CREATE TABLE `vista_glosas_iniciales_reportes` (`ID` bigint(20), `FechaIPS` date, `FechaAuditoria` date, `FechaRegistro` date, `CodigoGlosa` int(3), `num_factura` varchar(20), `CodigoActividad` varchar(20), `EstadoGlosa` int(11), `ValorActividad` double, `ValorGlosado` double, `ValorLevantado` double, `ValorAceptado` double, `ValorXConciliar` double, `ValorConciliado` double, `Updated` timestamp, `Sync` datetime, `DescripcionGlosa` mediumtext, `cod_administrador` varchar(6), `fecha_factura` date, `nombre_prestador` varchar(60), `cod_prestador` bigint(20), `nit_prestador` bigint(20), `nit_administrador` bigint(20), `nombre_administrador` varchar(50), `regimen_eps` varchar(22));


CREATE TABLE `vista_salud_consolidaciones_masivas` (`ID` int(11), `FechaConciliacion` date, `CuentaRIPSTemp` int(6) unsigned zerofill, `num_factura` varchar(45), `CodigoActividad` varchar(20), `ValorLevantado` double, `ValorAceptado` double, `Observaciones` text, `Soporte` varchar(200), `Conciliada` int(11), `Extemporanea` int(1), `ValorLevantadoPositivo` int(1), `ValorAceptadoPositivo` int(1), `Factura` varchar(20), `CuentaRIPS` bigint(10) unsigned, `CodigoActividadAM` varchar(20), `NombreActividadAM` varchar(30), `EstadoGlosaAM` bigint(11), `TotalAM` double(19,2), `CodigoActividadAT` varchar(20), `NombreActividadAT` varchar(30), `EstadoGlosaAT` bigint(11), `TotalAT` double(19,2), `CodigoActividadAP` varchar(7), `EstadoGlosaAP` bigint(11), `TotalAP` double(19,2), `NombreActividad` varchar(255), `CodigoActividadAC` varchar(7), `EstadoGlosaAC` bigint(11), `TotalAC` double(19,2));


CREATE TABLE `vista_salud_cuentas_rips` (`CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `FechaDesde` date, `NombreCortoEPS` varchar(120), `FechaHasta` date, `fecha_radicado` date, `numero_radicado` varchar(20), `NumFacturas` bigint(21), `Total` double(19,2), `idEstadoGlosa` int(2), `EstadoGlosa` varchar(50), `Dias` bigint(11));


CREATE TABLE `vista_salud_facturas_diferencias` (`id_factura_generada` int(20), `CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_factura` varchar(20), `fecha_factura` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `valor_neto_pagar` double(15,2), `id_factura_pagada` bigint(20), `fecha_pago_factura` date, `valor_pagado` double(15,2), `num_pago` int(10), `DiferenciaEnPago` double(19,2), `tipo_negociacion` enum('evento','capita'), `dias_pactados` int(2), `fecha_radicado` date, `numero_radicado` varchar(20), `Soporte` varchar(200));


CREATE TABLE `vista_salud_facturas_glosas` (`CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `FechaRadicado` date, `numero_radicado` varchar(20), `num_factura` varchar(20), `fecha_factura` date, `fecha_radicado` date, `EstadoGlosa` int(2), `TipoID` varchar(2), `NumIdentificacion` bigint(20));


CREATE TABLE `vista_salud_facturas_no_pagas` (`id_factura_generada` int(20), `DiasMora` bigint(12), `CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_factura` varchar(20), `fecha_factura` date, `fecha_radicado` date, `numero_radicado` varchar(20), `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `dias_pactados` int(2), `Soporte` varchar(200), `EstadoCobro` varchar(20));


CREATE TABLE `vista_salud_facturas_pagas` (`id_factura_generada` int(20), `CuentaRIPS` int(6) unsigned zerofill, `CuentaGlobal` varchar(45), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_factura` varchar(20), `fecha_factura` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `valor_neto_pagar` double(15,2), `id_factura_pagada` bigint(20), `fecha_pago_factura` date, `valor_pagado` double(15,2), `num_pago` int(10), `tipo_negociacion` enum('evento','capita'), `dias_pactados` int(2), `fecha_radicado` date, `numero_radicado` varchar(20), `Soporte` varchar(200));


CREATE TABLE `vista_salud_facturas_prejuridicos` (`ID` int(20), `idCobroPrejuridico` int(11), `num_factura` varchar(20), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_ident_prest_servicio` bigint(20), `fecha_factura` date, `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `fecha_radicado` date, `numero_radicado` varchar(20), `SoporteRadicado` varchar(200), `SoporteCobro` varchar(50), `EstadoFactura` varchar(50), `EstadoCobro` varchar(20));


CREATE TABLE `vista_salud_facturas_usuarios` (`num_factura` varchar(20), `num_ident_usuario` bigint(20));


CREATE TABLE `vista_salud_glosas_masivas` (`ID` bigint(20), `FechaIPS` date, `FechaAuditoria` date, `ValorGlosado` double, `Analizado` int(1), `GlosaInicial` int(1), `GlosaControlRespuestas` int(1), `CodigoActividad` varchar(45), `Observaciones` text, `Soporte` varchar(200), `Factura` varchar(20), `CuentaRIPS` bigint(10) unsigned, `CodEps` varchar(6), `NIT` bigint(20), `CodigoGlosa` bigint(11), `CodigoActividadAM` varchar(20), `NombreActividadAM` varchar(30), `TotalAM` double(19,2), `CodigoActividadAT` varchar(20), `NombreActividadAT` varchar(30), `TotalAT` double(19,2), `CodigoActividadAP` varchar(7), `NombreActividad` varchar(255), `TotalAP` double(19,2), `CodigoActividadAC` varchar(7), `TotalAC` double(19,2), `idGlosa` bigint(20), `idGlosaTemp` bigint(20));


CREATE TABLE `vista_salud_pagas_no_generadas` (`id_pagados` bigint(20), `num_factura` varchar(20), `idEPS` varchar(25), `nom_enti_administradora` varchar(100), `fecha_pago_factura` date, `num_pago` int(10), `valor_bruto_pagar` double(15,2), `valor_descuento` double(15,2), `valor_iva` double(15,2), `valor_retefuente` double(15,2), `valor_reteiva` double(15,2), `valor_reteica` double(15,2), `valor_otrasretenciones` double(15,2), `valor_cruces` double(15,2), `valor_anticipos` double(15,2), `valor_pagado` double(15,2), `tipo_negociacion` varchar(25), `nom_cargue` varchar(20), `fecha_cargue` datetime, `Proceso` varchar(25), `Estado` varchar(10), `Soporte` text, `idUser` int(11), `Arma030Anterior` enum('S','N'), `NumeroFacturaAdres` varchar(45), `Updated` timestamp, `Sync` datetime);


CREATE TABLE `vista_salud_procesos_gerenciales` (`ID` bigint(20), `idProceso` int(11), `Fecha` date, `IPS` varchar(45), `EPS` varchar(50), `NombreProceso` text, `Concepto` varchar(45), `Observaciones` text, `Soporte` varchar(200));


CREATE TABLE `vista_salud_respuestas` (`ID` bigint(20), `cuenta` int(6) unsigned zerofill, `factura` varchar(20), `Tratado` int(1), `Soporte` varchar(200), `valor_glosado_eps` double, `valor_levantado_eps` double, `valor_aceptado_ips` double, `cod_estado` int(2), `valor_x_conciliar` double, `observacion_auditor` text, `fecha_respuesta` date, `cod_glosa_respuesta` bigint(12), `cod_actividad` varchar(20), `descripcion_actividad` text, `valor_total_actividad` double, `id_glosa_inicial` bigint(20), `EstadoGlosaHistorico` int(3), `fecha_factura` date, `numero_radicado` varchar(20), `fecha_radicado` date, `valor_factura` double(15,2), `cod_administrador` varchar(6), `nombre_administrador` varchar(200), `cod_prestador` bigint(20), `nombre_prestador` varchar(60), `nit_prestador` bigint(20), `nit_administrador` bigint(20), `regimen_eps` varchar(22), `identificacion` bigint(20), `tipo_identificacion` varchar(2), `edad_usuario` bigint(11), `unidad_medida_edad` varchar(1), `sexo_usuario` varchar(1), `cod_glosa_inicial` bigint(11), `descripcion_glosa_inicial` mediumtext, `descripcion_glosa_respuesta` mediumtext, `descripcion_estado` varchar(50), `descripcion_estado_historico` varchar(50));


CREATE TABLE `vista_salud_respuestas_excel` (`ID` bigint(20), `cuenta` int(6) unsigned zerofill, `factura` varchar(20), `Tratado` int(1), `Soporte` varchar(200), `valor_glosado_eps` double, `valor_levantado_eps` double, `valor_aceptado_ips` double, `cod_estado` int(2), `valor_x_conciliar` double, `observacion_auditor` text, `fecha_respuesta` date, `cod_glosa_respuesta` bigint(12), `cod_actividad` varchar(20), `descripcion_actividad` text, `valor_total_actividad` double, `id_glosa_inicial` bigint(20), `EstadoGlosaHistorico` int(3), `fecha_factura` date, `numero_radicado` varchar(20), `fecha_radicado` date, `valor_factura` double(15,2), `cod_administrador` varchar(6), `nombre_administrador` varchar(200), `cod_prestador` bigint(20), `nombre_prestador` varchar(60), `nit_prestador` bigint(20), `nit_administrador` bigint(20), `regimen_eps` varchar(22), `identificacion` bigint(20), `tipo_identificacion` varchar(2), `edad_usuario` bigint(11), `unidad_medida_edad` varchar(1), `sexo_usuario` varchar(1), `cod_glosa_inicial` bigint(11), `descripcion_glosa_inicial` mediumtext, `descripcion_glosa_respuesta` mediumtext, `descripcion_estado` varchar(50), `descripcion_estado_historico` varchar(50));


CREATE TABLE `vista_siho` (`id_factura_generada` int(20), `diasPago` bigint(11), `DiasMora` bigint(12), `cod_prest_servicio` bigint(12), `razon_social` varchar(60), `num_factura` varchar(20), `fecha_factura` date, `fecha_radicado` date, `numero_radicado` varchar(20), `cod_enti_administradora` varchar(6), `nom_enti_administradora` varchar(200), `valor_neto_pagar` double(15,2), `tipo_negociacion` enum('evento','capita'), `estado` varchar(50));


CREATE TABLE `vista_temporal_actividades_af` (`Archivo` varchar(2), `idArchivo` bigint(20), `Codigo` varchar(20), `Descripcion` varchar(255), `ValorUnitario` double, `Cantidad` varbinary(33), `Total` double, `EstadoGlosa` int(11), `Estado` varchar(50));


DROP TABLE IF EXISTS `vista_af`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_af` AS select `salud_archivo_facturacion_mov_generados`.`id_fac_mov_generados` AS `id_fac_mov_generados`,`salud_archivo_facturacion_mov_generados`.`cod_prest_servicio` AS `cod_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`razon_social` AS `razon_social`,`salud_archivo_facturacion_mov_generados`.`tipo_ident_prest_servicio` AS `tipo_ident_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`num_ident_prest_servicio` AS `num_ident_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`num_factura` AS `num_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_factura` AS `fecha_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_inicio` AS `fecha_inicio`,`salud_archivo_facturacion_mov_generados`.`fecha_final` AS `fecha_final`,`salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` AS `cod_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` AS `nom_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`num_contrato` AS `num_contrato`,`salud_archivo_facturacion_mov_generados`.`plan_beneficios` AS `plan_beneficios`,`salud_archivo_facturacion_mov_generados`.`num_poliza` AS `num_poliza`,`salud_archivo_facturacion_mov_generados`.`valor_total_pago` AS `valor_total_pago`,`salud_archivo_facturacion_mov_generados`.`valor_comision` AS `valor_comision`,`salud_archivo_facturacion_mov_generados`.`valor_descuentos` AS `valor_descuentos`,`salud_archivo_facturacion_mov_generados`.`valor_neto_pagar` AS `valor_neto_pagar`,`salud_archivo_facturacion_mov_generados`.`tipo_negociacion` AS `tipo_negociacion`,`salud_archivo_facturacion_mov_generados`.`nom_cargue` AS `nom_cargue`,`salud_archivo_facturacion_mov_generados`.`fecha_cargue` AS `fecha_cargue`,`salud_archivo_facturacion_mov_generados`.`idUser` AS `idUser`,`salud_archivo_facturacion_mov_generados`.`eps_radicacion` AS `eps_radicacion`,`salud_archivo_facturacion_mov_generados`.`dias_pactados` AS `dias_pactados`,`salud_archivo_facturacion_mov_generados`.`fecha_radicado` AS `fecha_radicado`,`salud_archivo_facturacion_mov_generados`.`numero_radicado` AS `numero_radicado`,`salud_archivo_facturacion_mov_generados`.`Soporte` AS `Soporte`,`salud_archivo_facturacion_mov_generados`.`estado` AS `estado`,`salud_archivo_facturacion_mov_generados`.`EstadoCobro` AS `EstadoCobro`,`salud_archivo_facturacion_mov_generados`.`Arma030Anterior` AS `Arma030Anterior`,`salud_archivo_facturacion_mov_generados`.`Escenario` AS `Escenario`,`salud_archivo_facturacion_mov_generados`.`CuentaGlobal` AS `CuentaGlobal`,`salud_archivo_facturacion_mov_generados`.`CuentaRIPS` AS `CuentaRIPS`,`salud_archivo_facturacion_mov_generados`.`EstadoGlosa` AS `EstadoGlosa`,`salud_archivo_facturacion_mov_generados`.`Updated` AS `Updated`,`salud_archivo_facturacion_mov_generados`.`Sync` AS `Sync`,(select `salud_eps`.`Genera030` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora`)) AS `GeneraCircular` from `salud_archivo_facturacion_mov_generados`;

DROP TABLE IF EXISTS `vista_af_devueltos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_af_devueltos` AS select `t2`.`id_fac_mov_generados` AS `ID`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`LineaArchivo` AS `LineaArchivo`,`t2`.`CuentaGlobal` AS `CuentaGlobal`,`t2`.`CuentaRIPS` AS `CuentaRIPS`,`t2`.`EstadoGlosa` AS `EstadoGlosa`,`t2`.`fecha_cargue` AS `fecha_cargue` from (`salud_rips_facturas_generadas_temp` `t1` join `salud_archivo_facturacion_mov_generados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where (`t2`.`EstadoGlosa` = 9);

DROP TABLE IF EXISTS `vista_af_duplicados`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_af_duplicados` AS select `t2`.`id_fac_mov_generados` AS `ID`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`LineaArchivo` AS `LineaArchivo`,`t2`.`CuentaGlobal` AS `CuentaGlobal`,`t2`.`CuentaRIPS` AS `CuentaRIPS`,`t2`.`EstadoGlosa` AS `EstadoGlosa`,`t2`.`fecha_cargue` AS `fecha_cargue` from (`salud_rips_facturas_generadas_temp` `t1` join `salud_archivo_facturacion_mov_generados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where (`t2`.`EstadoGlosa` <> 9);

DROP TABLE IF EXISTS `vista_af_semaforo`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_af_semaforo` AS select `salud_archivo_facturacion_mov_generados`.`id_fac_mov_generados` AS `id_fac_mov_generados`,`salud_archivo_facturacion_mov_generados`.`cod_prest_servicio` AS `cod_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`razon_social` AS `razon_social`,`salud_archivo_facturacion_mov_generados`.`tipo_ident_prest_servicio` AS `tipo_ident_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`num_ident_prest_servicio` AS `num_ident_prest_servicio`,`salud_archivo_facturacion_mov_generados`.`num_factura` AS `num_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_factura` AS `fecha_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_inicio` AS `fecha_inicio`,`salud_archivo_facturacion_mov_generados`.`fecha_final` AS `fecha_final`,`salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` AS `cod_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` AS `nom_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`num_contrato` AS `num_contrato`,`salud_archivo_facturacion_mov_generados`.`plan_beneficios` AS `plan_beneficios`,`salud_archivo_facturacion_mov_generados`.`num_poliza` AS `num_poliza`,`salud_archivo_facturacion_mov_generados`.`valor_total_pago` AS `valor_total_pago`,`salud_archivo_facturacion_mov_generados`.`valor_comision` AS `valor_comision`,`salud_archivo_facturacion_mov_generados`.`valor_descuentos` AS `valor_descuentos`,`salud_archivo_facturacion_mov_generados`.`valor_neto_pagar` AS `valor_neto_pagar`,`salud_archivo_facturacion_mov_generados`.`tipo_negociacion` AS `tipo_negociacion`,`salud_archivo_facturacion_mov_generados`.`nom_cargue` AS `nom_cargue`,`salud_archivo_facturacion_mov_generados`.`fecha_cargue` AS `fecha_cargue`,`salud_archivo_facturacion_mov_generados`.`idUser` AS `idUser`,`salud_archivo_facturacion_mov_generados`.`eps_radicacion` AS `eps_radicacion`,`salud_archivo_facturacion_mov_generados`.`dias_pactados` AS `dias_pactados`,`salud_archivo_facturacion_mov_generados`.`fecha_radicado` AS `fecha_radicado`,`salud_archivo_facturacion_mov_generados`.`numero_radicado` AS `numero_radicado`,`salud_archivo_facturacion_mov_generados`.`Soporte` AS `Soporte`,`salud_archivo_facturacion_mov_generados`.`estado` AS `estado`,`salud_archivo_facturacion_mov_generados`.`EstadoCobro` AS `EstadoCobro`,`salud_archivo_facturacion_mov_generados`.`Arma030Anterior` AS `Arma030Anterior`,`salud_archivo_facturacion_mov_generados`.`Escenario` AS `Escenario`,`salud_archivo_facturacion_mov_generados`.`CuentaGlobal` AS `CuentaGlobal`,`salud_archivo_facturacion_mov_generados`.`CuentaRIPS` AS `CuentaRIPS`,`salud_archivo_facturacion_mov_generados`.`EstadoGlosa` AS `EstadoGlosa`,`salud_archivo_facturacion_mov_generados`.`Updated` AS `Updated`,`salud_archivo_facturacion_mov_generados`.`Sync` AS `Sync`,(select max(`vista_glosas_iniciales`.`DiasTranscurridos`) from `vista_glosas_iniciales` where ((`vista_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) and (`vista_glosas_iniciales`.`EstadoGlosa` = 1))) AS `Dias`,(select `vista_salud_facturas_usuarios`.`num_ident_usuario` from `vista_salud_facturas_usuarios` where (`vista_salud_facturas_usuarios`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `identificacion_usuario` from `salud_archivo_facturacion_mov_generados`;

DROP TABLE IF EXISTS `vista_cartera_x_edades`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_cartera_x_edades` AS select 'T' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '1' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where (`vista_salud_facturas_no_pagas`.`DiasMora` < 1) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '1_30' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 1) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 30)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '31_60' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 31) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 60)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '61_90' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 61) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 90)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '91_120' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 91) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 120)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '121_180' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 121) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 180)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '181_360' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where ((`vista_salud_facturas_no_pagas`.`DiasMora` >= 181) and (`vista_salud_facturas_no_pagas`.`DiasMora` <= 360)) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora` union all select '360' AS `RangoDias`,`vista_salud_facturas_no_pagas`.`cod_enti_administradora` AS `idEPS`,`vista_salud_facturas_no_pagas`.`nom_enti_administradora` AS `nom_enti_administradora`,sum(`vista_salud_facturas_no_pagas`.`valor_neto_pagar`) AS `TotalCartera`,count(`vista_salud_facturas_no_pagas`.`num_factura`) AS `TotalItems`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `vista_salud_facturas_no_pagas`.`cod_enti_administradora`) limit 1) AS `DiasPactados` from `vista_salud_facturas_no_pagas` where (`vista_salud_facturas_no_pagas`.`DiasMora` >= 360) group by `vista_salud_facturas_no_pagas`.`cod_enti_administradora`;

DROP TABLE IF EXISTS `vista_cartera_x_edades_organizada`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_cartera_x_edades_organizada` AS select `vista_cartera_x_edades`.`idEPS` AS `CodEPS`,(select sum(`vista_cartera_x_edades`.`TotalCartera`) from `vista_cartera_x_edades` where (((select `vista_cartera_x_edades`.`idEPS`) = `vista_cartera_x_edades`.`idEPS`) and (`vista_cartera_x_edades`.`RangoDias` = 'T'))) AS `TotalCartera` from `vista_cartera_x_edades` group by `vista_cartera_x_edades`.`idEPS`;

DROP TABLE IF EXISTS `vista_circular_07`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_circular_07` AS select `t1`.`id_fac_mov_generados` AS `id_factura_generada`,(select ((to_days(now()) - to_days(`t1`.`fecha_radicado`)) - `t1`.`dias_pactados`)) AS `DiasMora`,`t1`.`CuentaRIPS` AS `CuentaRIPS`,`t1`.`CuentaGlobal` AS `CuentaGlobal`,`t1`.`cod_prest_servicio` AS `cod_prest_servicio`,`t1`.`razon_social` AS `razon_social`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`fecha_radicado` AS `fecha_radicado`,`t1`.`numero_radicado` AS `numero_radicado`,(select (`t1`.`fecha_radicado` + interval (select `t1`.`dias_pactados`) day)) AS `FechaVencimiento`,`t1`.`cod_enti_administradora` AS `cod_enti_administradora`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,(select `salud_eps`.`tipo_regimen` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `t1`.`cod_enti_administradora`)) AS `RegimenEPS`,`t1`.`valor_neto_pagar` AS `valor_neto_pagar`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`dias_pactados` AS `dias_pactados`,`t1`.`Soporte` AS `Soporte`,`t1`.`EstadoCobro` AS `EstadoCobro`,(select ifnull((select sum(`salud_glosas_iniciales`.`ValorGlosado`) from `salud_glosas_iniciales` where ((`salud_glosas_iniciales`.`num_factura` = `t1`.`num_factura`) and (`salud_glosas_iniciales`.`EstadoGlosa` <= 7))),0)) AS `ValorGlosaInicial`,(select ifnull((select sum(`salud_glosas_iniciales`.`ValorLevantado`) from `salud_glosas_iniciales` where ((`salud_glosas_iniciales`.`num_factura` = `t1`.`num_factura`) and (`salud_glosas_iniciales`.`EstadoGlosa` <= 7))),0)) AS `ValorGlosaLevantada`,(select ifnull((select sum(`salud_glosas_iniciales`.`ValorAceptado`) from `salud_glosas_iniciales` where ((`salud_glosas_iniciales`.`num_factura` = `t1`.`num_factura`) and (`salud_glosas_iniciales`.`EstadoGlosa` <= 7))),0)) AS `ValorGlosaAceptada`,(select ifnull((select sum(`salud_glosas_iniciales`.`ValorXConciliar`) from `salud_glosas_iniciales` where ((`salud_glosas_iniciales`.`num_factura` = `t1`.`num_factura`) and (`salud_glosas_iniciales`.`EstadoGlosa` <= 7))),0)) AS `ValorGlosaXConciliar`,(select ifnull((select sum(`salud_archivo_facturacion_mov_pagados`.`valor_pagado`) from `salud_archivo_facturacion_mov_pagados` where (`salud_archivo_facturacion_mov_pagados`.`num_factura` = `t1`.`num_factura`)),0)) AS `TotalPagos`,(select ((`t1`.`valor_neto_pagar` - (select `ValorGlosaAceptada`)) - (select `TotalPagos`))) AS `SaldoFinalFactura` from `salud_archivo_facturacion_mov_generados` `t1` where ((`t1`.`estado` <> '') and (`t1`.`estado` <> 'PAGADA'));

DROP TABLE IF EXISTS `vista_glosas_iniciales`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_glosas_iniciales` AS select `salud_glosas_iniciales`.`ID` AS `ID`,`salud_glosas_iniciales`.`FechaIPS` AS `FechaIPS`,`salud_glosas_iniciales`.`FechaAuditoria` AS `FechaAuditoria`,`salud_glosas_iniciales`.`FechaRegistro` AS `FechaRegistro`,`salud_glosas_iniciales`.`CodigoGlosa` AS `CodigoGlosa`,`salud_glosas_iniciales`.`num_factura` AS `num_factura`,`salud_glosas_iniciales`.`CodigoActividad` AS `CodigoActividad`,`salud_glosas_iniciales`.`EstadoGlosa` AS `EstadoGlosa`,`salud_glosas_iniciales`.`ValorActividad` AS `ValorActividad`,`salud_glosas_iniciales`.`ValorGlosado` AS `ValorGlosado`,`salud_glosas_iniciales`.`ValorLevantado` AS `ValorLevantado`,`salud_glosas_iniciales`.`ValorAceptado` AS `ValorAceptado`,`salud_glosas_iniciales`.`ValorXConciliar` AS `ValorXConciliar`,`salud_glosas_iniciales`.`ValorConciliado` AS `ValorConciliado`,`salud_glosas_iniciales`.`Updated` AS `Updated`,`salud_glosas_iniciales`.`Sync` AS `Sync`,(select (to_days(now()) - to_days(`salud_glosas_iniciales`.`FechaIPS`))) AS `DiasTranscurridos`,(select `salud_archivo_facturacion_mov_generados`.`CuentaRIPS` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_glosas_iniciales`.`num_factura`)) AS `CuentaRIPS` from `salud_glosas_iniciales`;

DROP TABLE IF EXISTS `vista_glosas_iniciales_reportes`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_glosas_iniciales_reportes` AS select `salud_glosas_iniciales`.`ID` AS `ID`,`salud_glosas_iniciales`.`FechaIPS` AS `FechaIPS`,`salud_glosas_iniciales`.`FechaAuditoria` AS `FechaAuditoria`,`salud_glosas_iniciales`.`FechaRegistro` AS `FechaRegistro`,`salud_glosas_iniciales`.`CodigoGlosa` AS `CodigoGlosa`,`salud_glosas_iniciales`.`num_factura` AS `num_factura`,`salud_glosas_iniciales`.`CodigoActividad` AS `CodigoActividad`,`salud_glosas_iniciales`.`EstadoGlosa` AS `EstadoGlosa`,`salud_glosas_iniciales`.`ValorActividad` AS `ValorActividad`,`salud_glosas_iniciales`.`ValorGlosado` AS `ValorGlosado`,`salud_glosas_iniciales`.`ValorLevantado` AS `ValorLevantado`,`salud_glosas_iniciales`.`ValorAceptado` AS `ValorAceptado`,`salud_glosas_iniciales`.`ValorXConciliar` AS `ValorXConciliar`,`salud_glosas_iniciales`.`ValorConciliado` AS `ValorConciliado`,`salud_glosas_iniciales`.`Updated` AS `Updated`,`salud_glosas_iniciales`.`Sync` AS `Sync`,(select `salud_archivo_conceptos_glosas`.`descrpcion_concep_especifico` from `salud_archivo_conceptos_glosas` where (`salud_archivo_conceptos_glosas`.`cod_glosa` = `salud_glosas_iniciales`.`CodigoGlosa`) limit 1) AS `DescripcionGlosa`,(select `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `cod_administrador`,(select `salud_archivo_facturacion_mov_generados`.`fecha_factura` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `fecha_factura`,(select `salud_archivo_facturacion_mov_generados`.`razon_social` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `nombre_prestador`,(select `salud_archivo_facturacion_mov_generados`.`cod_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `cod_prestador`,(select `salud_archivo_facturacion_mov_generados`.`num_ident_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_iniciales`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `nit_prestador`,(select `salud_eps`.`nit` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `nit_administrador`,(select `salud_eps`.`nombre_completo` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `nombre_administrador`,(select `salud_eps`.`tipo_regimen` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `regimen_eps` from `salud_glosas_iniciales` where (`salud_glosas_iniciales`.`EstadoGlosa` <= 7);

DROP TABLE IF EXISTS `vista_salud_consolidaciones_masivas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_consolidaciones_masivas` AS select `salud_conciliaciones_masivas_temp`.`ID` AS `ID`,`salud_conciliaciones_masivas_temp`.`FechaConciliacion` AS `FechaConciliacion`,`salud_conciliaciones_masivas_temp`.`CuentaRIPS` AS `CuentaRIPSTemp`,`salud_conciliaciones_masivas_temp`.`num_factura` AS `num_factura`,`salud_conciliaciones_masivas_temp`.`CodigoActividad` AS `CodigoActividad`,`salud_conciliaciones_masivas_temp`.`ValorLevantado` AS `ValorLevantado`,`salud_conciliaciones_masivas_temp`.`ValorAceptado` AS `ValorAceptado`,`salud_conciliaciones_masivas_temp`.`Observaciones` AS `Observaciones`,`salud_conciliaciones_masivas_temp`.`Soporte` AS `Soporte`,`salud_conciliaciones_masivas_temp`.`Conciliada` AS `Conciliada`,(select (`salud_conciliaciones_masivas_temp`.`FechaConciliacion` > now())) AS `Extemporanea`,(select (`salud_conciliaciones_masivas_temp`.`ValorLevantado` >= 0)) AS `ValorLevantadoPositivo`,(select (`salud_conciliaciones_masivas_temp`.`ValorAceptado` >= 0)) AS `ValorAceptadoPositivo`,(select `salud_archivo_facturacion_mov_generados`.`num_factura` from `salud_archivo_facturacion_mov_generados` where (`salud_conciliaciones_masivas_temp`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`)) AS `Factura`,(select `salud_archivo_facturacion_mov_generados`.`CuentaRIPS` from `salud_archivo_facturacion_mov_generados` where ((`salud_conciliaciones_masivas_temp`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) and (`salud_conciliaciones_masivas_temp`.`CuentaRIPS` = `salud_archivo_facturacion_mov_generados`.`CuentaRIPS`))) AS `CuentaRIPS`,(select `salud_archivo_medicamentos`.`cod_medicamento` from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAM`,(select `salud_archivo_medicamentos`.`nom_medicamento` from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `NombreActividadAM`,(select `salud_archivo_medicamentos`.`EstadoGlosa` from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `EstadoGlosaAM`,(select sum(`salud_archivo_medicamentos`.`valor_total_medic`) from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAM`,(select `salud_archivo_otros_servicios`.`cod_servicio` from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAT`,(select `salud_archivo_otros_servicios`.`nom_servicio` from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `NombreActividadAT`,(select `salud_archivo_otros_servicios`.`EstadoGlosa` from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `EstadoGlosaAT`,(select sum(`salud_archivo_otros_servicios`.`valor_total_material`) from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAT`,(select `salud_archivo_procedimientos`.`cod_procedimiento` from `salud_archivo_procedimientos` where ((`salud_archivo_procedimientos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_procedimientos`.`cod_procedimiento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAP`,(select `salud_archivo_procedimientos`.`EstadoGlosa` from `salud_archivo_procedimientos` where ((`salud_archivo_procedimientos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_procedimientos`.`cod_procedimiento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `EstadoGlosaAP`,(select sum(`salud_archivo_procedimientos`.`valor_procedimiento`) from `salud_archivo_procedimientos` where ((`salud_archivo_procedimientos`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_procedimientos`.`cod_procedimiento` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAP`,(select `salud_cups`.`descripcion_cups` from `salud_cups` where (`salud_cups`.`codigo_sistema` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`) limit 1) AS `NombreActividad`,(select `salud_archivo_consultas`.`cod_consulta` from `salud_archivo_consultas` where ((`salud_archivo_consultas`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_consultas`.`cod_consulta` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAC`,(select `salud_archivo_consultas`.`EstadoGlosa` from `salud_archivo_consultas` where ((`salud_archivo_consultas`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_consultas`.`cod_consulta` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `EstadoGlosaAC`,(select sum(`salud_archivo_consultas`.`valor_consulta`) from `salud_archivo_consultas` where ((`salud_archivo_consultas`.`num_factura` = `salud_conciliaciones_masivas_temp`.`num_factura`) and (`salud_archivo_consultas`.`cod_consulta` = `salud_conciliaciones_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAC` from `salud_conciliaciones_masivas_temp`;

DROP TABLE IF EXISTS `vista_salud_cuentas_rips`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_cuentas_rips` AS select `salud_archivo_facturacion_mov_generados`.`CuentaRIPS` AS `CuentaRIPS`,`salud_archivo_facturacion_mov_generados`.`CuentaGlobal` AS `CuentaGlobal`,`salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` AS `cod_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` AS `nom_enti_administradora`,(select min(`salud_archivo_facturacion_mov_generados`.`fecha_factura`)) AS `FechaDesde`,(select `salud_eps`.`sigla_nombre` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora`)) AS `NombreCortoEPS`,(select max(`salud_archivo_facturacion_mov_generados`.`fecha_factura`)) AS `FechaHasta`,`salud_archivo_facturacion_mov_generados`.`fecha_radicado` AS `fecha_radicado`,`salud_archivo_facturacion_mov_generados`.`numero_radicado` AS `numero_radicado`,count(`salud_archivo_facturacion_mov_generados`.`id_fac_mov_generados`) AS `NumFacturas`,sum(`salud_archivo_facturacion_mov_generados`.`valor_neto_pagar`) AS `Total`,min(`salud_archivo_facturacion_mov_generados`.`EstadoGlosa`) AS `idEstadoGlosa`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_estado_glosas`.`ID` = min(`salud_archivo_facturacion_mov_generados`.`EstadoGlosa`))) AS `EstadoGlosa`,(select max(`vista_glosas_iniciales`.`DiasTranscurridos`) from `vista_glosas_iniciales` where ((`vista_glosas_iniciales`.`CuentaRIPS` = `salud_archivo_facturacion_mov_generados`.`CuentaRIPS`) and ((`vista_glosas_iniciales`.`EstadoGlosa` = 1) or (`vista_glosas_iniciales`.`EstadoGlosa` = 3)))) AS `Dias` from `salud_archivo_facturacion_mov_generados` group by `salud_archivo_facturacion_mov_generados`.`CuentaRIPS`;

DROP TABLE IF EXISTS `vista_salud_facturas_diferencias`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_diferencias` AS select `t1`.`id_fac_mov_generados` AS `id_factura_generada`,`t1`.`CuentaRIPS` AS `CuentaRIPS`,`t1`.`CuentaGlobal` AS `CuentaGlobal`,`t1`.`cod_prest_servicio` AS `cod_prest_servicio`,`t1`.`razon_social` AS `razon_social`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`cod_enti_administradora` AS `cod_enti_administradora`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,`t1`.`valor_neto_pagar` AS `valor_neto_pagar`,`t2`.`id_pagados` AS `id_factura_pagada`,`t2`.`fecha_pago_factura` AS `fecha_pago_factura`,`t2`.`valor_pagado` AS `valor_pagado`,`t2`.`num_pago` AS `num_pago`,(select (`t1`.`valor_neto_pagar` - `t2`.`valor_pagado`)) AS `DiferenciaEnPago`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`dias_pactados` AS `dias_pactados`,`t1`.`fecha_radicado` AS `fecha_radicado`,`t1`.`numero_radicado` AS `numero_radicado`,`t1`.`Soporte` AS `Soporte` from (`salud_archivo_facturacion_mov_generados` `t1` join `salud_archivo_facturacion_mov_pagados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where (`t1`.`estado` = 'DIFERENCIA');

DROP TABLE IF EXISTS `vista_salud_facturas_glosas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_glosas` AS select `salud_archivo_facturacion_mov_generados`.`CuentaRIPS` AS `CuentaRIPS`,`salud_archivo_facturacion_mov_generados`.`CuentaGlobal` AS `CuentaGlobal`,`salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` AS `cod_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` AS `nom_enti_administradora`,`salud_archivo_facturacion_mov_generados`.`fecha_radicado` AS `FechaRadicado`,`salud_archivo_facturacion_mov_generados`.`numero_radicado` AS `numero_radicado`,`salud_archivo_facturacion_mov_generados`.`num_factura` AS `num_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_factura` AS `fecha_factura`,`salud_archivo_facturacion_mov_generados`.`fecha_radicado` AS `fecha_radicado`,`salud_archivo_facturacion_mov_generados`.`EstadoGlosa` AS `EstadoGlosa`,(select `salud_archivo_consultas`.`tipo_ident_usuario` from `salud_archivo_consultas` where (`salud_archivo_consultas`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `TipoID`,(select `salud_archivo_consultas`.`num_ident_usuario` from `salud_archivo_consultas` where (`salud_archivo_consultas`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) limit 1) AS `NumIdentificacion` from `salud_archivo_facturacion_mov_generados`;

DROP TABLE IF EXISTS `vista_salud_facturas_no_pagas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_no_pagas` AS select `t1`.`id_fac_mov_generados` AS `id_factura_generada`,(select ((to_days(now()) - to_days(`t1`.`fecha_radicado`)) - `t1`.`dias_pactados`)) AS `DiasMora`,`t1`.`CuentaRIPS` AS `CuentaRIPS`,`t1`.`CuentaGlobal` AS `CuentaGlobal`,`t1`.`cod_prest_servicio` AS `cod_prest_servicio`,`t1`.`razon_social` AS `razon_social`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`fecha_radicado` AS `fecha_radicado`,`t1`.`numero_radicado` AS `numero_radicado`,`t1`.`cod_enti_administradora` AS `cod_enti_administradora`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,`t1`.`valor_neto_pagar` AS `valor_neto_pagar`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`dias_pactados` AS `dias_pactados`,`t1`.`Soporte` AS `Soporte`,`t1`.`EstadoCobro` AS `EstadoCobro` from `salud_archivo_facturacion_mov_generados` `t1` where ((`t1`.`estado` = 'RADICADO') or (`t1`.`estado` = ''));

DROP TABLE IF EXISTS `vista_salud_facturas_pagas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_pagas` AS select `t1`.`id_fac_mov_generados` AS `id_factura_generada`,`t1`.`CuentaRIPS` AS `CuentaRIPS`,`t1`.`CuentaGlobal` AS `CuentaGlobal`,`t1`.`cod_prest_servicio` AS `cod_prest_servicio`,`t1`.`razon_social` AS `razon_social`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`cod_enti_administradora` AS `cod_enti_administradora`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,`t1`.`valor_neto_pagar` AS `valor_neto_pagar`,`t2`.`id_pagados` AS `id_factura_pagada`,`t2`.`fecha_pago_factura` AS `fecha_pago_factura`,`t2`.`valor_pagado` AS `valor_pagado`,`t2`.`num_pago` AS `num_pago`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`dias_pactados` AS `dias_pactados`,`t1`.`fecha_radicado` AS `fecha_radicado`,`t1`.`numero_radicado` AS `numero_radicado`,`t1`.`Soporte` AS `Soporte` from (`salud_archivo_facturacion_mov_generados` `t1` join `salud_archivo_facturacion_mov_pagados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where (`t1`.`estado` = 'PAGADA');

DROP TABLE IF EXISTS `vista_salud_facturas_prejuridicos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_prejuridicos` AS select `t2`.`id_fac_mov_generados` AS `ID`,`t1`.`idCobroPrejuridico` AS `idCobroPrejuridico`,`t2`.`num_factura` AS `num_factura`,`t2`.`cod_prest_servicio` AS `cod_prest_servicio`,`t2`.`razon_social` AS `razon_social`,`t2`.`num_ident_prest_servicio` AS `num_ident_prest_servicio`,`t2`.`fecha_factura` AS `fecha_factura`,`t2`.`cod_enti_administradora` AS `cod_enti_administradora`,`t2`.`nom_enti_administradora` AS `nom_enti_administradora`,`t2`.`valor_neto_pagar` AS `valor_neto_pagar`,`t2`.`tipo_negociacion` AS `tipo_negociacion`,`t2`.`fecha_radicado` AS `fecha_radicado`,`t2`.`numero_radicado` AS `numero_radicado`,`t2`.`Soporte` AS `SoporteRadicado`,(select `salud_cobros_prejuridicos`.`Soporte` from `salud_cobros_prejuridicos` where (`salud_cobros_prejuridicos`.`ID` = `t1`.`idCobroPrejuridico`)) AS `SoporteCobro`,`t2`.`estado` AS `EstadoFactura`,`t2`.`EstadoCobro` AS `EstadoCobro` from (`salud_cobros_prejuridicos_relaciones` `t1` join `salud_archivo_facturacion_mov_generados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where ((`t2`.`EstadoCobro` = 'PREJURIDICO1') or (`t2`.`EstadoCobro` = 'PREJURIDICO2'));

DROP TABLE IF EXISTS `vista_salud_facturas_usuarios`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_facturas_usuarios` AS select `salud_archivo_consultas`.`num_factura` AS `num_factura`,`salud_archivo_consultas`.`num_ident_usuario` AS `num_ident_usuario` from `salud_archivo_consultas` group by `salud_archivo_consultas`.`num_factura` union select `salud_archivo_procedimientos`.`num_factura` AS `num_factura`,`salud_archivo_procedimientos`.`num_ident_usuario` AS `num_ident_usuario` from `salud_archivo_procedimientos` group by `salud_archivo_procedimientos`.`num_factura` union select `salud_archivo_otros_servicios`.`num_factura` AS `num_factura`,`salud_archivo_otros_servicios`.`num_ident_usuario` AS `num_ident_usuario` from `salud_archivo_otros_servicios` group by `salud_archivo_otros_servicios`.`num_factura` union select `salud_archivo_medicamentos`.`num_factura` AS `num_factura`,`salud_archivo_medicamentos`.`num_ident_usuario` AS `num_ident_usuario` from `salud_archivo_medicamentos` group by `salud_archivo_medicamentos`.`num_factura`;

DROP TABLE IF EXISTS `vista_salud_glosas_masivas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_glosas_masivas` AS select `salud_glosas_masivas_temp`.`ID` AS `ID`,`salud_glosas_masivas_temp`.`FechaIPS` AS `FechaIPS`,`salud_glosas_masivas_temp`.`FechaAuditoria` AS `FechaAuditoria`,`salud_glosas_masivas_temp`.`ValorGlosado` AS `ValorGlosado`,`salud_glosas_masivas_temp`.`Analizado` AS `Analizado`,`salud_glosas_masivas_temp`.`GlosaInicial` AS `GlosaInicial`,`salud_glosas_masivas_temp`.`GlosaControlRespuestas` AS `GlosaControlRespuestas`,`salud_glosas_masivas_temp`.`CodigoActividad` AS `CodigoActividad`,`salud_glosas_masivas_temp`.`Observaciones` AS `Observaciones`,`salud_glosas_masivas_temp`.`Soporte` AS `Soporte`,(select `salud_archivo_facturacion_mov_generados`.`num_factura` from `salud_archivo_facturacion_mov_generados` where (`salud_glosas_masivas_temp`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`)) AS `Factura`,(select `salud_archivo_facturacion_mov_generados`.`CuentaRIPS` from `salud_archivo_facturacion_mov_generados` where ((`salud_glosas_masivas_temp`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`) and (`salud_glosas_masivas_temp`.`CuentaRips` = `salud_archivo_facturacion_mov_generados`.`CuentaRIPS`))) AS `CuentaRIPS`,(select `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` from `salud_archivo_facturacion_mov_generados` where ((`salud_glosas_masivas_temp`.`ID_EPS` = `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora`) and (`salud_glosas_masivas_temp`.`num_factura` = `salud_archivo_facturacion_mov_generados`.`num_factura`))) AS `CodEps`,(select `salud_eps`.`nit` from `salud_eps` where ((`salud_glosas_masivas_temp`.`NIT_EPS` = `salud_eps`.`nit`) and (`salud_glosas_masivas_temp`.`ID_EPS` = `salud_eps`.`cod_pagador_min`))) AS `NIT`,(select `salud_archivo_conceptos_glosas`.`cod_glosa` from `salud_archivo_conceptos_glosas` where (`salud_glosas_masivas_temp`.`CodigoGlosa` = `salud_archivo_conceptos_glosas`.`cod_glosa`)) AS `CodigoGlosa`,(select `salud_archivo_medicamentos`.`cod_medicamento` from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAM`,(select `salud_archivo_medicamentos`.`nom_medicamento` from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `NombreActividadAM`,(select sum(`salud_archivo_medicamentos`.`valor_total_medic`) from `salud_archivo_medicamentos` where ((`salud_archivo_medicamentos`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_medicamentos`.`cod_medicamento` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAM`,(select `salud_archivo_otros_servicios`.`cod_servicio` from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAT`,(select `salud_archivo_otros_servicios`.`nom_servicio` from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `NombreActividadAT`,(select sum(`salud_archivo_otros_servicios`.`valor_total_material`) from `salud_archivo_otros_servicios` where ((`salud_archivo_otros_servicios`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_otros_servicios`.`cod_servicio` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAT`,(select `salud_archivo_procedimientos`.`cod_procedimiento` from `salud_archivo_procedimientos` where ((`salud_archivo_procedimientos`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_procedimientos`.`cod_procedimiento` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAP`,(select `salud_cups`.`descripcion_cups` from `salud_cups` where (`salud_cups`.`codigo_sistema` = `salud_glosas_masivas_temp`.`CodigoActividad`) limit 1) AS `NombreActividad`,(select sum(`salud_archivo_procedimientos`.`valor_procedimiento`) from `salud_archivo_procedimientos` where ((`salud_archivo_procedimientos`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_procedimientos`.`cod_procedimiento` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAP`,(select `salud_archivo_consultas`.`cod_consulta` from `salud_archivo_consultas` where ((`salud_archivo_consultas`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_consultas`.`cod_consulta` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `CodigoActividadAC`,(select sum(`salud_archivo_consultas`.`valor_consulta`) from `salud_archivo_consultas` where ((`salud_archivo_consultas`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_archivo_consultas`.`cod_consulta` = `salud_glosas_masivas_temp`.`CodigoActividad`)) limit 1) AS `TotalAC`,(select `salud_glosas_iniciales`.`ID` from `salud_glosas_iniciales` where ((`salud_glosas_iniciales`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_glosas_iniciales`.`CodigoActividad` = `salud_glosas_masivas_temp`.`CodigoActividad`) and (`salud_glosas_iniciales`.`CodigoGlosa` = `salud_glosas_masivas_temp`.`CodigoGlosa`)) limit 1) AS `idGlosa`,(select `salud_glosas_iniciales_temp`.`ID` from `salud_glosas_iniciales_temp` where ((`salud_glosas_iniciales_temp`.`num_factura` = `salud_glosas_masivas_temp`.`num_factura`) and (`salud_glosas_iniciales_temp`.`CodigoActividad` = `salud_glosas_masivas_temp`.`CodigoActividad`) and (`salud_glosas_iniciales_temp`.`CodigoGlosa` = `salud_glosas_masivas_temp`.`CodigoGlosa`)) limit 1) AS `idGlosaTemp` from `salud_glosas_masivas_temp`;

DROP TABLE IF EXISTS `vista_salud_pagas_no_generadas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_pagas_no_generadas` AS select `t1`.`id_pagados` AS `id_pagados`,`t1`.`num_factura` AS `num_factura`,`t1`.`idEPS` AS `idEPS`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,`t1`.`fecha_pago_factura` AS `fecha_pago_factura`,`t1`.`num_pago` AS `num_pago`,`t1`.`valor_bruto_pagar` AS `valor_bruto_pagar`,`t1`.`valor_descuento` AS `valor_descuento`,`t1`.`valor_iva` AS `valor_iva`,`t1`.`valor_retefuente` AS `valor_retefuente`,`t1`.`valor_reteiva` AS `valor_reteiva`,`t1`.`valor_reteica` AS `valor_reteica`,`t1`.`valor_otrasretenciones` AS `valor_otrasretenciones`,`t1`.`valor_cruces` AS `valor_cruces`,`t1`.`valor_anticipos` AS `valor_anticipos`,`t1`.`valor_pagado` AS `valor_pagado`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`nom_cargue` AS `nom_cargue`,`t1`.`fecha_cargue` AS `fecha_cargue`,`t1`.`Proceso` AS `Proceso`,`t1`.`Estado` AS `Estado`,`t1`.`Soporte` AS `Soporte`,`t1`.`idUser` AS `idUser`,`t1`.`Arma030Anterior` AS `Arma030Anterior`,`t1`.`NumeroFacturaAdres` AS `NumeroFacturaAdres`,`t1`.`Updated` AS `Updated`,`t1`.`Sync` AS `Sync` from (`salud_archivo_facturacion_mov_pagados` `t1` left join `salud_archivo_facturacion_mov_generados` `t2` on((`t1`.`num_factura` = `t2`.`num_factura`))) where isnull(`t2`.`num_factura`);

DROP TABLE IF EXISTS `vista_salud_procesos_gerenciales`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_procesos_gerenciales` AS select `t1`.`ID` AS `ID`,`t1`.`idProceso` AS `idProceso`,`t1`.`Fecha` AS `Fecha`,(select `empresapro`.`RazonSocial` from `empresapro` where (`empresapro`.`idEmpresaPro` = `t2`.`IPS`) limit 1) AS `IPS`,(select `salud_eps`.`nombre_completo` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `t2`.`EPS`) limit 1) AS `EPS`,`t2`.`NombreProceso` AS `NombreProceso`,`t2`.`Concepto` AS `Concepto`,`t1`.`Observaciones` AS `Observaciones`,`t1`.`Soporte` AS `Soporte` from (`salud_procesos_gerenciales_archivos` `t1` join `salud_procesos_gerenciales` `t2` on((`t1`.`idProceso` = `t2`.`ID`)));

DROP TABLE IF EXISTS `vista_salud_respuestas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_respuestas` AS select `salud_archivo_control_glosas_respuestas`.`ID` AS `ID`,`salud_archivo_control_glosas_respuestas`.`CuentaRIPS` AS `cuenta`,`salud_archivo_control_glosas_respuestas`.`num_factura` AS `factura`,`salud_archivo_control_glosas_respuestas`.`Tratado` AS `Tratado`,`salud_archivo_control_glosas_respuestas`.`Soporte` AS `Soporte`,`salud_archivo_control_glosas_respuestas`.`valor_glosado_eps` AS `valor_glosado_eps`,`salud_archivo_control_glosas_respuestas`.`valor_levantado_eps` AS `valor_levantado_eps`,`salud_archivo_control_glosas_respuestas`.`valor_aceptado_ips` AS `valor_aceptado_ips`,`salud_archivo_control_glosas_respuestas`.`EstadoGlosa` AS `cod_estado`,((`salud_archivo_control_glosas_respuestas`.`valor_glosado_eps` - `salud_archivo_control_glosas_respuestas`.`valor_levantado_eps`) - `salud_archivo_control_glosas_respuestas`.`valor_aceptado_ips`) AS `valor_x_conciliar`,`salud_archivo_control_glosas_respuestas`.`observacion_auditor` AS `observacion_auditor`,`salud_archivo_control_glosas_respuestas`.`fecha_registo` AS `fecha_respuesta`,`salud_archivo_control_glosas_respuestas`.`id_cod_glosa` AS `cod_glosa_respuesta`,`salud_archivo_control_glosas_respuestas`.`CodigoActividad` AS `cod_actividad`,`salud_archivo_control_glosas_respuestas`.`DescripcionActividad` AS `descripcion_actividad`,`salud_archivo_control_glosas_respuestas`.`valor_actividad` AS `valor_total_actividad`,`salud_archivo_control_glosas_respuestas`.`idGlosa` AS `id_glosa_inicial`,`salud_archivo_control_glosas_respuestas`.`EstadoGlosaHistorico` AS `EstadoGlosaHistorico`,(select `salud_archivo_facturacion_mov_generados`.`fecha_factura` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `fecha_factura`,(select `salud_archivo_facturacion_mov_generados`.`numero_radicado` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `numero_radicado`,(select `salud_archivo_facturacion_mov_generados`.`fecha_radicado` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `fecha_radicado`,(select `salud_archivo_facturacion_mov_generados`.`valor_neto_pagar` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `valor_factura`,(select `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `cod_administrador`,(select `salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nombre_administrador`,(select `salud_archivo_facturacion_mov_generados`.`cod_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `cod_prestador`,(select `salud_archivo_facturacion_mov_generados`.`razon_social` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nombre_prestador`,(select `salud_archivo_facturacion_mov_generados`.`num_ident_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nit_prestador`,(select `salud_eps`.`nit` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `nit_administrador`,(select `salud_eps`.`tipo_regimen` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `regimen_eps`,(select `vista_salud_facturas_usuarios`.`num_ident_usuario` from `vista_salud_facturas_usuarios` where (`vista_salud_facturas_usuarios`.`num_factura` = (select `factura`)) limit 1) AS `identificacion`,(select `salud_archivo_usuarios`.`tipo_ident_usuario` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `tipo_identificacion`,(select `salud_archivo_usuarios`.`edad` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `edad_usuario`,(select `salud_archivo_usuarios`.`unidad_medida_edad` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `unidad_medida_edad`,(select `salud_archivo_usuarios`.`sexo` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `sexo_usuario`,(select `salud_glosas_iniciales`.`CodigoGlosa` from `salud_glosas_iniciales` where (`salud_archivo_control_glosas_respuestas`.`idGlosa` = `salud_glosas_iniciales`.`ID`) limit 1) AS `cod_glosa_inicial`,(select `salud_archivo_conceptos_glosas`.`descrpcion_concep_especifico` from `salud_archivo_conceptos_glosas` where ((select `cod_glosa_inicial`) = `salud_archivo_conceptos_glosas`.`cod_glosa`) limit 1) AS `descripcion_glosa_inicial`,(select `salud_archivo_conceptos_glosas`.`descrpcion_concep_especifico` from `salud_archivo_conceptos_glosas` where (`salud_archivo_conceptos_glosas`.`cod_glosa` = `salud_archivo_control_glosas_respuestas`.`id_cod_glosa`) limit 1) AS `descripcion_glosa_respuesta`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_archivo_control_glosas_respuestas`.`EstadoGlosa` = `salud_estado_glosas`.`ID`) limit 1) AS `descripcion_estado`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_archivo_control_glosas_respuestas`.`EstadoGlosaHistorico` = `salud_estado_glosas`.`ID`) limit 1) AS `descripcion_estado_historico` from `salud_archivo_control_glosas_respuestas`;

DROP TABLE IF EXISTS `vista_salud_respuestas_excel`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_salud_respuestas_excel` AS select `salud_archivo_control_glosas_respuestas`.`ID` AS `ID`,`salud_archivo_control_glosas_respuestas`.`CuentaRIPS` AS `cuenta`,`salud_archivo_control_glosas_respuestas`.`num_factura` AS `factura`,`salud_archivo_control_glosas_respuestas`.`Tratado` AS `Tratado`,`salud_archivo_control_glosas_respuestas`.`Soporte` AS `Soporte`,`salud_archivo_control_glosas_respuestas`.`valor_glosado_eps` AS `valor_glosado_eps`,`salud_archivo_control_glosas_respuestas`.`valor_levantado_eps` AS `valor_levantado_eps`,`salud_archivo_control_glosas_respuestas`.`valor_aceptado_ips` AS `valor_aceptado_ips`,`salud_archivo_control_glosas_respuestas`.`EstadoGlosa` AS `cod_estado`,((`salud_archivo_control_glosas_respuestas`.`valor_glosado_eps` - `salud_archivo_control_glosas_respuestas`.`valor_levantado_eps`) - `salud_archivo_control_glosas_respuestas`.`valor_aceptado_ips`) AS `valor_x_conciliar`,`salud_archivo_control_glosas_respuestas`.`observacion_auditor` AS `observacion_auditor`,`salud_archivo_control_glosas_respuestas`.`fecha_registo` AS `fecha_respuesta`,`salud_archivo_control_glosas_respuestas`.`id_cod_glosa` AS `cod_glosa_respuesta`,`salud_archivo_control_glosas_respuestas`.`CodigoActividad` AS `cod_actividad`,`salud_archivo_control_glosas_respuestas`.`DescripcionActividad` AS `descripcion_actividad`,`salud_archivo_control_glosas_respuestas`.`valor_actividad` AS `valor_total_actividad`,`salud_archivo_control_glosas_respuestas`.`idGlosa` AS `id_glosa_inicial`,`salud_archivo_control_glosas_respuestas`.`EstadoGlosaHistorico` AS `EstadoGlosaHistorico`,(select `salud_archivo_facturacion_mov_generados`.`fecha_factura` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `fecha_factura`,(select `salud_archivo_facturacion_mov_generados`.`numero_radicado` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `numero_radicado`,(select `salud_archivo_facturacion_mov_generados`.`fecha_radicado` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `fecha_radicado`,(select `salud_archivo_facturacion_mov_generados`.`valor_neto_pagar` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `valor_factura`,(select `salud_archivo_facturacion_mov_generados`.`cod_enti_administradora` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `cod_administrador`,(select `salud_archivo_facturacion_mov_generados`.`nom_enti_administradora` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nombre_administrador`,(select `salud_archivo_facturacion_mov_generados`.`cod_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `cod_prestador`,(select `salud_archivo_facturacion_mov_generados`.`razon_social` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nombre_prestador`,(select `salud_archivo_facturacion_mov_generados`.`num_ident_prest_servicio` from `salud_archivo_facturacion_mov_generados` where (`salud_archivo_facturacion_mov_generados`.`num_factura` = `salud_archivo_control_glosas_respuestas`.`num_factura`) limit 1) AS `nit_prestador`,(select `salud_eps`.`nit` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `nit_administrador`,(select `salud_eps`.`tipo_regimen` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = (select `cod_administrador`)) limit 1) AS `regimen_eps`,(select `vista_salud_facturas_usuarios`.`num_ident_usuario` from `vista_salud_facturas_usuarios` where (`vista_salud_facturas_usuarios`.`num_factura` = (select `factura`)) limit 1) AS `identificacion`,(select `salud_archivo_usuarios`.`tipo_ident_usuario` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `tipo_identificacion`,(select `salud_archivo_usuarios`.`edad` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `edad_usuario`,(select `salud_archivo_usuarios`.`unidad_medida_edad` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `unidad_medida_edad`,(select `salud_archivo_usuarios`.`sexo` from `salud_archivo_usuarios` where (`salud_archivo_usuarios`.`num_ident_usuario` = (select `identificacion`)) limit 1) AS `sexo_usuario`,(select `salud_glosas_iniciales`.`CodigoGlosa` from `salud_glosas_iniciales` where (`salud_archivo_control_glosas_respuestas`.`idGlosa` = `salud_glosas_iniciales`.`ID`) limit 1) AS `cod_glosa_inicial`,(select `salud_archivo_conceptos_glosas`.`descrpcion_concep_especifico` from `salud_archivo_conceptos_glosas` where ((select `cod_glosa_inicial`) = `salud_archivo_conceptos_glosas`.`cod_glosa`) limit 1) AS `descripcion_glosa_inicial`,(select `salud_archivo_conceptos_glosas`.`descrpcion_concep_especifico` from `salud_archivo_conceptos_glosas` where (`salud_archivo_conceptos_glosas`.`cod_glosa` = `salud_archivo_control_glosas_respuestas`.`id_cod_glosa`) limit 1) AS `descripcion_glosa_respuesta`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_archivo_control_glosas_respuestas`.`EstadoGlosa` = `salud_estado_glosas`.`ID`) limit 1) AS `descripcion_estado`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_archivo_control_glosas_respuestas`.`EstadoGlosaHistorico` = `salud_estado_glosas`.`ID`) limit 1) AS `descripcion_estado_historico` from `salud_archivo_control_glosas_respuestas`;

DROP TABLE IF EXISTS `vista_siho`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_siho` AS select `t1`.`id_fac_mov_generados` AS `id_factura_generada`,(select `salud_eps`.`dias_convenio` from `salud_eps` where (`salud_eps`.`cod_pagador_min` = `t1`.`cod_enti_administradora`)) AS `diasPago`,(select ((to_days('2018-05-02') - to_days(`t1`.`fecha_radicado`)) - (select `diasPago`))) AS `DiasMora`,`t1`.`cod_prest_servicio` AS `cod_prest_servicio`,`t1`.`razon_social` AS `razon_social`,`t1`.`num_factura` AS `num_factura`,`t1`.`fecha_factura` AS `fecha_factura`,`t1`.`fecha_radicado` AS `fecha_radicado`,`t1`.`numero_radicado` AS `numero_radicado`,`t1`.`cod_enti_administradora` AS `cod_enti_administradora`,`t1`.`nom_enti_administradora` AS `nom_enti_administradora`,`t1`.`valor_neto_pagar` AS `valor_neto_pagar`,`t1`.`tipo_negociacion` AS `tipo_negociacion`,`t1`.`estado` AS `estado` from `salud_archivo_facturacion_mov_generados` `t1` where ((`t1`.`estado` <> 'PAGADA') and (`t1`.`estado` <> 'DEVUELTA') and (`t1`.`estado` <> ''));

DROP TABLE IF EXISTS `vista_temporal_actividades_af`;
CREATE ALGORITHM=UNDEFINED DEFINER=`techno`@`%` SQL SECURITY DEFINER VIEW `vista_temporal_actividades_af` AS select 'AC' AS `Archivo`,`salud_archivo_consultas`.`id_consultas` AS `idArchivo`,`salud_archivo_consultas`.`cod_consulta` AS `Codigo`,(select `salud_cups`.`descripcion_cups` from `salud_cups` where (`salud_cups`.`codigo_sistema` = `salud_archivo_consultas`.`cod_consulta`)) AS `Descripcion`,`salud_archivo_consultas`.`valor_consulta` AS `ValorUnitario`,'1' AS `Cantidad`,`salud_archivo_consultas`.`valor_consulta` AS `Total`,`salud_archivo_consultas`.`EstadoGlosa` AS `EstadoGlosa`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_estado_glosas`.`ID` = `salud_archivo_consultas`.`EstadoGlosa`)) AS `Estado` from `salud_archivo_consultas` where (`salud_archivo_consultas`.`num_factura` = 'FV-1011420') union select 'AP' AS `Archivo`,`salud_archivo_procedimientos`.`id_procedimiento` AS `idArchivo`,`salud_archivo_procedimientos`.`cod_procedimiento` AS `Codigo`,(select `salud_cups`.`descripcion_cups` from `salud_cups` where (`salud_cups`.`codigo_sistema` = `salud_archivo_procedimientos`.`cod_procedimiento`)) AS `Descripcion`,(sum(`salud_archivo_procedimientos`.`valor_procedimiento`) / count(`salud_archivo_procedimientos`.`id_procedimiento`)) AS `ValorUnitario`,count(`salud_archivo_procedimientos`.`id_procedimiento`) AS `Cantidad`,sum(`salud_archivo_procedimientos`.`valor_procedimiento`) AS `Total`,`salud_archivo_procedimientos`.`EstadoGlosa` AS `EstadoGlosa`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_estado_glosas`.`ID` = `salud_archivo_procedimientos`.`EstadoGlosa`)) AS `Estado` from `salud_archivo_procedimientos` where (`salud_archivo_procedimientos`.`num_factura` = 'FV-1011420') group by `salud_archivo_procedimientos`.`cod_procedimiento` union select 'AT' AS `Archivo`,`salud_archivo_otros_servicios`.`id_otro_servicios` AS `idArchivo`,`salud_archivo_otros_servicios`.`cod_servicio` AS `Codigo`,`salud_archivo_otros_servicios`.`nom_servicio` AS `Descripcion`,`salud_archivo_otros_servicios`.`valor_unit_material` AS `ValorUnitario`,sum(`salud_archivo_otros_servicios`.`cantidad`) AS `Cantidad`,sum(`salud_archivo_otros_servicios`.`valor_total_material`) AS `Total`,`salud_archivo_otros_servicios`.`EstadoGlosa` AS `EstadoGlosa`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_estado_glosas`.`ID` = `salud_archivo_otros_servicios`.`EstadoGlosa`)) AS `Estado` from `salud_archivo_otros_servicios` where (`salud_archivo_otros_servicios`.`num_factura` = 'FV-1011420') group by `salud_archivo_otros_servicios`.`cod_servicio` union select 'AM' AS `Archivo`,`salud_archivo_medicamentos`.`id_medicamentos` AS `idArchivo`,`salud_archivo_medicamentos`.`cod_medicamento` AS `Codigo`,`salud_archivo_medicamentos`.`nom_medicamento` AS `Descripcion`,`salud_archivo_medicamentos`.`valor_unit_medic` AS `ValorUnitario`,sum(`salud_archivo_medicamentos`.`num_und_medic`) AS `Cantidad`,sum(`salud_archivo_medicamentos`.`valor_total_medic`) AS `Total`,`salud_archivo_medicamentos`.`EstadoGlosa` AS `EstadoGlosa`,(select `salud_estado_glosas`.`Estado_glosa` from `salud_estado_glosas` where (`salud_estado_glosas`.`ID` = `salud_archivo_medicamentos`.`EstadoGlosa`)) AS `Estado` from `salud_archivo_medicamentos` where (`salud_archivo_medicamentos`.`num_factura` = 'FV-1011420') group by `salud_archivo_medicamentos`.`cod_medicamento`;

-- 2019-06-21 21:21:58
