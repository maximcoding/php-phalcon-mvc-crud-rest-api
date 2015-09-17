-- phpMyAdmin SQL Dump
-- version 4.5.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2015 at 04:16 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phalconDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_group`
--

CREATE TABLE IF NOT EXISTS `access_group` (
`id` INT(11) NOT NULL,
`name` VARCHAR(50) NOT NULL,
`rules` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `access_group`
--

INSERT INTO `access_group` (`id`, `name`, `rules`) VALUES
(1, 'admin', 'allow_all'),
(2, 'other', 'read_only');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` INT(11) NOT NULL,
`username` VARCHAR(100) NOT NULL,
`password` VARCHAR(60) NOT NULL,
`email` VARCHAR(150) NOT NULL,
`access_group_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `access_group_id`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@mail.com', 2),
(2, 'petrovky', 'c327e92ea10f7773037429ba850de356a617881c', 'petr@mail.com', 1),
(4, 'nutrimax', '9fd90cad37586ed94f8604ae08d6f1558012c9ee', 'maximcoding@gmail.com', 1),
(7, 'vasiliy', '6d5c748ee6b1567072c51496da4e15774bd1ee8a', 'strikalo@mail.ru', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_group`
--
ALTER TABLE `access_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UserAccessGroup` (`access_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_UserAccessGroup` FOREIGN KEY (`access_group_id`) REFERENCES `access_group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
