CREATE TABLE `facturas_anulaciones` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `TipoMovimiento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idFactura` (`idFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;