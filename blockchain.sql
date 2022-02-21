-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 13, 2020 alle 09:19
-- Versione del server: 10.4.6-MariaDB
-- Versione PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blockchain`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `block`
--

CREATE TABLE `block` (
  `blockHash` varchar(128) NOT NULL,
  `merkleRootHash` varchar(128) NOT NULL,
  `previousBlockHash` varchar(64) NOT NULL,
  `index_block` int(11) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `transaction1` varchar(64) NOT NULL,
  `transaction2` varchar(64) NOT NULL,
  `target` int(11) NOT NULL,
  `nonce` int(11) NOT NULL,
  `miner` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `block`
--

INSERT INTO `block` (`blockHash`, `merkleRootHash`, `previousBlockHash`, `index_block`, `timestamp`, `transaction1`, `transaction2`, `target`, `nonce`, `miner`) VALUES
('27b469d72f2052f0000e35079c66be0af56f6b2a03e38d584810f1ca3defc9c9', 'ab79400405b2471f14f927e86c68d49231de620ca30bb48c808768bb28bf742c', 'da1f6b70f235e2e074fcbc9525bc21c7ac8c825b3a14c717633cb0701ea22901', 1, '2020-06-11 19:02:00', '84b75494dfdf2294ef2301e79c0756a4d2817c4b23b4bc482a31fbbebaf9ff8f', 'da179706696fc252dde15428a0355313ee18ca05c0107c1eb9f056e39e3241a7', 10, 8, '0d4b6071c87aadb2da19cd66e1aa1f3a55b32940b61f5b227d071523fa8b53bf'),
('7d3cb051d98e878a5ad7c5daaa0f3481d51e052ba9ae6baea310bd7e4c47f30a', '7e6b02f81b6da181ee1d3508616036c40db0f1849bff4bcd6631194844ec3cf4', '27b469d72f2052f0000e35079c66be0af56f6b2a03e38d584810f1ca3defc9c9', 2, '2020-06-11 19:13:10', '7d55cd4b07af80c1db58b91a75a925983321f86a6a1cd69d892c432a014685bf', '9668051ab8dcadaac2c36a0e98313bb81b835de6a70f72ce8f895d9d77fc4263', 2, 2, 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e'),
('d0396a031310bcad0b902dd7edf179e787f026eba9bbd9b048d89656a1f56936', 'e3584713dcbdb637fa9c35bcd3e27f651a3b94a344c43fd7d0d3c2cc21b1f520', '7d3cb051d98e878a5ad7c5daaa0f3481d51e052ba9ae6baea310bd7e4c47f30a', 3, '2020-06-13 00:05:29', '1d56545c5407b1ee6d8c27461c5b7ac005374a7bfa47c03b8a1b4644a51ff7d5', '656eedf0b707fca254c8bdc38d82f83d5ae770dc6130da2441581a77b8257bc5', 6, 5, '0d4b6071c87aadb2da19cd66e1aa1f3a55b32940b61f5b227d071523fa8b53bf'),
('da1f6b70f235e2e074fcbc9525bc21c7ac8c825b3a14c717633cb0701ea22901', '1c7f165648a9b89491a765772981611e44d12708bd178f05e0f30f8f5a27bee9', 'da1f6b70f235e2e074fcbc9525bc21c7ac8c825b3a14c717633cb0701ea22901', 0, '2020-06-11 18:59:48', '7dc33eb2d976b050b0c14f4516d4977bf7229f46d83c9b58eef1b6764e57317b', 'f54e1b052a2a3121310fb79cce32429ee613fc35cdcfdb1f4027579cdd4a2fe0', 10, 3, '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08');

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `transactions` (
  `index_transaction` varchar(64) NOT NULL,
  `from_email` varchar(64) NOT NULL,
  `to_email` varchar(64) NOT NULL,
  `amount` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `index_block` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `transactions`
--

INSERT INTO `transactions` (`index_transaction`, `from_email`, `to_email`, `amount`, `time`, `status`, `index_block`) VALUES
('1d56545c5407b1ee6d8c27461c5b7ac005374a7bfa47c03b8a1b4644a51ff7d5', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', 2, '2020-06-12 22:02:01', 'confirmed', 3),
('656eedf0b707fca254c8bdc38d82f83d5ae770dc6130da2441581a77b8257bc5', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', 5, '2020-06-12 22:04:55', 'confirmed', 3),
('7d55cd4b07af80c1db58b91a75a925983321f86a6a1cd69d892c432a014685bf', '3009be769fb8f956e8413ee9f3e0836e34968bc40457d0a10c549d2edcf00cc1', '0d4b6071c87aadb2da19cd66e1aa1f3a55b32940b61f5b227d071523fa8b53bf', 100, '2020-06-11 17:01:44', 'confirmed', 2),
('7dc33eb2d976b050b0c14f4516d4977bf7229f46d83c9b58eef1b6764e57317b', '3009be769fb8f956e8413ee9f3e0836e34968bc40457d0a10c549d2edcf00cc1', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 100, '2020-06-10 15:00:19', 'confirmed', 0),
('84b75494dfdf2294ef2301e79c0756a4d2817c4b23b4bc482a31fbbebaf9ff8f', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', 10, '2020-06-10 15:11:46', 'confirmed', 1),
('9668051ab8dcadaac2c36a0e98313bb81b835de6a70f72ce8f895d9d77fc4263', '0d4b6071c87aadb2da19cd66e1aa1f3a55b32940b61f5b227d071523fa8b53bf', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 10, '2020-06-11 17:12:25', 'confirmed', 2),
('da179706696fc252dde15428a0355313ee18ca05c0107c1eb9f056e39e3241a7', '561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', 3, '2020-06-10 15:50:24', 'confirmed', 1),
('f54e1b052a2a3121310fb79cce32429ee613fc35cdcfdb1f4027579cdd4a2fe0', '3009be769fb8f956e8413ee9f3e0836e34968bc40457d0a10c549d2edcf00cc1', 'f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', 100, '2020-06-10 15:11:21', 'confirmed', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `conto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `password`, `conto`, `nome`) VALUES
('0d4b6071c87aadb2da19cd66e1aa1f3a55b32940b61f5b227d071523fa8b53bf', '64b4d0f47c93ce23d157e68a58767356283dc9b63c459d45d0e0e39b3a64b9b9', '115.00', 'mike'),
('3009be769fb8f956e8413ee9f3e0836e34968bc40457d0a10c549d2edcf00cc1', '3009be769fb8f956e8413ee9f3e0836e34968bc40457d0a10c549d2edcf00cc1', '0.00', 'network'),
('561b9ca3fe688437860c00c22a1a764b6d5439c7cacb2ab378c88fe18c474a08', '03fd72f81572805dd59f829b94fd8a6f82077fb435ca2b406d9595718e521afa', '85.00', 'lorenzo'),
('f72ea745f0995cfa10f236bd95f08713f39a0eb2942bbbd72f53cc089ffa430e', '4ea7ea4917057a1fcbb3bffdb673602d9b961ff14b239cc7a8d96933b8a18b51', '140.00', 'natalia');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`blockHash`),
  ADD UNIQUE KEY `id_transaction1_FK` (`transaction1`) USING BTREE,
  ADD UNIQUE KEY `id_transaction2_FK` (`transaction2`) USING BTREE,
  ADD UNIQUE KEY `index_block` (`index_block`) USING BTREE,
  ADD KEY `id_miner_FK` (`miner`),
  ADD KEY `previousBlockHash_FK` (`previousBlockHash`) USING BTREE;

--
-- Indici per le tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`index_transaction`),
  ADD KEY `from_email_FK` (`from_email`),
  ADD KEY `id_to_email_FK` (`to_email`),
  ADD KEY `index_block` (`index_block`) USING BTREE;

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`email`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `block`
--
ALTER TABLE `block`
  ADD CONSTRAINT `id_miner_FK` FOREIGN KEY (`miner`) REFERENCES `utente` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_transaction1_FK` FOREIGN KEY (`transaction1`) REFERENCES `transactions` (`index_transaction`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_transaction2_FK` FOREIGN KEY (`transaction2`) REFERENCES `transactions` (`index_transaction`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `previousBlockHash_FK` FOREIGN KEY (`previousBlockHash`) REFERENCES `block` (`blockHash`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `from_email_FK` FOREIGN KEY (`from_email`) REFERENCES `utente` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_to_email_FK` FOREIGN KEY (`to_email`) REFERENCES `utente` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `index_block_FK` FOREIGN KEY (`index_block`) REFERENCES `block` (`index_block`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
