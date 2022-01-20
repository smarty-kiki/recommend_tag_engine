# up
alter table `good` add column `content` longtext NOT NULL;
alter table `good` add column `extend_id` int(11) unsigned not null default 0;

# down
alter table `good` drop column `content`;
alter table `good` drop column `extend_id`;
