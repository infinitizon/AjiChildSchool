ALTER TABLE `exam_type` 
DROP COLUMN `status`,
ADD COLUMN `session_term_id` INT NULL AFTER `max_score`,
ADD COLUMN `ius_yn` TINYINT(1) NULL DEFAULT 1 AFTER `session_term_id`;