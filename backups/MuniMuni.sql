SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `MuniMuni`
--




CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `dad` varchar(20) NOT NULL,
  `type` varchar(2) NOT NULL,
  `level` int(5) NOT NULL,
  `active` varchar(2) NOT NULL,
  `inflacion` varchar(2) NOT NULL,
  `imputable` varchar(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;


INSERT INTO accounts VALUES
("1","1","ACTIVO","0","0","1","1","0","0"),
("2","2","PASIVO","0","0","1","1","0","1"),
("17","3","PATRIMONIO NETO","0","0","1","1","0","0"),
("18","4","RESULTADO POSITIVO","0","0","1","1","0","0"),
("19","5","RESULTADO NEGATIVO","0","0","1","1","0","0"),
("20","1-1","ACTIVO CORRIENTE","1","0","2","1","0","0"),
("21","1-1-1","DISPONIBILIDADES","1-1","0","3","1","0","0"),
("22","1-1-1-1","Caja","1-1-1","1","4","1","0","1"),
("23","1-1-1-2","BANCOS","1-1-1","0","4","1","0","0"),
("24","1-1-1-2-1","Banco Provincia CC","1-1-1-2","1","5","1","0","1"),
("25","1-1-1-2-2","Banco Nacion CA","1-1-1-2","1","5","1","0","1"),
("26","1-1-1-2-3","Banco Nacion CC","1-1-1-2","1","5","1","0","1"),
("27","1-1-1-3","Valores a depositar","1-1-1","1","4","1","0","1"),
("28","1-1-2","CUENTAS POR COBRAR","1-1","0","3","1","0","0"),
("29","1-1-2-1","Deudores en CC","1-1-2","1","4","1","0","1"),
("30","1-1-2-2","Documentos a cobrar","1-1-2","1","4","1","0","1"),
("31","1-1-2-3","IVA CrÃ©dito Fiscal","1-1-2","1","4","1","0","1"),
("32","1-1-2-4","AFIP-IVA a favor","1-1-2","1","4","1","0","1"),
("33","1-1-3","BIENES DE CAMBIO","1-1","0","3","1","0","0"),
("34","1-1-3-1","MercaderÃ­as","1-1-3","1","4","1","0","1"),
("35","1-1-3-2","PA - Chupetines","1-1-3","1","4","1","0","1"),
("36","1-1-3-3","PB - GarrapiÃ±adas","1-1-3","1","4","1","0","1"),
("37","1-1-3-4","PC - Alfajores","1-1-3","1","4","1","0","1"),
("38","1-1-3-5","PD - Galletitas","1-1-3","1","4","1","0","1"),
("39","1-1-4","INVERSIONES","1-1","0","3","1","0","0"),
("40","1-1-5","OTROS CRÃ‰DITOS","1-1","0","3","1","0","0"),
("41","1-1-5-1","Socio 1 Cuenta Particular","1-1-5","1","4","1","0","1"),
("42","1-1-5-2","Socio 2 Cuenta Particular","1-1-5","1","4","1","0","1"),
("43","1-2","ACTIVO NO CORRIENTE","1","0","2","1","0","0"),
("44","1-2-1","BIENES DE USO","1-2","0","3","1","0","0"),
("45","1-2-1-1","Rodados","1-2-1","1","4","1","0","1"),
("46","1-2-1-2","Muebles y Ãštiles","1-2-1","1","4","1","0","1"),
("47","2-1","PASIVO CORRIENTE","2","0","2","1","0","0"),
("48","2-1-1","DEUDAS","2-1","0","3","1","0","0"),
("49","2-1-1-1","DEUDAS COMERCIALES","2-1-1","0","4","1","0","0"),
("50","2-1-1-1-1","Proveedores","2-1-1-1","1","5","1","0","1"),
("51","2-1-1-1-2","Obligaciones a pagar","2-1-1-1","1","5","1","0","1"),
("52","2-1-1-2","DEUDAS FISCALES","2-1-1","0","4","1","0","0"),
("53","2-1-1-2-1","IVA dÃ©bito fiscal","2-1-1-2","1","5","1","0","1"),
("54","2-1-1-2-2","IVA prec. no insc.","2-1-1-2","1","5","1","0","1"),
("55","2-1-1-2-3","IIBB a pagar","2-1-1-2","1","5","1","0","1"),
("56","2-1-1-2-4","Tasa insp. Segur e Hig a pagar","2-1-1-2","1","5","1","0","1"),
("57","2-1-1-2-5","AFIP - IVA a pagar","2-1-1-2","1","5","1","0","1"),
("58","2-1-1-3","DEUDAS LABORALES Y PREV.","2-1-1","0","4","1","0","0"),
("59","2-1-1-3-1","Sueldos a pagar","2-1-1-3","1","5","1","0","1"),
("60","2-1-1-4","DEUDAS BANCARIAS","2-1-1","0","4","1","0","0"),
("61","2-2","PASIVO NO CORRIENTE","2","0","2","1","0","0"),
("62","3-1","CAPITAL","3","0","2","1","0","0"),
("63","3-1-1","Capital social","3-1","1","3","1","0","1"),
("64","3-2","RESERVAS","3","0","2","1","0","0"),
("65","3-3","RESULTADOS ACUMULADOS","3","0","2","1","0","0"),
("66","3-3-1","Resultados del ej. anterior","3-3","1","3","1","0","1"),
("67","4-1","INGRESOS ORDINARIOS","4","0","2","1","0","0"),
("68","4-1-1","Ventas","4-1","1","3","1","0","1"),
("69","4-1-2","Intereses Obtenidos","4-1","1","3","1","0","1"),
("70","4-1-3","Descuentos Obtenidos","4-1","1","3","1","0","1"),
("71","4-1-4","Ingresos por serv. prestados","4-1","1","3","1","0","1"),
("72","4-1-5","Ingresos por fletes","4-1","1","3","1","0","1"),
("73","4-2","INGRESOS EXTRAORDINARIOS","4","0","2","1","0","0"),
("74","5-1","GASTOS DE COMERCIALIZACION","5","0","2","1","0","0"),
("75","5-1-1","Costo de venta","5-1","1","3","1","0","1"),
("76","5-2","GASTOS ADMINISTRATIVOS","5","0","2","1","0","0"),
("77","5-2-1","Impuestos nacionales","5-2","1","3","1","0","1"),
("78","5-2-2","Agua, luz y gas","5-2","1","3","1","0","1"),
("79","5-2-3","TelÃ©fono","5-2","1","3","1","0","1"),
("80","5-2-4","Alquileres Cedidos","5-2","1","3","1","0","1"),
("81","5-2-5","IIBB","5-2","1","3","1","0","1"),
("82","5-2-6","Tasa por Insp. Seg. e Hig.","5-2","1","3","1","0","1"),
("83","5-3","GASTOS EN PERSONAL","5","0","2","1","0","0"),
("84","5-3-1","Sueldos y jornales","5-3","1","3","1","0","1"),
("85","5-3-2","Cargas sociales","5-3","1","3","1","0","1"),
("86","5-4","GASTOS FINANCIEROS","5","0","2","1","0","0"),
("87","5-4-1","Intereses Cedidos","5-4","1","3","1","0","1"),
("88","5-4-2","Descuentos cedidos","5-4","1","3","1","0","1"),
("89","5-4-3","Gastos bancarios","5-4","1","3","1","0","1"),
("90","5-5","OTROS GASTOS","5","0","2","1","0","0"),
("91","5-5-1","Fletes pagados","5-5","1","3","1","0","1"),
("92","5-5-2","ConservaciÃ³n y mantenimiento","5-5","1","3","1","0","1");




CREATE TABLE `assets` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;


INSERT INTO assets VALUES
("9","2019-08-28","0","0"),
("26","2019-08-15","0","9"),
("27","2019-08-15","0","9"),
("28","2019-08-15","0","9"),
("29","2019-08-15","0","5"),
("30","2019-08-15","0","5"),
("31","2019-08-15","0","5"),
("32","2019-08-21","0","5"),
("33","2019-08-21","0","5"),
("34","2019-08-21","0","5"),
("35","2019-08-21","0","5"),
("36","2019-08-21","0","5"),
("37","2019-08-21","0","5"),
("38","2019-08-21","0","5"),
("39","2019-08-21","0","5"),
("40","2019-08-28","0","5"),
("41","2019-08-28","0","5"),
("42","2019-08-28","0","5"),
("43","2019-08-28","0","5"),
("44","2019-08-28","0","5"),
("45","2019-08-28","0","5"),
("46","2019-08-28","0","5"),
("47","2019-08-28","0","5"),
("48","2019-08-28","0","5"),
("49","2019-08-28","0","5"),
("50","2019-08-14","0","5"),
("51","2019-08-14","0","5"),
("52","2019-08-15","0","5"),
("53","2019-08-13","0","5"),
("54","2019-08-14","0","5"),
("55","2019-08-13","0","5"),
("56","2019-08-06","0","5"),
("59","2019-08-15","0","5"),
("60","2019-08-06","0","5"),
("61","2019-08-06","0","5"),
("62","2019-08-21","0","5"),
("63","2019-08-14","1","5"),
("64","2019-08-15","0","5"),
("65","2019-08-29","1","0"),
("66","2019-08-28","0","5"),
("67","2019-08-28","0","5"),
("68","2019-07-30","0","5"),
("69","2019-07-30","0","5"),
("70","2019-07-30","0","5"),
("71","2019-07-30","0","5"),
("72","2019-07-30","0","5"),
("73","2019-08-20","0","5"),
("74","2019-08-20","0","5"),
("75","2019-08-20","0","5"),
("76","2019-08-20","0","5"),
("77","2019-08-27","0","5"),
("78","2019-08-27","0","5"),
("79","2019-08-13","0","5"),
("80","2019-08-13","0","5"),
("81","2019-08-13","0","5"),
("82","2019-08-13","0","5"),
("83","2019-08-13","0","5"),
("84","2019-08-13","0","5"),
("85","2019-08-13","0","5"),
("86","2019-08-13","0","5"),
("87","2019-08-13","0","5"),
("88","2019-08-13","0","5"),
("89","2019-08-13","0","5"),
("90","2019-08-13","0","5"),
("91","2019-08-13","0","5"),
("92","2019-08-13","0","5"),
("93","2019-08-13","0","5"),
("94","2019-08-13","0","5"),
("95","2019-08-13","0","5"),
("96","2019-08-27","0","5"),
("97","2019-08-14","0","5"),
("98","2019-08-20","0","5"),
("99","2019-08-07","0","5"),
("100","2019-08-07","0","5"),
("101","2019-08-07","0","5"),
("102","2019-08-07","0","5"),
("103","2019-08-07","0","5"),
("104","2019-08-07","0","5"),
("105","2019-08-07","0","5"),
("106","2019-08-07","0","5"),
("107","2019-08-07","0","5"),
("108","2019-08-07","0","5"),
("109","2019-08-07","0","5"),
("110","2019-08-07","0","5"),
("111","2019-08-07","0","5"),
("112","2019-08-07","0","5"),
("113","2019-08-07","0","5"),
("114","2019-08-07","0","5"),
("115","2019-08-07","0","5"),
("116","2019-08-21","0","5"),
("117","2001-12-01","0","5"),
("118","2019-08-27","0","5"),
("119","2019-10-19","1","5");




CREATE TABLE `assets_row` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `asset_number` int(11) NOT NULL,
  `row_number` varchar(200) CHARACTER SET latin1 NOT NULL,
  `account` varchar(200) CHARACTER SET latin1 NOT NULL,
  `date_op` date NOT NULL,
  `date_ven` date NOT NULL,
  `suc` varchar(200) CHARACTER SET latin1 NOT NULL,
  `secc` varchar(200) CHARACTER SET latin1 NOT NULL,
  `concep` text CHARACTER SET latin1 NOT NULL,
  `type` int(2) NOT NULL,
  `import` varchar(200) CHARACTER SET latin1 NOT NULL,
  `comprobante` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;


INSERT INTO assets_row VALUES
("2","18","2","3545","2019-08-06","2019-08-13","4","4","hfhhhhshshhrt","1","4444",""),
("3","18","3","1111","2019-08-20","2019-08-13","1","2","Hola a todos","0","111",""),
("4","19","1","214343","2019-08-21","2019-08-30","0","12","edtrfyughjoipklÃ©dtrfygubhinjomkprfyguhijo","0","845132",""),
("5","25","1","121","2019-08-13","2019-08-13","1","1","eswrdtfopklfyugokpl","1","1111","trfyugojipk"),
("6","25","2","gtggtt","2019-07-30","2019-08-21","3","3","3q4h5rh45","0","3333","gregregeger"),
("7","25","1","vghbnjm","2019-08-21","2019-08-13","7","7","xdcfvgbhnjl","0","444","vgjhbknm"),
("8","28","1","33","2019-08-20","2019-08-20","3","4","fsdd","0","333","fsdfd"),
("9","51","1","1-1-1-1","2019-08-13","0000-00-00","","","ertyui","1","5512.2",""),
("10","51","1","1-1-1-1","2019-08-13","0000-00-00","","","ertyui","1","5512.2",""),
("11","51","1","1-1-1-1","2019-08-13","0000-00-00","","","ertyui","1","5512.2",""),
("12","51","1","1-1-1-1","2019-08-13","0000-00-00","","","ertyui","1","5512.2",""),
("13","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("14","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("15","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("16","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("17","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("18","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("19","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("20","51","2","2-1-1-1-1","2019-08-13","0000-00-00","1","1","rsxdtcfyvgubhinjo","0","777","dxcfghnjkl"),
("21","52","1","1-1-1-1","2019-08-25","0000-00-00","","","ALQUILER","1","15000",""),
("22","52","1","1-1-1-1","2019-08-25","0000-00-00","","","ALQUILER","1","15000",""),
("23","52","2","1-1-1-1","2019-08-30","0000-00-00","","","Alquiler REGV","0","30000",""),
("24","53","1","1-1-1-1","1996-10-25","0000-00-00","","","aaa","0","15000",""),
("25","53","2","1-1-1-1","1996-10-10","0000-00-00","","","1","1","15000",""),
("26","54","1","1-1-1-1","2019-05-10","0000-00-00","","","dfghjk","0","15000",""),
("27","54","1","1-1-1-1","2019-05-10","0000-00-00","","","dfghjk","0","15000",""),
("28","54","2","1-1-1-1","2019-05-10","0000-00-00","","","fcvgbhnjmk","0","1000",""),
("29","54","2","1-1-1-1","2019-05-10","0000-00-00","","","fcvgbhnjmk","0","1000",""),
("30","55","1","1-1-1-1","1010-10-10","0000-00-00","","","hy","1","15000",""),
("31","55","2","1-1-1-1","2019-08-06","0000-00-00","","","fvgbhnjmk","0","15000",""),
("32","55","3","1-1-1-1","2019-08-06","0000-00-00","","","fghjmfghjk","0","101.5","b"),
("33","55","4","1-1-1-1","2019-08-21","0000-00-00","","","xdcfvgbhnjm","1","101.5",""),
("34","55","4","1-1-1-1","2019-08-21","0000-00-00","","","xdcfvgbhnjm","1","101.5",""),
("35","55","4","1-1-1-1","2019-08-21","0000-00-00","","","xdcfvgbhnjm","1","101.5",""),
("36","57","1","1-1-1-1","1021-10-25","0000-00-00","","","nn","0","15.2",""),
("37","57","2","1-1-1-1","1996-10-10","0000-00-00","","","hjgsdc","1","15.256",""),
("38","57","3","1-1-1-1","2019-08-06","0000-00-00","","","hhh","0","0.056",""),
("39","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("40","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("41","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("42","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("43","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("44","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("45","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("46","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("47","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("48","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("49","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("50","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("51","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("52","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("53","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("54","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("55","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("56","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("57","57","4","1-1-1-1","2019-08-20","0000-00-00","","","www","1","0",""),
("60","58","1","1-1-1-1","2019-08-12","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergn","1","1524.25",""),
("61","58","1","1-1-1-1","2019-08-12","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrng","1","1524.25",""),
("62","58","2","1-1-1-1","2019-08-13","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrng","1","1512",""),
("63","58","2","1-1-1-1","2019-08-13","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrng","1","1512",""),
("64","58","3","1-1-1-1","2019-08-12","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrng","0","11",""),
("65","59","1","1-1-1-1","2019-08-07","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrng","0","0",""),
("66","59","1","1-1-1-1","2019-08-07","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfo","0","0",""),
("69","65","1","1-1-1-1","2019-08-20","0000-00-00","","","PAGO ALQUILER","0","15000","APPP-515"),
("70","65","2","2-1-1-1-1","2019-08-20","0000-00-00","","","PAGO ALQUILER","1","15000",""),
("71","96","1","1-1-1-1","2019-08-21","0000-00-00","","","hgghg","1","11111","j"),
("72","96","1","1-1-1-1","2019-08-21","0000-00-00","","","hgghg","1","11111","j"),
("75","116","1","1-1-1-2-3","2019-08-06","2019-08-27","1","5","edtrfyughjoipklÃ©dtrfygubhinjomkprfyguhijo","0","175.25","dxcfghnjkl"),
("77","116","1","1-1-2-4","2019-08-20","0000-00-00","","","edtrfyughjoipklÃ©dtrfygubhinjomkprfyguhijo","1","170",""),
("78","119","1","1-1-1-2-3","2019-08-20","0000-00-00","","","qjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwefnwrngfoergnqjnuwe","0","5000",""),
("79","119","2","1-1-2-3","2019-08-20","0000-00-00","","","dcfvgbhnj","1","5000","");




CREATE TABLE `auditory` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `movement` varchar(200) NOT NULL,
  `description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;


INSERT INTO auditory VALUES
("1","ADMIN","2019-08-22","22:06:57","HA INICIADO SESION.",""),
("2","ADMIN","2019-08-22","22:21:29","LISTAR USUARIOS",""),
("3","ADMIN","2019-08-22","22:21:41","NUEVO USUARIO","CARGA DEL USUARIO: dfghjkl"),
("4","ADMIN","2019-08-22","22:21:42","LISTAR USUARIOS",""),
("5","ADMIN","2019-08-22","22:22:28","LISTAR USUARIOS",""),
("6","ADMIN","2019-08-22","22:22:44","LISTAR USUARIOS",""),
("7","ADMIN","2019-08-22","22:22:50","LISTAR USUARIOS",""),
("8","ADMIN","2019-08-22","22:22:51","LISTAR USUARIOS",""),
("9","ADMIN","2019-08-22","22:23:04","LISTAR USUARIOS",""),
("10","ADMIN","2019-08-22","22:23:09","LISTAR USUARIOS",""),
("11","ADMIN","2019-08-22","22:23:16","LISTAR USUARIOS",""),
("12","ADMIN","2019-08-22","22:23:19","LISTAR USUARIOS",""),
("13","ADMIN","2019-08-22","22:23:22","LISTAR USUARIOS",""),
("14","ADMIN","2019-08-22","22:23:28","LISTAR USUARIOS",""),
("15","ADMIN","2019-08-26","19:00:39","HA INICIADO SESION.",""),
("16","ADMIN","2019-08-28","18:17:08","ABRIR AUDITORIA",""),
("17","ADMIN","2019-08-28","18:17:14","ABRIR AUDITORIA",""),
("18","ADMIN","2019-08-28","18:22:16","ABRIR AUDITORIA",""),
("19","ADMIN","2019-08-28","18:22:43","ABRIR AUDITORIA",""),
("20","ADMIN","2019-08-28","19:22:36","HA CERRADO SESION.",""),
("21","ADMIN","2019-08-28","19:22:47","HA INICIADO SESION.",""),
("22","ADMIN","2019-08-28","19:23:02","LISTAR VENDEDORES",""),
("23","ADMIN","2019-08-28","19:23:18","LISTAR VENDEDORES",""),
("24","ADMIN","2019-08-28","19:23:23","LISTAR PUNTOS DE VENTA",""),
("25","ADMIN","2019-08-28","19:23:31","LISTAR PUNTOS DE VENTA",""),
("26","ADMIN","2019-08-29","23:28:01","HA INICIADO SESION.",""),
("27","ADMIN","2019-08-31","19:38:51","LISTAR VENDEDORES",""),
("28","ADMIN","2019-08-31","19:38:57","LISTAR PUNTOS DE VENTA",""),
("29","ADMIN","2019-08-31","19:42:44","ABRIR AUDITORIA",""),
("30","ADMIN","2019-08-31","19:42:53","ABRIR AUDITORIA",""),
("31","ADMIN","2019-08-31","19:42:59","ABRIR AUDITORIA",""),
("32","ADMIN","2019-08-31","19:43:28","HA CERRADO SESION.",""),
("33","SUPERADMINISTRADOR","2019-08-31","19:43:37","SE REALIZO COPIA DE SEGURIDAD","");




CREATE TABLE `companydata` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `dbname` varchar(50) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `style` varchar(50) NOT NULL,
  `tribut_id` varchar(30) NOT NULL,
  `customertype` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `inicio_fiscal` date NOT NULL,
  `fin_fiscal` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO companydata VALUES
("1","MuniMuni","MuniMuni","MuniMuni.jpg","skyblue.css","34567890\'","Consumidor Final","rftgyhujiko","2019-01-01","2019-12-31");




CREATE TABLE `pdv` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






CREATE TABLE `sellers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `birthdate` date NOT NULL,
  `phone` bigint(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pdv_ID` int(11) NOT NULL,
  `zone_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `active` varchar(2) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL,
  `user` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


INSERT INTO users VALUES
("1","ADMIN","SI","admin","SUPERADMINISTRADOR","admin"),
("2","dfghjkl","SI","ygibijk","","VGHBJNUK");




CREATE TABLE `zones` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


INSERT INTO zones VALUES
("1","NORTE"),
("2","SUR"),
("3","CENTRO"),
("4","ESTE"),
("5","OESTE");




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;