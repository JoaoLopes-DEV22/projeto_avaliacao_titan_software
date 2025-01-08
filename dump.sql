-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para db_titan_software
CREATE DATABASE IF NOT EXISTS `db_titan_software` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_titan_software`;

-- Copiando estrutura para tabela db_titan_software.tbl_empresa
CREATE TABLE IF NOT EXISTS `tbl_empresa` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela db_titan_software.tbl_empresa: ~1 rows (aproximadamente)
INSERT INTO `tbl_empresa` (`id_empresa`, `nome`) VALUES
	(1, 'Titan Software');

-- Copiando estrutura para tabela db_titan_software.tbl_funcionario
CREATE TABLE IF NOT EXISTS `tbl_funcionario` (
  `id_funcionario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `salario` double(10,2) DEFAULT NULL,
  `bonificacao` double(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_funcionario`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `fk_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tbl_empresa` (`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela db_titan_software.tbl_funcionario: ~1 rows (aproximadamente)
INSERT INTO `tbl_funcionario` (`id_funcionario`, `nome`, `cpf`, `rg`, `email`, `id_empresa`, `data_cadastro`, `salario`, `bonificacao`) VALUES
	(11, 'João', '47102917805', '', 'joao@hotmail.com', 1, '2019-01-08', 2000.00, 400.00);

-- Copiando estrutura para tabela db_titan_software.tbl_usuario
CREATE TABLE IF NOT EXISTS `tbl_usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela db_titan_software.tbl_usuario: ~1 rows (aproximadamente)
INSERT INTO `tbl_usuario` (`id_usuario`, `login`, `senha`) VALUES
	(1, 'teste@gmail.com.br', '81dc9bdb52d04dc20036dbd8313ed055');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
