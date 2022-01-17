# up
create table `not_show` (
    `id` int(11) unsigned not null auto_increment,
    `good_id` int(11) DEFAULT NULL,
    `user_id` int(11) DEFAULT NULL,
    primary key (`id`),
    index `idx_not_show`(`user_id`, `good_id`)
) engine=innodb default charset=utf8mb4;

# down
drop table `not_show`;
