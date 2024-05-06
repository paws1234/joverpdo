-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2024 at 06:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joverhackingpdo`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `registration_code`) VALUES
(1, '', '$2y$10$Yte/vA9QWq.U8pbBceKLCuoeFRfnyD2qiuV89wZSM2ehhvCGAboxy', '66375911c1173'),
(2, 'paws', '$2y$10$VVZ8X9TmzhG0CHCWi3szkeZyKlP0RQGRJj27MQvJ14zGJrS/dd7bq', '6637591c6e975'),
(3, 'rex', '$2y$10$ZN3Q8aIzcTkBzsKzV5S/9./n08LE/csX.FljPeyXanv8eS9dtmeoK', '66375964245bf'),
(4, 'rex', '$2y$10$yQHD07TClK6lckBkDrwnH.ct5D1JX6WmpeH99cEPnparXFkKKYcFa', '663759a063c6e'),
(5, 'rex', '$2y$10$Tky1d8v0QICWhByyXlpP/eLELWcQ0MSb0N6DFJcuymXw2Frf6fWAK', '663759c35a39a'),
(6, 'paws', '$2y$10$u85phGqqGwmTt5zQKQSUV.8pMWLxkHNG1WGcUfwIXGNWP4srdL5UW', '663759c7ce988'),
(7, 'paws', '$2y$10$LX7Ozr.shRZpDhJAXw/wCO2PeT9OvdsOvOD7FrBQ.gGVUoeX4wkC6', '663759d10e0b9'),
(8, 'paws', '$2y$10$ZYoP79/hh5GI.UK.mXJ.aOP0Go9f3SIykab8tOxL8bc3Km08erXmu', '663759d89f2b9'),
(9, 'paws', '$2y$10$LoifahKK/IRxO0zK8lQXKeNd41BXPm7fcvkpKAwcrIyr57YE1L.Ka', '66375a43e2d5e'),
(10, 'keizer', '$2y$10$OPLctVMUubSgwMe.Eo7OJucgU0.yx40y4Enq9zW5NshKjByizTUPG', '66375a82bf2e5'),
(11, 'rex', '$2y$10$HSTIYeR8COymuA0OpdkCiuWK2HM6Ao6uy5DW.vjGond4HE6y589fq', '66375c47db70e'),
(12, 'clark', '$2y$10$LFmwn0MinCAx557H6yuLgOYQacbDtt3r3CMUwM1wQTBR0DbGEKibW', '663788f7a1a69'),
(13, 'rex', '$2y$10$3G7RAdUOMfGxhPNSwT31YOETW8gbwiWtYpePy7GtWsuwde71FfnrS', '6638435d8e129'),
(14, 'rex', '$2y$10$3RzKj/88SodbiriKanqZ4O47W05PdLTLF0hBa/MfPMyVPmVFBUlYq', '6638439ab6688'),
(15, 'rex', '$2y$10$CoM2gMNz.kL01292cRx4SOR6THhFSfGpcSGWEbB27w4xzrJYB2zQW', '663843e023f44'),
(16, 'klarkw', '$2y$10$vBLA1LEEHgLKj2ji9pYQT.5i5FgttYpjzbsjyYkm6mMYAcrXFZ74q', '66384d4089655'),
(17, 'klarkw', '$2y$10$lE./MHDJJIZXL7EQOSd30eBXIpBZZ1JC6NExLL.ckSvtg49l3.Er6', '66384d79c989d'),
(18, 'klarkw', '$2y$10$v9V/1tf.cELbR6tdi2XuzOxDKTrU1LKH31ghaUwAhpdSB2.WqDwYy', '66384d7d0abd1'),
(19, 'jason2', '$2y$10$rvRXR.aqfl7tJBORaep3Iun1qoaLPgVD3m0vPNbmScaINlzhZ6aSm', '66384dcaad470'),
(20, 'jason2', '$2y$10$OC02nsLW9DrZI3XQ1q2WuuLIrttm3hMIhy20FZHN9CJ7ay6/xstom', '66384f3e03ef2'),
(21, 'jason2', '$2y$10$CdaDaOBgzMkUa44LO8KAIezy6Zw18ljCK529ceCOYXGQEr9kRhKKa', '66384f40d39ee'),
(22, 'jasonx', '$2y$10$LRZujssRvk3LAKpwhvtR4ekatSE5tgL8aPK9znMLht7qjppHtzRiG', '66384f488ac59'),
(23, 'jaon', '$2y$10$urpVxshCb/jd/MOjvnil.OC.BrEyfS6BiwdEQTZcSdZ2UNDGFp8nu', '66384fcd63b64'),
(24, 'jaon', '$2y$10$8nwTl4du5kVENp/AuIRZq.K6iwcGc9LuNMJav./cHnse7/0KyP.Iu', '6638501a5146f'),
(25, 'jaon', '$2y$10$3Sil1pXsit/Ss7RWx85IOeNKlfcLR3pdgUyDTqtQVChYY1TGDOwzu', '663850346e7dd'),
(26, 'Capstone', '$2y$10$9MnWYukfY9TRhT7zMNTUjen4JXGsZRfSvcs3cGAwPPAgIn2/iRiGC', '663855d486e98');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
