-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 01:21 PM
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
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_bank`
--

CREATE TABLE `blood_bank` (
  `Bank_ID` int(11) NOT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_bank`
--

INSERT INTO `blood_bank` (`Bank_ID`, `Location`, `Email`) VALUES
(10500, 'Badda', 'bb.badda@gmail.com'),
(12121, 'Kallyanpur', 'bb.kallyanpur@gmail.com'),
(14235, 'Motijheel', 'bb.motijheel@gmail.com'),
(20012, 'Mohammadpur', 'bb.mohammadpur@gmail.com'),
(20111, 'Dhanmondi', 'bb.dhanmondi@gmail.com'),
(24658, 'Mirpur', 'bb.mirpur@gmail.com'),
(45634, 'Banani', 'bb.banani@gmail.com'),
(56423, 'Bashundhara', 'bb.bashundhara@gmail.com'),
(57357, 'Mohakhali', 'bb.mohakhali@gmail.com'),
(64532, 'Gulshan', 'bb.gulshan@gmail.com'),
(65786, 'Uttara', 'bb.uttara@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `blood_donation_record`
--

CREATE TABLE `blood_donation_record` (
  `Packet_serial_number` bigint(20) NOT NULL,
  `Donation_date` date DEFAULT NULL,
  `Expiry_date` date GENERATED ALWAYS AS (`Donation_date` + interval 42 day) STORED,
  `Blood_group` varchar(5) DEFAULT NULL,
  `Bank_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_donation_record`
--

INSERT INTO `blood_donation_record` (`Packet_serial_number`, `Donation_date`, `Blood_group`, `Bank_ID`) VALUES
(1, '2025-05-09', 'O-', NULL),
(3, '2025-05-03', 'A+', 10500),
(4, '2025-04-17', 'B+', 45634),
(6, '2025-05-03', 'AB+', 45634),
(7, '2025-05-02', 'O-', 10500),
(9, '2025-04-25', 'AB-', 10500),
(10, '2025-04-08', 'B+', 10500),
(11, '2025-05-07', 'A+', 10500),
(12, '2025-05-05', 'O-', 10500),
(13, '2025-04-10', 'O+', 45634),
(14, '2025-04-09', 'B+', 10500),
(15, '2025-05-09', 'O+', 45634),
(16, '2025-05-05', 'A+', 45634),
(17, '2025-04-24', 'B+', 45634),
(18, '2025-04-16', 'O+', 45634),
(19, '2025-05-03', 'AB+', 45634),
(20, '2025-05-02', 'O-', 45634),
(21, '2025-05-03', 'A-', 56423),
(22, '2025-04-04', 'AB-', 10500),
(23, '2025-04-02', 'B+', 10500),
(24, '2025-04-09', 'A+', 10500),
(25, '2025-05-01', 'O-', 56423),
(26, '2025-05-05', 'O+', 10500),
(27, '2025-04-23', 'B+', 10500),
(28, '2025-05-09', 'O+', 45634),
(29, '2025-05-09', 'O+', 56423),
(30, '2025-04-19', 'O+', 56423),
(31, '2025-05-09', 'A+', 56423),
(32, '2025-05-04', 'A-', 14235),
(33, '2025-04-16', 'A+', 24658),
(34, '2025-05-03', 'AB+', 56423),
(35, '2025-04-11', 'A-', 64532),
(36, '2025-05-03', 'A+', 14235),
(37, '2025-05-02', 'A+', 20111),
(38, '2025-05-03', 'AB+', 56423),
(39, '2025-05-06', 'A-', 14235),
(42, '2025-05-03', 'B-', 57357),
(44, '2025-05-03', 'O+', 12121),
(45, '2025-05-02', 'O+', 14235),
(46, '2025-05-03', 'AB+', 56423),
(47, '2025-04-01', 'B+', 24658),
(48, '2025-05-03', 'O-', 64532),
(49, '2025-05-03', 'B+', 20111),
(50, '2025-05-03', 'AB+', 56423),
(51, '2025-05-03', 'A+', 12121),
(52, '2025-04-24', 'AB+', 64532),
(53, '2025-05-03', 'O-', 57357),
(54, '2025-04-05', 'A-', 20012),
(55, '2025-05-03', 'B+', 20111),
(56, '2025-05-03', 'B+', 12121),
(57, '2025-05-03', 'B-', 20012),
(58, '2025-05-06', 'O+', 12121),
(59, '2025-05-03', 'AB+', 56423),
(60, '2025-05-03', 'AB+', 56423),
(61, '2025-05-03', 'O-', 57357),
(62, '2025-05-03', 'AB+', 56423),
(63, '2025-05-03', 'AB+', 56423),
(64, '2025-05-03', 'B+', 56423),
(65, '2025-04-13', 'O-', 20111),
(66, '2025-05-03', 'AB+', 56423),
(67, '2025-05-03', 'AB-', 20111),
(68, '2025-04-02', 'A+', 45634),
(69, '2025-05-03', 'AB+', 24658),
(70, '2025-05-03', 'O+', 64532),
(71, '2025-05-03', 'AB+', 56423),
(72, '2025-05-03', 'AB+', 56423),
(73, '2025-05-03', 'AB+', 56423),
(74, '2025-05-03', 'AB-', 56423),
(75, '2025-05-01', 'B-', 20111),
(76, '2025-05-03', 'AB+', 56423),
(77, '2025-04-01', 'B-', 14235),
(78, '2025-04-02', 'O+', 65786),
(79, '2025-05-09', 'B+', 10500),
(80, '2025-05-09', 'B+', 10500),
(81, '2025-05-09', 'B+', 10500),
(82, '2025-05-09', 'B+', 10500),
(83, '2025-05-09', 'B+', 10500),
(84, '2025-05-09', 'B+', 10500),
(85, '2025-05-09', 'O-', 12121),
(86, '2025-05-09', 'O-', 12121),
(87, '2025-05-09', 'O-', 12121),
(88, '2025-05-09', 'O-', 12121),
(89, '2025-05-09', 'O-', 12121),
(90, '2025-05-09', 'O-', 10500),
(91, '2025-05-09', 'AB-', 24658),
(92, '2025-05-09', 'O+', 12121),
(93, '2025-05-09', 'O+', 12121),
(94, '2025-05-09', 'O+', 20012),
(95, '2025-05-10', 'O+', 12121),
(96, '2025-05-10', 'O+', 24658),
(97, '2025-05-10', 'O+', 20111),
(98, '2025-05-10', 'O+', 14235);

-- --------------------------------------------------------

--
-- Table structure for table `donate`
--

CREATE TABLE `donate` (
  `Bank_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `User_ID` int(11) NOT NULL,
  `Times_donated` int(11) DEFAULT NULL,
  `Last_donation_date` date DEFAULT NULL,
  `Regular_donor_flag` tinyint(1) DEFAULT NULL,
  `Recognized_donor_flag` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`User_ID`, `Times_donated`, `Last_donation_date`, `Regular_donor_flag`, `Recognized_donor_flag`) VALUES
(100, 5, '2025-05-10', 0, 1),
(103, 1, '2025-02-02', 1, 0),
(105, 1, '2025-05-05', 1, 0),
(108, 1, '2025-04-08', 1, 0),
(119, 5, '2025-05-01', 0, 1),
(121, 5, '2025-05-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `Serial_number` int(11) NOT NULL,
  `Question` varchar(255) DEFAULT NULL,
  `Answer` varchar(255) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`Serial_number`, `Question`, `Answer`, `User_ID`) VALUES
(1, 'Who can donate blood?', 'Generally, healthy individuals aged 18–60 years, weighing at least 50 kg, and having no major illnesses can donate blood.', 100),
(2, 'How often can I donate blood?', 'You can donate whole blood every 3 months (12 weeks). For platelet donation, the interval is usually 2 weeks.', 100),
(3, 'Is blood donation safe?', 'Yes, blood donation is completely safe. Sterile, disposable needles are used for each donor, eliminating any risk of infection.', 100),
(4, 'How long does the donation process take?', 'The entire process usually takes about 30 to 45 minutes, including registration, health screening, donation, and rest.', 100),
(5, 'Will I feel weak after donating blood?', NULL, 100),
(6, 'What should I do before donating blood?', NULL, 100);

-- --------------------------------------------------------

--
-- Table structure for table `provides`
--

CREATE TABLE `provides` (
  `User_ID` int(11) NOT NULL,
  `Bank_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipient`
--

CREATE TABLE `recipient` (
  `User_ID` int(11) NOT NULL,
  `times_recieved` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipient`
--

INSERT INTO `recipient` (`User_ID`, `times_recieved`) VALUES
(100, 7),
(101, 3),
(102, 1),
(105, 2),
(110, 1),
(116, 1),
(120, 1),
(121, 1);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `Request_ID` int(11) NOT NULL,
  `Blood_group` varchar(5) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  `Time` date DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`Request_ID`, `Blood_group`, `Location`, `Comment`, `Time`, `User_ID`) VALUES
(3, 'AB+', 'Motijheel', 'I need AB+ blood', '2025-05-13', 101),
(9, 'A+', 'Mohammadpur', 'I need this A+ blood urgently.', '2025-05-15', 121),
(10, 'O-', 'Shyamoli', 'I need this blood urgently.', '2025-05-16', 121),
(11, 'O-', 'Shyamoli', 'I need this blood urgently.', '2025-05-16', 121);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL,
  `First_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone_number` varchar(15) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Birth_date` date DEFAULT NULL,
  `Blood_group` varchar(5) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `Gender` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_ID`, `First_Name`, `Last_Name`, `Email`, `Phone_number`, `Address`, `Birth_date`, `Blood_group`, `password`, `Gender`) VALUES
(100, 'Azmain', 'Ishmam', 'ishmamm50@gmail.com', '01741609767', '16/8, Primary School Road, Kallyanpur, Dhaka', '2003-03-28', 'O+', '$2y$10$O6Ixs.wnPm.371SFVbHBveS82RBEE1GyzKblVa9IeeZKvGq5l68Am', 'Male'),
(101, 'Fairouj ', 'Raisa', 'fairouj.raisa09@gmail.com', '01765620639', 'M-75/2, West Merul Badda, Badda, Dhaka', '2003-07-09', 'B+', '$2y$10$g0r2JKt98P.5cTVGMJ1UM.RHeZ9xAC3bgc.nd1h3H3ShbjmQAVpn.', 'Female'),
(102, 'Sayor', 'Hasan', 'sayor@gmail.com', '01779473929', 'M63, Gudara Housing Society, Badda, Dhaka', '2003-12-30', 'O-', '$2y$10$BAqLsCG2T72TYCg5z4nSRePjCIn4/HpS99.c2MpvWUuWkI3LSHcru', 'Male'),
(103, 'Rubai', 'Mahmud', 'rubai@gmail.com', '01643047123', '6/2, 1 no. Road, Kallyanpur, Dhaka', '0000-00-00', 'AB+', '$2y$10$vlCYG5Dv8nKrVlwwZXXCcOrvx2o135hkKaJCLllg8giZP1nPS7YZm', 'Male'),
(104, 'Arif', 'Hossain', 'arif.hossain@mail.com', '01712345678', 'Dhanmondi, Dhaka', '1998-03-12', 'A+', '$2y$10$gHaqZIM/HUFVvXl1hDiD7.pUS7tvyljZe4ax8AYCIMQAW8SSkFC4C', 'Male'),
(105, 'Nusrat', 'Jahan', 'nusrat.jahan@gmail.com', '01876543210', 'Agrabad, Dhaka', '1995-07-08', 'B+', '$2y$10$eC84jPbt5df1K0FdGK3aTunQkPgOanr4P7YyS0XFOplHAsVf6HqhC', 'Female'),
(106, 'Tanvir', 'Rahman', 'tanvir.rahman@yahoo.com', '01711112222', 'Uttara, Dhaka', '1996-11-30', 'O+', '$2y$10$J8OA2ywBdcNsK9J0.SrHvOZwvwiEh6X8jBvUSj6Kgw6Y/xEk5WM4O', 'Male'),
(107, 'Sharmin', 'Akter', 'sharmin.akter@hotmail.com', '01677889900', 'Khulshi, Chattogram', '1999-01-15', 'AB+', '$2y$10$scen5JsxC3AgmWlRCyks8uJpVqVKuH/gHz2t4xTgpR0mkB1ZddNfG', 'Female'),
(108, 'Rafi', 'Islam', 'rafi.islam@bdmail.com', '01933221144', 'Rajshahi City', '2000-05-25', 'A-', '$2y$10$iEdqaS0TqPvgY2dCTjDbYeUbSTXOWGiZe81zXhCRcWfSzC1GYLgLm', 'Male'),
(109, 'Labiba', 'Sultana', 'labiba.s@gmail.com', '01322334455', 'Kushtia Sadar', '2001-08-19', 'O-', '$2y$10$q4HPWWtmWVjY.1I1MLX0WuUJ4RvgUXtv2OElAEI.oVdWaiFKSrPjW', 'Female'),
(110, 'Mamun', 'Chowdhury', 'mamun.c@hotmail.com', '01799887766', 'Sylhet Zindabazar', '1994-12-02', 'B-', '$2y$10$ugxCInzZP/J/kok2lkw.t.ytWi6.P3BhYsrlglomx4toGhA8VO.DS', 'Male'),
(111, 'Sumaiya', 'Haque', 'sumaiya.haque@bd.net', '01855443322', 'Barisal Sadar', '1997-06-11', 'AB-', '$2y$10$8nvYWAW2ox/FOMU1Y..IwuYJ4CI2G6bqba2VQxWHijpTOOlMHAnBa', 'Female'),
(112, 'Nayeem', 'Karim', 'nayeemk@gmail.com', '01999887766', 'Gazipur Chowrasta', '1993-10-29', 'A+', '$2y$10$YclzCCfY1seh7Zv0v22Szuy0i7usFU.uVATouDvf9gb6j/nJuzIfu', 'Male'),
(113, 'Tanjina', 'Rahman', 'tanjina.r@yahoo.com', '01733334444', 'Comilla Kotbari', '2002-02-18', 'B+', '$2y$10$v1r3sFK5yMc1f/Bm0oSzre1zaiQrMdZRaGgzZ/Sm0QeHaK0U/vi2C', 'Female'),
(114, 'Zia', 'Mahmud', 'zia.mahmud@bangla.com', '01811112233', 'Mirpur-2, Dhaka', '1990-09-13', 'O+', '$2y$10$OLj2gQO8dx0C3tU2Ck80vulJW3I05sc7WNknymnbPhRi/jL7G0NAa', 'Male'),
(115, 'Zisan', 'Asif', 'zisan@gmail.com', '01611798203', '16/8, Primary School Road, Kallyanpur, Dhaka', '2003-05-28', 'O+', '$2y$10$4qQKJ1JJTdJxo8Ee9b4fN.ZL74rWO1AVeZErjmRNKLonL5afHOWqW', 'Male'),
(116, 'Suraiya ', 'Disha', 'disha@gmail.com', '0177953724', 'Mirpur-1, Dhaka', '0000-00-00', 'O+', '$2y$10$i3t3z5TYjycgAZteH9a4tOT/0hSAgkZspprGwI1nZvlbhNvEyyI.2', 'Female'),
(117, 'Rukaiya', 'Siddiqa', 'rukaiya.s@gmail.com', '01733221100', 'Narayanganj Fatullah', '1999-11-05', 'A-', '$2y$10$Gfui2RL6Fy/UqUA/OHHsKe4PIO6ZqhN8NSeidzu5hCEKabxQBWmfS', 'Female'),
(118, 'Tarek', 'Aziz', 'tarek.aziz@hotmail.com', '01822334411', 'Dinajpur Main Road', '1992-01-21', 'A+', '$2y$10$jl8YRdnyOI1Cj5PkZ58iju7He23.sAuifAQEszOqZjRdt0hifioB2', 'Male'),
(119, 'Sanjida', 'Nahar', 'sanjida.n@bd.com', '01344556622', 'Tangail Court Road', '2000-10-10', 'B+', '$2y$10$ZDEh3.2IJzL5aNIRMJflreaNIdblTD78UEGXvSNkom4emDN9Qx2hS', 'Female'),
(120, 'Mehzabin', 'Alam', 'mehzabin.alam@gmail.com', '01755443388', 'Jessore New Town', '1993-12-30', 'AB+', '$2y$10$7acUoJa2iOxLy49QQSR1yu.R3AUAB.qIfSS6cv5TXwmXp5T2Cxac.', 'Female'),
(121, 'Fatin', 'Anjum', 'rahin@gmail.com', '01777777777', 'Mohammadpur, Dhaka', '2003-12-30', 'O+', '$2y$10$ilHVlNrlNjVXGwvtDndaTeTngi3VLZA3.iBmFdOi7fM2hl5iMWqBi', 'Male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_bank`
--
ALTER TABLE `blood_bank`
  ADD PRIMARY KEY (`Bank_ID`);

--
-- Indexes for table `blood_donation_record`
--
ALTER TABLE `blood_donation_record`
  ADD PRIMARY KEY (`Packet_serial_number`),
  ADD KEY `Bank_ID` (`Bank_ID`);

--
-- Indexes for table `donate`
--
ALTER TABLE `donate`
  ADD PRIMARY KEY (`Bank_ID`,`User_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`Serial_number`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `provides`
--
ALTER TABLE `provides`
  ADD PRIMARY KEY (`User_ID`,`Bank_ID`),
  ADD KEY `Bank_ID` (`Bank_ID`);

--
-- Indexes for table `recipient`
--
ALTER TABLE `recipient`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`Request_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `Serial_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `Request_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_donation_record`
--
ALTER TABLE `blood_donation_record`
  ADD CONSTRAINT `blood_donation_record_ibfk_1` FOREIGN KEY (`Bank_ID`) REFERENCES `blood_bank` (`Bank_ID`);

--
-- Constraints for table `donate`
--
ALTER TABLE `donate`
  ADD CONSTRAINT `donate_ibfk_1` FOREIGN KEY (`Bank_ID`) REFERENCES `blood_bank` (`Bank_ID`),
  ADD CONSTRAINT `donate_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `donor`
--
ALTER TABLE `donor`
  ADD CONSTRAINT `donor_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `provides`
--
ALTER TABLE `provides`
  ADD CONSTRAINT `provides_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`),
  ADD CONSTRAINT `provides_ibfk_2` FOREIGN KEY (`Bank_ID`) REFERENCES `blood_bank` (`Bank_ID`);

--
-- Constraints for table `recipient`
--
ALTER TABLE `recipient`
  ADD CONSTRAINT `recipient_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
