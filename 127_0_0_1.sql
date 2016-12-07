-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2015 at 10:56 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--
--
-- Database: `thechildschools`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(45) DEFAULT NULL,
  `section_id` varchar(45) DEFAULT NULL,
  `class_name` varchar(45) DEFAULT NULL,
  `class_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class`, `section_id`, `class_name`, `class_desc`) VALUES
(1, 'Primary 1', 'A', 'Pri 1 A', ''),
(2, 'Primary 2', 'A', 'Pri 2 A', ''),
(3, 'Primary 3', 'A', 'Pri 3 A', ''),
(4, 'Primary 4', 'A', 'Pri 4 A', ''),
(5, 'Primary 5', 'A', 'Pri 5 A', ''),
(6, 'Primary 6', 'A', 'Pri 6 A', ''),
(7, 'Nursery 2', 'A', 'Nur 2', '');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject`
--

CREATE TABLE IF NOT EXISTS `class_subject` (
  `class_subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` varchar(10) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ins_yn` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`class_subject_id`),
  KEY `fk_class_subject_class1_idx` (`class_id`),
  KEY `fk_class_subject_teacher1_idx` (`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

--
-- Dumping data for table `class_subject`
--

INSERT INTO `class_subject` (`class_subject_id`, `class_id`, `subject_id`, `teacher_id`, `created`, `last_modified`, `ins_yn`) VALUES
(1, 3, 'Maths', 2011, '2015-10-11 12:17:21', NULL, 1),
(2, 3, 'Eng', 2011, '2015-10-11 12:17:21', NULL, 1),
(3, 2, 'Maths', 2011, '2015-10-11 12:17:41', NULL, 1),
(4, 2, 'Eng', 2011, '2015-10-11 12:17:41', NULL, 1),
(5, 1, 'Maths', 2011, '2015-10-17 17:30:22', NULL, 1),
(6, 1, 'Eng', 2011, '2015-10-17 17:30:22', NULL, 1),
(7, 1, 'SocStud', 2011, '2015-10-17 17:30:22', NULL, 1),
(8, 1, 'Maths', 2011, '2015-10-17 17:30:26', NULL, 1),
(9, 1, 'Eng', 2011, '2015-10-17 17:30:26', NULL, 1),
(10, 1, 'SocStud', 2011, '2015-10-17 17:30:26', NULL, 1),
(11, 2, 'Maths', 2011, '2015-10-26 10:49:03', NULL, 1),
(12, 2, 'Eng', 2011, '2015-10-26 10:49:03', NULL, 1),
(13, 2, 'SocStud', 2011, '2015-10-26 10:49:03', NULL, 1),
(14, 2, 'Phncs', 2011, '2015-10-26 10:49:03', NULL, 1),
(15, 2, 'Frnh', 2011, '2015-10-26 10:49:03', NULL, 1),
(16, 2, 'Quantitati', 2011, '2015-10-26 10:49:03', NULL, 1),
(17, 2, 'SocSci', 2011, '2015-10-26 10:49:03', NULL, 1),
(18, 2, 'CivicEdu', 2011, '2015-10-26 10:49:03', NULL, 1),
(19, 2, 'BasSci', 2011, '2015-10-26 10:49:03', NULL, 1),
(20, 2, 'Vrbl', 2011, '2015-10-26 10:49:03', NULL, 1),
(21, 2, 'Agric', 2011, '2015-10-26 10:49:03', NULL, 1),
(22, 2, 'EngLit', 2011, '2015-10-26 10:49:03', NULL, 1),
(23, 2, 'EngGram', 2011, '2015-10-26 10:49:03', NULL, 1),
(24, 2, 'CRS', 2011, '2015-10-26 10:49:03', NULL, 1),
(25, 2, 'PHE', 2011, '2015-10-26 10:49:03', NULL, 1),
(26, 2, 'Computer', 2011, '2015-10-26 10:49:03', NULL, 1),
(27, 2, 'HomeEcons', 2011, '2015-10-26 10:49:03', NULL, 1),
(28, 2, 'Yor', 2011, '2015-10-26 10:49:03', NULL, 1),
(29, 2, 'EngComp', 2011, '2015-10-26 10:49:03', NULL, 1),
(30, 2, 'CCA', 2011, '2015-10-26 10:49:03', NULL, 1),
(31, 2, 'Maths', 2011, '2015-10-26 10:49:08', NULL, 1),
(32, 2, 'Eng', 2011, '2015-10-26 10:49:08', NULL, 1),
(33, 2, 'SocStud', 2011, '2015-10-26 10:49:08', NULL, 1),
(34, 2, 'Phncs', 2011, '2015-10-26 10:49:08', NULL, 1),
(35, 2, 'Frnh', 2011, '2015-10-26 10:49:08', NULL, 1),
(36, 2, 'Quantitati', 2011, '2015-10-26 10:49:08', NULL, 1),
(37, 2, 'SocSci', 2011, '2015-10-26 10:49:08', NULL, 1),
(38, 2, 'CivicEdu', 2011, '2015-10-26 10:49:08', NULL, 1),
(39, 2, 'BasSci', 2011, '2015-10-26 10:49:08', NULL, 1),
(40, 2, 'Vrbl', 2011, '2015-10-26 10:49:08', NULL, 1),
(41, 2, 'Agric', 2011, '2015-10-26 10:49:08', NULL, 1),
(42, 2, 'EngLit', 2011, '2015-10-26 10:49:08', NULL, 1),
(43, 2, 'EngGram', 2011, '2015-10-26 10:49:08', NULL, 1),
(44, 2, 'CRS', 2011, '2015-10-26 10:49:08', NULL, 1),
(45, 2, 'PHE', 2011, '2015-10-26 10:49:08', NULL, 1),
(46, 2, 'Computer', 2011, '2015-10-26 10:49:08', NULL, 1),
(47, 2, 'HomeEcons', 2011, '2015-10-26 10:49:08', NULL, 1),
(48, 2, 'Yor', 2011, '2015-10-26 10:49:08', NULL, 1),
(49, 2, 'EngComp', 2011, '2015-10-26 10:49:08', NULL, 1),
(50, 2, 'CCA', 2011, '2015-10-26 10:49:08', NULL, 1),
(51, 2, 'Maths', 2011, '2015-10-26 10:49:09', NULL, 1),
(52, 2, 'Eng', 2011, '2015-10-26 10:49:09', NULL, 1),
(53, 2, 'SocStud', 2011, '2015-10-26 10:49:09', NULL, 1),
(54, 2, 'Phncs', 2011, '2015-10-26 10:49:09', NULL, 1),
(55, 2, 'Frnh', 2011, '2015-10-26 10:49:09', NULL, 1),
(56, 2, 'Quantitati', 2011, '2015-10-26 10:49:10', NULL, 1),
(57, 2, 'SocSci', 2011, '2015-10-26 10:49:10', NULL, 1),
(58, 2, 'CivicEdu', 2011, '2015-10-26 10:49:10', NULL, 1),
(59, 2, 'BasSci', 2011, '2015-10-26 10:49:10', NULL, 1),
(60, 2, 'Vrbl', 2011, '2015-10-26 10:49:10', NULL, 1),
(61, 2, 'Agric', 2011, '2015-10-26 10:49:10', NULL, 1),
(62, 2, 'EngLit', 2011, '2015-10-26 10:49:10', NULL, 1),
(63, 2, 'EngGram', 2011, '2015-10-26 10:49:10', NULL, 1),
(64, 2, 'CRS', 2011, '2015-10-26 10:49:10', NULL, 1),
(65, 2, 'PHE', 2011, '2015-10-26 10:49:10', NULL, 1),
(66, 2, 'Computer', 2011, '2015-10-26 10:49:10', NULL, 1),
(67, 2, 'HomeEcons', 2011, '2015-10-26 10:49:10', NULL, 1),
(68, 2, 'Yor', 2011, '2015-10-26 10:49:10', NULL, 1),
(69, 2, 'EngComp', 2011, '2015-10-26 10:49:10', NULL, 1),
(70, 2, 'CCA', 2011, '2015-10-26 10:49:10', NULL, 1),
(71, 2, 'Maths', 2011, '2015-10-26 10:49:22', NULL, 1),
(72, 2, 'Eng', 2011, '2015-10-26 10:49:22', NULL, 1),
(73, 2, 'SocStud', 2011, '2015-10-26 10:49:22', NULL, 1),
(74, 2, 'Phncs', 2011, '2015-10-26 10:49:22', NULL, 1),
(75, 2, 'Frnh', 2011, '2015-10-26 10:49:22', NULL, 1),
(76, 2, 'Quantitati', 2011, '2015-10-26 10:49:22', NULL, 1),
(77, 2, 'SocSci', 2011, '2015-10-26 10:49:22', NULL, 1),
(78, 2, 'CivicEdu', 2011, '2015-10-26 10:49:22', NULL, 1),
(79, 2, 'BasSci', 2011, '2015-10-26 10:49:22', NULL, 1),
(80, 2, 'Vrbl', 2011, '2015-10-26 10:49:22', NULL, 1),
(81, 2, 'Agric', 2011, '2015-10-26 10:49:22', NULL, 1),
(82, 2, 'EngLit', 2011, '2015-10-26 10:49:22', NULL, 1),
(83, 2, 'EngGram', 2011, '2015-10-26 10:49:23', NULL, 1),
(84, 2, 'CRS', 2011, '2015-10-26 10:49:23', NULL, 1),
(85, 2, 'PHE', 2011, '2015-10-26 10:49:23', NULL, 1),
(86, 2, 'Computer', 2011, '2015-10-26 10:49:23', NULL, 1),
(87, 2, 'HomeEcons', 2011, '2015-10-26 10:49:23', NULL, 1),
(88, 2, 'Yor', 2011, '2015-10-26 10:49:23', NULL, 1),
(89, 2, 'EngComp', 2011, '2015-10-26 10:49:23', NULL, 1),
(90, 2, 'CCA', 2011, '2015-10-26 10:49:23', NULL, 1),
(91, 2, 'Maths', 2011, '2015-10-26 10:49:23', NULL, 1),
(92, 2, 'Eng', 2011, '2015-10-26 10:49:23', NULL, 1),
(93, 2, 'SocStud', 2011, '2015-10-26 10:49:23', NULL, 1),
(94, 2, 'Phncs', 2011, '2015-10-26 10:49:23', NULL, 1),
(95, 2, 'Frnh', 2011, '2015-10-26 10:49:23', NULL, 1),
(96, 2, 'Quantitati', 2011, '2015-10-26 10:49:23', NULL, 1),
(97, 2, 'SocSci', 2011, '2015-10-26 10:49:23', NULL, 1),
(98, 2, 'CivicEdu', 2011, '2015-10-26 10:49:23', NULL, 1),
(99, 2, 'BasSci', 2011, '2015-10-26 10:49:23', NULL, 1),
(100, 2, 'Vrbl', 2011, '2015-10-26 10:49:23', NULL, 1),
(101, 2, 'Agric', 2011, '2015-10-26 10:49:23', NULL, 1),
(102, 2, 'EngLit', 2011, '2015-10-26 10:49:23', NULL, 1),
(103, 2, 'EngGram', 2011, '2015-10-26 10:49:23', NULL, 1),
(104, 2, 'CRS', 2011, '2015-10-26 10:49:23', NULL, 1),
(105, 2, 'PHE', 2011, '2015-10-26 10:49:23', NULL, 1),
(106, 2, 'Computer', 2011, '2015-10-26 10:49:23', NULL, 1),
(107, 2, 'HomeEcons', 2011, '2015-10-26 10:49:23', NULL, 1),
(108, 2, 'Yor', 2011, '2015-10-26 10:49:23', NULL, 1),
(109, 2, 'EngComp', 2011, '2015-10-26 10:49:23', NULL, 1),
(110, 2, 'CCA', 2011, '2015-10-26 10:49:23', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exam_type`
--

CREATE TABLE IF NOT EXISTS `exam_type` (
  `exam_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_type_desc` varchar(45) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `max_score` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`exam_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `exam_type`
--

INSERT INTO `exam_type` (`exam_type_id`, `exam_type_desc`, `status`, `max_score`, `created`, `modified`) VALUES
(1, 'Test 1', 1, '10', '2015-10-11 15:17:06', NULL),
(2, 'Test 2', 1, '10', '2015-10-11 15:17:06', NULL),
(3, 'Test 3', 1, '10', '2015-10-11 15:17:06', NULL),
(4, 'Test 4', 0, '10', '2015-10-11 15:17:06', NULL),
(5, 'Test 5', 0, '10', '2015-10-11 15:17:06', NULL),
(6, 'Exam 1', 1, '70', '2015-10-11 15:17:06', NULL),
(7, 'Exam 2', 0, '30', '2015-10-11 15:17:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `examinations`
--

CREATE TABLE IF NOT EXISTS `examinations` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_term_id` int(11) DEFAULT NULL,
  `class_subject_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_type_id` int(11) DEFAULT NULL,
  `max_score` int(11) DEFAULT NULL,
  `stud_score` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`exam_id`),
  KEY `fk_examinations_exam_type1_idx` (`exam_type_id`),
  KEY `fk_examinations_student1_idx` (`student_id`),
  KEY `fk_examinations_class_subject1_idx` (`class_subject_id`),
  KEY `fk_examinations_session_term1_idx` (`session_term_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `examinations`
--

INSERT INTO `examinations` (`exam_id`, `session_term_id`, `class_subject_id`, `student_id`, `exam_type_id`, `max_score`, `stud_score`, `created`, `last_modified`) VALUES
(1, 2001, 3, 2002, 1, NULL, 20, NULL, '2015-10-21 07:36:12'),
(2, 2001, 3, 2002, 2, NULL, 5, NULL, '2015-10-17 16:56:20'),
(3, 2001, 3, 2002, 3, NULL, 8, NULL, NULL),
(4, 2001, 3, 2014, 1, NULL, 70, NULL, '2015-10-13 11:15:48'),
(5, 2001, 3, 2014, 2, NULL, 6, NULL, NULL),
(6, 2001, 4, 2002, 1, NULL, 30, NULL, '2015-10-21 07:36:34'),
(7, 2001, 4, 2002, 2, NULL, 7, NULL, NULL),
(8, 2001, 4, 2002, 3, NULL, 8, NULL, NULL),
(9, 2001, 3, 2002, 6, NULL, 60, NULL, NULL),
(10, 2002, 3, 2002, 1, NULL, 6, NULL, NULL),
(11, 2002, 3, 2002, 2, NULL, 8, NULL, NULL),
(12, 2002, 4, 2002, 1, NULL, 9, NULL, NULL),
(13, 2002, 4, 2002, 2, NULL, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `list_def`
--

CREATE TABLE IF NOT EXISTS `list_def` (
  `r_k` int(11) NOT NULL AUTO_INCREMENT,
  `def_id` varchar(45) DEFAULT NULL,
  `val_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`r_k`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=157 ;

--
-- Dumping data for table `list_def`
--

INSERT INTO `list_def` (`r_k`, `def_id`, `val_desc`) VALUES
(150, '00-SEX', 'Sex'),
(151, 'CTC-CTR', 'Countries'),
(152, 'CTC-STA', 'States'),
(153, '00-STAT', 'Status'),
(154, '00-TT', 'Teacher Type'),
(155, '00-SUBJ', 'Subjects'),
(156, 'CLS-SCTN', 'Class Sections');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE IF NOT EXISTS `parent` (
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Describes a student.\nA Parent is a Person\nA Parent has many  /* comment truncated */ /*students*/';

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `person_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_type_id` int(11) DEFAULT NULL,
  `f_name` varchar(50) DEFAULT NULL,
  `m_name` varchar(50) DEFAULT NULL,
  `l_name` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `p_word` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `phone2` varchar(15) DEFAULT NULL,
  `pix` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `last_login_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Describes a person' AUTO_INCREMENT=2016 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`person_id`, `person_type_id`, `f_name`, `m_name`, `l_name`, `email`, `username`, `p_word`, `dob`, `sex`, `phone`, `phone2`, `pix`, `status`, `last_login_ip`, `last_login_date`) VALUES
(2001, 1, 'Admin', 'Admin', 'Admin', NULL, 'admin', '$2y$12$hENL2lJlPKSpleydmNI49OndOKE2UQkWxPsvKHoqMNFuhqJR5PNOi', '2015-10-08', 1, 'NULL', NULL, NULL, 1, NULL, NULL),
(2002, 3, 'student', 'A Name', 'Hans', NULL, 'student', '$2y$12$zSoRikxbZ1Rx3/1n3Lr5OOk3Z48S6lJiTeOEUf3YfKNP8PRUlZ00S', '2009-10-14', 1, '090909090909', NULL, '/assets/images/profiles/1445101268.jpg', 1, NULL, NULL),
(2011, 1, 'Ajiboye', 'sola', 'Adetosin', NULL, 'sola', '$2y$12$zSoRikxbZ1Rx3/1n3Lr5OOk3Z48S6lJiTeOEUf3YfKNP8PRUlZ00S', '1984-09-14', 2, '08026656212', NULL, NULL, 1, NULL, NULL),
(2012, 1, 'Aa', 'Daramola', 'Teacher', NULL, 'daramola', '$2y$12$zSoRikxbZ1Rx3/1n3Lr5OOk3Z48S6lJiTeOEUf3YfKNP8PRUlZ00S', '1985-04-10', 1, '08169606003', NULL, NULL, 1, NULL, NULL),
(2013, 3, 'Aladeloba', 'Daniel', 'Ajiboye', NULL, 'aladeloba', NULL, '2013-11-26', 1, '08029433747', NULL, '/assets/images/profiles/1444924958.jpg', 1, NULL, NULL),
(2014, 3, 'Bimbo', '', 'Hassan', NULL, 'bimbs', NULL, '1997-10-01', 2, '090909090909', NULL, NULL, 1, NULL, NULL),
(2015, 1, 'Adetutu', 'Abimbola', 'Azeez', NULL, 'abimbola', NULL, '1976-03-09', 2, '08052407484', NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person_type`
--

CREATE TABLE IF NOT EXISTS `person_type` (
  `person_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `session_term`
--

CREATE TABLE IF NOT EXISTS `session_term` (
  `session_term_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_nm` varchar(50) DEFAULT NULL,
  `term` varchar(50) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `ius_yn` tinyint(1) DEFAULT NULL,
  `session_term_desc` varchar(100) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_term_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2022 ;

--
-- Dumping data for table `session_term`
--

INSERT INTO `session_term` (`session_term_id`, `session_nm`, `term`, `class_id`, `ius_yn`, `session_term_desc`, `create_date`) VALUES
(2001, '2015/2016', '1', 2, 1, '2015/2016 session, 1st term', '2015-10-17 16:49:29'),
(2002, '2015/2016', '2', 2, 0, '2015/2016 session, 2st term', '2015-10-17 16:49:29'),
(2003, '2015/2016', '3', 2, 0, '2015/2016 session, 3rd term', '2015-10-16 15:25:25'),
(2004, '2015/2016', '1', 1, 1, '2015/2016 session, 1st term', '2015-10-18 20:27:54'),
(2005, '2015/2016', '2', 1, 0, '2015/2016 session, 2nd term', '2015-10-17 15:29:30'),
(2006, '2015/2016', '3', 1, 0, '2015/2016 session, 3rd term', '2015-10-18 20:27:54'),
(2007, '2015/2016', '1', 3, 1, '2015/2016 session, 1st term', NULL),
(2008, '2015/2016', '2', 3, 0, '2015/2016 session, 2nd term', NULL),
(2009, '2015/2016', '3', 3, 0, '2015/2016 session, 3rd term', NULL),
(2010, '2015/2016', '1', 4, 1, '2015/2016 session, 1st term', NULL),
(2011, '2015/2016', '2', 4, 0, '2015/2016 session, 2nd term', NULL),
(2012, '2015/2016', '3', 4, 0, '2015/2016 session, 3rd term', NULL),
(2013, '2015/2016', '1', 5, 1, '2015/2016 session, 1st term', NULL),
(2014, '2015/2016', '2', 5, 0, '2015/2016 session, 2nd term', NULL),
(2015, '2015/2016', '3', 5, 0, '2015/2016 session, 3rd term', NULL),
(2016, '2015/2016', '1', 6, 1, '2015/2016 session, 1st term', NULL),
(2017, '2015/2016', '2', 6, 0, '2015/2016 session, 2nd term', NULL),
(2018, '2015/2016', '3', 6, 0, '2015/2016 session, 3rd term', NULL),
(2019, '2015/2016', '1', 7, 1, '2015/2016 session, 1st term', NULL),
(2020, '2015/2016', '2', 7, 0, '2015/2016 session, 2nd term', NULL),
(2021, '2015/2016', '3', 7, 0, '2015/2016 session, 3rd term', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `adminNo` varchar(45) DEFAULT NULL,
  `times_present` int(11) DEFAULT NULL,
  `times_punctual` int(11) DEFAULT NULL,
  `teacher_comment` varchar(500) DEFAULT NULL,
  `principal_comment` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  KEY `fk_student_parent1_idx` (`parent_id`),
  KEY `fk_student_class1_idx` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Describes a student.\nA Student is a Person';

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `class_id`, `parent_id`, `adminNo`, `times_present`, `times_punctual`, `teacher_comment`, `principal_comment`) VALUES
(2002, 2, NULL, 'B1234', 21, 20, 'Good Boy pass', 'Promoted to Baby class'),
(2013, 2, NULL, 'B1235', 0, 0, '', ''),
(2014, 2, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

CREATE TABLE IF NOT EXISTS `student_subject` (
  `student_subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_wb_lov`
--

CREATE TABLE IF NOT EXISTS `t_wb_lov` (
  `r_k` int(11) NOT NULL AUTO_INCREMENT,
  `dm_yn` tinyint(1) DEFAULT '0',
  `par_id` int(11) DEFAULT '0',
  `def_id` varchar(15) DEFAULT NULL,
  `val_id` varchar(10) DEFAULT NULL,
  `val_dsc` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`r_k`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Settings table.\nHolds various settings especially to populat /* comment truncated */ /*e LOVs*/' AUTO_INCREMENT=2040 ;

--
-- Dumping data for table `t_wb_lov`
--

INSERT INTO `t_wb_lov` (`r_k`, `dm_yn`, `par_id`, `def_id`, `val_id`, `val_dsc`) VALUES
(1011, 1, 0, '00-SEX', '1', 'Male'),
(1012, 1, 0, '00-SEX', '2', 'Female'),
(1013, 0, 0, 'CTC-CTR', 'NIG', 'Nigeria'),
(1014, 0, 0, 'CTC-CTR', 'USA', 'United State Of America'),
(1015, 1, 0, '00-STAT', '0', 'Pending'),
(1016, 1, 0, '00-STAT', '1', 'Approved'),
(1017, 1, 0, '00-STAT', '2', 'Rejected'),
(1018, 1, 0, '00-STAT', '3', 'Cancelled'),
(1019, 0, 1013, 'CTC-STA', 'AB', 'Abia'),
(1020, 0, 1013, 'CTC-STA', 'AD', 'Adamawa'),
(1021, 0, 1013, 'CTC-STA', 'AK', 'Akwa Ibom'),
(1022, 0, 1013, 'CTC-STA', 'AN', 'Anambra'),
(1023, 0, 1013, 'CTC-STA', 'BA', 'Bauchi'),
(1024, 0, 1013, 'CTC-STA', 'BY', 'Bayelsa'),
(1025, 0, 1013, 'CTC-STA', 'BE', 'Benue'),
(1026, 0, 1013, 'CTC-STA', 'BO', 'Borno'),
(1027, 0, 1013, 'CTC-STA', 'CR', 'Cross River'),
(1028, 0, 1013, 'CTC-STA', 'DE', 'Delta'),
(1029, 0, 1013, 'CTC-STA', 'EB', 'Ebonyi'),
(1030, 0, 1013, 'CTC-STA', 'ED', 'Edo'),
(1031, 0, 1013, 'CTC-STA', 'EK', 'Ekiti'),
(1032, 0, 1013, 'CTC-STA', 'EN', 'Enugu'),
(1033, 0, 1013, 'CTC-STA', 'FC', 'Federal Capital Territory'),
(1034, 0, 1013, 'CTC-STA', 'GO', 'Gombe'),
(1035, 0, 1013, 'CTC-STA', 'IM', 'Imo'),
(1036, 0, 1013, 'CTC-STA', 'JI', 'Jigawa'),
(1037, 0, 1013, 'CTC-STA', 'KD', 'Kaduna'),
(1038, 0, 1013, 'CTC-STA', 'KN', 'Kano'),
(1039, 0, 1013, 'CTC-STA', 'KT', 'Katsina'),
(1040, 0, 1013, 'CTC-STA', 'KE', 'Kebbi'),
(1041, 0, 1013, 'CTC-STA', 'KO', 'Kogi'),
(1042, 0, 1013, 'CTC-STA', 'KW', 'Kwara'),
(1043, 0, 1013, 'CTC-STA', 'LA', 'Lagos'),
(1044, 0, 1013, 'CTC-STA', 'NA', 'Nasarawa'),
(1045, 0, 1013, 'CTC-STA', 'NI', 'Niger'),
(1046, 0, 1013, 'CTC-STA', 'OG', 'Ogun'),
(1047, 0, 1013, 'CTC-STA', 'ON', 'Ondo'),
(1048, 0, 1013, 'CTC-STA', 'OS', 'Osun'),
(1049, 0, 1013, 'CTC-STA', 'OY', 'Oyo'),
(1050, 0, 1013, 'CTC-STA', 'PL', 'Plateau'),
(1051, 0, 1013, 'CTC-STA', 'RI', 'Rivers'),
(1052, 0, 1013, 'CTC-STA', 'SO', 'Sokoto'),
(1053, 0, 1013, 'CTC-STA', 'TA', 'Taraba'),
(1054, 0, 1013, 'CTC-STA', 'YO', 'Yobe'),
(1055, 0, 1013, 'CTC-STA', 'ZA', 'Zamfara'),
(1056, 0, 1014, 'CTC-STA', 'AL', 'Alabama'),
(1057, 0, 1014, 'CTC-STA', 'AK', 'Alaska'),
(1058, 0, 0, '00-TT', '1', 'Admin'),
(1059, 0, 0, '00-TT', '2', 'General'),
(2001, 0, 0, '00-SUBJ', 'Maths', 'Mathematics'),
(2002, 0, 0, '00-SUBJ', 'Eng', 'English'),
(2003, 0, 0, 'CLS-SCTN', 'A', 'Section A'),
(2004, 0, 0, 'CLS-SCTN', 'B', 'Section B'),
(2005, 0, 0, 'CLS-SCTN', 'C', 'Section C'),
(2006, 0, 0, '00-SUBJ', 'SocStud', 'Social Studies'),
(2007, 0, 0, '00-SUBJ', 'NumWork', 'Number Work'),
(2008, 0, 0, '00-SUBJ', 'HlthHab', 'Health Habit'),
(2009, 0, 0, '00-SUBJ', 'LettWrk', 'Letter Work'),
(2010, 0, 0, '00-SUBJ', 'SocHab', 'Social Habit'),
(2011, 0, 0, '00-SUBJ', 'Write', 'Writing'),
(2012, 0, 0, '00-SUBJ', 'Rhym', 'Rhymes'),
(2013, 0, 0, '00-SUBJ', 'ColAct', 'Colouring Activities'),
(2014, 0, 0, '00-SUBJ', 'Read', 'Reading'),
(2015, 0, 0, '00-SUBJ', 'Phncs', 'Phonics'),
(2016, 0, 0, '00-SUBJ', 'Sci', 'Science'),
(2017, 0, 0, '00-SUBJ', 'Frnh', 'French'),
(2018, 0, 0, '00-SUBJ', 'verbal', 'Verbal Reasoning'),
(2019, 0, 0, '00-SUBJ', 'Quantitati', 'Quantitative reasoning'),
(2020, 0, 0, '00-SUBJ', 'Msc', 'Music'),
(2021, 0, 0, '00-SUBJ', 'SocSci', 'Social Science'),
(2022, 0, 0, '00-SUBJ', 'CivicEdu', 'Civic Education'),
(2023, 0, 0, '00-SUBJ', 'Voctnal', 'Vocational'),
(2024, 0, 0, '00-SUBJ', 'HandWri', 'Handwriting'),
(2025, 0, 0, '00-SUBJ', 'BasSci', 'Basic Science'),
(2026, 0, 0, '00-SUBJ', 'Vrbl', 'Verbal'),
(2027, 0, 0, '00-SUBJ', 'Agric', 'Agricultural Science'),
(2028, 0, 0, '00-SUBJ', 'EngLit', 'English Literature'),
(2029, 0, 0, '00-SUBJ', 'EngGram', 'English Grammar'),
(2030, 0, 0, '00-SUBJ', 'CRS', 'Christian Religious Studies'),
(2031, 0, 0, '00-SUBJ', 'PHE', 'Physical & Health Education'),
(2032, 0, 0, '00-SUBJ', 'Computer', 'Computer Science'),
(2033, 0, 0, '00-SUBJ', 'HomeEcons', 'Home Economics'),
(2034, 0, 0, '00-SUBJ', 'Yor', 'Yoruba'),
(2035, 0, 0, '00-SUBJ', 'EngComp', 'English Composition'),
(2036, 0, 0, '00-SUBJ', 'MentMaths', 'Mental Maths'),
(2037, 0, 0, '00-SUBJ', 'CCA', 'C.C.A.'),
(2038, 0, 0, '00-SUBJ', 'BasicTech', 'Basic Tech.'),
(2039, 0, 0, '00-SUBJ', 'BusStd', 'Business Studies');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2016 ;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_type_id`) VALUES
(2001, 1),
(2011, 2),
(2012, 2),
(2015, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD CONSTRAINT `fk_class_subject_class1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_class_subject_teacher1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `examinations`
--
ALTER TABLE `examinations`
  ADD CONSTRAINT `fk_examinations_class_subject1` FOREIGN KEY (`class_subject_id`) REFERENCES `class_subject` (`class_subject_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_examinations_exam_type1` FOREIGN KEY (`exam_type_id`) REFERENCES `exam_type` (`exam_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_examinations_session_term1` FOREIGN KEY (`session_term_id`) REFERENCES `session_term` (`session_term_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_examinations_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `fk_parent_person1` FOREIGN KEY (`parent_id`) REFERENCES `person` (`person_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_class1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_student_parent1` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`parent_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_student_person1` FOREIGN KEY (`student_id`) REFERENCES `person` (`person_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_person1` FOREIGN KEY (`teacher_id`) REFERENCES `person` (`person_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
