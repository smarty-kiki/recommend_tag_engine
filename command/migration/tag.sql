# up
CREATE TABLE `tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `trans_tag_id` int(11) DEFAULT NULL,
  `trans_type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  index `idx_trans`(`type`, `trans_type`, `trans_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# down
drop table `tag`;
