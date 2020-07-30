ALTER TABLE `facturas` ADD `idTraza` VARCHAR(100) NOT NULL AFTER `ReferenciaTutela`, ADD INDEX `idTraza` (`idTraza`);

