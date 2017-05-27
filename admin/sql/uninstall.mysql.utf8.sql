DROP TABLE IF EXISTS `#__nok_pm_project_comments`;
DROP TABLE IF EXISTS `#__nok_pm_task_comments`;
DROP TABLE IF EXISTS `#__nok_pm_tasks`;
DROP TABLE IF EXISTS `#__nok_pm_projects`;
DELETE FROM `#__assets` WHERE `name` LIKE 'com_nokprjmgnt%';
DELETE FROM `#__assets` WHERE `name` LIKE '#__nok_pm_%';

