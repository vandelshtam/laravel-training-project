-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Ноя 02 2021 г., 19:09
-- Версия сервера: 5.7.32
-- Версия PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `laravel-training-project`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `author_user_id` int(11) NOT NULL,
  `name_chat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_chat` int(11) DEFAULT NULL,
  `chat_avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'img/demo/avatars/admin-m.png',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favorites` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `banned` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'author',
  `c` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `search_chat` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL,
  `commentable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentable_id` int(11) DEFAULT NULL,
  `banned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `imageable_id` int(11) NOT NULL,
  `imageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `infos`
--

CREATE TABLE `infos` (
  `id` int(10) UNSIGNED NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` text COLLATE utf8mb4_unicode_ci,
  `phone` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'img/demo/avatars/avatar-m.png',
  `user_id` int(11) NOT NULL,
  `infosable_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `infos`
--

INSERT INTO `infos` (`id`, `occupation`, `location`, `position`, `phone`, `status`, `avatar`, `user_id`, `infosable_id`, `created_at`, `updated_at`) VALUES
(1, 'Moscow', 'Moscow2', 'manager', '9998877', 1, 'img/demo/avatars/avatar-m.png', 1, 1, NULL, '2021-11-02 04:38:38'),
(2, 'Minsk', 'Minsk', 'provider', '8887766', 2, 'img/demo/avatars/avatar-m.png', 2, 2, NULL, '2021-09-24 03:46:07'),
(3, 'Rome', 'Rome', 'ingener', '7776655', 2, 'img/demo/avatars/avatar-m.png', 3, 3, '2021-09-25 03:01:14', '2021-09-25 03:01:14'),
(4, 'London', 'London', 'manager', '6665544', 2, 'img/demo/avatars/avatar-m.png', 4, 4, '2021-09-27 02:27:28', '2021-09-27 02:27:28'),
(5, 'Paris', 'Paris', 'provider', '5554433', 1, 'img/demo/avatars/avatar-m.png', 5, 5, '2021-09-27 14:21:56', '2021-09-27 14:21:56');

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `messageable_id` int(11) NOT NULL,
  `messageable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2014_10_12_000000_create_users_table', 1),
(14, '2014_10_12_100000_create_password_resets_table', 1),
(15, '2019_08_19_000000_create_failed_jobs_table', 1),
(16, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(17, '2021_08_28_161335_create_posts_table', 1),
(18, '2021_08_28_161437_create_infos_table', 1),
(19, '2021_08_31_063832_create_socials_table', 1),
(20, '2021_09_09_085753_create_chats_table', 1),
(21, '2021_09_09_085818_create_messages_table', 1),
(22, '2021_09_15_135652_create_userlists_table', 1),
(23, '2021_09_15_135814_create_images_table', 1),
(24, '2021_09_15_150503_create_comments_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_post` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_post` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'img/demo/avatars/avatar-m.png',
  `title_post` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `favorites` int(11) DEFAULT NULL,
  `postable_id` int(11) NOT NULL,
  `banned` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `info_id` int(11) NOT NULL,
  `social_id` int(11) NOT NULL,
  `c` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `search_post` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `socials`
--

CREATE TABLE `socials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `telegram` text COLLATE utf8mb4_unicode_ci,
  `vk` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `socials`
--

INSERT INTO `socials` (`id`, `telegram`, `vk`, `instagram`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'otto', 'otto', 'otto', 1, '2021-09-25 07:58:54', '2021-09-25 07:59:27'),
(2, 'viky', 'viky', 'viky', 2, '2021-09-25 07:58:54', '2021-09-25 07:59:27'),
(3, 'vladi', 'vladi', 'vladi', 3, '2021-09-27 17:21:56', '2021-09-27 17:21:56'),
(4, 'Morzav', 'Morzav', 'Morzav', 4, '2021-09-25 06:01:14', '2021-09-25 06:01:14'),
(5, 'sandra', 'sandra', 'sandra', 5, '2021-09-27 05:27:28', '2021-09-27 05:27:28');

-- --------------------------------------------------------

--
-- Структура таблицы `userlists`
--

CREATE TABLE `userlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `info_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `userlistable_id` int(11) DEFAULT NULL,
  `userlistable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_chat` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favorites` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `userlists`
--

INSERT INTO `userlists` (`id`, `created_at`, `updated_at`, `info_id`, `user_id`, `chat_id`, `userlistable_id`, `userlistable_type`, `status_chat`, `name`, `favorites`, `role`) VALUES
(19, NULL, '2021-09-25 04:16:09', 56, 2, 9, 9, 'App\\Models\\Chat', NULL, 'Чат крутого', NULL, 'participant'),
(20, '2021-09-25 03:54:23', '2021-09-25 03:54:23', 56, 56, 9, 9, 'App\\Models\\Chat', NULL, 'Чат крутого', NULL, 'author'),
(21, NULL, '2021-09-28 01:54:10', 1, 1, 1, 1, 'App\\Models\\Chat', NULL, 'Наш чат  Номер  1', NULL, 'moderator'),
(22, NULL, NULL, 56, 56, 1, 1, 'App\\Models\\Chat', NULL, 'Наш чат  Номер  1', NULL, 'participant'),
(23, NULL, NULL, 3, 3, 2, 2, 'App\\Models\\Chat', NULL, 'Чат номер 1', NULL, 'participant'),
(24, NULL, NULL, 6, 6, 2, 2, 'App\\Models\\Chat', NULL, 'Чат номер 1', NULL, 'participant'),
(25, NULL, NULL, 1, 2, 10, 10, 'App\\Models\\Chat', NULL, '555aaaaa', NULL, 'participant'),
(26, NULL, NULL, 1, 3, 10, 10, 'App\\Models\\Chat', NULL, '555aaaaa', NULL, 'participant'),
(27, '2021-11-02 03:50:52', '2021-11-02 03:50:52', 1, 1, 10, 10, 'App\\Models\\Chat', NULL, '555aaaaa', NULL, 'author');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `info_id` int(11) DEFAULT NULL,
  `social_id` int(11) DEFAULT NULL,
  `c` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `search` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `admin`, `remember_token`, `created_at`, `updated_at`, `info_id`, `social_id`, `c`, `search`) VALUES
(1, 'Otto222', 'otto@otto', '2021-09-24 11:42:02', '$2y$10$Xcx12RjGaiHFotbNgpklTu0b.7x0q.gfM7D7Rsd1BS0AE4TQaohR6', 1, NULL, NULL, '2021-11-02 04:38:38', 1, 1, 'c_1', 'otto'),
(2, 'Viky', 'viky@viky', '2021-09-24 12:57:28', '$2y$10$1dDzilC2Mr2P1UVcgna1Ne1tXOgCQE9H2mse4SpDA87/If4P4TOcC', 0, NULL, NULL, '2021-09-24 12:57:28', 2, 2, 'c_2', 'viky'),
(3, 'Morzav', 'morzav@morzav', '2021-09-25 03:02:11', '$2y$10$SBjrYDTvvhsRMp3Wf9YJOOirYk1JpGCN1ygUx4EWZWyIdkbWNiv2m', 1, NULL, '2021-09-25 03:01:14', '2021-09-25 03:02:11', 3, 3, 'c_3', 'morzav'),
(4, 'Sandra', 'sandra@sandra', '2021-09-27 02:28:52', '$2y$10$Wza07TEqTb2kDYth.FIlrOGmK533nU8frbMeLUo/icF59IiobmZV6', NULL, NULL, '2021-09-27 02:27:28', '2021-09-27 02:28:52', 4, 4, 'c_4', 'sandra'),
(5, 'Vladi', 'vladi@vladi', '2021-09-27 14:22:54', '$2y$10$GuW2Wo/jVbjmu0mrAb1bPOQKtLNaen4aXrZlDGXz/uimRNXcPCcky', NULL, NULL, '2021-09-27 14:21:56', '2021-09-27 14:22:54', 5, 5, 'c_5', 'vladi');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `infos`
--
ALTER TABLE `infos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `socials`
--
ALTER TABLE `socials`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `userlists`
--
ALTER TABLE `userlists`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `infos`
--
ALTER TABLE `infos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `socials`
--
ALTER TABLE `socials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT для таблицы `userlists`
--
ALTER TABLE `userlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;
