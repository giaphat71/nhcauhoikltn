-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 24, 2022 lúc 10:40 AM
-- Phiên bản máy phục vụ: 10.4.17-MariaDB
-- Phiên bản PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `nganhangcauhoi`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhoi`
--

CREATE TABLE `cauhoi` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `idmonhoc` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `text` text NOT NULL DEFAULT '',
  `data` text NOT NULL DEFAULT '{}' COMMENT 'json',
  `tieuchi` text NOT NULL DEFAULT '[]' COMMENT 'json',
  `addtime` datetime NOT NULL DEFAULT current_timestamp(),
  `isshare` int(11) NOT NULL DEFAULT 0,
  `lastmodify` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `cauhoi`
--

INSERT INTO `cauhoi` (`id`, `type`, `idmonhoc`, `author`, `text`, `data`, `tieuchi`, `addtime`, `isshare`, `lastmodify`) VALUES
(54, 'choosethebest', 1, 1, '<p>CodeIgniter l&agrave; g&igrave;</p>', '{\"answer\":[\"Framework cho php\",\"Framework cho js\",\"Th\\u01b0 vi\\u1ec7n cho php\",\"Th\\u01b0 vi\\u1ec7n giao di\\u1ec7n html\"],\"rightanswer\":\"Framework cho php\"}', '[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]', '2022-06-10 10:32:33', 0, '2022-06-10 10:32:33'),
(56, 'checkalltrue', 1, 1, '<p>Gấu tr&uacute;c ăn ....&nbsp;</p>', '{\"answer\":[\"C\\u1ecf\",\"Tr\\u00fac\",\"C\\u00e2y\",\"L\\u00e1\"],\"rightanswer\":[\"C\\u1ecf\",\"Tr\\u00fac\"]}', '[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]', '2022-06-16 00:11:58', 0, '2022-06-16 00:11:58'),
(57, 'putinplace', 1, 1, '<p>Con g&agrave; hay <strong>quả trứng</strong> c&oacute; trước</p>', '{\"answer\":[\"qu\\u1ea3 tr\\u1ee9ng\"]}', '[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]', '2022-06-16 01:03:57', 0, '2022-06-16 01:03:57'),
(58, 'askandanswer', 1, 1, '<p>Con g&agrave; hay quả trứng c&oacute; trước</p>', '{\"answer\":\"Con g\\u00e0\"}', '[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]', '2022-06-16 01:29:02', 0, '2022-06-16 01:29:02'),
(59, 'connectpair', 1, 1, '<p>nối 3 chữ</p>', '{\"ansleft\":[\"abc\",\"def\",\"xyz\",\"ghk\"],\"ansright\":[\"cba\",\"fed\",\"zxy\",\"khg\"],\"pair\":[[\"abc\",\"cba\"],[\"def\",\"fed\"],[\"xyz\",\"zxy\"],[\"ghk\",\"khg\"]]}', '[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]', '2022-06-16 08:11:33', 0, '2022-06-16 08:11:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `addtime` datetime NOT NULL DEFAULT current_timestamp(),
  `content` text NOT NULL DEFAULT '',
  `datarollback` text NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `history`
--

INSERT INTO `history` (`id`, `author`, `action`, `addtime`, `content`, `datarollback`) VALUES
(1, 1, 'saveperm', '2022-06-14 16:37:00', 'Phát CNTT đã sửa quyền cho <a href=\"/admin/canbo/1/quyen\">/admin/canbo/1/quyen</a>Phát CNTT[/url].', ''),
(2, 1, 'addquestion', '2022-06-15 23:46:39', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/55\">/canbo/cauhoi/1/55</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(3, 1, 'addquestion', '2022-06-16 00:11:58', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/56\">/canbo/cauhoi/1/56</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(4, 1, 'addquestion', '2022-06-16 01:03:57', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/57\">/canbo/cauhoi/1/57</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(5, 1, 'addquestion', '2022-06-16 01:29:02', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/58\">/canbo/cauhoi/1/58</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(6, 1, 'deletequest', '2022-06-16 01:31:42', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/53\">/canbo/cauhoi/1/53</a>câu hỏi[/url].', '{\"id\":\"53\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi<\\/p>\",\"data\":{\"answer\":[\"\\u0110\\u00e1p \\u00e1n 1\",\"\\u0110\\u00e1p \\u00e1n 2\",\"\\u0110\\u00e1p \\u00e1n 3\",\"\\u0110\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0110\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"5\"},{\"slugname\":\"thu-nghiem\",\"value\":\"\"}],\"addtime\":\"2022-06-10 10:08:57\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 10:08:57\"}'),
(7, 1, 'deletequest', '2022-06-16 01:31:46', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/52\">/canbo/cauhoi/1/52</a>câu hỏi[/url].', '{\"id\":\"52\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi c&aacute;c \\u0111&aacute;p &aacute;n \\u0111\\u1ec1u \\u0111&uacute;ng<\\/p>\",\"data\":{\"answer\":[\"\\u0110\\u00e1p \\u00e1n 1\",\"\\u0110\\u00e1p \\u00e1n 2\",\"\\u0110\\u00e1p \\u00e1n 3\",\"A,B,C \\u0111\\u1ec1u \\u0111\\u00fang\"],\"rightanswer\":\"A,B,C \\u0111\\u1ec1u \\u0111\\u00fang\",\"lockposition\":true},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"10\"}],\"addtime\":\"2022-06-10 04:21:36\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:21:36\"}'),
(8, 1, 'deletequest', '2022-06-16 01:31:47', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/51\">/canbo/cauhoi/1/51</a>câu hỏi[/url].', '{\"id\":\"51\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 5 \\u0111\\u1ed9 kh&oacute; 3<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-10 04:12:55\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:55\"}'),
(9, 1, 'deletequest', '2022-06-16 01:31:48', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/50\">/canbo/cauhoi/1/50</a>câu hỏi[/url].', '{\"id\":\"50\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 4 \\u0111\\u1ed9 kh&oacute; 3<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-10 04:12:46\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:46\"}'),
(10, 1, 'deletequest', '2022-06-16 01:31:49', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/49\">/canbo/cauhoi/1/49</a>câu hỏi[/url].', '{\"id\":\"49\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 3 \\u0111\\u1ed9 kh&oacute; 3<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-10 04:12:38\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:38\"}'),
(11, 1, 'deletequest', '2022-06-16 01:31:50', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/48\">/canbo/cauhoi/1/48</a>câu hỏi[/url].', '{\"id\":\"48\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 2 \\u0111\\u1ed9 kh&oacute; 3<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-10 04:12:31\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:31\"}'),
(12, 1, 'deletequest', '2022-06-16 01:31:51', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/47\">/canbo/cauhoi/1/47</a>câu hỏi[/url].', '{\"id\":\"47\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 1 \\u0111\\u1ed9 kh&oacute; 3<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-10 04:12:25\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:25\"}'),
(13, 1, 'deletequest', '2022-06-16 01:31:52', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/46\">/canbo/cauhoi/1/46</a>câu hỏi[/url].', '{\"id\":\"46\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 5 \\u0111\\u1ed9 kh&oacute; 2<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"2\"}],\"addtime\":\"2022-06-10 04:12:14\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:14\"}'),
(14, 1, 'deletequest', '2022-06-16 01:31:54', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/45\">/canbo/cauhoi/1/45</a>câu hỏi[/url].', '{\"id\":\"45\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 4 \\u0111\\u1ed9 kh&oacute; 2<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"2\"}],\"addtime\":\"2022-06-10 04:12:09\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:09\"}'),
(15, 1, 'deletequest', '2022-06-16 01:31:56', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/44\">/canbo/cauhoi/1/44</a>câu hỏi[/url].', '{\"id\":\"44\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 3 \\u0111\\u1ed9 kh&oacute; 2<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"2\"}],\"addtime\":\"2022-06-10 04:12:02\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:12:02\"}'),
(16, 1, 'deletequest', '2022-06-16 01:31:58', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/43\">/canbo/cauhoi/1/43</a>câu hỏi[/url].', '{\"id\":\"43\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 2 \\u0111\\u1ed9 kh&oacute; 2<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"2\"}],\"addtime\":\"2022-06-10 04:11:54\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:54\"}'),
(17, 1, 'deletequest', '2022-06-16 01:31:59', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/42\">/canbo/cauhoi/1/42</a>câu hỏi[/url].', '{\"id\":\"42\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 1 \\u0111\\u1ed9 kh&oacute; 2<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"2\"}],\"addtime\":\"2022-06-10 04:11:45\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:45\"}'),
(18, 1, 'deletequest', '2022-06-16 01:32:00', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/41\">/canbo/cauhoi/1/41</a>câu hỏi[/url].', '{\"id\":\"41\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 5 \\u0111\\u1ed9 kh&oacute; 1<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-06-10 04:11:35\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:35\"}'),
(19, 1, 'deletequest', '2022-06-16 01:32:01', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/40\">/canbo/cauhoi/1/40</a>câu hỏi[/url].', '{\"id\":\"40\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 4 \\u0111\\u1ed9 kh&oacute; 1<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-06-10 04:11:27\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:27\"}'),
(20, 1, 'deletequest', '2022-06-16 01:32:02', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/39\">/canbo/cauhoi/1/39</a>câu hỏi[/url].', '{\"id\":\"39\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 3 \\u0111\\u1ed9 kh&oacute; 1<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-06-10 04:11:19\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:19\"}'),
(21, 1, 'deletequest', '2022-06-16 01:32:06', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/5\">/canbo/cauhoi/1/5</a>câu hỏi[/url].', '{\"id\":\"5\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng m\\u1ed9t b\\u1eb1ng m\\u1ea5y?<\\/p>\",\"data\":{\"answer\":[\"123\",\"456\",\"789\",\"9809\"],\"rightanswer\":\"123\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"},{\"slugname\":\"nhap-mon\",\"value\":\"\"}],\"addtime\":\"2022-03-23 09:41:32\",\"isshare\":\"0\",\"lastmodify\":\"2022-03-23 09:41:32\"}'),
(22, 1, 'deletequest', '2022-06-16 01:32:07', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/4\">/canbo/cauhoi/1/4</a>câu hỏi[/url].', '{\"id\":\"4\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng m\\u1ed9t b\\u1eb1ng m\\u1ea5y?<\\/p>\",\"data\":{\"answer\":[\"456\",\"4567\",\"56\",\"657\"],\"rightanswer\":\"456\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"},{\"slugname\":\"nhap-mon\",\"value\":\"\"}],\"addtime\":\"2022-03-23 09:41:18\",\"isshare\":\"0\",\"lastmodify\":\"2022-03-23 09:41:18\"}'),
(23, 1, 'deletequest', '2022-06-16 01:32:08', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/3\">/canbo/cauhoi/1/3</a>câu hỏi[/url].', '{\"id\":\"3\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng m\\u1ed9t b\\u1eb1ng m\\u1ea5y?<\\/p>\",\"data\":{\"answer\":[\"2\",\"3\",\"4\",\"5\"],\"rightanswer\":\"2\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"},{\"slugname\":\"nhap-mon\",\"value\":\"\"}],\"addtime\":\"2022-03-23 09:41:05\",\"isshare\":\"0\",\"lastmodify\":\"2022-03-23 09:41:05\"}'),
(24, 1, 'deletequest', '2022-06-16 01:32:08', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/2\">/canbo/cauhoi/1/2</a>câu hỏi[/url].', '{\"id\":\"2\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng hai b\\u1eb1ng m\\u1ea5y?<\\/p>\",\"data\":{\"answer\":[\"1\",\"2\",\"3\",\"4\"],\"rightanswer\":\"1\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"3\"},{\"slugname\":\"nhap-mon\",\"value\":\"\"}],\"addtime\":\"2022-03-23 05:05:46\",\"isshare\":\"0\",\"lastmodify\":\"2022-03-23 05:05:46\"}'),
(25, 1, 'deletequest', '2022-06-16 01:32:09', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/1\">/canbo/cauhoi/1/1</a>câu hỏi[/url].', '{\"id\":\"1\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng m\\u1ed9t b\\u1eb1ng m\\u1ea5y?<\\/p>\",\"data\":{\"answer\":[\"3\",\"2\"],\"rightanswer\":\"3\"},\"tieuchi\":[{\"slugname\":\"nhap-mon\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"0\"}],\"addtime\":\"2022-03-23 04:37:23\",\"isshare\":\"0\",\"lastmodify\":\"2022-03-23 04:37:23\"}'),
(26, 1, 'deletequest', '2022-06-16 01:32:16', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/31\">/canbo/cauhoi/1/31</a>câu hỏi[/url].', '{\"id\":\"31\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>Tr\\u1ee9ng hay g&agrave; c&oacute; tr\\u01b0\\u1edbc<\\/p>\",\"data\":{\"answer\":[\"G\\u00e0\",\"Tr\\u1ee9ng\"],\"rightanswer\":\"G\\u00e0\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"3\"}],\"addtime\":\"2022-06-09 21:15:11\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-09 21:15:11\"}'),
(27, 1, 'deletequest', '2022-06-16 01:32:17', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/26\">/canbo/cauhoi/1/26</a>câu hỏi[/url].', '{\"id\":\"26\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>M\\u1ed9t c\\u1ed9ng 5 b\\u1eb1ng m\\u1ea5y<\\/p>\",\"data\":{\"answer\":[\"5\",\"6\",\"7\",\"8\"],\"rightanswer\":\"6\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"10\"}],\"addtime\":\"2022-05-19 02:56:01\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 02:56:01\"}'),
(28, 1, 'deletequest', '2022-06-16 01:32:18', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/21\">/canbo/cauhoi/1/21</a>câu hỏi[/url].', '{\"id\":\"21\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 1 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"1\",\"2\",\"3\",\"4\"],\"rightanswer\":\"2\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-05-19 01:44:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:44:06\"}'),
(29, 1, 'deletequest', '2022-06-16 01:32:19', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/22\">/canbo/cauhoi/1/22</a>câu hỏi[/url].', '{\"id\":\"22\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 2 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"2\",\"3\",\"5\",\"4\"],\"rightanswer\":\"3\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-05-19 01:44:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:44:06\"}'),
(30, 1, 'deletequest', '2022-06-16 01:32:19', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/23\">/canbo/cauhoi/1/23</a>câu hỏi[/url].', '{\"id\":\"23\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 3 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"3\",\"4\",\"5\",\"6\"],\"rightanswer\":\"4\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-05-19 01:44:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:44:06\"}'),
(31, 1, 'deletequest', '2022-06-16 01:32:20', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/24\">/canbo/cauhoi/1/24</a>câu hỏi[/url].', '{\"id\":\"24\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 4 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"4\",\"5\",\"6\",\"7\"],\"rightanswer\":\"5\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-05-19 01:44:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:44:06\"}'),
(32, 1, 'deletequest', '2022-06-16 01:32:21', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/25\">/canbo/cauhoi/1/25</a>câu hỏi[/url].', '{\"id\":\"25\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 5 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"5\",\"6\",\"7\",\"8\"],\"rightanswer\":\"6\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-05-19 01:44:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:44:06\"}'),
(33, 1, 'deletequest', '2022-06-16 01:32:26', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/16\">/canbo/cauhoi/1/16</a>câu hỏi[/url].', '{\"id\":\"16\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 1 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"1\",\"2\",\"3\",\"4\"],\"rightanswer\":\"2\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:40:08\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:40:08\"}'),
(34, 1, 'deletequest', '2022-06-16 01:32:27', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/17\">/canbo/cauhoi/1/17</a>câu hỏi[/url].', '{\"id\":\"17\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 2 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"2\",\"3\",\"5\",\"4\"],\"rightanswer\":\"3\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:40:08\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:40:08\"}'),
(35, 1, 'deletequest', '2022-06-16 01:32:27', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/18\">/canbo/cauhoi/1/18</a>câu hỏi[/url].', '{\"id\":\"18\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 3 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"3\",\"4\",\"5\",\"6\"],\"rightanswer\":\"4\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:40:08\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:40:08\"}'),
(36, 1, 'deletequest', '2022-06-16 01:32:29', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/19\">/canbo/cauhoi/1/19</a>câu hỏi[/url].', '{\"id\":\"19\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 4 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"4\",\"5\",\"6\",\"7\"],\"rightanswer\":\"5\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:40:08\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:40:08\"}'),
(37, 1, 'deletequest', '2022-06-16 01:32:29', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/9\">/canbo/cauhoi/1/9</a>câu hỏi[/url].', '{\"id\":\"9\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 4 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"4\",\"5\",\"6\",\"7\"],\"rightanswer\":\"5\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:39:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:39:06\"}'),
(38, 1, 'deletequest', '2022-06-16 01:32:32', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/10\">/canbo/cauhoi/1/10</a>câu hỏi[/url].', '{\"id\":\"10\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"M\\u1ed9t c\\u1ed9ng 5 b\\u1eb1ng m\\u1ea5y\",\"data\":{\"answer\":[\"5\",\"6\",\"7\",\"8\"],\"rightanswer\":\"6\"},\"tieuchi\":[{\"slugname\":null,\"value\":\"\"}],\"addtime\":\"2022-05-19 01:39:06\",\"isshare\":\"0\",\"lastmodify\":\"2022-05-19 01:39:06\"}'),
(39, 1, 'deletequest', '2022-06-16 01:32:35', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/38\">/canbo/cauhoi/1/38</a>câu hỏi[/url].', '{\"id\":\"38\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 2 \\u0111\\u1ed9 kh&oacute; 1<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"1\"}],\"addtime\":\"2022-06-10 04:11:10\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:10\"}'),
(40, 1, 'deletequest', '2022-06-16 01:32:36', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/37\">/canbo/cauhoi/1/37</a>câu hỏi[/url].', '{\"id\":\"37\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>C&acirc;u h\\u1ecfi th\\u1eed nghi\\u1ec7m 1 \\u0111\\u1ed9 kh&oacute; 1<\\/p>\",\"data\":{\"answer\":[\"\\u0111\\u00e1p \\u00e1n 1\",\"\\u0111\\u00e1p \\u00e1n 2\",\"\\u0111\\u00e1p \\u00e1n 3\",\"\\u0111\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0111\\u00e1p \\u00e1n 1\"},\"tieuchi\":[{\"slugname\":\"do-kho\",\"value\":\"1\"},{\"slugname\":\"thu-nghiem\",\"value\":\"\"}],\"addtime\":\"2022-06-10 04:11:01\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-10 04:11:01\"}'),
(41, 1, 'deletequest', '2022-06-16 01:32:44', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/55\">/canbo/cauhoi/1/55</a>câu hỏi[/url].', '{\"id\":\"55\",\"type\":\"checkalltrue\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>dsfsdf<\\/p>\",\"data\":null,\"tieuchi\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}],\"addtime\":\"2022-06-15 23:46:39\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-15 23:46:39\"}'),
(42, 1, 'addquestion', '2022-06-16 08:11:33', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/59\">/canbo/cauhoi/1/59</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(43, 1, 'addquestion', '2022-06-16 08:13:17', 'Phát CNTT thêm 1 <a href=\"/canbo/cauhoi/1/60\">/canbo/cauhoi/1/60</a>câu hỏi[/url] cho môn học Nhập Môn Công Nghệ Thông Tin.', ''),
(44, 1, 'deletequest', '2022-06-16 08:18:51', 'Phát CNTT xóa 1 <a href=\"/canbo/cauhoi/1/60\">/canbo/cauhoi/1/60</a>câu hỏi[/url].', '{\"id\":\"60\",\"type\":\"choosethebest\",\"idmonhoc\":\"1\",\"author\":\"1\",\"text\":\"<p>sdfsdf<\\/p>\",\"data\":{\"answer\":[\"\\u0110\\u00e1p \\u00e1n 1\",\"\\u0110\\u00e1p \\u00e1n 2\",\"\\u0110\\u00e1p \\u00e1n 3\",\"\\u0110\\u00e1p \\u00e1n 4\"],\"rightanswer\":\"\\u0110\\u00e1p \\u00e1n 1\"},\"tieuchi\":[],\"addtime\":\"2022-06-16 08:13:17\",\"isshare\":\"0\",\"lastmodify\":\"2022-06-16 08:13:17\"}'),
(45, 1, 'login', '2022-06-17 11:10:04', 'Quản trị Phát CNTT đăng nhập vào hệ thống.', ''),
(46, 1, 'addmatrix', '2022-06-17 12:41:25', 'Phát CNTT thêm 1 <a href=\"/canbo/matrix/1/8\">ma trận câu hỏi</a> cho môn học Nhập Môn Công Nghệ Thông Tin.', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `matrix`
--

CREATE TABLE `matrix` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `idmonhoc` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `addtime` datetime NOT NULL DEFAULT current_timestamp(),
  `lastupdate` datetime NOT NULL DEFAULT current_timestamp(),
  `questioncount` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL DEFAULT '',
  `totalpoint` int(11) NOT NULL DEFAULT 0,
  `data` text NOT NULL DEFAULT '[]' COMMENT 'json',
  `generated` int(11) NOT NULL DEFAULT 0,
  `isshare` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `matrix`
--

INSERT INTO `matrix` (`id`, `name`, `idmonhoc`, `author`, `addtime`, `lastupdate`, `questioncount`, `description`, `totalpoint`, `data`, `generated`, `isshare`) VALUES
(1, 'Kiểm Tra 15p', 1, 1, '2022-03-24 12:53:08', '2022-06-09 21:16:39', 5, 'kiểm tra 15, chương 1', 10, '[{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"2\",\"tags\":[{\"slugname\":\"do-kho\",\"value\":\"1\"}]},{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"2\",\"tags\":[{\"slugname\":\"do-kho\",\"value\":\"3\"}]},{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"1\",\"tags\":[{\"slugname\":\"do-kho\",\"value\":\"2\"}]}]', 0, 0),
(6, 'Kiểm tra 1 tiết', 1, 1, '2022-06-02 21:16:55', '2022-06-02 21:16:55', 5, 'Kiểm tra 1 tiết', 10, '[{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"5\",\"tags\":[{\"slugname\":\"nhap-mon\",\"value\":\"\"}]}]', 0, 0),
(7, 'Kiểm tra hệ thống', 1, 1, '2022-06-10 04:54:21', '2022-06-10 04:54:24', 15, '', 10, '[{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"5\",\"tags\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"3\"}]},{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"5\",\"tags\":[{\"slugname\":\"do-kho\",\"value\":\"2\"},{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]},{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"5\",\"tags\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"},{\"slugname\":\"do-kho\",\"value\":\"1\"}]}]', 0, 0),
(8, 'test', 1, 1, '2022-06-17 12:41:25', '2022-06-17 12:41:29', 5, '', 10, '[{\"name\":\"Nh\\u00f3m c\\u00e2u h\\u1ecfi \",\"count\":\"5\",\"tags\":[{\"slugname\":\"thu-nghiem\",\"value\":\"\"}]}]', 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `matrixresult`
--

CREATE TABLE `matrixresult` (
  `id` int(11) NOT NULL,
  `idmatrix` int(11) NOT NULL,
  `data` mediumtext NOT NULL,
  `addtime` datetime NOT NULL DEFAULT current_timestamp(),
  `idmonhoc` int(11) NOT NULL,
  `runner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `matrixresult`
--

INSERT INTO `matrixresult` (`id`, `idmatrix`, `data`, `addtime`, `idmonhoc`, `runner`) VALUES
(1, 1, '[[\"5\",\"3\",\"26\",\"24\",\"23\",\"4\",\"21\",\"22\",\"25\"]]', '2022-06-02 15:36:30', 1, 1),
(2, 1, '[[\"4\",\"22\",\"5\",\"26\",\"21\",\"23\",\"3\",\"25\",\"24\"]]', '2022-06-02 15:36:34', 1, 1),
(3, 1, '[[\"24\",\"23\",\"22\",\"5\",\"4\",\"21\",\"26\",\"3\",\"25\"]]', '2022-06-02 15:37:22', 1, 1),
(4, 1, '[[\"25\",\"21\",\"4\",\"5\",\"24\",\"22\",\"23\",\"3\",\"26\"]]', '2022-06-02 15:38:10', 1, 1),
(5, 1, '[[\"4\",\"3\",\"5\",\"26\",\"21\",\"22\",\"25\",\"24\",\"23\"]]', '2022-06-02 15:40:46', 1, 1),
(6, 1, '[[\"4\",\"23\",\"25\",\"21\",\"26\",\"5\",\"22\",\"24\",\"3\"]]', '2022-06-02 17:57:46', 1, 1),
(7, 1, '[[\"5\",\"23\",\"4\",\"26\",\"21\",\"24\",\"3\",\"25\",\"22\"]]', '2022-06-02 17:57:47', 1, 1),
(8, 1, '[[\"21\",\"26\",\"24\",\"4\",\"3\",\"25\",\"23\",\"5\",\"22\"]]', '2022-06-02 17:57:48', 1, 1),
(9, 1, '[[\"22\",\"5\",\"24\",\"23\",\"4\",\"21\",\"25\",\"26\",\"3\"]]', '2022-06-02 17:57:48', 1, 1),
(10, 1, '[[\"23\",\"25\",\"5\",\"24\",\"3\",\"26\",\"21\",\"22\",\"4\"]]', '2022-06-02 17:57:49', 1, 1),
(11, 1, '[[\"3\",\"5\",\"21\",\"24\",\"22\",\"23\",\"25\",\"4\",\"26\"]]', '2022-06-02 17:57:49', 1, 1),
(12, 1, '[[\"5\",\"26\",\"3\",\"24\",\"4\",\"22\",\"21\",\"23\",\"25\"]]', '2022-06-02 17:57:50', 1, 1),
(13, 1, '[[\"3\",\"4\",\"5\",\"26\",\"22\",\"25\",\"21\",\"23\",\"24\"]]', '2022-06-02 17:57:50', 1, 1),
(14, 1, '[[\"23\",\"22\",\"3\",\"4\",\"24\",\"5\",\"21\",\"26\",\"25\"]]', '2022-06-02 17:57:50', 1, 1),
(15, 1, '[[\"4\",\"22\",\"26\",\"3\",\"5\",\"23\",\"25\",\"24\",\"21\"]]', '2022-06-02 17:57:51', 1, 1),
(16, 1, '[[\"24\",\"23\",\"25\",\"22\",\"4\",\"21\",\"26\",\"3\",\"5\"]]', '2022-06-02 17:57:51', 1, 1),
(17, 1, '[[\"25\",\"5\",\"21\",\"23\",\"22\",\"3\",\"26\",\"4\",\"24\"]]', '2022-06-02 17:57:52', 1, 1),
(18, 1, '[[\"5\",\"4\",\"3\",\"24\",\"21\",\"25\",\"26\",\"22\",\"23\"]]', '2022-06-02 17:57:52', 1, 1),
(19, 1, '[[\"3\",\"24\",\"23\",\"4\",\"21\",\"22\",\"25\",\"5\",\"26\"]]', '2022-06-02 17:57:53', 1, 1),
(20, 1, '[[\"25\",\"26\",\"24\",\"5\",\"3\",\"23\",\"21\",\"4\",\"22\"]]', '2022-06-02 17:57:53', 1, 1),
(21, 1, '[[\"5\",\"3\",\"23\",\"22\",\"25\",\"26\",\"4\",\"24\",\"21\"]]', '2022-06-02 17:57:56', 1, 1),
(22, 1, '[[\"4\",\"3\",\"22\",\"5\",\"24\",\"21\",\"23\",\"25\",\"26\"]]', '2022-06-02 17:57:56', 1, 1),
(23, 1, '[[\"25\",\"22\",\"24\",\"3\",\"26\",\"5\",\"4\",\"21\",\"23\"]]', '2022-06-02 17:57:57', 1, 1),
(24, 1, '[[\"25\",\"4\",\"21\",\"22\",\"5\",\"24\",\"26\",\"3\",\"23\"]]', '2022-06-02 17:57:57', 1, 1),
(25, 1, '[[\"4\",\"5\",\"21\",\"23\",\"25\",\"3\",\"24\",\"22\",\"26\"]]', '2022-06-02 17:57:58', 1, 1),
(26, 1, '[[\"26\",\"25\",\"5\",\"22\",\"23\",\"4\",\"21\",\"3\",\"24\"]]', '2022-06-02 17:57:59', 1, 1),
(27, 1, '[[\"24\",\"23\",\"3\",\"21\",\"4\",\"5\",\"26\",\"25\",\"22\"]]', '2022-06-02 17:57:59', 1, 1),
(28, 1, '[[\"21\",\"26\",\"3\",\"22\",\"25\",\"5\",\"24\",\"23\",\"4\"]]', '2022-06-02 18:00:29', 1, 1),
(29, 1, '[[\"23\",\"24\",\"4\",\"26\",\"21\",\"25\",\"5\",\"22\",\"3\"]]', '2022-06-02 18:00:29', 1, 1),
(30, 1, '[[\"4\",\"5\",\"25\",\"3\",\"26\",\"23\",\"22\",\"24\",\"21\"]]', '2022-06-02 18:00:30', 1, 1),
(31, 1, '[[\"21\",\"5\",\"26\",\"24\",\"25\",\"4\",\"3\",\"22\",\"23\"]]', '2022-06-02 21:09:14', 1, 1),
(32, 1, '[[\"3\",\"26\",\"25\",\"23\",\"4\",\"5\",\"21\",\"22\",\"24\"]]', '2022-06-02 21:09:16', 1, 1),
(33, 1, '[[\"23\",\"22\",\"21\",\"26\",\"3\",\"24\",\"4\",\"5\",\"25\"]]', '2022-06-02 21:09:17', 1, 1),
(34, 1, '[[\"26\",\"5\",\"21\",\"23\",\"25\",\"3\",\"22\",\"24\",\"4\"]]', '2022-06-02 21:09:18', 1, 1),
(35, 6, '[[\"4\",\"3\",\"1\",\"2\",\"5\"]]', '2022-06-02 21:22:54', 1, 1),
(36, 6, '[[\"3\",\"4\",\"2\",\"5\",\"1\"]]', '2022-06-02 21:23:23', 1, 1),
(37, 6, '[[\"5\",\"4\",\"3\",\"2\",\"1\"]]', '2022-06-02 21:29:25', 1, 1),
(38, 6, '[[\"1\",\"5\",\"3\",\"2\",\"4\"]]', '2022-06-02 21:30:21', 1, 1),
(39, 6, '[[\"4\",\"5\",\"2\",\"1\",\"3\"]]', '2022-06-03 06:14:53', 1, 1),
(40, 6, '[[\"4\",\"3\",\"1\",\"2\",\"5\"]]', '2022-06-03 08:43:26', 1, 1),
(41, 6, '[[\"2\",\"4\",\"3\",\"5\",\"1\"]]', '2022-06-03 18:17:52', 1, 1),
(42, 1, '[[\"24\",\"21\",\"4\",\"5\",\"23\",\"3\",\"22\",\"25\"]]', '2022-06-09 21:07:04', 1, 1),
(43, 1, '[[\"4\",\"21\",\"23\",\"25\",\"22\",\"3\",\"24\",\"5\"]]', '2022-06-09 21:07:15', 1, 1),
(44, 1, '[[\"5\",\"22\",\"3\",\"25\",\"23\",\"4\",\"21\",\"24\"]]', '2022-06-09 21:07:16', 1, 1),
(45, 1, '[[\"24\",\"23\",\"22\",\"5\",\"4\",\"21\",\"25\",\"3\"]]', '2022-06-09 21:07:16', 1, 1),
(46, 1, '[[\"5\",\"22\",\"4\",\"24\",\"21\",\"23\",\"25\",\"3\"]]', '2022-06-09 21:07:17', 1, 1),
(47, 1, '[[\"3\",\"25\",\"4\",\"21\",\"22\",\"23\",\"24\",\"5\"]]', '2022-06-09 21:07:17', 1, 1),
(48, 1, '[[\"5\",\"25\",\"3\",\"4\",\"21\",\"24\",\"22\",\"23\"]]', '2022-06-09 21:07:18', 1, 1),
(49, 1, '[[\"3\",\"22\",\"23\",\"24\",\"4\",\"21\",\"25\",\"5\"]]', '2022-06-09 21:07:19', 1, 1),
(50, 1, '[[\"4\",\"25\",\"24\",\"21\",\"3\",\"23\",\"22\",\"5\"]]', '2022-06-09 21:07:19', 1, 1),
(51, 1, '[[\"23\",\"4\",\"5\",\"22\",\"24\",\"3\",\"25\",\"21\"]]', '2022-06-09 21:07:20', 1, 1),
(52, 1, '[[\"22\",\"23\",\"25\",\"4\",\"21\",\"24\",\"3\",\"5\"]]', '2022-06-09 21:07:21', 1, 1),
(53, 1, '[[\"5\",\"4\",\"24\",\"23\",\"3\",\"25\",\"22\",\"21\"]]', '2022-06-09 21:07:22', 1, 1),
(54, 1, '[[\"22\",\"25\",\"4\",\"21\",\"5\",\"23\",\"3\",\"24\"]]', '2022-06-09 21:07:23', 1, 1),
(55, 1, '[[\"21\",\"3\",\"4\",\"22\",\"5\",\"23\",\"24\",\"25\"]]', '2022-06-09 21:07:23', 1, 1),
(56, 1, '[[\"25\",\"5\",\"3\",\"4\",\"22\",\"21\",\"24\",\"23\"]]', '2022-06-09 21:07:25', 1, 1),
(57, 1, '[[\"21\",\"3\",\"22\",\"4\",\"24\",\"23\",\"5\",\"25\"]]', '2022-06-09 21:07:25', 1, 1),
(58, 1, '[[\"5\",\"3\",\"4\",\"22\",\"21\",\"25\",\"23\",\"24\"]]', '2022-06-09 21:07:26', 1, 1),
(59, 1, '[[\"25\",\"4\",\"5\",\"22\",\"24\",\"3\",\"21\",\"23\"]]', '2022-06-09 21:07:27', 1, 1),
(60, 1, '[[\"21\",\"3\",\"4\",\"23\",\"25\",\"24\",\"5\",\"22\"]]', '2022-06-09 21:07:28', 1, 1),
(61, 1, '[[\"25\",\"22\",\"5\",\"24\"]]', '2022-06-09 21:07:45', 1, 1),
(62, 1, '[[\"22\",\"24\",\"3\",\"4\"]]', '2022-06-09 21:08:21', 1, 1),
(63, 1, '[[\"22\",\"5\",\"21\",\"25\"]]', '2022-06-09 21:08:22', 1, 1),
(64, 1, '[[\"4\",\"5\",\"3\",\"22\"]]', '2022-06-09 21:08:23', 1, 1),
(65, 1, '[[\"3\",\"23\",\"5\",\"24\"]]', '2022-06-09 21:08:26', 1, 1),
(66, 1, '[[\"24\",\"5\",\"25\",\"22\"]]', '2022-06-09 21:08:59', 1, 1),
(67, 1, '[[\"5\",\"24\",\"25\",\"3\"]]', '2022-06-09 21:09:01', 1, 1),
(68, 1, '[[\"5\",\"23\",\"3\",\"24\"]]', '2022-06-09 21:09:02', 1, 1),
(69, 1, '[[\"5\",\"23\",\"24\",\"4\"]]', '2022-06-09 21:09:03', 1, 1),
(70, 1, '[[\"21\",\"4\",\"23\",\"3\"]]', '2022-06-09 21:09:04', 1, 1),
(71, 1, '[[\"5\",\"25\",\"3\",\"23\"]]', '2022-06-09 21:09:05', 1, 1),
(72, 1, '[[\"22\",\"21\",\"4\",\"24\"]]', '2022-06-09 21:09:08', 1, 1),
(73, 1, '[[\"22\",\"24\",\"23\",\"21\"]]', '2022-06-09 21:09:08', 1, 1),
(74, 1, '[[\"21\",\"4\",\"3\",\"24\"]]', '2022-06-09 21:09:09', 1, 1),
(75, 1, '[[\"21\",\"23\",\"2\",\"26\"]]', '2022-06-09 21:15:36', 1, 1),
(76, 1, '[[\"24\",\"25\",\"2\",\"26\"]]', '2022-06-09 21:15:48', 1, 1),
(77, 1, '[[\"21\",\"23\",\"2\",\"26\"]]', '2022-06-09 21:15:49', 1, 1),
(78, 1, '[[\"3\",\"22\",\"31\",\"26\"]]', '2022-06-09 21:15:50', 1, 1),
(79, 1, '[[\"25\",\"4\",\"2\",\"26\"]]', '2022-06-09 21:15:54', 1, 1),
(80, 1, '[[\"23\",\"5\",\"31\",\"26\"]]', '2022-06-09 21:15:55', 1, 1),
(81, 1, '[[\"23\",\"5\",\"2\",\"26\"]]', '2022-06-09 21:16:22', 1, 1),
(82, 1, '[[\"3\",\"4\",\"31\",\"26\"]]', '2022-06-09 21:16:24', 1, 1),
(83, 1, '[[\"25\",\"5\",\"2\",\"26\"]]', '2022-06-09 21:16:25', 1, 1),
(84, 1, '[[\"24\",\"4\",\"2\",\"26\"]]', '2022-06-09 21:16:26', 1, 1),
(85, 1, '[[\"5\",\"24\",\"2\",\"26\"]]', '2022-06-09 21:16:27', 1, 1),
(86, 1, '[[\"3\",\"24\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:16:40', 1, 1),
(87, 1, '[[\"25\",\"4\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:16:44', 1, 1),
(88, 1, '[[\"4\",\"5\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:16:46', 1, 1),
(89, 1, '[[\"25\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:16:47', 1, 1),
(90, 1, '[[\"24\",\"23\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:17:57', 1, 1),
(91, 1, '[[\"5\",\"3\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:18:07', 1, 1),
(92, 1, '[[\"3\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:18:27', 1, 1),
(93, 1, '[[\"25\",\"23\",\"2\",\"31\",\"32\"],[\"25\",\"5\",\"2\",\"31\",\"32\"],[\"5\",\"23\",\"2\",\"31\",\"32\"],[\"24\",\"25\",\"31\",\"2\",\"32\"],[\"24\",\"21\",\"31\",\"2\",\"32\"],[\"24\",\"22\",\"31\",\"2\",\"32\"],[\"22\",\"4\",\"31\",\"2\",\"32\"],[\"23\",\"4\",\"31\",\"2\",\"32\"],[\"4\",\"3\",\"2\",\"31\",\"32\"],[\"24\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:19:28', 1, 1),
(94, 1, '[[\"23\",\"3\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:23:55', 1, 1),
(95, 1, '[[\"24\",\"3\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:23:57', 1, 1),
(96, 1, '[[\"23\",\"25\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:23:57', 1, 1),
(97, 1, '[[\"22\",\"25\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:23:58', 1, 1),
(98, 1, '[[\"3\",\"4\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:23:59', 1, 1),
(99, 1, '[[\"25\",\"4\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:01', 1, 1),
(100, 1, '[[\"21\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:25:02', 1, 1),
(101, 1, '[[\"4\",\"3\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:03', 1, 1),
(102, 1, '[[\"4\",\"23\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:25:05', 1, 1),
(103, 1, '[[\"3\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:07', 1, 1),
(104, 1, '[[\"23\",\"3\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:07', 1, 1),
(105, 1, '[[\"24\",\"25\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:08', 1, 1),
(106, 1, '[[\"5\",\"22\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:08', 1, 1),
(107, 1, '[[\"25\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:09', 1, 1),
(108, 1, '[[\"5\",\"25\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:25:10', 1, 1),
(109, 1, '[[\"21\",\"23\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:10', 1, 1),
(110, 1, '[[\"24\",\"4\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:11', 1, 1),
(111, 1, '[[\"22\",\"3\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:25:12', 1, 1),
(112, 1, '[[\"21\",\"4\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:12', 1, 1),
(113, 1, '[[\"24\",\"5\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:25:13', 1, 1),
(114, 1, '[[\"23\",\"22\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:16', 1, 1),
(115, 1, '[[\"24\",\"25\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:25:18', 1, 1),
(116, 1, '[[\"4\",\"23\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:26:12', 1, 1),
(117, 1, '[[\"25\",\"22\",\"2\",\"31\",\"32\"],[\"22\",\"23\",\"2\",\"31\",\"32\"],[\"21\",\"4\",\"2\",\"31\",\"32\"],[\"22\",\"3\",\"31\",\"2\",\"32\"],[\"5\",\"23\",\"2\",\"31\",\"32\"],[\"24\",\"3\",\"31\",\"2\",\"32\"],[\"23\",\"22\",\"31\",\"2\",\"32\"],[\"22\",\"23\",\"2\",\"31\",\"32\"],[\"24\",\"21\",\"31\",\"2\",\"32\"],[\"21\",\"4\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:27:32', 1, 1),
(118, 1, '[[\"25\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:47:16', 1, 1),
(119, 1, '[[\"23\",\"22\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:49:22', 1, 1),
(120, 1, '[[\"22\",\"23\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:28', 1, 1),
(121, 1, '[[\"24\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:40', 1, 1),
(122, 1, '[[\"21\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:42', 1, 1),
(123, 1, '[[\"24\",\"23\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:43', 1, 1),
(124, 1, '[[\"5\",\"4\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:43', 1, 1),
(125, 1, '[[\"22\",\"23\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:49:44', 1, 1),
(126, 1, '[[\"4\",\"21\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:49:45', 1, 1),
(127, 1, '[[\"25\",\"3\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:49:46', 1, 1),
(128, 1, '[[\"24\",\"3\",\"31\",\"2\",\"32\"]]', '2022-06-09 21:49:47', 1, 1),
(129, 1, '[[\"25\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:49:47', 1, 1),
(130, 6, '[[\"4\",\"3\",\"2\",\"5\",\"1\"]]', '2022-06-09 21:49:58', 1, 1),
(131, 6, '[[\"5\",\"3\",\"2\",\"4\",\"1\"]]', '2022-06-09 21:51:27', 1, 1),
(132, 6, '[[\"3\",\"4\",\"5\",\"2\",\"1\"]]', '2022-06-09 21:51:31', 1, 1),
(133, 6, '[[\"1\",\"4\",\"3\",\"5\",\"2\"]]', '2022-06-09 21:51:32', 1, 1),
(134, 6, '[[\"5\",\"4\",\"3\",\"1\",\"2\"]]', '2022-06-09 21:51:32', 1, 1),
(135, 6, '[[\"3\",\"2\",\"5\",\"4\",\"1\"]]', '2022-06-09 21:51:33', 1, 1),
(136, 6, '[[\"3\",\"1\",\"5\",\"2\",\"4\"]]', '2022-06-09 21:51:33', 1, 1),
(137, 6, '[[\"1\",\"5\",\"3\",\"2\",\"4\"]]', '2022-06-09 21:51:34', 1, 1),
(138, 6, '[[\"2\",\"5\",\"1\",\"4\",\"3\"]]', '2022-06-09 21:51:35', 1, 1),
(139, 6, '[[\"5\",\"3\",\"2\",\"4\",\"1\"]]', '2022-06-09 21:51:36', 1, 1),
(140, 6, '[[\"2\",\"5\",\"3\",\"1\",\"4\"]]', '2022-06-09 21:52:03', 1, 1),
(141, 6, '[[\"1\",\"4\",\"5\",\"3\",\"2\"]]', '2022-06-09 21:52:03', 1, 1),
(142, 6, '[[\"5\",\"1\",\"3\",\"4\",\"2\"]]', '2022-06-09 21:52:04', 1, 1),
(143, 6, '[[\"4\",\"3\",\"1\",\"5\",\"2\"]]', '2022-06-09 21:52:04', 1, 1),
(144, 6, '[[\"1\",\"2\",\"4\",\"3\",\"5\"]]', '2022-06-09 21:52:05', 1, 1),
(145, 6, '[[\"4\",\"1\",\"2\",\"3\",\"5\"]]', '2022-06-09 21:52:05', 1, 1),
(146, 6, '[[\"1\",\"3\",\"5\",\"2\",\"4\"]]', '2022-06-09 21:54:36', 1, 1),
(147, 6, '[[\"3\",\"1\",\"5\",\"2\",\"4\"]]', '2022-06-09 21:55:11', 1, 1),
(148, 1, '[[\"25\",\"22\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:58:01', 1, 1),
(149, 1, '[[\"3\",\"23\",\"2\",\"31\",\"32\"]]', '2022-06-09 21:58:01', 1, 1),
(150, 7, '[[\"47\",\"50\",\"52\",\"49\",\"51\",\"43\",\"46\",\"44\",\"42\",\"45\",\"41\",\"38\",\"37\",\"39\",\"40\"]]', '2022-06-10 04:57:18', 1, 1),
(151, 7, '[[\"47\",\"49\",\"52\",\"50\",\"48\",\"44\",\"45\",\"42\",\"46\",\"43\",\"38\",\"37\",\"40\",\"41\",\"39\"]]', '2022-06-10 04:57:37', 1, 1),
(152, 7, '[[\"52\",\"47\",\"48\",\"51\",\"50\",\"45\",\"43\",\"44\",\"46\",\"42\",\"40\",\"38\",\"41\",\"37\",\"39\"]]', '2022-06-10 04:58:02', 1, 1),
(153, 7, '[[\"51\",\"48\",\"49\",\"50\",\"47\",\"42\",\"43\",\"44\",\"46\",\"45\",\"39\",\"37\",\"40\",\"41\",\"38\"]]', '2022-06-10 04:58:02', 1, 1),
(154, 7, '[[\"50\",\"49\",\"51\",\"48\",\"52\",\"44\",\"46\",\"45\",\"42\",\"43\",\"41\",\"38\",\"39\",\"37\",\"40\"]]', '2022-06-10 04:58:03', 1, 1),
(155, 7, '[[\"47\",\"48\",\"49\",\"50\",\"51\",\"43\",\"45\",\"42\",\"46\",\"44\",\"37\",\"41\",\"38\",\"40\",\"39\"]]', '2022-06-10 04:58:19', 1, 1),
(156, 7, '[[\"51\",\"49\",\"50\",\"48\",\"47\",\"45\",\"42\",\"43\",\"44\",\"46\",\"40\",\"37\",\"41\",\"39\",\"38\"]]', '2022-06-10 10:06:51', 1, 1),
(157, 8, '[[\"54\",\"57\",\"58\",\"59\",\"56\"]]', '2022-06-17 12:41:31', 1, 1),
(158, 8, '[[\"54\",\"56\",\"57\",\"59\",\"58\"]]', '2022-06-17 12:42:25', 1, 1),
(159, 8, '[[\"56\",\"59\",\"57\",\"58\",\"54\"]]', '2022-06-17 12:42:40', 1, 1),
(160, 8, '[[\"57\",\"59\",\"58\",\"56\",\"54\"]]', '2022-06-17 12:42:53', 1, 1),
(161, 8, '[[\"54\",\"57\",\"59\",\"58\",\"56\"]]', '2022-06-17 12:43:04', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `mamonhoc` varchar(50) NOT NULL,
  `nganh` varchar(50) NOT NULL,
  `chuongtrinh` text NOT NULL DEFAULT '[]' COMMENT 'json'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `monhoc`
--

INSERT INTO `monhoc` (`id`, `name`, `mamonhoc`, `nganh`, `chuongtrinh`) VALUES
(1, 'Nhập Môn Công Nghệ Thông Tin', '32737', 'CNTT', '[\"Ch\\u01b0\\u01a1ng 1\",\"Ch\\u01b0\\u01a1ng 2\",\"Ch\\u01b0\\u01a1ng 3\"]'),
(2, 'Toán', '1', 'CNTT', '[\"1\",\"2\",\"3\"]');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permgroup`
--

CREATE TABLE `permgroup` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `permgroup`
--

INSERT INTO `permgroup` (`id`, `name`, `data`) VALUES
(1, 'Siêu quản trị viên', '{\"addquestion\":true,\r\n\"updatequestion\":true,\r\n\"deletequest\":true,\r\n\"addmatrix\":true,\r\n\"updatematrix\":true,\r\n\"saveasroot\":true,\r\n\"runmatrix\":true,\r\n\"addpermtouser\":true,\r\n\"lockuserperm\":true,\r\n\"deluserperm\":true,\r\n\"addmonhoc\":true,\r\n\"updatemonhoc\":true,\r\n\"deletemonhoc\":true,\r\n\"addcanbo\":true,\r\n\"updatecanbo\":true,\r\n\"deletecanbo\":true,\r\n\"acceptuser\":true,\r\n\"addtieuchi\":true,\r\n\"savetieuchi\":true,\"grant\":true,\"rollback\":true}'),
(2, 'Quản trị viên', '{\"addquestion\":true,\r\n\"updatequestion\":true,\r\n\"deletequest\":true,\r\n\"addmatrix\":true,\r\n\"updatematrix\":true,\r\n\"saveasroot\":true,\r\n\"runmatrix\":true,\r\n\"addpermtouser\":true,\r\n\"lockuserperm\":true,\r\n\"deluserperm\":true,\r\n\"addmonhoc\":true,\r\n\"updatemonhoc\":true,\r\n\"deletemonhoc\":true,\r\n\"addcanbo\":true,\r\n\"updatecanbo\":true,\r\n\"deletecanbo\":true,\r\n\"acceptuser\":true,\r\n\"addtieuchi\":true,\r\n\"savetieuchi\":true,\"rollback\":true}'),
(3, 'Quản trị cán bộ', '{\r\n\"addpermtouser\":true,\r\n\"lockuserperm\":true,\r\n\"deluserperm\":true,\r\n\"addcanbo\":true,\r\n\"updatecanbo\":true,\r\n\"deletecanbo\":true,\r\n\"acceptuser\":true\r\n}'),
(4, 'Quản trị môn học', '{\r\n\"addmonhoc\":true,\r\n\"updatemonhoc\":true,\r\n\"deletemonhoc\":true\r\n}'),
(5, 'Giảng viên', '{\r\n\"addquestion\":true,\r\n\"updatequestion\":true,\r\n\"deletequest\":true,\r\n\"addmatrix\":true,\r\n\"updatematrix\":true,\r\n\"saveasroot\":true,\r\n\"runmatrix\":true}');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `idcanbo` int(11) NOT NULL,
  `idmonhoc` int(11) NOT NULL,
  `islocked` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `permission`
--

INSERT INTO `permission` (`id`, `idcanbo`, `idmonhoc`, `islocked`) VALUES
(2, 2, 1, 0),
(3, 1, 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rootmatrix`
--

CREATE TABLE `rootmatrix` (
  `id` int(11) NOT NULL,
  `idmatrix` int(11) NOT NULL,
  `idresult` int(11) NOT NULL,
  `idmonhoc` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `idchallenge` int(11) NOT NULL DEFAULT 0,
  `addtime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `rootmatrix`
--

INSERT INTO `rootmatrix` (`id`, `idmatrix`, `idresult`, `idmonhoc`, `author`, `idchallenge`, `addtime`) VALUES
(1, 6, 38, 1, 1, 0, '2022-06-10 03:39:17'),
(2, 6, 38, 1, 1, 0, '2022-06-10 03:39:17'),
(3, 1, 93, 1, 1, 8, '2022-06-10 03:39:17'),
(4, 7, 155, 1, 1, 0, '2022-06-10 04:59:25'),
(5, 7, 156, 1, 1, 0, '2022-06-10 10:06:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tieuchi`
--

CREATE TABLE `tieuchi` (
  `id` int(11) NOT NULL,
  `slugname` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(15) NOT NULL,
  `valuerange` text NOT NULL DEFAULT '{}' COMMENT 'json'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `tieuchi`
--

INSERT INTO `tieuchi` (`id`, `slugname`, `name`, `type`, `valuerange`) VALUES
(1, 'do-kho', 'Độ Khó', 'number', '{\"min\":1,\"max\":15}'),
(2, 'nhap-mon', 'Nhập Môn', 'have', '{}'),
(3, 'thu-nghiem', 'Thử nghiệm', 'have', '{}');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `donvi` varchar(80) DEFAULT NULL,
  `isadmin` int(11) NOT NULL DEFAULT 0,
  `addtime` datetime DEFAULT current_timestamp(),
  `email` varchar(200) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '/images/defaultavatar.png',
  `lastlogin` datetime DEFAULT NULL,
  `lastip` varchar(80) NOT NULL DEFAULT '',
  `islocked` int(11) NOT NULL DEFAULT 0,
  `isaccept` int(11) NOT NULL DEFAULT 0,
  `perm` text NOT NULL DEFAULT '{}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `users`
-- Tài khoản: admin , mật khẩu: passwordforadmin
--

INSERT INTO `users` (`id`, `account`, `password`, `name`, `donvi`, `isadmin`, `addtime`, `email`, `avatar`, `lastlogin`, `lastip`, `islocked`, `isaccept`, `perm`) VALUES
(1, 'admin', '21a0998704ee362dee4340b5e3f31ad3', 'Phát CNTT', 'KCNTT', 1, NULL, '', '/images/defaultavatar.png', NULL, '', 0, 1, '{\"addpermtouser\":true,\"lockuserperm\":true,\"deluserperm\":true,\"addmonhoc\":true,\"updatemonhoc\":true,\"deletemonhoc\":true,\"addcanbo\":true,\"updatecanbo\":true,\"deletecanbo\":true,\"acceptuser\":true,\"addtieuchi\":true,\"savetieuchi\":true,\"addquestion\":true,\"updatequestion\":true,\"deletequest\":true,\"addmatrix\":true,\"updatematrix\":true,\"saveasroot\":true,\"runmatrix\":true,\"grant\":true,\"group\":1}'),
(2, 'long', 'bc98ecdec3f334cc82348a427e94e25a', 'Trần Huy Long', 'KCNTT', 0, NULL, '', '', NULL, '', 0, 1, '{}'),
(3, 'long2', '75e8b21226884b4e40925d2db3c4d9e9', 'Trần Huy Long2', 'KCNTT', 0, NULL, '', '', NULL, '', 1, 1, '{}'),
(4, 'longadmin', '6599d80a69bcf707074be4c2bd78ceda', 'Trần Huy Long', 'KCNTT', 1, NULL, '', '/images/defaultavatar.png', NULL, '', 0, 1, '{}');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idmonhoc` (`idmonhoc`),
  ADD KEY `author` (`author`);

--
-- Chỉ mục cho bảng `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`),
  ADD KEY `action` (`action`);

--
-- Chỉ mục cho bảng `matrix`
--
ALTER TABLE `matrix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idmonhoc` (`idmonhoc`),
  ADD KEY `author` (`author`);

--
-- Chỉ mục cho bảng `matrixresult`
--
ALTER TABLE `matrixresult`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matrixid` (`idmatrix`);

--
-- Chỉ mục cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `mamonhoc` (`mamonhoc`),
  ADD KEY `nganh` (`nganh`);

--
-- Chỉ mục cho bảng `permgroup`
--
ALTER TABLE `permgroup`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idcanbo` (`idcanbo`),
  ADD KEY `idmonhoc` (`idmonhoc`);

--
-- Chỉ mục cho bảng `rootmatrix`
--
ALTER TABLE `rootmatrix`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tieuchi`
--
ALTER TABLE `tieuchi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slugname` (`slugname`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `matrix`
--
ALTER TABLE `matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `matrixresult`
--
ALTER TABLE `matrixresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `permgroup`
--
ALTER TABLE `permgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `rootmatrix`
--
ALTER TABLE `rootmatrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tieuchi`
--
ALTER TABLE `tieuchi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
