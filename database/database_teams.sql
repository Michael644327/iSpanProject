-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-05-15 02:42:22
-- 伺服器版本： 8.0.36
-- PHP 版本： 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- teams table Create
CREATE TABLE `teams` (
  `team_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `team_title` varchar(40) DEFAULT NULL,
  `leader_id` int NOT NULL,
  `team_limit` int NOT NULL,
  `tour` int NOT NULL,
  `create_at` datetime NOT NULL,
  `team_status` int NOT NULL DEFAULT(0),
  `team_display` int NOT NULL DEFAULT(1),
  `team_note` varchar(600) DEFAULT NULL,
  `last_modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- teams data
INSERT INTO `teams` (`team_id`, `team_title`, `leader_id`, `team_limit`, `tour`, `create_at`, `last_modified_at`) VALUES
(1, '第一團', 2, 7, 3, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(2, '第二團', 5, 4, 4, '2024-05-10 14:47:18', '2024-05-13 09:12:08'),
(3, '失落..', 1, 3, 2, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(4, '海底總動員', 4, 3, 5, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(5, '尋寶糾團', 5, 2, 1, '2024-05-10 14:47:18', '2024-05-14 12:57:46'),
(6, '沒有要跑團要約吃飯', 4, 3, 6, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(7, '第7團', 7, 1, 10, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(8, '第8團', 8, 3, 8, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(9, '第9團', 4, 2, 9, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(10, '第10團', 9, 3, 7, '2024-05-10 14:47:18', '2024-05-10 14:47:18'),
(11, '勇氣之團', 10, 3, 3, '2024-05-13 09:10:14', '2024-05-13 09:10:14'),
(12, '我只是來打牌的', 9, 3, 5, '2024-05-13 09:14:26', '2024-05-13 09:14:26')
(13, '快樂團',10,1,5,'2024-05-15 09:36:12', '2024-05-15 09:36:12')

-- teams chat table
CREATE TABLE `teams_chats` (
  `chat_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chat_at` int DEFAULT NULL,
  `chat_by` int DEFAULT NULL,
  `chat_text` varchar(200) DEFAULT NULL,
  `chat_display` int NOT NULL DEFAULT(1),
  `create_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `teams_chats` (`chat_id`, `chat_at`, `chat_by`, `chat_text`, `create_at`) VALUES
(1, 1, 5, '哈囉大家好我搶到第一團了', '2024-05-13 13:38:28'),
(2, 2, 5, '第二推', '2024-05-13 13:38:37'),
(3, 1, 7, '再測試一次', '2024-05-13 14:03:50'),
(4, 1, 8, '我也來測試看看', '2024-05-13 14:04:14'),
(5, 3, 4, '這團也測試看看', '2024-05-13 14:16:17'),
(6, 12, 8, '測試看看留言機能', '2024-05-14 10:30:19'),
(7, 12, 7, '測試', '2024-05-14 10:38:25'),
(8, 12, 2, '都在留言沒人要加', '2024-05-14 10:52:54'),
(9, 12, 3, 'ㄏㄏ', '2024-05-14 10:53:30'),
(10, 1, 3, '廣告廣告廣告', '2024-05-14 10:58:02'),
(11, 1, 10, '這團好熱鬧', '2024-05-14 10:58:49'),
(12, 5, 2, '我想加但沒空', '2024-05-14 11:31:24');

--

CREATE TABLE `teams_members` (
  `no` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `join_team_id` int DEFAULT NULL,
  `join_user_id` int DEFAULT NULL,
  `create_at` datetime DEFAULT (now())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `teams_members` (`join_team_id`, `join_user_id`, `create_at`) VALUES
(1, 3, '2024-05-13 15:14:49'),
(2, 1, '2024-05-13 15:14:49'),
(2, 8, '2024-05-13 15:14:49'),
(7, 12, '2024-05-13 15:14:49'),
(10, 7, '2024-05-13 15:14:49'),
(10, 9, '2024-05-13 15:14:49'),
(9, 5, '2024-05-13 15:14:49'),
(6, 1, '2024-05-13 15:14:49'),
(6, 2, '2024-05-13 15:14:49');

--

CREATE TABLE `teams_status` (
  `status_id` int NOT NULL PRIMARY KEY,
  `status_text` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `teams_status` (`status_id`, `status_text`) VALUES
(0, '募集中'),
(1, '已滿團'),
(2, '準備成團'),
(3, '已成團');