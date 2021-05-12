-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 12, 2021 alle 21:43
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ludoteca`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `gioca` (IN `Codice_videogioco` INT, IN `key_appoggio_1` INT, IN `Codice_scheda` INT)  Begin
Declare orario datetime;
Set orario=current_timestamp();
Insert into sit_attuale values (codice_videogioco, key_appoggio_1, codice_scheda, orario);
Delete from sit_attuale where sit_attuale.key_appoggio_1=key_appoggio_1 and inizio<>orario;
Delete from sit_attuale where sit_attuale.codice_scheda=codice_scheda and inizio<>orario;
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ingresso` (IN `cf` VARCHAR(20), IN `codice_scheda` INT)  Begin
update scheda set inizio=(current_timestamp()), cf=(cf) where codice=codice_scheda;
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Inizia_evento` (IN `nome_evento` VARCHAR(30), IN `codice_videogioco` INT, IN `durata_minuti` INT)  Begin 
Insert into evento values (current_timestamp(), Nome_evento, codice_videogioco, default, durata_minuti);
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc1` (IN `categoria` VARCHAR(20))  Begin
Case categoria
When 'fps' then
Select Codice, Titolo, Genere, sum(n_avvi) as N_avvi, count(*) as Utenti from vista2 join fps on (codice_videogioco=codice) 
group by codice, titolo, genere order by utenti desc;
Select v.Codice, v.Titolo, c.Genere, 'Si' as Non_usato from videogioco v join fps c on v.codice=c.codice where v.codice not in(select codice_videogioco from vista2);
Select sum(t.Utenti) as N_utenti_Fps from (Select Codice, count(*) as Utenti from vista2 join fps on (codice_videogioco=codice) group by codice) t;
When 'arcade' then
Select Codice, Titolo, sum(n_avvi) as N_avvi, count(*) as Utenti from vista2 join arcade on (codice_videogioco=codice) 
group by codice, titolo order by utenti desc;
Select v.Codice, v.Titolo, 'Si' as Non_usato from videogioco v join arcade c on v.codice=c.codice where v.codice not in(select codice_videogioco from vista2);
Select sum(t.Utenti) as N_utenti_Arcade from (Select Codice, count(*) as Utenti from vista2 join Arcade on (codice_videogioco=codice) group by codice) t;
When 'quiz' then
Select Codice, Titolo, Argomento, sum(n_avvi) as N_avvi, count(*) as Utenti from vista2 join quiz on (codice_videogioco=codice) 
group by codice, titolo, Argomento order by utenti desc;
Select v.Codice, v.Titolo, c.Argomento, 'Si' as Non_usato from videogioco v join quiz c on v.codice=c.codice where v.codice not in(select codice_videogioco from vista2);
Select sum(t.Utenti) as N_utenti_Quiz from (Select Codice, count(*) as Utenti from vista2 join quiz on (codice_videogioco=codice) group by codice) t;
When 'corsa' then
Select Codice, Titolo, Tipo_gara, sum(n_avvi) as N_avvi, count(*) as Utenti from vista2 join corsa on (codice_videogioco=codice) 
group by codice, titolo,Tipo_gara order by utenti desc;
Select v.Codice, v.Titolo, c.Tipo_gara, 'Si' as Non_usato from videogioco v join corsa c on v.codice=c.codice where v.codice not in(select codice_videogioco from vista2);
Select sum(t.Utenti) as N_utenti_Corsa from (Select Codice, count(*) as Utenti from vista2 join corsa on (codice_videogioco=codice) group by codice) t;
When 'tutti_compatto' then
Select * from (Select 'Fps' as categoria, sum(t.Utenti) as N_utenti from (Select Codice, count(*) as Utenti from vista2 join fps on (codice_videogioco=codice) group by codice) t) a
Union (Select 'Arcade' as categoria, sum(t.Utenti) as N_utenti from (Select Codice, count(*) as Utenti from vista2 join Arcade on (codice_videogioco=codice) group by codice) t)
Union (Select 'Quiz' as categoria, sum(t.Utenti) as N_utenti from (Select Codice, count(*) as Utenti from vista2 join quiz on (codice_videogioco=codice) group by codice) t)
Union (Select 'Corsa' as categoria, sum(t.Utenti) as N_utenti from (Select Codice, count(*) as Utenti from vista2 join corsa on (codice_videogioco=codice) group by codice) t)
Order by N_utenti desc;
When 'tutti' then
Select Codice_videogioco as codice, Titolo, sum(n_avvi) as N_avvi, count(*) as Utenti from vista2
group by codice, titolo order by utenti desc;
Select v.Codice, v.Titolo, 'Si' as Non_usato from videogioco v where v.codice not in(select codice_videogioco from vista2);
Else select 'Inserire uno fra fps, arcade, quiz, tutti, tutti_compatto' as Errore;
End case;
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc2` (IN `numero` INT)  Begin
Declare tot int;
select count(*) into tot from vista1; 
Drop temporary table if exists temp;
Create temporary table temp(posizione int, cf varchar(20), punti_totali int, occupazione varchar(30), anno_nascita date, media_punteggio decimal(7,2),  
media_sconto decimal(5,2), media_spesa decimal(5,2), totale_spesa decimal(10,2), N_fps int, N_arcade int, N_corsa int, N_quiz int);
Insert into temp
Select (tot-(select count(*) from vista1 v2 where v1.punti_totali > v2.punti_totali and v1.cf<>v2.cf))  as posizione,  v1.cf, v1.punti_totali,  v1.occupazione, v1.anno_nascita, v1.media_punteggio, v1.media_sconto, v1.media_spesa, v1.totale_spesa, v1.N_fps, v1.N_arcade, v1.N_corsa, v1.N_quiz
From vista1 v1
Order by posizione;
Select posizione, cf, year(anno_nascita) as anno_nascita, media_punteggio, media_sconto, punti_totali from temp where posizione<=numero;
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc3` (IN `codice_scheda` INT, IN `punteggio` INT)  Begin 
Declare  time_inizio datetime;
Declare cf_persona varchar(20);
Select inizio into time_inizio from scheda where codice=codice_scheda;
Select cf into cf_persona from scheda where codice=codice_scheda;
If not exists(select *from (select * from sit_attuale union (select Codice_videogioco, key_appoggio_1, Codice_scheda, inizio from sit_passato)) o
   where o.codice_scheda=codice_scheda and inizio > time_inizio and inizio<current_timestamp) 
then insert into cronologia 
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), 0, 0, (hour(timediff(current_timestamp(),time_inizio))+1)*5);
Else Case
When punteggio <=50 then insert into cronologia 
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 0, (hour(timediff(current_timestamp(),time_inizio))+1)*5);
When punteggio>50 and punteggio<=100 then insert into cronologia 
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 11.5, (1-11.5/100)*(hour(timediff(current_timestamp(),time_inizio))+1)*5);
When punteggio>100 and punteggio<=150 then insert into cronologia 
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 22.5, (1-22.5/100)*(hour(timediff(current_timestamp(),time_inizio))+1)*5);
When punteggio>150 and punteggio <=300  then insert into cronologia  
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 32.5, (1-32.5/100)*(hour(timediff(current_timestamp(),time_inizio))+1)*5);
When punteggio>300 and punteggio <=500  then insert into cronologia
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 42.5, (1-42.5/100)*(hour(timediff(current_timestamp(),time_inizio))+1)*5);
When punteggio>500 then insert into cronologia 
values(cf_persona, Codice_scheda, time_inizio, current_timestamp(), punteggio, 52.5, (1-52.5/100)*(hour(timediff(current_timestamp(),time_inizio))+1)*5);
End case;
End if;
End$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc4` ()  Begin
Update evento set concluso=1 where concluso=0 and  (inizio+interval durata_minuti minute) < current_timestamp();
Select  e.Nome, g.Titolo, t.Modificatore_bonus, t.Modificatore_difficolta, 
Timediff((inizio+interval durata_minuti minute), current_timestamp()) as tempo_rimasto
from evento e join tipo_evento t on e.nome=t.nome join videogioco g on e.videogioco=g.codice
where concluso=0 order by tempo_rimasto;
End$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `arcade`
--

CREATE TABLE `arcade` (
  `Codice` int(11) NOT NULL,
  `Record_punteggio` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `arcade`
--

INSERT INTO `arcade` (`Codice`, `Record_punteggio`) VALUES
(11, 1000),
(12, 250),
(13, 0),
(14, 0),
(15, 90);

-- --------------------------------------------------------

--
-- Struttura della tabella `contest`
--

CREATE TABLE `contest` (
  `Cf` varchar(20) NOT NULL,
  `Nome_videogioco` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `contest`
--

INSERT INTO `contest` (`Cf`, `Nome_videogioco`) VALUES
('bbc99', 'Grand Theft Auto V');

-- --------------------------------------------------------

--
-- Struttura della tabella `corsa`
--

CREATE TABLE `corsa` (
  `Codice` int(11) NOT NULL,
  `Tipo_gara` varchar(30) DEFAULT NULL,
  `Tempo_record` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `corsa`
--

INSERT INTO `corsa` (`Codice`, `Tipo_gara`, `Tempo_record`) VALUES
(6, 'F1', '00:01:45'),
(7, 'Rally', '00:03:00'),
(8, 'F1', '00:01:20'),
(9, 'MotoGp', '00:02:09'),
(10, 'Nascar', '00:00:30');

-- --------------------------------------------------------

--
-- Struttura della tabella `cronologia`
--

CREATE TABLE `cronologia` (
  `Cf` varchar(20) NOT NULL,
  `Codice_scheda` int(11) NOT NULL,
  `Inizio` datetime NOT NULL,
  `Fine` datetime DEFAULT NULL,
  `Punteggio` int(11) DEFAULT 0,
  `sconto` decimal(5,2) DEFAULT NULL,
  `spesa` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `cronologia`
--

INSERT INTO `cronologia` (`Cf`, `Codice_scheda`, `Inizio`, `Fine`, `Punteggio`, `sconto`, `spesa`) VALUES
('bbc99', 1, '2020-12-16 15:30:00', '2020-12-16 18:32:31', 50, '0.00', '20.00'),
('bbc99', 1, '2020-12-17 16:30:00', '2020-12-17 17:43:43', 62, '11.50', '8.85'),
('bbc99', 1, '2020-12-17 17:56:05', '2020-12-17 17:56:13', 10, '0.00', '5.00'),
('bbc99', 5, '2020-12-19 08:00:00', '2020-12-19 10:50:44', 120, '22.50', '11.63'),
('bbn9', 2, '2020-12-17 16:45:00', '2020-12-17 17:43:43', 40, '0.00', '5.00'),
('bbn9', 3, '2020-12-19 08:00:00', '2020-12-19 10:50:44', 180, '32.50', '10.13'),
('bccc79', 3, '2020-12-16 16:00:00', '2020-12-16 18:32:31', 125, '22.50', '11.63'),
('bccc79', 4, '2020-12-19 08:30:00', '2020-12-19 10:50:44', 350, '42.50', '8.63'),
('bln01', 2, '2020-12-19 08:45:00', '2020-12-19 10:50:44', 20, '0.00', '15.00'),
('bln01', 3, '2020-12-17 16:00:00', '2020-12-17 17:43:43', 10, '0.00', '10.00'),
('bln01', 5, '2020-12-16 17:00:00', '2020-12-16 18:32:31', 650, '52.50', '4.75'),
('llpc01', 2, '2020-12-16 15:45:00', '2020-12-16 18:32:31', 75, '11.50', '13.27'),
('lmn98', 4, '2020-12-16 17:30:00', '2020-12-16 18:32:31', 118, '22.50', '7.75'),
('lmn98', 6, '2020-12-19 10:30:00', '2020-12-19 10:50:45', 67, '11.50', '4.43'),
('mcr77', 4, '2020-12-17 16:30:00', '2020-12-17 17:43:44', 111, '22.50', '7.75'),
('mcr77', 6, '2020-12-16 16:30:00', '2020-12-16 18:32:32', 400, '42.50', '8.63'),
('yuv7', 1, '2020-12-16 18:34:00', '2020-12-16 18:35:12', 0, '0.00', '5.00'),
('yuv7', 1, '2020-12-19 08:30:00', '2020-12-19 10:50:34', 110, '22.50', '11.63');

--
-- Trigger `cronologia`
--
DELIMITER $$
CREATE TRIGGER `trig2` BEFORE INSERT ON `cronologia` FOR EACH ROW Begin 
If not exists(select * from scheda where codice=new.codice_scheda and cf=new.cf)
Then signal SQLSTATE '45000' set message_text='tale scheda non è associata a nessun cf';
End if;
End
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig3d` AFTER DELETE ON `cronologia` FOR EACH ROW Begin 
Update persona set punti_totali=punti_totali-old.punteggio where cf=old.cf;
End
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig3i` AFTER INSERT ON `cronologia` FOR EACH ROW Begin 
Update persona set punti_totali=punti_totali+new.punteggio where cf=new.cf;
update scheda set inizio=default, cf=default where codice=new.codice_scheda;
Delete from sit_attuale where  codice_scheda=new.codice_scheda;
End
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `evento`
--

CREATE TABLE `evento` (
  `Inizio` datetime NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Videogioco` int(11) NOT NULL,
  `Concluso` tinyint(1) DEFAULT 0,
  `Durata_minuti` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `evento`
--

INSERT INTO `evento` (`Inizio`, `Nome`, `Videogioco`, `Concluso`, `Durata_minuti`) VALUES
('2020-12-18 12:39:12', 'bonus 1_5x', 1, 1, 30),
('2020-12-18 13:11:26', 'brutale 3x', 1, 1, 120),
('2020-12-22 11:00:52', 'bonus 2x', 10, 0, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `fps`
--

CREATE TABLE `fps` (
  `Codice` int(11) NOT NULL,
  `Genere` varchar(30) DEFAULT NULL,
  `Record_uccisioni_partita` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `fps`
--

INSERT INTO `fps` (`Codice`, `Genere`, `Record_uccisioni_partita`) VALUES
(1, 'Fantasy', 80),
(2, 'Fantascienza', 20),
(3, 'Simulazione', 60),
(4, 'Fantascienza', 0),
(5, 'Fantascienza', 101);

--
-- Trigger `fps`
--
DELIMITER $$
CREATE TRIGGER `provafps` BEFORE INSERT ON `fps` FOR EACH ROW begin
if new.codice in(select * from (select codice from arcade) a union
   (select codice from quiz) union
   (select codice from corsa))
then signal SQLSTATE '45000' set message_text='errore, codice gia associato ad un altro tipo';
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `persona`
--

CREATE TABLE `persona` (
  `CF` varchar(20) NOT NULL,
  `Password` varchar(60) DEFAULT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Anno_nascita` date DEFAULT NULL,
  `Occupazione` varchar(30) DEFAULT NULL,
  `Punti_totali` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `persona`
--

INSERT INTO `persona` (`CF`, `Password`, `Nome`, `Anno_nascita`, `Occupazione`, `Punti_totali`) VALUES
('bbc99', '$2y$10$rzMrWweRHNw/20gn4bC.F.VuhYF77ow3ps.Cti5FR3uV2a9bqVnBG', 'Giuseppe Calleri', '1999-09-03', 'Disoccupato', 242),
('bbn9', '$2y$10$SqMBaLRM5n2IN7IajVUoG.9Sc.xNvcPMH3SlRi.bPK/7mrrMtlKxu', 'Fabio Nicosia', '1990-07-30', 'BioMedico', 220),
('bccc79', '$2y$10$e7NJDcmlxu775a2T2y3rJuIPFcHBSDVk2WpJREjqJJE8an1yuvZkS', 'Alessandra Garaffo', '1979-02-11', 'Psicologo', 475),
('bln01', '$2y$10$P4d4rcfZ7.DaQ3B4q7fPM.w98WdNHxwrgDmo/CTANMfT40Zq9mFY.', 'Francesca Didio', '2001-07-08', 'Studente', 680),
('llpc01', '$2y$10$nl6xA2Y5rDc5IoZZuTOaZen1QVSoJefBa5A3Sr1XgXHaHOQ2nBPt.', 'Paola Gullotta', '1995-09-11', 'Dentista', 75),
('lmn98', '$2y$10$MCilTHlrwfDDz8VyYxPhtu2vwcdrlQ07jj0xcOT0NBj7W1/fIoBT.', 'Fancesco Fichera', '1998-03-22', 'youtuber', 185),
('mcr77', '$2y$10$ScW5j9ug3ZFVkwDMYdkzwOWSdsGIGV9OsxEPxaif3.BgClKtYzDpe', 'Guglielmo Cantone', '1965-08-16', 'Imprenditore', 511),
('TheGarbage', '$2y$10$KhRBmOlrlBKHUTZa.6sOR.Xdj55eytZD5FZHIA7RUdY6aXseLn9f.', 'Davide Bucchieri', '1999-05-27', 'studente', 0),
('yuv7', '$2y$10$8bmkQpnW2OAX.jyNnOaxMO2l7PknemFxvj6Qd2996Y27fbjKpedXC', 'Paola Valenti', '1969-09-11', 'Architetto', 110);

-- --------------------------------------------------------

--
-- Struttura della tabella `postazione`
--

CREATE TABLE `postazione` (
  `Key_appoggio_1` int(11) NOT NULL,
  `Id_postazione` int(11) DEFAULT NULL,
  `Sala` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `postazione`
--

INSERT INTO `postazione` (`Key_appoggio_1`, `Id_postazione`, `Sala`) VALUES
(1, 1, 'SalaA'),
(5, 1, 'SalaC'),
(13, 1, 'SalaF'),
(11, 1, 'SalaQ'),
(2, 2, 'SalaA'),
(6, 2, 'SalaC'),
(14, 2, 'SalaF'),
(12, 2, 'SalaQ'),
(3, 3, 'SalaA'),
(7, 3, 'SalaC'),
(15, 3, 'SalaF'),
(4, 4, 'SalaA'),
(8, 4, 'SalaC'),
(16, 4, 'SalaF'),
(9, 5, 'SalaC'),
(10, 6, 'SalaC');

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `Codice_videogioco` int(11) NOT NULL,
  `cf` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `preferiti`
--

INSERT INTO `preferiti` (`Codice_videogioco`, `cf`) VALUES
(1, 'bbc99'),
(2, 'bbc99'),
(5, 'bbc99'),
(10, 'bbc99');

-- --------------------------------------------------------

--
-- Struttura della tabella `quiz`
--

CREATE TABLE `quiz` (
  `Codice` int(11) NOT NULL,
  `Argomento` varchar(30) DEFAULT NULL,
  `N_domande` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `quiz`
--

INSERT INTO `quiz` (`Codice`, `Argomento`, `N_domande`) VALUES
(16, 'Generico', 20),
(17, 'Attualita', 10),
(18, 'Film e serie tv', 15),
(19, 'Generico', 5),
(20, 'A scelta', 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `sala`
--

CREATE TABLE `sala` (
  `Nome` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sala`
--

INSERT INTO `sala` (`Nome`) VALUES
('SalaA'),
('SalaC'),
('SalaF'),
('SalaQ');

-- --------------------------------------------------------

--
-- Struttura della tabella `scheda`
--

CREATE TABLE `scheda` (
  `Codice` int(11) NOT NULL,
  `Cf` varchar(20) DEFAULT NULL,
  `Inizio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `scheda`
--

INSERT INTO `scheda` (`Codice`, `Cf`, `Inizio`) VALUES
(1, NULL, NULL),
(2, NULL, NULL),
(3, NULL, NULL),
(4, NULL, NULL),
(5, NULL, NULL),
(6, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `sit_attuale`
--

CREATE TABLE `sit_attuale` (
  `Codice_videogioco` int(11) NOT NULL,
  `Key_appoggio_1` int(11) NOT NULL,
  `Codice_scheda` int(11) NOT NULL,
  `Inizio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `sit_attuale`
--
DELIMITER $$
CREATE TRIGGER `trig1` BEFORE INSERT ON `sit_attuale` FOR EACH ROW Begin 
Declare settore varchar(6);
If exists(select * from scheda where codice=new.codice_scheda and  cf is null)
Then signal SQLSTATE '45000' set message_text='scheda non associata a nessuno in questo momento';
End if;
Select sala into settore from postazione where key_appoggio_1=new.key_appoggio_1;
Case settore
When 'SalaA' then if not exists(select codice from arcade where codice=new.codice_videogioco)
Then signal SQLSTATE '45000' set message_text='puoi giocare solo arcade in questa postazione';
 end if;
When 'SalaC' then if not exists(select codice from corsa where codice=new.codice_videogioco)
Then signal SQLSTATE '45000' set message_text='puoi giocare solo corsa in questa postazione';
 end if;
When 'SalaF' then if not exists(select codice from fps where codice=new.codice_videogioco)
Then signal SQLSTATE '45000' set message_text='puoi giocare solo fps in questa postazione';
 end if;
When 'SalaQ' then if not exists(select codice from quiz where codice=new.codice_videogioco)
Then signal SQLSTATE '45000' set message_text='puoi giocare solo quiz in questa postazione';
 end if;
End case;
End
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trig4` AFTER DELETE ON `sit_attuale` FOR EACH ROW Begin 
Insert into  `sit_passato`  (`Codice_videogioco`, `key_appoggio_1`, `Codice_scheda`,`inizio` )
Values (old.Codice_videogioco, old.key_appoggio_1, old.Codice_scheda, old.inizio);
End
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `sit_passato`
--

CREATE TABLE `sit_passato` (
  `Key_appoggio_2` int(11) NOT NULL,
  `Codice_videogioco` int(11) DEFAULT NULL,
  `Key_appoggio_1` int(11) DEFAULT NULL,
  `Codice_scheda` int(11) DEFAULT NULL,
  `Inizio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sit_passato`
--

INSERT INTO `sit_passato` (`Key_appoggio_2`, `Codice_videogioco`, `Key_appoggio_1`, `Codice_scheda`, `Inizio`) VALUES
(23, 1, 13, 1, '2020-12-17 17:40:00'),
(1, 1, 15, 1, '2020-12-16 15:40:00'),
(33, 1, 15, 1, '2020-12-17 17:56:09'),
(34, 1, 15, 1, '2020-12-19 09:35:00'),
(39, 1, 15, 3, '2020-12-19 08:40:00'),
(2, 1, 15, 4, '2020-12-16 17:40:00'),
(43, 1, 15, 5, '2020-12-19 08:40:00'),
(3, 2, 15, 3, '2020-12-16 16:30:00'),
(41, 2, 15, 4, '2020-12-19 09:30:00'),
(24, 3, 15, 1, '2020-12-17 16:31:00'),
(40, 3, 15, 3, '2020-12-19 08:20:00'),
(4, 3, 15, 5, '2020-12-16 17:20:00'),
(29, 5, 15, 3, '2020-12-17 17:40:00'),
(5, 6, 7, 1, '2020-12-16 18:10:00'),
(26, 6, 7, 2, '2020-12-17 17:10:00'),
(44, 6, 7, 5, '2020-12-19 10:10:00'),
(27, 7, 7, 2, '2020-12-17 17:10:00'),
(36, 8, 7, 2, '2020-12-19 09:10:00'),
(6, 8, 7, 5, '2020-12-16 17:10:00'),
(35, 8, 9, 1, '2020-12-19 08:35:00'),
(7, 8, 9, 6, '2020-12-16 16:35:00'),
(30, 9, 9, 4, '2020-12-17 17:35:00'),
(8, 10, 9, 3, '2020-12-16 17:35:00'),
(42, 10, 9, 4, '2020-12-19 09:35:00'),
(22, 11, 1, 3, '2020-12-17 17:20:00'),
(25, 12, 1, 1, '2020-12-17 17:43:28'),
(37, 12, 1, 2, '2020-12-19 08:50:00'),
(9, 12, 1, 5, '2020-12-16 17:50:00'),
(10, 15, 1, 1, '2020-12-16 16:50:00'),
(45, 15, 1, 5, '2020-12-19 08:50:00'),
(38, 18, 12, 2, '2020-12-19 08:50:00'),
(11, 18, 12, 5, '2020-12-16 17:40:00'),
(28, 19, 12, 2, '2020-12-17 16:00:00'),
(12, 20, 12, 2, '2020-12-16 16:00:00'),
(46, 20, 12, 6, '2020-12-19 10:35:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_evento`
--

CREATE TABLE `tipo_evento` (
  `Nome` varchar(30) NOT NULL,
  `modificatore_bonus` decimal(5,2) DEFAULT NULL,
  `modificatore_difficolta` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tipo_evento`
--

INSERT INTO `tipo_evento` (`Nome`, `modificatore_bonus`, `modificatore_difficolta`) VALUES
('Bonus 1_5x', '1.50', '1.00'),
('Bonus 2x', '2.00', '1.00'),
('Brutale 1_5x', '3.00', '1.50'),
('Brutale 3x', '4.00', '3.00'),
('Brutale 5x', '10.00', '5.00');

-- --------------------------------------------------------

--
-- Struttura della tabella `videogioco`
--

CREATE TABLE `videogioco` (
  `Codice` int(11) NOT NULL,
  `Titolo` varchar(30) DEFAULT NULL,
  `Pegi` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `videogioco`
--

INSERT INTO `videogioco` (`Codice`, `Titolo`, `Pegi`) VALUES
(1, 'Doom', 18),
(2, 'Halo', 16),
(3, 'Cod', 18),
(4, 'Wolfenstein', 18),
(5, 'Half life', 18),
(6, 'Monza F1', 3),
(7, 'Rally', 12),
(8, 'Abu Dabhi F1', 3),
(9, 'Qatar MotoGp', 7),
(10, 'Nascar California', 3),
(11, 'Pong', 3),
(12, 'Space invaders', 3),
(13, 'Pac-man', 3),
(14, 'Tetris', 3),
(15, 'Snake', 3),
(16, 'Tabu', 12),
(17, 'Moderno', 12),
(18, 'Film', 12),
(19, 'Sapere e Potere', 12),
(20, 'Trivia', 12);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `vista1`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `vista1` (
`cf` varchar(20)
,`punti_totali` int(11)
,`occupazione` varchar(30)
,`anno_nascita` date
,`media_punteggio` decimal(7,2)
,`media_sconto` decimal(5,2)
,`media_spesa` decimal(5,2)
,`totale_spesa` decimal(27,2)
,`N_fps` varchar(21)
,`N_arcade` varchar(21)
,`N_corsa` varchar(21)
,`N_quiz` varchar(21)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `vista2`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `vista2` (
`cf` varchar(20)
,`codice_videogioco` int(11)
,`titolo` varchar(30)
,`pegi` int(11)
,`n_avvi` bigint(21)
);

-- --------------------------------------------------------

--
-- Struttura per vista `vista1`
--
DROP TABLE IF EXISTS `vista1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista1`  AS SELECT `p`.`cf` AS `cf`, `p`.`punti_totali` AS `punti_totali`, `p`.`occupazione` AS `occupazione`, `p`.`anno_nascita` AS `anno_nascita`, cast(avg(`s`.`punteggio`) as decimal(7,2)) AS `media_punteggio`, cast(avg(`s`.`sconto`) as decimal(5,2)) AS `media_sconto`, cast(avg(`s`.`spesa`) as decimal(5,2)) AS `media_spesa`, sum(`s`.`spesa`) AS `totale_spesa`, coalesce(`f`.`N_fps`,'0') AS `N_fps`, coalesce(`a`.`N_arcade`,'0') AS `N_arcade`, coalesce(`c`.`N_corsa`,'0') AS `N_corsa`, coalesce(`q`.`N_quiz`,'0') AS `N_quiz` FROM ((((((select `persona`.`CF` AS `cf`,`persona`.`Punti_totali` AS `punti_totali`,`persona`.`Occupazione` AS `occupazione`,`persona`.`Anno_nascita` AS `anno_nascita` from `persona`) `p` left join (select `cronologia`.`Cf` AS `cf`,`cronologia`.`sconto` AS `sconto`,`cronologia`.`Punteggio` AS `punteggio`,`cronologia`.`spesa` AS `spesa` from `cronologia`) `s` on(`p`.`cf` = `s`.`cf`)) left join (select `vista2`.`cf` AS `cf`,count(0) AS `N_fps` from (`vista2` join `fps` on(`vista2`.`codice_videogioco` = `fps`.`Codice`)) group by `vista2`.`cf`) `f` on(`f`.`cf` = `p`.`cf`)) left join (select `vista2`.`cf` AS `cf`,count(0) AS `N_arcade` from (`vista2` join `arcade` on(`vista2`.`codice_videogioco` = `arcade`.`Codice`)) group by `vista2`.`cf`) `a` on(`a`.`cf` = `p`.`cf`)) left join (select `vista2`.`cf` AS `cf`,count(0) AS `N_corsa` from (`vista2` join `corsa` on(`vista2`.`codice_videogioco` = `corsa`.`Codice`)) group by `vista2`.`cf`) `c` on(`c`.`cf` = `p`.`cf`)) left join (select `vista2`.`cf` AS `cf`,count(0) AS `N_quiz` from (`vista2` join `quiz` on(`vista2`.`codice_videogioco` = `quiz`.`Codice`)) group by `vista2`.`cf`) `q` on(`q`.`cf` = `p`.`cf`)) GROUP BY `p`.`cf` ;

-- --------------------------------------------------------

--
-- Struttura per vista `vista2`
--
DROP TABLE IF EXISTS `vista2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista2`  AS SELECT `o`.`cf` AS `cf`, `v`.`Codice` AS `codice_videogioco`, `v`.`Titolo` AS `titolo`, `v`.`Pegi` AS `pegi`, `o`.`n_avvi` AS `n_avvi` FROM (`videogioco` `v` join (select `c`.`Cf` AS `cf`,`o`.`Codice_videogioco` AS `codice_videogioco`,count(0) AS `n_avvi` from ((select `sit_attuale`.`Codice_videogioco` AS `Codice_videogioco`,`sit_attuale`.`Key_appoggio_1` AS `Key_appoggio_1`,`sit_attuale`.`Codice_scheda` AS `Codice_scheda`,`sit_attuale`.`Inizio` AS `Inizio` from `sit_attuale` union (select `sit_passato`.`Codice_videogioco` AS `Codice_videogioco`,`sit_passato`.`Key_appoggio_1` AS `key_appoggio_1`,`sit_passato`.`Codice_scheda` AS `Codice_scheda`,`sit_passato`.`Inizio` AS `inizio` from `sit_passato`)) `o` join `cronologia` `c` on(`o`.`Codice_scheda` = `c`.`Codice_scheda`)) where `c`.`Inizio` < `o`.`Inizio` and `c`.`Fine` > `o`.`Inizio` group by `o`.`Codice_videogioco`,`c`.`Cf`) `o` on(`v`.`Codice` = `o`.`codice_videogioco`)) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `arcade`
--
ALTER TABLE `arcade`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`Cf`),
  ADD KEY `idx_cd` (`Cf`);

--
-- Indici per le tabelle `corsa`
--
ALTER TABLE `corsa`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `cronologia`
--
ALTER TABLE `cronologia`
  ADD PRIMARY KEY (`Cf`,`Codice_scheda`,`Inizio`),
  ADD KEY `idx_cd` (`Cf`),
  ADD KEY `idx_codice_scheda` (`Codice_scheda`),
  ADD KEY `idx_inizio` (`Inizio`);

--
-- Indici per le tabelle `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`Inizio`,`Nome`,`Videogioco`),
  ADD KEY `idx_inizio` (`Inizio`),
  ADD KEY `idx_nome` (`Nome`),
  ADD KEY `idx_videogioco` (`Videogioco`);

--
-- Indici per le tabelle `fps`
--
ALTER TABLE `fps`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`CF`);

--
-- Indici per le tabelle `postazione`
--
ALTER TABLE `postazione`
  ADD PRIMARY KEY (`Key_appoggio_1`),
  ADD UNIQUE KEY `Id_postazione` (`Id_postazione`,`Sala`),
  ADD KEY `idx_sala` (`Sala`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`cf`,`Codice_videogioco`),
  ADD KEY `idx_codice_videogioco` (`Codice_videogioco`),
  ADD KEY `idx_cf` (`cf`);

--
-- Indici per le tabelle `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`Nome`);

--
-- Indici per le tabelle `scheda`
--
ALTER TABLE `scheda`
  ADD PRIMARY KEY (`Codice`),
  ADD UNIQUE KEY `Cf` (`Cf`);

--
-- Indici per le tabelle `sit_attuale`
--
ALTER TABLE `sit_attuale`
  ADD PRIMARY KEY (`Codice_videogioco`,`Key_appoggio_1`,`Codice_scheda`),
  ADD KEY `idx_codice_videogioco` (`Codice_videogioco`),
  ADD KEY `idx_key_appoggio_1` (`Key_appoggio_1`),
  ADD KEY `idx_codice_scheda` (`Codice_scheda`);

--
-- Indici per le tabelle `sit_passato`
--
ALTER TABLE `sit_passato`
  ADD PRIMARY KEY (`Key_appoggio_2`),
  ADD UNIQUE KEY `Codice_videogioco` (`Codice_videogioco`,`Key_appoggio_1`,`Codice_scheda`,`Inizio`),
  ADD KEY `idx_codice_videogioco` (`Codice_videogioco`),
  ADD KEY `idx_key_appoggio_1` (`Key_appoggio_1`),
  ADD KEY `idx_codice_scheda` (`Codice_scheda`);

--
-- Indici per le tabelle `tipo_evento`
--
ALTER TABLE `tipo_evento`
  ADD PRIMARY KEY (`Nome`);

--
-- Indici per le tabelle `videogioco`
--
ALTER TABLE `videogioco`
  ADD PRIMARY KEY (`Codice`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `postazione`
--
ALTER TABLE `postazione`
  MODIFY `Key_appoggio_1` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `scheda`
--
ALTER TABLE `scheda`
  MODIFY `Codice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `sit_passato`
--
ALTER TABLE `sit_passato`
  MODIFY `Key_appoggio_2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT per la tabella `videogioco`
--
ALTER TABLE `videogioco`
  MODIFY `Codice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `arcade`
--
ALTER TABLE `arcade`
  ADD CONSTRAINT `arcade_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `contest`
--
ALTER TABLE `contest`
  ADD CONSTRAINT `contest_ibfk_1` FOREIGN KEY (`Cf`) REFERENCES `persona` (`CF`);

--
-- Limiti per la tabella `corsa`
--
ALTER TABLE `corsa`
  ADD CONSTRAINT `corsa_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `cronologia`
--
ALTER TABLE `cronologia`
  ADD CONSTRAINT `cronologia_ibfk_1` FOREIGN KEY (`Cf`) REFERENCES `persona` (`CF`),
  ADD CONSTRAINT `cronologia_ibfk_2` FOREIGN KEY (`Codice_scheda`) REFERENCES `scheda` (`Codice`);

--
-- Limiti per la tabella `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`Nome`) REFERENCES `tipo_evento` (`Nome`),
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`Videogioco`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `fps`
--
ALTER TABLE `fps`
  ADD CONSTRAINT `fps_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `postazione`
--
ALTER TABLE `postazione`
  ADD CONSTRAINT `postazione_ibfk_1` FOREIGN KEY (`Sala`) REFERENCES `sala` (`Nome`);

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`cf`) REFERENCES `persona` (`CF`),
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`Codice_videogioco`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`Codice`) REFERENCES `videogioco` (`Codice`);

--
-- Limiti per la tabella `scheda`
--
ALTER TABLE `scheda`
  ADD CONSTRAINT `scheda_ibfk_1` FOREIGN KEY (`Cf`) REFERENCES `persona` (`CF`);

--
-- Limiti per la tabella `sit_attuale`
--
ALTER TABLE `sit_attuale`
  ADD CONSTRAINT `sit_attuale_ibfk_1` FOREIGN KEY (`Codice_videogioco`) REFERENCES `videogioco` (`Codice`),
  ADD CONSTRAINT `sit_attuale_ibfk_2` FOREIGN KEY (`Key_appoggio_1`) REFERENCES `postazione` (`Key_appoggio_1`),
  ADD CONSTRAINT `sit_attuale_ibfk_3` FOREIGN KEY (`Codice_scheda`) REFERENCES `scheda` (`Codice`);

--
-- Limiti per la tabella `sit_passato`
--
ALTER TABLE `sit_passato`
  ADD CONSTRAINT `sit_passato_ibfk_1` FOREIGN KEY (`Codice_videogioco`) REFERENCES `videogioco` (`Codice`),
  ADD CONSTRAINT `sit_passato_ibfk_2` FOREIGN KEY (`Key_appoggio_1`) REFERENCES `postazione` (`Key_appoggio_1`),
  ADD CONSTRAINT `sit_passato_ibfk_3` FOREIGN KEY (`Codice_scheda`) REFERENCES `scheda` (`Codice`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
