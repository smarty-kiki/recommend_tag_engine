# up
CREATE TABLE `tag_target` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `class` varchar(200) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  index `idx_class`(`class`, `class_id`, `tag_id`, `count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# down
drop table `tag_target`;
