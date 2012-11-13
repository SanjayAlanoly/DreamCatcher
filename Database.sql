--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `Project_CF`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`) VALUES
(1, 'Bangalore'),
(2, 'Mangalore'),
(3, 'Trivandrum'),
(4, 'Mumbai'),
(5, 'Pune'),
(6, 'Chennai'),
(8, 'Vellore'),
(10, 'Cochin'),
(11, 'Hyderabad'),
(12, 'Delhi'),
(13, 'Chandigarh'),
(14, 'Kolkata'),
(15, 'Nagpur'),
(16, 'Coimbatore'),
(17, 'Vizag'),
(18, 'Vijayawada'),
(19, 'Gwalior'),
(20, 'Lucknow'),
(21, 'Bhopal'),
(22, 'Mysore'),
(23, 'Guntur'),
(24, 'Ahmedabad'),
(25, 'Dehradun'),
(26, 'Leadership');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE IF NOT EXISTS `donation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `volunteer_id` int(11) unsigned NOT NULL,
  `donation_on` datetime NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `volunteer_id` (`volunteer_id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `donation`
--


-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE IF NOT EXISTS `volunteer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL UNIQUE,
  `status` enum('vol','poc','city','regional','unknown') DEFAULT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `volunteer`
--
