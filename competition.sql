-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-01-06 09:13:09
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `competition`
--
CREATE DATABASE IF NOT EXISTS `competition` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `competition`;

-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

CREATE TABLE `admin` (
  `ssn` varchar(15) NOT NULL,
  `job` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `admin`
--

INSERT INTO `admin` (`ssn`, `job`) VALUES
('admin', 'administrator');

-- --------------------------------------------------------

--
-- 資料表結構 `announcement`
--

CREATE TABLE `announcement` (
  `aid` int(11) NOT NULL,
  `ssn` varchar(15) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `announcement`
--

INSERT INTO `announcement` (`aid`, `ssn`, `title`, `content`, `datetime`) VALUES
(1, 'admin', NULL, '公告測試', '2024-03-01 00:00:00'),
(2, 'admin', '比賽報名開始', '激發學生創意競賽的報名已經開始，截止日期為 2024-04-30。', '2024-04-01 10:54:02'),
(3, 'admin', '比賽說明會', '將於 2024-04-10 在圖書館舉辦比賽說明會，歡迎所有學生參加。', '2024-04-05 09:43:25'),
(4, 'admin', '作品提交截止日期', '請在 2024-05-15 前完成作品提交。', '2024-04-06 13:47:06'),
(5, 'admin', '作品提交截止日期', '請在 2024-05-15 前完成作品提交。', '2024-04-06 13:47:06'),
(6, 'admin', '決賽日程', '決賽將於 2024-06-10 在高雄大學大禮堂舉行。', '2024-06-01 15:14:41');

-- --------------------------------------------------------

--
-- 資料表結構 `judge`
--

CREATE TABLE `judge` (
  `ssn` varchar(15) NOT NULL,
  `number` int(15) NOT NULL,
  `title` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `judge`
--

INSERT INTO `judge` (`ssn`, `number`, `title`) VALUES
('A235427941', 1, '中央研究院院士'),
('E201997072', 3, '國立中正大學校長'),
('G131985115', 2, '國家發展委員會協理'),
('ju', 5, 'jutle'),
('S217599850', 4, '國立高雄大學資管系教授');

-- --------------------------------------------------------

--
-- 資料表結構 `piece`
--

CREATE TABLE `piece` (
  `pid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `demo` varchar(50) NOT NULL,
  `poster` varchar(50) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `document` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `piece`
--

INSERT INTO `piece` (`pid`, `tid`, `name`, `demo`, `poster`, `code`, `document`) VALUES
(4, 1, '這一件作品', 'https://www.youtube.com/', 'https://www.poster.com/', 'https://www.github.com/', 'https://www.document.com/'),
(5, 2, '一個作品', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- 資料表結構 `score`
--

CREATE TABLE `score` (
  `tid` int(11) NOT NULL,
  `ssn` varchar(15) NOT NULL COMMENT '評審',
  `score` float NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `score`
--

INSERT INTO `score` (`tid`, `ssn`, `score`, `comment`) VALUES
(1, 'ju', 90, '');

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE `student` (
  `ssn` varchar(15) NOT NULL,
  `department` varchar(15) NOT NULL,
  `grade` int(11) NOT NULL,
  `sid` varchar(15) NOT NULL,
  `tid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student`
--

INSERT INTO `student` (`ssn`, `department`, `grade`, `sid`, `tid`) VALUES
('C131503217', '建築學系', 1, 'A1132118', NULL),
('C175671334', '土木與環境工程學系', 2, 'B1123446', NULL),
('C210213167', '應用經濟學系', 1, 'A1132624', NULL),
('D145703888', '西洋語文學系', 1, 'B1135237', NULL),
('F100768852', '電機工程學系', 3, 'A1113848', NULL),
('F195789330', '應用經濟學系', 4, 'A1101321', 2),
('G156204175', '資訊管理學系', 4, 'A1101901', 1),
('G165316795', '西洋語文學系', 2, 'B1122126', NULL),
('G182545316', '土木與環境工程學系', 1, 'B1135127', NULL),
('H220720414', '西洋語文學系', 3, 'A1112221', 2),
('J125968306', '資訊管理學系', 3, 'A1113030', NULL),
('J134417661', '西洋語文學系', 3, 'B1111513', NULL),
('J210016306', '電機工程學系', 1, 'B1135517', NULL),
('N136854487', '應用化學系', 2, 'B1124701', NULL),
('P188054481', '應用數學系', 1, 'B1134534', NULL),
('Q196264563', '應用數學系', 4, 'B1104331', NULL),
('Q212510286', '建築學系', 1, 'B1131613', NULL),
('stu', 'stu', 1, 'A9999999', 1),
('T168938131', '資訊工程學系', 3, 'A1114014', 1),
('X127044818', '西洋語文學系', 3, 'B1113702', NULL),
('Y110738244', '資訊工程學系', 4, 'A1101637', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `teacher`
--

CREATE TABLE `teacher` (
  `ssn` varchar(15) NOT NULL,
  `degree` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `teacher`
--

INSERT INTO `teacher` (`ssn`, `degree`) VALUES
('L118972775', '國立台灣大學資訊工程學系博士'),
('N268102478', '國立交通大學電機工程學系博士'),
('tea', 'teaDegree'),
('W208891828', '美國哈佛大學甘迺迪政府學院博士'),
('Z192547336', '國立成功大學建築學系博士');

-- --------------------------------------------------------

--
-- 資料表結構 `team`
--

CREATE TABLE `team` (
  `tid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '隊伍名稱',
  `teacher_ssn` varchar(15) DEFAULT NULL,
  `year` int(11) NOT NULL COMMENT '參與年份',
  `rank` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `team`
--

INSERT INTO `team` (`tid`, `name`, `teacher_ssn`, `year`, `rank`) VALUES
(1, '這一隊', NULL, 2024, '1'),
(2, '第二隊', 'N268102478', 2024, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `ssn` varchar(15) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phonenumber` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`ssn`, `name`, `password`, `phonenumber`, `address`, `email`) VALUES
('A235427941', '王靜宜', '*7hCYfTfG&', '0918572791', '臺中市南屯區永春南路23號', 'pinggu@example.org'),
('admin', 'admin', '123', '0900000000', 'admin', 'admin'),
('C131503217', '管佩珊', 'RpE@JaBq^1', '0906786196', '高雄市鳳山區新昌街1003號', 'zhangtao@example.org'),
('C175671334', '郭鈺婷', 'qW&q6WkC)w', '0962985119', '桃園市中壢區興建街23號', 'zxu@example.org'),
('C210213167', '蕭志偉', 'cdVi54Jp*K', '0994496083', '彰化縣彰化市安溪東路8號', 'chao57@example.org'),
('D145703888', '熊佳穎', '&6Ec*8)9Ur', '0988255917', '新北市三重區西門巷51號0樓', 'leizhou@example.org'),
('E201997072', '沈欣怡', 'Ov^d8EUteh', '0948627613', '屏東縣屏東市華山街35號', 'qiujun@example.org'),
('F100768852', '顧慧君', '(9$ZP6aum1', '0943359484', '連江縣勝利街1段17號', 'fang95@example.net'),
('F195789330', '林佩君', '*&LYqzm&)7', '0979369270', '彰化縣線西鄉線東路17號', 'guowei@example.net'),
('G131985115', '李佳蓉', 'r9TKf$kV@s', '0927931066', '彰化縣花壇鄉金城街7號', 'pyuan@example.org'),
('G156204175', '高瑋婷', 'Q*4uH3ZwAr', '0919847168', '彰化縣竹塘鄉大湖路18號', 'fliu@example.net'),
('G165316795', '張婷婷', 'Q4W8EwUB)^', '0981689473', '高雄市楠梓區學府路2段575號之4', 'zhouping@example.org'),
('G182545316', '余羽', '!tJVh+FsF4', '0954087314', '高雄市旗津區北汕尾巷24號', 'kyan@example.org'),
('H220720414', '吳宗翰', 'M4QgUOap$1', '0913430537', '宜蘭縣中央街1段11號4樓', 'gchen@example.org'),
('J125968306', '汪鈺婷', 'X0i+9BIe^m', '0954644181', '嘉義市東區世賢路34號', 'mingxie@example.net'),
('J134417661', '胡怡安', '4eAxMfr5$f', '0979181414', '新竹市學府街7號5樓', 'tao24@example.org'),
('J210016306', '齊傑克', 'W#h4LyuRv@', '0951780375', '桃園市龍潭區花園新城二街13號', 'chaodai@example.com'),
('ju', 'ju', '123', 'ju', 'ju', 'ju@google.com'),
('L118972775', '朱冠霖', '@dxx2ZgG8R', '0970895714', '南投縣光復縣動物園路90號之0', 'gang36@example.net'),
('N136854487', '楊俊賢', '@!wrwRfe#6', '0939334847', '高雄市鳳山區新昌街21號', 'yaojie@example.com'),
('N268102478', '董雅涵', 'SQ5FQMtk+T', '0963302786', '高雄市楠梓區後昌新路32號', 'uren@example.org'),
('P188054481', '宋淑惠', 's)%7CZIh#W', '0961699784', '高雄市鳳山區公園巷3號7樓', 'qliu@example.net'),
('Q196264563', '江冠霖', '+CLL$cC@k3', '0940753855', '台南市新營區龍山寺街67號8樓', 'nshen@example.net'),
('Q212510286', '曾怡婷', '%!Cv1JzjWJ', '0939204654', '屏東縣鹽埔鄉中和路9號', 'zmo@example.net'),
('S217599850', '李雅芳', '%CkL3PNs*4', '0916132376', '嘉義市西區文華街21號', 'minqiao@example.org'),
('stu', 'stuName', '123', 'stuPhone', 'stuAddress', 'stu@gmail.com'),
('T168938131', '張怡伶', ')^6ZyjMhyb', '0996275028', '彰化縣員林市和平路5號之7', 'ykang@example.com'),
('tea', 'tea', '123', 'tea', 'tea', 'tea@'),
('W208891828', '李庭瑋', '52SOUFJ9$l', '0949069624', '嘉義縣竹崎鄉木柵寮2號', 'hejuan@example.net'),
('X127044818', '袁怡安', 'umTYDF!$$2', '0977325273', '台東縣卑南鄉市明德街2號2樓', 'liangjun@example.com'),
('Y110738244', '趙宜庭', 'C&2Z3NhPU+', '0940827019', '高雄市鳳山區新埔街27號之4', 'fangming@example.org'),
('Z192547336', '李俊傑', 'wi3yMN%qX(', '0956213706', '基隆縣頭份區民族路17號2樓', 'li19@example.org');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ssn`);

--
-- 資料表索引 `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `ssn` (`ssn`);

--
-- 資料表索引 `judge`
--
ALTER TABLE `judge`
  ADD PRIMARY KEY (`ssn`),
  ADD KEY `number` (`number`);

--
-- 資料表索引 `piece`
--
ALTER TABLE `piece`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `tid` (`tid`);

--
-- 資料表索引 `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`tid`,`ssn`),
  ADD KEY `ssn` (`ssn`);

--
-- 資料表索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ssn`),
  ADD KEY `tid` (`tid`);

--
-- 資料表索引 `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`ssn`);

--
-- 資料表索引 `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `teacher_ssn` (`teacher_ssn`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ssn`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcement`
--
ALTER TABLE `announcement`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `judge`
--
ALTER TABLE `judge`
  MODIFY `number` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `piece`
--
ALTER TABLE `piece`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `team`
--
ALTER TABLE `team`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ssn`) REFERENCES `user` (`ssn`);

--
-- 資料表的限制式 `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`ssn`) REFERENCES `admin` (`ssn`);

--
-- 資料表的限制式 `judge`
--
ALTER TABLE `judge`
  ADD CONSTRAINT `judge_ibfk_1` FOREIGN KEY (`ssn`) REFERENCES `user` (`ssn`);

--
-- 資料表的限制式 `piece`
--
ALTER TABLE `piece`
  ADD CONSTRAINT `piece_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `team` (`tid`);

--
-- 資料表的限制式 `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `team` (`tid`),
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`ssn`) REFERENCES `judge` (`ssn`);

--
-- 資料表的限制式 `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`ssn`) REFERENCES `user` (`ssn`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `team` (`tid`);

--
-- 資料表的限制式 `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`ssn`) REFERENCES `user` (`ssn`);

--
-- 資料表的限制式 `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`teacher_ssn`) REFERENCES `teacher` (`ssn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
