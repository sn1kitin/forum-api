CREATE DATABASE IF NOT EXISTS `forum_api_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `forum_api_db`;

DROP TABLE IF EXISTS `replies`;
DROP TABLE IF EXISTS `topics`;
DROP TABLE IF EXISTS `threads`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(30) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `threads` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `topics` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `thread_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `body` TEXT NOT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `replies` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `topic_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `body` TEXT NOT NULL,
    `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`username`, `email`, `password`, `created_at`) VALUES
('admin', 'admin@forum.test', '$2y$10$PtdbmfGH8x7j3CVhgxiirOzPCARll36AyaRwoAQWOlP8VQtk5tOim', '2026-09-01 08:00:00'),
('jan', 'jan@forum.test', '$2y$10$c/1Lbiejhz9eZ1FNLa1/rupUFdwHAENJuQ1iNH.LGND3cz.xK7rXu', '2026-09-01 08:00:00'),
('sara', 'sara@forum.test', '$2y$10$c/1Lbiejhz9eZ1FNLa1/rupUFdwHAENJuQ1iNH.LGND3cz.xK7rXu', '2026-09-01 08:00:00'),
('timmie', 'tim@forum.test', '$2y$10$c/1Lbiejhz9eZ1FNLa1/rupUFdwHAENJuQ1iNH.LGND3cz.xK7rXu', '2026-09-01 08:00:00');

INSERT INTO `threads` (`user_id`, `title`, `description`, `created_at`) VALUES
(1, 'Nisi in aliquip', 'Reprehenderit officia in aliquip mollit labore voluptate consectetur.', '2026-09-01 17:00:39'),
(2, 'Sit nostrud dolor dolore voluptate', 'Aute aute pariatur laborum sunt consequat eiusmod cupidatat ut.', '2026-09-02 00:08:23'),
(4, 'Duis magna', 'Aute nisi do nisi duis do lorem laboris amet ea sunt sint velit adipiscing cillum quis.', '2026-09-02 08:03:03'),
(4, 'Irure sit', 'Ipsum nisi reprehenderit deserunt laboris proident consequat reprehenderit aute commodo.', '2026-09-02 16:10:47'),
(4, 'Nostrud cillum sint', 'Aute adipiscing dolore amet duis ex voluptate anim ad nisi officia dolor consectetur ipsum reprehenderit.', '2026-09-03 00:28:23');

INSERT INTO `topics` (`thread_id`, `user_id`, `title`, `body`, `created_at`) VALUES
(1, 2, 'Exercitation incididunt sed irure', 'Nostrud duis mollit sed nulla anim sunt ea. Deserunt magna ad reprehenderit cupidatat non commodo labore. Adipiscing ipsum labore dolore mollit sunt velit duis. Dolor elit id culpa labore sint.', '2026-09-03 01:11:50'),
(1, 1, 'Consectetur nulla consequat laboris qui', 'Sunt ad laboris qui ullamco ut. Dolore amet et aliqua cillum magna elit ut pariatur qui.', '2026-09-03 09:22:37'),
(2, 2, 'Sint exercitation', 'Laborum esse non sit nostrud mollit ad ex sunt cupidatat minim commodo labore enim. Ex laboris culpa pariatur ut qui aliqua excepteur quis do.', '2026-09-03 11:16:05'),
(2, 3, 'Et ea', 'Tempor nisi culpa ea officia fugiat aliquip ea proident consequat. Esse elit eiusmod adipiscing aliquip velit fugiat cupidatat non. Labore aute lorem est magna deserunt ut nisi in amet in ut dolor dolore.', '2026-09-03 13:53:13'),
(3, 1, 'Ipsum exercitation veniam', 'Minim duis qui amet magna magna ea aliqua exercitation lorem laborum excepteur. Dolor esse cupidatat dolore dolore dolor sed sit enim mollit dolore.', '2026-09-03 21:30:22'),
(3, 4, 'Nulla velit culpa non', 'Labore labore voluptate laborum consectetur sit fugiat laborum consequat sed ut dolore aliqua. Qui occaecat ad sint amet in. Id voluptate irure duis mollit amet irure ad deserunt exercitation. Aute est irure adipiscing mollit labore.', '2026-09-03 22:07:13'),
(3, 3, 'Voluptate esse dolor et', 'Ea amet aliqua et ullamco eiusmod exercitation officia officia nisi sint. Fugiat exercitation laboris nostrud consectetur ipsum. Nulla dolore tempor adipiscing proident sint quis ipsum cillum laboris consectetur.', '2026-09-04 01:54:59'),
(4, 1, 'Qui officia labore laborum est', 'Ex magna exercitation minim ullamco dolore nulla fugiat elit cillum aute sed proident ullamco. Magna consectetur consectetur nisi ut voluptate exercitation consequat ut consectetur cupidatat ea. Id in sit tempor incididunt pariatur occaecat amet magna quis reprehenderit. Non excepteur in amet voluptate irure cupidatat consectetur occaecat ullamco quis id.', '2026-09-04 04:20:48'),
(4, 4, 'Officia consectetur esse labore incididunt', 'Quis duis incididunt exercitation consequat laboris voluptate sint consequat aute do enim. Consectetur est quis irure aliquip elit reprehenderit cillum sint. Consequat ipsum tempor non id anim voluptate voluptate cupidatat.', '2026-09-04 09:22:59'),
(5, 4, 'Laborum officia', 'Amet eiusmod laboris minim deserunt sunt ex. Non aute lorem lorem sint dolore voluptate cillum tempor id cillum.', '2026-09-04 12:13:06'),
(5, 1, 'Nulla irure do laborum ipsum', 'Ut do velit minim veniam sed. Irure fugiat quis excepteur cillum cupidatat aliqua deserunt.', '2026-09-04 17:37:49');

INSERT INTO `replies` (`topic_id`, `user_id`, `body`, `created_at`) VALUES
(1, 1, 'Proident et velit nisi culpa id do nisi proident ipsum et do. Anim qui veniam non do qui fugiat veniam quis laboris officia ullamco proident et.', '2026-09-04 22:47:13'),
(2, 3, 'Mollit est dolore sit ad ex irure duis. Non ullamco do sunt sint adipiscing nisi occaecat duis do aute.', '2026-09-05 05:32:51'),
(2, 3, 'Minim ea sunt amet nulla sed in deserunt do fugiat esse. Non proident esse proident officia proident irure enim id anim nostrud.', '2026-09-05 12:41:23'),
(2, 3, 'Magna esse magna ullamco occaecat ad non fugiat aliquip ex velit non esse. In irure nostrud commodo eiusmod enim in elit reprehenderit ipsum ad sit.', '2026-09-05 13:24:20'),
(3, 4, 'Et aute aute irure nisi non. Reprehenderit cillum reprehenderit consectetur reprehenderit exercitation fugiat adipiscing ipsum minim ea laboris id mollit.', '2026-09-05 16:41:11'),
(3, 4, 'Ex consequat anim eiusmod non do ullamco anim.', '2026-09-05 19:05:23'),
(3, 2, 'Esse aliquip esse commodo quis non excepteur elit voluptate sit irure aute mollit elit. Laboris eiusmod ad sunt nisi officia consectetur voluptate ad sint minim duis veniam.', '2026-09-05 20:34:19'),
(3, 3, 'Magna dolore nisi ea aute commodo voluptate exercitation velit cillum occaecat. Amet sed non nostrud officia labore amet ullamco consequat.', '2026-09-06 03:40:08'),
(4, 2, 'Amet anim nulla laboris adipiscing laborum ea dolor excepteur aliquip elit ex sunt.', '2026-09-06 10:39:35'),
(4, 4, 'Esse nostrud qui culpa sed et incididunt enim aute voluptate elit. Ad ullamco fugiat quis proident voluptate aute.', '2026-09-06 18:23:15'),
(4, 3, 'Ad laborum qui nulla aute enim pariatur. Ut proident veniam sunt sint aute culpa amet consectetur excepteur enim.', '2026-09-06 22:51:54'),
(4, 2, 'Minim fugiat consequat culpa sed amet ut nulla consequat. Aliqua quis excepteur cillum tempor elit eiusmod nostrud.', '2026-09-07 00:14:23'),
(5, 2, 'Adipiscing do in proident ullamco incididunt enim dolor in fugiat non. Id cillum ea laboris quis commodo commodo magna incididunt ad.', '2026-09-07 07:42:22'),
(5, 4, 'Pariatur exercitation ipsum deserunt aliquip minim. Aliqua ullamco nostrud dolor excepteur nisi deserunt consectetur sint et eiusmod amet dolore occaecat.', '2026-09-07 10:56:03'),
(5, 3, 'Fugiat irure aute ex sit occaecat ad dolore deserunt nisi ullamco esse lorem reprehenderit. Ex velit sint in et labore adipiscing mollit incididunt cupidatat.', '2026-09-07 15:07:31'),
(5, 4, 'Cupidatat do do consequat anim exercitation occaecat do qui. Quis ut laborum esse proident nulla amet sunt cupidatat proident in consectetur voluptate.', '2026-09-07 21:24:48'),
(6, 4, 'Commodo magna esse nisi occaecat dolore aute sit reprehenderit duis tempor cillum do adipiscing. Veniam duis exercitation incididunt nulla velit excepteur.', '2026-09-08 01:45:46'),
(6, 1, 'Culpa eiusmod laborum et laborum occaecat.', '2026-09-08 03:55:03'),
(6, 3, 'Id laboris minim nisi id ipsum irure laboris et fugiat reprehenderit tempor duis adipiscing. Exercitation eiusmod dolore dolor deserunt do.', '2026-09-08 06:29:27'),
(7, 2, 'Amet irure consequat veniam commodo ex pariatur laboris officia.', '2026-09-08 11:23:08'),
(7, 2, 'In cillum consectetur nulla anim esse.', '2026-09-08 17:13:11'),
(7, 3, 'Ea ipsum ex do fugiat ea fugiat aliquip commodo reprehenderit ex qui. Aute pariatur dolor laborum sint reprehenderit aute consequat nisi nostrud.', '2026-09-08 21:15:14'),
(8, 1, 'Do aliquip duis laboris esse et nulla aliquip in aliquip exercitation qui.', '2026-09-09 05:04:12'),
(9, 4, 'Ad deserunt aute elit laboris ad reprehenderit commodo nulla sit laboris. Sunt et anim sed fugiat excepteur irure id magna magna.', '2026-09-09 09:09:02'),
(9, 2, 'Consectetur velit dolore adipiscing non do dolore cupidatat elit anim nostrud.', '2026-09-09 13:00:51'),
(9, 4, 'Incididunt consequat magna consectetur velit anim nostrud. Ipsum nisi ex amet adipiscing dolore dolor do dolor commodo laboris aute.', '2026-09-09 19:48:43'),
(10, 4, 'Occaecat elit reprehenderit veniam esse labore esse voluptate adipiscing.', '2026-09-10 03:28:01'),
(10, 3, 'Cillum labore reprehenderit aute magna exercitation ullamco aute officia in occaecat. Deserunt enim commodo reprehenderit do esse ex tempor sint sunt excepteur esse.', '2026-09-10 06:01:46'),
(11, 1, 'Commodo enim ex consectetur incididunt sed laboris nostrud magna reprehenderit. Occaecat aute non tempor pariatur tempor cupidatat incididunt in adipiscing ad enim aliqua adipiscing.', '2026-09-10 09:23:09');
