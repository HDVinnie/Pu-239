<?php

$sql_updates = [
    [
        'id'    => 1,
        'info'  => 'Drop unnecessary column',
        'date'  => '06 Dec, 2017',
        'query' => 'ALTER TABLE `polls` DROP `starter_name`',
    ],
    [
        'id'    => 2,
        'info'  => 'Add missing FULLTEXT index for pm messages search',
        'date'  => '07 Dec, 2017',
        'query' => 'ALTER TABLE `messages` ADD FULLTEXT INDEX `ft_subject` (`subject`);',
    ],
    [
        'id'    => 3,
        'info'  => 'Add missing FULLTEXT index for pm messages search',
        'date'  => '07 Dec, 2017',
        'query' => 'ALTER TABLE `messages` ADD FULLTEXT INDEX `ft_msg` (`msg`);',
    ],
    [
        'id'    => 4,
        'info'  => 'Add missing FULLTEXT index for pm messages search',
        'date'  => '07 Dec, 2017',
        'query' => 'ALTER TABLE `messages` ADD FULLTEXT INDEX `ft_subject_msg` (`subject`, `msg`);',
    ],
    [
        'id'    => 5,
        'info'  => 'Remove unnecessary column',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `database_updates` DROP COLUMN `info`;',
    ],
    [
        'id'    => 6,
        'info'  => 'Update last_action default value',
        'date'  => '10 Dec, 2017',
        'query' => 'ALTER TABLE `peers` MODIFY `last_action` int(10) unsigned NOT NULL DEFAULT 0;',
    ],
    [
        'id'    => 7,
        'info'  => 'Update prev_action default value',
        'date'  => '10 Dec, 2017',
        'query' => 'ALTER TABLE `peers` MODIFY `prev_action` int(10) unsigned NOT NULL DEFAULT 0;',
    ],
    [
        'id'    => 8,
        'info'  => 'Add unique index on ips table',
        'date'  => '11 Dec, 2017',
        'query' => 'ALTER TABLE `ips` ADD UNIQUE INDEX `ip_userid`(`ip`, `userid`);',
    ],
    [
        'id'    => 9,
        'info'  => 'Increase seedbonus limits',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `users` MODIFY `seedbonus` decimal(20,1) NOT NULL DEFAULT 200;',
    ],
    [
        'id'    => 10,
        'info'  => 'Set initial invites for new users to 0',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `users` MODIFY `invites` int(10) unsigned NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 11,
        'info'  => 'Update events.startTime add default value',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `events` MODIFY `startTime` int(10) unsigned NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 12,
        'info'  => 'Update events.endTime add default value',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `events` MODIFY `endTime` int(10) unsigned NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 13,
        'info'  => 'Update events.displayDates add default value',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `events` MODIFY `displayDates` tinyint(1) NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 14,
        'info'  => 'Update invite_codes.receiver',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` MODIFY `receiver` int(10) unsigned NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 15,
        'info'  => 'Update invite_codes.code',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` MODIFY `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
    ],
    [
        'id'    => 16,
        'info'  => 'Update invite_codes.invite_added',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` CHANGE `invite_added` `added` int(10) unsigned NOT NULL DEFAULT 0',
    ],
    [
        'id'    => 17,
        'info'  => 'Update invite_codes.email',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` MODIFY `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
    ],
    [
        'id'    => 18,
        'info'  => 'Drop Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` DROP INDEX `sender`',
    ],
    [
        'id'    => 19,
        'info'  => 'Add Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` ADD INDEX `sender`(`sender`)',
    ],
    [
        'id'    => 20,
        'info'  => 'Drop Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `invite_codes` DROP INDEX `sender_2`',
    ],
    [
        'id'    => 21,
        'info'  => 'Rename Table',
        'date'  => '31 Dec, 2017',
        'query' => 'RENAME TABLE `password_resets` TO `tokens`' ,
    ],
    [
        'id'    => 22,
        'info'  => 'Update Collation',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `torrents` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    ],
    [
        'id'    => 23,
        'info'  => 'Drop unnecessary table',
        'date'  => '31 Dec, 2017',
        'query' => 'DROP TABLE `torrent_pass`',
    ],
    [
        'id'    => 24,
        'info'  => 'Update users.email',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `users` MODIFY `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
    ],
    [
        'id'    => 25,
        'info'  => 'Drop Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `tokens` DROP INDEX `password_resets_email_index`',
    ],
    [
        'id'    => 26,
        'info'  => 'Create Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `tokens` ADD INDEX `id`(`id`)',
    ],
    [
        'id'    => 27,
        'info'  => 'Create Index',
        'date'  => '31 Dec, 2017',
        'query' => 'ALTER TABLE `tokens` ADD INDEX `email`(`email`)',
    ],
];
