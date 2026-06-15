-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 14 2026 г., 00:50
-- Версия сервера: 5.7.33-log
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cotton_plantation`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `company_name` varchar(200) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `register_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `login`, `password`, `company_name`, `contact_person`, `phone`, `email`, `address`, `register_date`, `last_login`) VALUES
(1, NULL, NULL, 'ООО \"Текстиль-М\"', 'Иванов Пётр Сергеевич', '+7-495-123-45-67', 'info@textile-m.ru', 'г. Москва, ул. Ленина, д. 10', '2026-03-31 07:48:48', NULL),
(2, NULL, NULL, 'АО \"Хлопок-Трейд\"', 'Петрова Анна Ивановна', '+7-812-234-56-78', 'sales@cotton-trade.ru', 'г. Санкт-Петербург, Невский пр., д. 25', '2026-03-31 07:48:48', NULL),
(3, NULL, NULL, 'ИП Смирнов В.К.', 'Смирнов Владимир Константинович', '+7-843-345-67-89', 'smirnov@mail.ru', 'г. Казань, ул. Баумана, д. 5', '2026-03-31 07:48:48', NULL),
(4, NULL, NULL, 'ООО \"Узбек-Хлопок\"', 'Каримов Рустам Алиевич', '+998-71-123-45-67', 'karimov@uz-cotton.uz', 'г. Ташкент, ул. Амира Темура, д. 15', '2026-03-31 07:48:48', NULL),
(5, NULL, NULL, 'ЗАО \"Ткань-Плюс\"', 'Козлова Елена Дмитриевна', '+7-343-456-78-90', 'kozlova@tkan-plus.ru', 'г. Екатеринбург, ул. Малышева, д. 30', '2026-03-31 07:48:48', NULL),
(6, 'test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ООО \"Тест-Клиент\"', NULL, '+7-111-111-11-11', 'test@test.ru', 'г. Москва, ул. Тестовая, 1', '2026-03-31 07:48:48', '2026-03-31 09:36:00'),
(7, 'idiot', '$2y$10$10kSdXRIDmJOKAPyEiPBcuJoOWocgvcEdXYjype7YZgj4G.dEQ9G2', 'ООО \"Тмыв денег\"', NULL, '+7-111-111-11-11', 'abcde777776@gmail.com', 'улица пушкина дом колотушкина', '2026-03-31 08:23:39', '2026-03-31 15:26:01');

-- --------------------------------------------------------

--
-- Структура таблицы `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `cotton_type_id` int(11) DEFAULT NULL,
  `contract_date` date DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `contracts`
--

INSERT INTO `contracts` (`id`, `client_id`, `employee_id`, `cotton_type_id`, `contract_date`, `total_amount`) VALUES
(1, 1, 1, 1, '2023-05-15', '2500000.00'),
(2, 2, 2, 2, '2023-06-20', '3800000.00'),
(3, 1, 1, 3, '2022-11-10', '1900000.00'),
(4, 3, 2, 1, '2023-08-05', '1200000.00'),
(5, 4, 1, 4, '2023-09-12', '2800000.00'),
(6, 5, 2, 2, '2023-10-01', '3200000.00'),
(7, 2, 1, 5, '2022-12-15', '2100000.00'),
(8, 3, 2, 1, '2023-07-22', '1500000.00');

-- --------------------------------------------------------

--
-- Структура таблицы `contract_items`
--

CREATE TABLE `contract_items` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `cotton_type_id` int(11) DEFAULT NULL,
  `quantity_tons` decimal(10,2) DEFAULT NULL,
  `price_at_sale` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `contract_items`
--

INSERT INTO `contract_items` (`id`, `contract_id`, `cotton_type_id`, `quantity_tons`, `price_at_sale`) VALUES
(1, 1, 1, '10.00', '150000.00'),
(2, 1, 2, '5.00', '180000.00'),
(3, 2, 2, '15.00', '180000.00'),
(4, 2, 1, '8.00', '150000.00'),
(5, 3, 3, '12.00', '130000.00'),
(6, 4, 1, '7.00', '150000.00'),
(7, 5, 4, '14.00', '165000.00'),
(8, 5, 1, '5.00', '150000.00'),
(9, 6, 2, '12.00', '180000.00'),
(10, 6, 5, '6.00', '145000.00'),
(11, 7, 5, '10.00', '145000.00'),
(12, 8, 1, '8.00', '150000.00');

-- --------------------------------------------------------

--
-- Структура таблицы `cotton_types`
--

CREATE TABLE `cotton_types` (
  `id` int(11) NOT NULL,
  `sort_name` varchar(100) NOT NULL,
  `fiber_length` decimal(5,2) DEFAULT NULL,
  `price_per_ton` decimal(10,2) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `cotton_types`
--

INSERT INTO `cotton_types` (`id`, `sort_name`, `fiber_length`, `price_per_ton`, `description`) VALUES
(1, 'Бухарский-3', '35.50', '165000.00', 'Средневолокнистый хлопок, устойчив к засухе'),
(2, 'Андижан-38', '38.20', '180000.00', 'Длинноволокнистый хлопок премиум-класса'),
(3, 'Наманган-77', '32.00', '143000.00', 'Коротковолокнистый хлопок, раннеспелый'),
(4, 'Сурхан-9', '36.80', '165000.00', 'Средневолокнистый, высокая урожайность'),
(5, 'Хорезм-12', '34.00', '105000.00', 'Универсальный сорт, адаптирован к местным условиям');

-- --------------------------------------------------------

--
-- Структура таблицы `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `position`, `hire_date`, `phone`) VALUES
(1, 'Козлов Дмитрий Андреевич', 'Менеджер по продажам', '2020-01-15', '+7-999-111-22-33'),
(2, 'Николаева Елена Владимировна', 'Старший менеджер', '2019-06-20', '+7-999-222-33-44'),
(3, 'Ахмедов Рустам Ильич', 'Агроном', '2021-03-10', '+7-999-333-44-55'),
(4, 'Иванова Мария Петровна', 'Бухгалтер', '2020-09-01', '+7-999-444-55-66'),
(5, 'Сидоров Алексей Николаевич', 'Директор плантации', '2018-01-10', '+7-999-555-66-77');

-- --------------------------------------------------------

--
-- Структура таблицы `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `area_hectares` decimal(10,2) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `fields`
--

INSERT INTO `fields` (`id`, `field_name`, `area_hectares`, `location`) VALUES
(1, 'Поле №1 \"Северное\"', '150.50', 'Сурхандарьинская область, район Шерабад'),
(2, 'Поле №2 \"Южное\"', '200.00', 'Хорезмская область, район Хива'),
(3, 'Поле №3 \"Восточное\"', '175.30', 'Ферганская долина, район Коканд'),
(4, 'Поле №4 \"Западное\"', '180.75', 'Бухарская область, район Каракуль'),
(5, 'Поле №5 \"Центральное\"', '220.00', 'Ташкентская область, район Зангиата');

-- --------------------------------------------------------

--
-- Структура таблицы `harvest`
--

CREATE TABLE `harvest` (
  `id` int(11) NOT NULL,
  `field_id` int(11) DEFAULT NULL,
  `cotton_type_id` int(11) DEFAULT NULL,
  `harvest_year` year(4) DEFAULT NULL,
  `quantity_tons` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `harvest`
--

INSERT INTO `harvest` (`id`, `field_id`, `cotton_type_id`, `harvest_year`, `quantity_tons`) VALUES
(1, 1, 1, 2023, '45.50'),
(2, 1, 2, 2023, '30.00'),
(3, 2, 1, 2023, '55.00'),
(4, 2, 3, 2022, '40.00'),
(5, 3, 2, 2023, '35.50'),
(6, 3, 4, 2023, '42.00'),
(7, 4, 1, 2022, '38.00'),
(8, 4, 5, 2023, '50.00'),
(9, 5, 2, 2023, '60.00'),
(10, 5, 3, 2022, '45.00');

-- --------------------------------------------------------

--
-- Структура таблицы `harvest_items_link`
--

CREATE TABLE `harvest_items_link` (
  `harvest_id` int(11) NOT NULL,
  `contract_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `log_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `logs`
--

INSERT INTO `logs` (`id`, `action`, `log_date`) VALUES
(1, 'Создание базы данных cotton_plantation', '2024-01-15 07:00:00'),
(2, 'Добавление таблицы clients', '2024-01-15 07:05:00'),
(3, 'Добавление таблицы contracts', '2024-01-15 07:10:00'),
(4, 'Изменение движка таблицы logs на InnoDB', '2024-01-15 08:00:00'),
(5, 'Возврат движка таблицы logs на MyISAM', '2024-01-15 08:30:00'),
(6, 'Отправлено письмо от abcde777776@gmail.com: sdfgh', '2026-03-31 07:27:33'),
(7, 'Отправлено письмо от abcde777776@gmail.com: sdfgh', '2026-03-31 07:27:35'),
(8, 'Отправлено письмо от abcde777776@gmail.com: sdfgh', '2026-03-31 10:02:37'),
(9, 'Отправлено письмо от abcde777776@gmail.com: asdfgth', '2026-03-31 10:11:28'),
(10, 'Отправлено письмо от abcde777776@gmail.com: щдшлгонр', '2026-03-31 11:50:42'),
(11, 'Просмотр каталога сортов хлопка (MySQLi пример)', '2026-04-07 12:52:07'),
(12, 'Просмотр каталога (MySQLi пример)', '2026-04-07 12:53:53'),
(13, 'Просмотр каталога (MySQLi пример)', '2026-04-07 12:54:01'),
(14, 'Просмотр каталога (MySQLi пример)', '2026-04-07 12:54:02'),
(15, 'Просмотр каталога (MySQLi пример)', '2026-04-07 12:54:03');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `author`, `created_at`, `views`) VALUES
(1, 'Начат сбор урожая на поле №5', 'Ожидается рекордный урожай сорта Андижан-38. Планируется собрать более 60 тонн качественного хлопка.', 'Агроном Р. Ахмедов', '2026-03-31 07:48:48', 2),
(2, 'Новые цены на хлопок', 'Снижены цены на все сорта на 5% до конца месяца. Успейте сделать выгодный заказ!', 'Менеджер Д. Козлов', '2026-03-31 07:48:48', 1),
(3, 'Расширение ассортимента', 'Добавлен новый сорт Сурхан-9 с улучшенными характеристиками волокна.', 'Директор А. Сидоров', '2026-03-31 07:48:48', 1),
(4, 'Поздравляем с праздником!', 'Коллектив плантации поздравляет всех с Днём независимости Узбекистана!', 'Администрация', '2026-03-31 07:48:48', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','processing','completed','cancelled') DEFAULT 'new',
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `delivery_address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `order_date`, `status`, `total_amount`, `delivery_address`, `phone`, `email`) VALUES
(1, 6, '2026-03-31 07:53:23', 'processing', '540000.00', 'г. Москва, ул. Тестовая, 1', '+7-111-111-11-11', 'test@test.ru'),
(2, 7, '2026-03-31 08:26:51', 'cancelled', '314635.00', 'улица пушкина дом колотушкина', '+7-111-111-11-11', 'abcde777776@gmail.com');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cotton_type_id` int(11) NOT NULL,
  `quantity_tons` decimal(10,2) DEFAULT '0.00',
  `price_at_sale` decimal(10,2) DEFAULT '0.00',
  `subtotal` decimal(12,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `cotton_type_id`, `quantity_tons`, `price_at_sale`, `subtotal`) VALUES
(1, 1, 2, '3.00', '180000.00', '540000.00'),
(2, 2, 1, '467.00', '150000.00', '70050000.00');

-- --------------------------------------------------------

--
-- Структура таблицы `temp_calculator`
--

CREATE TABLE `temp_calculator` (
  `id` int(11) NOT NULL,
  `cotton_sort` varchar(100) DEFAULT NULL,
  `quantity_tons` decimal(10,2) DEFAULT NULL,
  `price_per_ton` decimal(10,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `visit_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `visits`
--

INSERT INTO `visits` (`id`, `page_name`, `visit_date`, `ip_address`) VALUES
(1, 'index.php', '2026-03-31 07:50:36', '127.0.0.1'),
(2, 'index.php', '2026-03-31 07:50:51', '127.0.0.1'),
(3, 'index.php', '2026-03-31 07:50:52', '127.0.0.1'),
(4, 'index.php', '2026-03-31 07:50:52', '127.0.0.1'),
(5, 'index.php', '2026-03-31 07:50:52', '127.0.0.1'),
(6, 'index.php', '2026-03-31 07:50:52', '127.0.0.1'),
(7, 'index.php', '2026-03-31 07:50:52', '127.0.0.1'),
(8, 'login.php', '2026-03-31 07:51:08', '127.0.0.1'),
(9, 'login.php', '2026-03-31 07:51:34', '127.0.0.1'),
(10, 'login.php', '2026-03-31 07:51:49', '127.0.0.1'),
(11, 'index.php', '2026-03-31 07:51:51', '127.0.0.1'),
(12, 'cabinet.php', '2026-03-31 07:51:54', '127.0.0.1'),
(13, 'index.php', '2026-03-31 07:51:56', '127.0.0.1'),
(14, 'login.php', '2026-03-31 07:51:58', '127.0.0.1'),
(15, 'login.php', '2026-03-31 07:52:08', '127.0.0.1'),
(16, 'login.php', '2026-03-31 07:52:30', '127.0.0.1'),
(17, 'cabinet.php', '2026-03-31 07:52:32', '127.0.0.1'),
(18, 'products.php', '2026-03-31 07:52:38', '127.0.0.1'),
(19, 'cart.php', '2026-03-31 07:52:43', '127.0.0.1'),
(20, 'cart.php', '2026-03-31 07:53:16', '127.0.0.1'),
(21, 'cart.php', '2026-03-31 07:53:17', '127.0.0.1'),
(22, 'cart.php', '2026-03-31 07:53:18', '127.0.0.1'),
(23, 'order.php', '2026-03-31 07:53:21', '127.0.0.1'),
(24, 'order.php', '2026-03-31 07:53:23', '127.0.0.1'),
(25, 'cabinet.php', '2026-03-31 07:53:24', '127.0.0.1'),
(26, 'index.php', '2026-03-31 07:53:29', '127.0.0.1'),
(27, 'registration.php', '2026-03-31 07:53:31', '127.0.0.1'),
(28, 'login.php', '2026-03-31 07:53:32', '127.0.0.1'),
(29, 'login.php', '2026-03-31 07:53:43', '127.0.0.1'),
(30, 'index.php', '2026-03-31 08:04:00', '127.0.0.1'),
(31, 'index.php', '2026-03-31 08:04:01', '127.0.0.1'),
(32, 'login.php', '2026-03-31 08:04:04', '127.0.0.1'),
(33, 'cabinet.php', '2026-03-31 08:04:10', '127.0.0.1'),
(34, 'products.php', '2026-03-31 08:04:19', '127.0.0.1'),
(35, 'gallery.php', '2026-03-31 08:04:22', '127.0.0.1'),
(36, 'products.php', '2026-03-31 08:04:23', '127.0.0.1'),
(37, 'products.php', '2026-03-31 08:04:30', '127.0.0.1'),
(38, 'products.php', '2026-03-31 08:04:34', '127.0.0.1'),
(39, 'products.php', '2026-03-31 08:04:42', '127.0.0.1'),
(40, 'products.php', '2026-03-31 08:04:47', '127.0.0.1'),
(41, 'products.php', '2026-03-31 08:04:55', '127.0.0.1'),
(42, 'products.php', '2026-03-31 08:06:23', '127.0.0.1'),
(43, 'products.php', '2026-03-31 08:06:25', '127.0.0.1'),
(44, 'products.php', '2026-03-31 08:06:28', '127.0.0.1'),
(45, 'gallery.php', '2026-03-31 08:06:30', '127.0.0.1'),
(46, 'products.php', '2026-03-31 08:06:33', '127.0.0.1'),
(47, 'gallery.php', '2026-03-31 08:06:34', '127.0.0.1'),
(48, 'news.php', '2026-03-31 08:06:35', '127.0.0.1'),
(49, 'news.php', '2026-03-31 08:06:38', '127.0.0.1'),
(50, 'news.php', '2026-03-31 08:06:40', '127.0.0.1'),
(51, 'contacts.php', '2026-03-31 08:06:42', '127.0.0.1'),
(52, 'index.php', '2026-03-31 08:06:48', '127.0.0.1'),
(53, 'gallery.php', '2026-03-31 08:06:49', '127.0.0.1'),
(54, 'products.php', '2026-03-31 08:06:50', '127.0.0.1'),
(55, 'products.php', '2026-03-31 08:19:55', '127.0.0.1'),
(56, 'gallery.php', '2026-03-31 08:19:58', '127.0.0.1'),
(57, 'gallery.php', '2026-03-31 08:20:00', '127.0.0.1'),
(58, 'gallery.php', '2026-03-31 08:20:01', '127.0.0.1'),
(59, 'gallery.php', '2026-03-31 08:20:01', '127.0.0.1'),
(60, 'gallery.php', '2026-03-31 08:20:01', '127.0.0.1'),
(61, 'products.php', '2026-03-31 08:20:02', '127.0.0.1'),
(62, 'products.php', '2026-03-31 08:20:04', '127.0.0.1'),
(63, 'products.php', '2026-03-31 08:20:04', '127.0.0.1'),
(64, 'products.php', '2026-03-31 08:20:05', '127.0.0.1'),
(65, 'products.php', '2026-03-31 08:20:06', '127.0.0.1'),
(66, 'products.php', '2026-03-31 08:20:06', '127.0.0.1'),
(67, 'gallery.php', '2026-03-31 08:20:09', '127.0.0.1'),
(68, 'gallery.php', '2026-03-31 08:20:24', '127.0.0.1'),
(69, 'gallery.php', '2026-03-31 08:20:32', '127.0.0.1'),
(70, 'gallery.php', '2026-03-31 08:20:33', '127.0.0.1'),
(71, 'gallery.php', '2026-03-31 08:20:33', '127.0.0.1'),
(72, 'index.php', '2026-03-31 08:20:36', '127.0.0.1'),
(73, 'gallery.php', '2026-03-31 08:20:39', '127.0.0.1'),
(74, 'products.php', '2026-03-31 08:20:52', '127.0.0.1'),
(75, 'products.php', '2026-03-31 08:21:58', '127.0.0.1'),
(76, 'login.php', '2026-03-31 08:22:05', '127.0.0.1'),
(77, 'cabinet.php', '2026-03-31 08:22:13', '127.0.0.1'),
(78, 'index.php', '2026-03-31 08:22:22', '127.0.0.1'),
(79, 'registration.php', '2026-03-31 08:22:28', '127.0.0.1'),
(80, 'registration.php', '2026-03-31 08:23:39', '127.0.0.1'),
(81, 'cabinet.php', '2026-03-31 08:23:41', '127.0.0.1'),
(82, 'index.php', '2026-03-31 08:23:45', '127.0.0.1'),
(83, 'login.php', '2026-03-31 08:23:48', '127.0.0.1'),
(84, 'cabinet.php', '2026-03-31 08:23:56', '127.0.0.1'),
(85, 'index.php', '2026-03-31 08:24:03', '127.0.0.1'),
(86, 'products.php', '2026-03-31 08:24:04', '127.0.0.1'),
(87, 'cart.php', '2026-03-31 08:24:12', '127.0.0.1'),
(88, 'cart.php', '2026-03-31 08:24:23', '127.0.0.1'),
(89, 'cart.php', '2026-03-31 08:26:14', '127.0.0.1'),
(90, 'order.php', '2026-03-31 08:26:18', '127.0.0.1'),
(91, 'order.php', '2026-03-31 08:26:22', '127.0.0.1'),
(92, 'cart.php', '2026-03-31 08:26:42', '127.0.0.1'),
(93, 'cart.php', '2026-03-31 08:26:46', '127.0.0.1'),
(94, 'order.php', '2026-03-31 08:26:49', '127.0.0.1'),
(95, 'order.php', '2026-03-31 08:26:51', '127.0.0.1'),
(96, 'products.php', '2026-03-31 08:26:54', '127.0.0.1'),
(97, 'cart.php', '2026-03-31 08:26:56', '127.0.0.1'),
(98, 'cabinet.php', '2026-03-31 08:26:57', '127.0.0.1'),
(99, 'index.php', '2026-03-31 08:31:36', '127.0.0.1'),
(100, 'index.php', '2026-03-31 08:31:38', '127.0.0.1'),
(101, 'products.php', '2026-03-31 08:31:42', '127.0.0.1'),
(102, 'gallery.php', '2026-03-31 08:31:43', '127.0.0.1'),
(103, 'products.php', '2026-03-31 08:31:48', '127.0.0.1'),
(104, 'gallery.php', '2026-03-31 08:31:52', '127.0.0.1'),
(105, 'index.php', '2026-03-31 09:20:10', '127.0.0.1'),
(106, 'products.php', '2026-03-31 09:20:36', '127.0.0.1'),
(107, 'gallery.php', '2026-03-31 09:21:31', '127.0.0.1'),
(108, 'news.php', '2026-03-31 09:22:19', '127.0.0.1'),
(109, 'login.php', '2026-03-31 09:23:27', '127.0.0.1'),
(110, 'cabinet.php', '2026-03-31 09:23:35', '127.0.0.1'),
(111, 'contacts.php', '2026-03-31 09:23:44', '127.0.0.1'),
(112, 'news.php', '2026-03-31 09:29:26', '127.0.0.1'),
(113, 'contacts.php', '2026-03-31 09:29:33', '127.0.0.1'),
(114, 'index.php', '2026-03-31 09:29:37', '127.0.0.1'),
(115, 'contacts.php', '2026-03-31 09:29:39', '127.0.0.1'),
(116, 'products.php', '2026-03-31 09:33:49', '127.0.0.1'),
(117, 'login.php', '2026-03-31 09:33:51', '127.0.0.1'),
(118, 'cabinet.php', '2026-03-31 09:36:00', '127.0.0.1'),
(119, 'contacts.php', '2026-03-31 10:04:32', '127.0.0.1'),
(120, 'contacts.php', '2026-03-31 10:04:39', '127.0.0.1'),
(121, 'contacts.php', '2026-03-31 10:27:33', '127.0.0.1'),
(122, 'contacts.php', '2026-03-31 10:27:35', '127.0.0.1'),
(123, 'contacts.php', '2026-03-31 13:02:37', '127.0.0.1'),
(124, 'index.php', '2026-03-31 13:02:40', '127.0.0.1'),
(125, 'index.php', '2026-03-31 13:10:51', '127.0.0.1'),
(126, 'products.php', '2026-03-31 13:10:59', '127.0.0.1'),
(127, 'gallery.php', '2026-03-31 13:11:03', '127.0.0.1'),
(128, 'news.php', '2026-03-31 13:11:04', '127.0.0.1'),
(129, 'contacts.php', '2026-03-31 13:11:08', '127.0.0.1'),
(130, 'contacts.php', '2026-03-31 13:11:28', '127.0.0.1'),
(131, 'index.php', '2026-03-31 13:11:57', '127.0.0.1'),
(132, 'index.php', '2026-03-31 13:14:04', '127.0.0.1'),
(133, 'index.php', '2026-03-31 14:50:25', '127.0.0.1'),
(134, 'products.php', '2026-03-31 14:50:28', '127.0.0.1'),
(135, 'contacts.php', '2026-03-31 14:50:33', '127.0.0.1'),
(136, 'index.php', '2026-03-31 14:50:39', '127.0.0.1'),
(137, 'contacts.php', '2026-03-31 14:50:42', '127.0.0.1'),
(138, 'news.php', '2026-03-31 14:52:02', '127.0.0.1'),
(139, 'gallery.php', '2026-03-31 14:52:17', '127.0.0.1'),
(140, 'products.php', '2026-03-31 14:52:28', '127.0.0.1'),
(141, 'index.php', '2026-03-31 14:52:29', '127.0.0.1'),
(142, 'products.php', '2026-03-31 14:52:40', '127.0.0.1'),
(143, 'cart.php', '2026-03-31 14:52:55', '127.0.0.1'),
(144, 'order.php', '2026-03-31 14:52:58', '127.0.0.1'),
(145, 'order.php', '2026-03-31 14:53:01', '127.0.0.1'),
(146, 'cart.php', '2026-03-31 14:53:10', '127.0.0.1'),
(147, 'cart.php', '2026-03-31 14:53:12', '127.0.0.1'),
(148, 'index.php', '2026-03-31 14:53:15', '127.0.0.1'),
(149, 'login.php', '2026-03-31 14:53:17', '127.0.0.1'),
(150, 'registration.php', '2026-03-31 14:53:29', '127.0.0.1'),
(151, 'login.php', '2026-03-31 14:53:32', '127.0.0.1'),
(152, 'cabinet.php', '2026-03-31 14:53:38', '127.0.0.1'),
(153, 'index.php', '2026-03-31 14:53:56', '127.0.0.1'),
(154, 'gallery.php', '2026-03-31 15:17:14', '127.0.0.1'),
(155, 'products.php', '2026-03-31 15:17:19', '127.0.0.1'),
(156, 'gallery.php', '2026-03-31 15:17:21', '127.0.0.1'),
(157, 'products.php', '2026-03-31 15:17:25', '127.0.0.1'),
(158, 'cart.php', '2026-03-31 15:17:50', '127.0.0.1'),
(159, 'cart.php', '2026-03-31 15:17:58', '127.0.0.1'),
(160, 'index.php', '2026-03-31 15:18:04', '127.0.0.1'),
(161, 'index.php', '2026-03-31 15:21:31', '127.0.0.1'),
(162, 'products.php', '2026-03-31 15:22:17', '127.0.0.1'),
(163, 'products.php', '2026-03-31 15:23:28', '127.0.0.1'),
(164, 'products.php', '2026-03-31 15:23:32', '127.0.0.1'),
(165, 'products.php', '2026-03-31 15:23:38', '127.0.0.1'),
(166, 'products.php', '2026-03-31 15:24:25', '127.0.0.1'),
(167, 'products.php', '2026-03-31 15:24:30', '127.0.0.1'),
(168, 'gallery.php', '2026-03-31 15:24:32', '127.0.0.1'),
(169, 'news.php', '2026-03-31 15:25:05', '127.0.0.1'),
(170, 'news.php', '2026-03-31 15:25:17', '127.0.0.1'),
(171, 'news.php', '2026-03-31 15:25:21', '127.0.0.1'),
(172, 'news.php', '2026-03-31 15:25:23', '127.0.0.1'),
(173, 'news.php', '2026-03-31 15:25:24', '127.0.0.1'),
(174, 'news.php', '2026-03-31 15:25:27', '127.0.0.1'),
(175, 'news.php', '2026-03-31 15:25:29', '127.0.0.1'),
(176, 'news.php', '2026-03-31 15:25:30', '127.0.0.1'),
(177, 'contacts.php', '2026-03-31 15:25:32', '127.0.0.1'),
(178, 'index.php', '2026-03-31 15:25:50', '127.0.0.1'),
(179, 'login.php', '2026-03-31 15:25:53', '127.0.0.1'),
(180, 'cabinet.php', '2026-03-31 15:26:01', '127.0.0.1'),
(181, 'products.php', '2026-03-31 15:26:31', '127.0.0.1'),
(182, 'cart.php', '2026-03-31 15:26:37', '127.0.0.1'),
(183, 'products.php', '2026-03-31 15:26:40', '127.0.0.1'),
(184, 'cart.php', '2026-03-31 15:26:45', '127.0.0.1'),
(185, 'order.php', '2026-03-31 15:26:54', '127.0.0.1'),
(186, 'order.php', '2026-03-31 15:27:09', '127.0.0.1'),
(187, 'cabinet.php', '2026-03-31 15:27:12', '127.0.0.1'),
(188, 'index.php', '2026-03-31 15:28:20', '127.0.0.1'),
(189, 'registration.php', '2026-03-31 15:28:21', '127.0.0.1'),
(190, 'registration.php', '2026-03-31 15:29:56', '127.0.0.1'),
(191, 'registration.php', '2026-03-31 15:30:14', '127.0.0.1'),
(192, 'registration.php', '2026-03-31 15:30:30', '127.0.0.1'),
(193, 'login.php', '2026-03-31 15:30:32', '127.0.0.1'),
(194, 'cabinet.php', '2026-03-31 15:30:42', '127.0.0.1'),
(195, 'products.php', '2026-03-31 15:30:55', '127.0.0.1'),
(196, 'gallery.php', '2026-03-31 15:31:02', '127.0.0.1'),
(197, 'cabinet.php', '2026-03-31 15:31:10', '127.0.0.1'),
(198, 'index.php', '2026-03-31 15:58:31', '127.0.0.1'),
(199, 'registration.php', '2026-03-31 15:58:33', '127.0.0.1'),
(200, 'index.php', '2026-03-31 15:59:09', '127.0.0.1'),
(201, 'login.php', '2026-03-31 15:59:11', '127.0.0.1'),
(202, 'cabinet.php', '2026-03-31 15:59:13', '127.0.0.1'),
(203, 'gallery.php', '2026-03-31 16:02:18', '127.0.0.1'),
(204, 'gallery.php', '2026-03-31 16:02:34', '127.0.0.1'),
(205, 'index.php', '2026-03-31 16:02:46', '127.0.0.1'),
(206, 'gallery.php', '2026-03-31 16:02:48', '127.0.0.1'),
(207, 'registration.php', '2026-03-31 16:03:21', '127.0.0.1'),
(208, 'index.php', '2026-04-06 08:29:12', '127.0.0.1'),
(209, 'registration.php', '2026-04-06 08:29:38', '127.0.0.1'),
(210, 'registration.php', '2026-04-06 08:33:49', '127.0.0.1'),
(211, 'registration.php', '2026-04-06 08:34:36', '127.0.0.1'),
(212, 'registration.php', '2026-04-06 08:34:59', '127.0.0.1'),
(213, 'index.php', '2026-04-06 10:05:19', '127.0.0.1'),
(214, 'registration.php', '2026-04-06 10:05:22', '127.0.0.1'),
(215, 'registration.php', '2026-04-06 10:06:04', '127.0.0.1'),
(216, 'registration.php', '2026-04-06 10:06:31', '127.0.0.1'),
(217, 'registration.php', '2026-04-06 10:07:02', '127.0.0.1'),
(218, 'registration.php', '2026-04-06 10:08:38', '127.0.0.1'),
(219, 'registration.php', '2026-04-06 10:21:42', '127.0.0.1'),
(220, 'registration.php', '2026-04-06 10:21:43', '127.0.0.1'),
(221, 'registration.php', '2026-04-06 10:21:43', '127.0.0.1'),
(222, 'registration.php', '2026-04-06 10:22:01', '127.0.0.1'),
(223, 'registration.php', '2026-04-06 10:22:16', '127.0.0.1'),
(224, 'registration.php', '2026-04-06 10:22:25', '127.0.0.1'),
(225, 'registration.php', '2026-04-06 10:22:28', '127.0.0.1'),
(226, 'registration.php', '2026-04-06 10:23:22', '127.0.0.1'),
(227, 'registration.php', '2026-04-06 10:23:50', '127.0.0.1'),
(228, 'login.php', '2026-04-06 10:23:53', '127.0.0.1'),
(229, 'login.php', '2026-04-06 10:24:06', '127.0.0.1'),
(230, 'login.php', '2026-04-06 10:24:16', '127.0.0.1'),
(231, 'login.php', '2026-04-06 10:24:20', '127.0.0.1'),
(232, 'login.php', '2026-04-07 15:17:24', '127.0.0.1'),
(233, 'index.php', '2026-04-07 15:17:26', '127.0.0.1'),
(234, 'registration.php', '2026-04-07 15:17:28', '127.0.0.1'),
(235, 'registration.php', '2026-04-07 15:31:41', '127.0.0.1'),
(236, 'registration.php', '2026-04-07 15:32:19', '127.0.0.1'),
(237, 'registration.php', '2026-04-07 15:33:09', '127.0.0.1'),
(238, 'registration.php', '2026-04-07 15:35:32', '127.0.0.1'),
(239, 'index.php', '2026-04-07 15:36:03', '127.0.0.1'),
(240, 'login.php', '2026-04-07 15:36:10', '127.0.0.1'),
(241, 'registration.php', '2026-04-07 15:36:13', '127.0.0.1'),
(242, 'index.php', '2026-04-13 12:41:09', '127.0.0.1'),
(243, 'index.php', '2026-04-13 13:03:30', '127.0.0.1'),
(244, 'index.php', '2026-04-14 13:17:05', '127.0.0.1'),
(245, 'index.php', '2026-04-14 13:36:17', '127.0.0.1'),
(246, 'index.php', '2026-04-14 15:22:57', '127.0.0.1'),
(247, 'contacts.php', '2026-04-14 15:23:01', '127.0.0.1'),
(248, 'index.php', '2026-04-14 15:23:04', '127.0.0.1'),
(249, 'index.php', '2026-04-14 20:38:31', '127.0.0.1'),
(250, 'index.php', '2026-05-12 16:12:48', '127.0.0.1'),
(251, 'login.php', '2026-05-12 16:12:50', '127.0.0.1'),
(252, 'index.php', '2026-05-12 16:12:55', '127.0.0.1'),
(253, 'index.php', '2026-06-14 00:27:37', '127.0.0.1'),
(254, 'gallery.php', '2026-06-14 00:27:45', '127.0.0.1'),
(255, 'index.php', '2026-06-14 00:27:48', '127.0.0.1'),
(256, 'contacts.php', '2026-06-14 00:27:53', '127.0.0.1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `cotton_type_id` (`cotton_type_id`);

--
-- Индексы таблицы `contract_items`
--
ALTER TABLE `contract_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `contract_items_ibfk_2` (`cotton_type_id`);

--
-- Индексы таблицы `cotton_types`
--
ALTER TABLE `cotton_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `harvest`
--
ALTER TABLE `harvest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `cotton_type_id` (`cotton_type_id`);

--
-- Индексы таблицы `harvest_items_link`
--
ALTER TABLE `harvest_items_link`
  ADD PRIMARY KEY (`harvest_id`,`contract_item_id`),
  ADD KEY `contract_item_id` (`contract_item_id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `cotton_type_id` (`cotton_type_id`);

--
-- Индексы таблицы `temp_calculator`
--
ALTER TABLE `temp_calculator`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `contract_items`
--
ALTER TABLE `contract_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `cotton_types`
--
ALTER TABLE `cotton_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `harvest`
--
ALTER TABLE `harvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `temp_calculator`
--
ALTER TABLE `temp_calculator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`cotton_type_id`) REFERENCES `cotton_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `contract_items`
--
ALTER TABLE `contract_items`
  ADD CONSTRAINT `contract_items_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `contract_items_ibfk_2` FOREIGN KEY (`cotton_type_id`) REFERENCES `cotton_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `harvest`
--
ALTER TABLE `harvest`
  ADD CONSTRAINT `harvest_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`),
  ADD CONSTRAINT `harvest_ibfk_2` FOREIGN KEY (`cotton_type_id`) REFERENCES `cotton_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `harvest_items_link`
--
ALTER TABLE `harvest_items_link`
  ADD CONSTRAINT `hil_ibfk_1` FOREIGN KEY (`harvest_id`) REFERENCES `harvest` (`id`),
  ADD CONSTRAINT `hil_ibfk_2` FOREIGN KEY (`contract_item_id`) REFERENCES `contract_items` (`id`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`cotton_type_id`) REFERENCES `cotton_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
