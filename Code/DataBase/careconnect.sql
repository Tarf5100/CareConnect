-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 05, 2025 at 11:57 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `careconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `ID` int(10) NOT NULL,
  `PatientID` int(10) NOT NULL,
  `DoctorID` int(10) NOT NULL,
  `date` date NOT NULL,
  `time` text NOT NULL,
  `reason` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`ID`, `PatientID`, `DoctorID`, `date`, `time`, `reason`, `status`) VALUES
(13, 8, 2578, '2025-04-09', '10:40', 'Experiencing occasional chest pain and shortness of breath.', 'Pending'),
(14, 8, 5209, '2025-04-04', '11:50', 'I\'ve been having frequent headaches and occasional dizziness.', 'Done'),
(15, 8, 5209, '2025-04-06', '02:00', 'Follow-up', 'Confirmed'),
(16, 9, 7841, '2025-04-04', '08:30', 'Routine check-up and vaccination for my child.', 'Done'),
(17, 10, 2578, '2025-04-08', '13:30', 'Chest discomfort and fatigue (follow-up)', 'Confirmed'),
(18, 10, 2578, '2025-03-03', '1:00', 'Chest discomfort and fatigue ', 'Done'),
(19, 9, 7841, '2025-04-09', '11:00', 'My 7-year-old has had a cough and low-grade fever for a few days.', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `ID` int(10) NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `UniqueFileName` text NOT NULL,
  `EmailAddress` text NOT NULL,
  `Password` text NOT NULL,
  `SpecialityID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`ID`, `FirstName`, `LastName`, `UniqueFileName`, `EmailAddress`, `Password`, `SpecialityID`) VALUES
(2578, 'Sara', 'Muhammad', '67f0267c69354_DrSara.png', 'sara.muhammad@careconnect.com', '$2y$10$Ai.Slp/MRS2qX11jsTrL8OMlqAfsknSCvJ7nJNl/QvnblpPSGJ7cC', 1),
(5209, 'Abdullah', 'Bader', '67f0273527496_DrAbdullah.png', 'abdullah.bader@careconnect.com', '$2y$10$uK8TwWF7gaAwMbnuY1kV9.feQvE5Czs040lsc8H225BbHys0tXBcK', 2),
(7841, 'Saleh', 'Ali', '67f026e024cc2_DrSaleh.png', 'saleh.ali@careconnect.com', '$2y$10$dHKY2phes8rq6qz2hX4.fuDVCj4OTGLYcDkydGPjrmSb.5//oI.l2', 3);

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `ID` int(10) NOT NULL,
  `MedicationName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`ID`, `MedicationName`) VALUES
(1, 'Paracetamol'),
(2, 'Naproxen'),
(3, 'Aspirin');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `ID` int(10) NOT NULL,
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,
  `Gender` enum('Female','Male') NOT NULL,
  `DoB` date NOT NULL,
  `EmailAddress` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`ID`, `FirstName`, `LastName`, `Gender`, `DoB`, `EmailAddress`, `Password`) VALUES
(8, 'Nour', 'Ahmed', 'Female', '1998-02-10', 'nour.ahmed@gmail.com', '$2y$10$SRDdKZPD/bJy.DGrL7I.vuJKigfXL//iV7jdvuZVPK8kHRQq9fsGW'),
(9, 'Mansour', 'Saud', 'Male', '2018-01-21', 'mansour.saud@gmail.com', '$2y$10$1JG/QmdkxcWk5vjTNtpAQewLgRWM7HZIVQTyQi7UyryTOBI7CNO2W'),
(10, 'Munira', 'Saad', 'Female', '1991-10-26', 'munira.saad@gmail.com', '$2y$10$s5w4X5Um7KK93KYCbKegxeIft1JaHDB.nTzBtoYTQdUFiF41L9PpO');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `ID` int(10) NOT NULL,
  `AppointmentID` int(10) NOT NULL,
  `MedicationID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`ID`, `AppointmentID`, `MedicationID`) VALUES
(9, 14, 1),
(10, 18, 3),
(11, 18, 2);

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `ID` int(10) NOT NULL,
  `speciality` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`ID`, `speciality`) VALUES
(1, 'Cardiology'),
(2, 'Neurology'),
(3, 'Pediatrics');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `MedicationID` (`MedicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`ID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`ID`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`SpecialityID`) REFERENCES `speciality` (`ID`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`ID`),
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`MedicationID`) REFERENCES `medication` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
