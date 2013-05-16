CREATE TABLE IF NOT EXISTS `pushover_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_sent` datetime NOT NULL,
  `curl_error` tinyint(1) NOT NULL DEFAULT '0',
  `http_status` int(3) DEFAULT NULL,
  `response_data` text,
  `message_data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;