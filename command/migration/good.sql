# up
create table `good` (
  `id` int(11) unsigned not null auto_increment,
  `name` varchar(200) not null default '',
  `url` varchar(500) not null default '',
  primary key (`id`)
) engine=innodb default charset=utf8mb4;

# down
drop table `good`;
