CREATE TABLE `#__nok_pm_projects` (
  `id` integer NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `catid` int(11) NOT NULL default '0',
  `priority` integer NOT NULL default 1,
  `duedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `read_asset_id` int(255) UNSIGNED NOT NULL default '0',
  `modify_asset_id` int(255) UNSIGNED NOT NULL default '0',
  `custom1` varchar(255) default NULL,
  `custom2` varchar(255) default NULL,
  `custom3` varchar(255) default NULL,
  `custom4` varchar(255) default NULL,
  `custom5` varchar(255) default NULL,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
)  DEFAULT CHARSET=utf8;

CREATE TABLE `#__nok_pm_tasks` (
  `id` integer NOT NULL auto_increment,
  `project_id` integer NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `priority` integer NOT NULL default 1,
  `duedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `responsible_user_id` int(255) UNSIGNED default NULL,
  `assign_user_ids` varchar(255) default NULL,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `idx_project_id` (`project_id`)
)  DEFAULT CHARSET=utf8;
