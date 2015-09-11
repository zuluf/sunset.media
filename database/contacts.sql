--
-- Database: `sunset`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `visitor_ip` varchar(255) NOT NULL,
  `contact` text NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;