-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 09:58 AM
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
-- Database: `skill_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `pengguna_id` bigint(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `biodata` text DEFAULT NULL,
  `tgl_dibuat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`pengguna_id`, `nama`, `email`, `password`, `jurusan`, `biodata`, `tgl_dibuat`) VALUES
(1, 'Seps', 'seps@mail.com', 'pass123', 'Informatika', 'Backend Developer dengan minat di AI', '2025-07-02 14:51:27'),
(2, 'Dewi', 'dewi@mail.com', 'pass456', 'Sistem Informasi', 'UI/UX Designer yang suka warna pastel', '2025-07-02 14:51:27'),
(3, 'Raka', 'raka@mail.com', 'pass789', 'Data Science', 'Suka bermain dengan data', '2025-07-02 14:51:27'),
(4, 'Nina', 'nina@mail.com', 'nina123', 'Manajemen', 'Tertarik di bidang project management', '2025-07-02 14:51:27'),
(5, 'Fajar', 'fajar@mail.com', 'fajar123', 'Teknik Komputer', 'Hobi membuat IoT project', '2025-07-02 14:51:27'),
(6, 'Alya', 'alya@mail.com', 'alya123', 'Informatika', 'Mobile Developer Flutter', '2025-07-02 14:51:27'),
(7, 'Zaki', 'zaki@mail.com', 'zaki123', 'Sistem Informasi', 'Frontend Developer dengan React', '2025-07-02 14:51:27'),
(8, 'Laras', 'laras@mail.com', 'laras123', 'Desain Komunikasi Visual', 'Graphic Designer dan Illustrator', '2025-07-02 14:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` bigint(20) NOT NULL,
  `creator_id` bigint(20) NOT NULL,
  `judul_project` varchar(255) NOT NULL,
  `deskripsi_project` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Merekrut',
  `tgl_dibuat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `creator_id`, `judul_project`, `deskripsi_project`, `status`, `tgl_dibuat`) VALUES
(1, 1, 'Skill Connect Platform', 'Platform untuk menghubungkan mahasiswa berdasarkan skill', 'Merekrut', '2025-07-02 14:51:27'),
(2, 2, 'Website Portfolio Designer', 'Web portfolio interaktif untuk desainer UI/UX', 'Merekrut', '2025-07-02 14:51:27'),
(3, 4, 'Manajemen Tugas Mahasiswa', 'Aplikasi untuk mengatur dan tracking tugas kuliah', 'Merekrut', '2025-07-02 14:51:27'),
(4, 6, 'App Booking Studio Musik', 'Aplikasi mobile booking studio musik lokal', 'Merekrut', '2025-07-02 14:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `project_member`
--

CREATE TABLE `project_member` (
  `project_member_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `pengguna_id` bigint(20) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_member`
--

INSERT INTO `project_member` (`project_member_id`, `project_id`, `pengguna_id`, `role`) VALUES
(1, 1, 1, 'Developer'),
(2, 1, 3, 'Data Analyst'),
(3, 1, 4, 'PM'),
(4, 2, 2, 'Designer'),
(5, 2, 8, 'Graphic Designer'),
(6, 3, 4, 'Project Manager'),
(7, 3, 6, 'Mobile Dev'),
(8, 4, 6, 'Mobile Dev'),
(9, 4, 7, 'Frontend Dev');

-- --------------------------------------------------------

--
-- Table structure for table `project_skill_requirement`
--

CREATE TABLE `project_skill_requirement` (
  `project_skill_requirement_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_skill_requirement`
--

INSERT INTO `project_skill_requirement` (`project_skill_requirement_id`, `project_id`, `skill_id`) VALUES
(1, 1, 1),
(3, 1, 3),
(2, 1, 4),
(4, 2, 2),
(5, 2, 8),
(6, 3, 3),
(7, 3, 6),
(8, 4, 6),
(9, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `skill_id` int(11) NOT NULL,
  `nama_skill` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`skill_id`, `nama_skill`) VALUES
(4, 'Data Analysis'),
(6, 'Flutter'),
(8, 'Graphic Design'),
(5, 'JavaScript'),
(3, 'Project Management'),
(1, 'Python'),
(7, 'ReactJS'),
(2, 'UI/UX Design');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `assignee_id` bigint(20) DEFAULT NULL,
  `judul_tugas` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'To Do',
  `deadline` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `project_id`, `assignee_id`, `judul_tugas`, `status`, `deadline`) VALUES
(1, 1, 1, 'Setup backend API', 'To Do', '2025-07-10'),
(2, 1, 3, 'Lakukan analisis user behavior', 'In Progress', '2025-07-11'),
(3, 2, 2, 'Desain halaman utama', 'Done', '2025-07-01'),
(4, 2, 8, 'Buat ilustrasi untuk landing page', 'To Do', '2025-07-05'),
(5, 3, 4, 'Rancang workflow tugas', 'Done', '2025-06-25'),
(6, 3, 6, 'Buat halaman login Flutter', 'In Progress', '2025-07-08'),
(7, 4, 6, 'Rancang UI booking studio', 'To Do', '2025-07-09'),
(8, 4, 7, 'Implementasi frontend React', 'To Do', '2025-07-09');

-- --------------------------------------------------------

--
-- Table structure for table `user_skill`
--

CREATE TABLE `user_skill` (
  `pengguna_skill_id` bigint(20) NOT NULL,
  `pengguna_id` bigint(20) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_skill`
--

INSERT INTO `user_skill` (`pengguna_skill_id`, `pengguna_id`, `skill_id`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 2, 2),
(4, 3, 1),
(5, 3, 4),
(6, 4, 3),
(7, 6, 6),
(8, 7, 7),
(9, 8, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`pengguna_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `fk_creator` (`creator_id`);

--
-- Indexes for table `project_member`
--
ALTER TABLE `project_member`
  ADD PRIMARY KEY (`project_member_id`),
  ADD UNIQUE KEY `uk_project_member` (`project_id`,`pengguna_id`),
  ADD KEY `fk_project_member_pengguna` (`pengguna_id`);

--
-- Indexes for table `project_skill_requirement`
--
ALTER TABLE `project_skill_requirement`
  ADD PRIMARY KEY (`project_skill_requirement_id`),
  ADD UNIQUE KEY `uk_project_skill_req` (`project_id`,`skill_id`),
  ADD KEY `fk_project_skill_skill` (`skill_id`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `nama_skill` (`nama_skill`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_task_project` (`project_id`),
  ADD KEY `fk_task_assignee` (`assignee_id`);

--
-- Indexes for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD PRIMARY KEY (`pengguna_skill_id`),
  ADD UNIQUE KEY `uk_pengguna_skill` (`pengguna_id`,`skill_id`),
  ADD KEY `fk_skill` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `pengguna_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_member`
--
ALTER TABLE `project_member`
  MODIFY `project_member_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `project_skill_requirement`
--
ALTER TABLE `project_skill_requirement`
  MODIFY `project_skill_requirement_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `pengguna_skill_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_creator` FOREIGN KEY (`creator_id`) REFERENCES `pengguna` (`pengguna_id`);

--
-- Constraints for table `project_member`
--
ALTER TABLE `project_member`
  ADD CONSTRAINT `fk_project_member_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`pengguna_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_member_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `project_skill_requirement`
--
ALTER TABLE `project_skill_requirement`
  ADD CONSTRAINT `fk_project_skill_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_skill_skill` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_assignee` FOREIGN KEY (`assignee_id`) REFERENCES `pengguna` (`pengguna_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD CONSTRAINT `fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`pengguna_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_skill` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`skill_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
