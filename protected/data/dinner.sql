-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-08-06 01:01:35
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinner`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_announcement`
--

CREATE TABLE `liv_announcement` (
  `id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL COMMENT '公告内容',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告信息';

--
-- 转存表中的数据 `liv_announcement`
--

INSERT INTO `liv_announcement` (`id`, `title`, `content`, `status`, `create_time`, `update_time`, `order_id`) VALUES
(1, 'Test', 'Test', 2, 1532754450, 1532754450, 1);

-- --------------------------------------------------------

--
-- 表的结构 `liv_complain`
--

CREATE TABLE `liv_complain` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='吐槽表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_config`
--

CREATE TABLE `liv_config` (
  `name` varchar(20) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订餐时间配置';

--
-- 转存表中的数据 `liv_config`
--

INSERT INTO `liv_config` (`name`, `start_time`, `end_time`, `is_open`) VALUES
('dinner_time', '09:00:00', '12:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `liv_food_order`
--

CREATE TABLE `liv_food_order` (
  `id` int(11) NOT NULL,
  `shop_id` int(10) NOT NULL COMMENT '该订单所属哪家店',
  `order_number` varchar(20) NOT NULL COMMENT '订单编号',
  `product_info` text NOT NULL COMMENT '商品信息',
  `pay_time` int(10) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态',
  `food_user_id` int(10) NOT NULL COMMENT '用户id',
  `total_price` float(7,1) UNSIGNED NOT NULL DEFAULT '0.0',
  `create_time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单';

--
-- 转存表中的数据 `liv_food_order`
--

INSERT INTO `liv_food_order` (`id`, `shop_id`, `order_number`, `product_info`, `pay_time`, `status`, `food_user_id`, `total_price`, `create_time`) VALUES
(5, 3, '20180729010628577525', 'a:3:{i:0;a:5:{s:2:"Id";s:1:"3";s:4:"Name";s:15:"招牌湯米粉";s:5:"Count";i:1;s:5:"Price";s:4:"40.0";s:10:"smallTotal";d:40;}i:1;a:5:{s:2:"Id";s:1:"2";s:4:"Name";s:12:"招牌湯麵";s:5:"Count";i:1;s:5:"Price";s:4:"40.0";s:10:"smallTotal";d:40;}i:2;a:5:{s:2:"Id";s:1:"7";s:4:"Name";s:15:"招牌湯粄條";s:5:"Count";i:1;s:5:"Price";s:4:"40.0";s:10:"smallTotal";d:40;}}', 0, 3, 3, 120.0, 1532819188),
(4, 3, '20180729010520197980', 'a:3:{i:0;a:5:{s:2:"Id";s:1:"8";s:4:"Name";s:9:"肝連麵";s:5:"Count";i:1;s:5:"Price";s:4:"85.0";s:10:"smallTotal";d:85;}i:1;a:5:{s:2:"Id";s:1:"7";s:4:"Name";s:15:"招牌湯粄條";s:5:"Count";i:1;s:5:"Price";s:4:"40.0";s:10:"smallTotal";d:40;}i:2;a:5:{s:2:"Id";s:2:"10";s:4:"Name";s:12:"肝連冬粉";s:5:"Count";i:1;s:5:"Price";s:4:"85.0";s:10:"smallTotal";d:85;}}', 0, 2, 3, 210.0, 1532819120),
(6, 1, '20180729072754800761', 'a:2:{i:0;a:5:{s:2:"Id";s:1:"4";s:4:"Name";s:13:"測試1號餐";s:5:"Count";i:1;s:5:"Price";s:4:"50.0";s:10:"smallTotal";d:50;}i:1;a:5:{s:2:"Id";s:1:"5";s:4:"Name";s:13:"測試2號餐";s:5:"Count";i:1;s:5:"Price";s:4:"60.0";s:10:"smallTotal";d:60;}}', 0, 1, 2, 110.0, 1532842074);

-- --------------------------------------------------------

--
-- 表的结构 `liv_food_order_log`
--

CREATE TABLE `liv_food_order_log` (
  `id` int(10) NOT NULL,
  `food_order_id` varchar(60) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单动态';

--
-- 转存表中的数据 `liv_food_order_log`
--

INSERT INTO `liv_food_order_log` (`id`, `food_order_id`, `status`, `create_time`) VALUES
(1, '1', 1, 1532754587),
(2, '1', 2, 1532754690),
(3, '2', 1, 1532765396),
(4, '3', 1, 1532768150),
(5, '3', 2, 1532769366),
(6, '4', 1, 1532819120),
(7, '4', 2, 1532819158),
(8, '5', 1, 1532819188),
(9, '5', 3, 1532819474),
(10, '6', 1, 1532842074);

-- --------------------------------------------------------

--
-- 表的结构 `liv_food_sort`
--

CREATE TABLE `liv_food_sort` (
  `id` int(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `fid` int(10) NOT NULL,
  `depath` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

--
-- 转存表中的数据 `liv_food_sort`
--

INSERT INTO `liv_food_sort` (`id`, `name`, `fid`, `depath`, `order_id`) VALUES
(1, '麵', 2, 2, 1),
(2, '飯', 1, 2, 2),
(3, '冬粉', 3, 4, 3),
(4, '米粉', 4, 5, 4),
(5, '粄條', 5, 6, 5);

-- --------------------------------------------------------

--
-- 表的结构 `liv_material`
--

CREATE TABLE `liv_material` (
  `id` int(10) NOT NULL,
  `name` varchar(120) NOT NULL COMMENT '图片名称',
  `filepath` varchar(100) NOT NULL COMMENT '原图的存储路径',
  `filename` varchar(40) NOT NULL COMMENT '文件名称',
  `type` varchar(30) NOT NULL COMMENT '图片类型',
  `mark` varchar(30) NOT NULL COMMENT '附件标记 img doc real',
  `imgwidth` smallint(4) NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `imgheight` smallint(4) NOT NULL DEFAULT '0' COMMENT '图片高度',
  `filesize` int(10) NOT NULL COMMENT '图片大小',
  `create_time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `liv_material`
--

INSERT INTO `liv_material` (`id`, `name`, `filepath`, `filename`, `type`, `mark`, `imgwidth`, `imgheight`, `filesize`, `create_time`) VALUES
(5, 'KK.png', '2018\\07\\', '0a451a0e2e28ca2edc93f2092eba603f.png', 'png', 'img', 84, 85, 3559, 1532766469),
(6, 'logoimage.jpg', '2018\\07\\', '40a0282b3fa0b937c6e5e0623bbb336f.jpg', 'jpg', 'img', 113, 98, 4391, 1532766544),
(4, 'logoimage.jpg', '2018\\07\\', 'f4df217d9435d8a3d6a5fbcbef448bab.jpg', 'jpg', 'img', 113, 98, 4391, 1532766431),
(7, 'KK.png', '2018\\07\\', 'b70a57d62f4de03d86c293e9e63ea701.png', 'png', 'img', 84, 85, 3559, 1532767180);

-- --------------------------------------------------------

--
-- 表的结构 `liv_members`
--

CREATE TABLE `liv_members` (
  `id` int(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(512) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `balance` float(7,1) UNSIGNED NOT NULL DEFAULT '0.0' COMMENT '账户余额',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='前台会员表';

--
-- 转存表中的数据 `liv_members`
--

INSERT INTO `liv_members` (`id`, `name`, `password`, `salt`, `sex`, `avatar`, `mobile`, `email`, `balance`, `status`, `create_time`, `update_time`, `order_id`) VALUES
(1, '2214', '2f66a21fa2aed1f28f173bca8458783b', 'iZGx5', 0, NULL, NULL, NULL, 101914.0, 2, 1532683618, 1532683618, 1),
(2, '2215', '6c26b1995c3e1e03b7ca5629b96a072a', 'mG8bu', 0, NULL, NULL, NULL, 100299.0, 2, 1532754314, 1532754314, 2),
(3, '2217', 'd16b5610435e410bc495adb1343ff181', 'TLlZm', 0, NULL, NULL, NULL, 99789.0, 2, 1532819020, 1532819020, 3);

-- --------------------------------------------------------

--
-- 表的结构 `liv_menus`
--

CREATE TABLE `liv_menus` (
  `id` int(10) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '菜名',
  `index_pic` int(10) NOT NULL DEFAULT '0',
  `sort_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属菜系',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属商家id',
  `price` float(7,1) UNSIGNED NOT NULL DEFAULT '0.0' COMMENT '价格',
  `unit` varchar(10) DEFAULT NULL COMMENT '价格单位',
  `brief` text COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜的状态',
  `create_time` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `tip` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单表';

--
-- 转存表中的数据 `liv_menus`
--

INSERT INTO `liv_menus` (`id`, `name`, `index_pic`, `sort_id`, `shop_id`, `price`, `unit`, `brief`, `status`, `create_time`, `order_id`, `tip`) VALUES
(2, '招牌湯麵', 0, 0, 3, 40.0, NULL, '', 2, 1532767200, 2, ''),
(3, '招牌湯米粉', 0, 0, 3, 40.0, NULL, '', 2, 1532767317, 3, ''),
(4, '測試1號餐', 0, 0, 1, 50.0, NULL, '', 2, 1532767364, 4, ''),
(5, '測試2號餐', 0, 0, 1, 60.0, NULL, '', 2, 1532767386, 5, ''),
(6, '招牌湯冬粉', 0, 0, 3, 40.0, NULL, '', 2, 1532767418, 6, ''),
(7, '招牌湯粄條', 0, 0, 3, 40.0, NULL, '', 2, 1532767437, 7, ''),
(8, '肝連麵', 0, 0, 3, 85.0, NULL, '', 2, 1532767465, 8, ''),
(12, '嘴邊肉麵', 0, 0, 3, 85.0, NULL, '', 2, 1532767705, 12, ''),
(9, '肝連米粉', 0, 0, 3, 85.0, NULL, '', 2, 1532767499, 9, ''),
(10, '肝連冬粉', 0, 0, 3, 85.0, NULL, '', 2, 1532767516, 10, ''),
(11, '肝連粄條', 0, 0, 3, 85.0, NULL, '', 2, 1532767532, 11, ''),
(13, '嘴邊肉米粉', 0, 0, 3, 85.0, NULL, '', 2, 1532767723, 13, ''),
(14, '嘴邊肉冬粉', 0, 0, 3, 85.0, NULL, '', 2, 1532767743, 14, ''),
(15, '嘴邊肉粄條', 0, 0, 3, 85.0, NULL, '', 2, 1532767760, 15, ''),
(16, '下水麵', 0, 0, 3, 80.0, NULL, '', 2, 1532767806, 16, ''),
(17, '下水米粉', 0, 0, 3, 80.0, NULL, '', 2, 1532767822, 17, ''),
(18, '下水冬粉', 0, 0, 3, 80.0, NULL, '', 2, 1532767837, 18, ''),
(19, '下水粄條', 0, 0, 3, 80.0, NULL, '', 2, 1532767852, 19, ''),
(20, '鵝腸麵', 0, 0, 3, 80.0, NULL, '', 2, 1532767884, 20, ''),
(21, '鵝腸米粉', 0, 0, 3, 80.0, NULL, '', 2, 1532767900, 21, ''),
(22, '鵝腸冬粉', 0, 0, 3, 80.0, NULL, '', 2, 1532767922, 22, ''),
(23, '鵝腸粄條', 0, 0, 3, 80.0, NULL, '', 2, 1532767940, 23, ''),
(24, '鱈魚麵', 0, 0, 3, 75.0, NULL, '', 2, 1532767957, 24, ''),
(25, '鱈魚米粉', 0, 0, 3, 75.0, NULL, '', 2, 1532767974, 25, ''),
(26, '鱈魚冬粉', 0, 0, 3, 75.0, NULL, '', 2, 1532767996, 26, ''),
(27, '鱈魚粄條', 0, 0, 3, 75.0, NULL, '', 2, 1532768007, 27, ''),
(28, '招牌乾麵', 0, 0, 3, 40.0, NULL, '', 2, 1532768028, 28, ''),
(29, '招牌乾米粉', 0, 0, 3, 40.0, NULL, '', 2, 1532768041, 29, ''),
(30, '招牌乾冬粉', 0, 0, 3, 40.0, NULL, '', 2, 1532768052, 30, ''),
(31, '招牌乾粄條', 0, 0, 3, 40.0, NULL, '', 2, 1532768063, 31, '');

-- --------------------------------------------------------

--
-- 表的结构 `liv_message`
--

CREATE TABLE `liv_message` (
  `id` int(10) NOT NULL,
  `shop_id` int(10) NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '留言内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '留言状态',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '留言时间',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `order_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';

--
-- 转存表中的数据 `liv_message`
--

INSERT INTO `liv_message` (`id`, `shop_id`, `content`, `status`, `create_time`, `user_id`, `order_id`) VALUES
(1, 1, 'jjj', 1, 1532765847, 1, 1),
(2, 1, 'oooooo', 1, 1532819224, 3, 2),
(3, 3, '1', 1, 1532931377, 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `liv_record_money`
--

CREATE TABLE `liv_record_money` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0扣款1充值',
  `money` float(7,1) UNSIGNED NOT NULL DEFAULT '0.0' COMMENT '金额',
  `create_time` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户扣款充值记录';

--
-- 转存表中的数据 `liv_record_money`
--

INSERT INTO `liv_record_money` (`id`, `user_id`, `type`, `money`, `create_time`) VALUES
(1, 2, 1, 200.0, 1532754560),
(2, 2, 1, 200.0, 1532754593),
(3, 2, 0, 100.0, 1532754690),
(4, 1, 1, 2000.0, 1532765358),
(5, 1, 0, 85.0, 1532769366),
(6, 2, 1, 99999.0, 1532769396),
(7, 1, 1, 99999.0, 1532769408),
(8, 3, 1, 99999.0, 1532819067),
(9, 3, 0, 210.0, 1532819158);

-- --------------------------------------------------------

--
-- 表的结构 `liv_reply`
--

CREATE TABLE `liv_reply` (
  `id` int(10) NOT NULL,
  `content` text NOT NULL,
  `message_id` int(10) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='回复表';

--
-- 转存表中的数据 `liv_reply`
--

INSERT INTO `liv_reply` (`id`, `content`, `message_id`, `create_time`, `user_id`) VALUES
(1, 'ggggggggg', 1, 1532819215, 3),
(2, 'XXXXX', 2, 1532819241, -1),
(3, '以抄收', 2, 1532819275, -1);

-- --------------------------------------------------------

--
-- 表的结构 `liv_shops`
--

CREATE TABLE `liv_shops` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '商户名',
  `district_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属地区id',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `address` varchar(1024) DEFAULT NULL COMMENT '详细地址',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `logo` varchar(250) DEFAULT NULL COMMENT '商家logo',
  `tel` varchar(50) DEFAULT NULL COMMENT '联系电话',
  `linkman` varchar(20) DEFAULT NULL COMMENT '联系人',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `url` varchar(128) NOT NULL COMMENT '商家url'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家基本信息';

--
-- 转存表中的数据 `liv_shops`
--

INSERT INTO `liv_shops` (`id`, `name`, `district_id`, `status`, `address`, `create_time`, `update_time`, `logo`, `tel`, `linkman`, `order_id`, `url`) VALUES
(1, '十里玻酒店', 0, 2, '地球上某一角落', 1532752472, 1532752472, '5', '7533967', '不想說', 1, '不要問'),
(2, '麥當勞', 0, 2, '中山區民權東路2段43號1樓', 1532766544, 1532766544, '6', '40666888', '無先生', 2, 'http://www.mcdonalds.com.tw/tw/ch/mdsl.html'),
(3, '冠鼎雞肉飯', 0, 2, '台北市中山區錦州街252號1樓號', 1532767180, 1532767180, '7', '0225032030', '無小姐', 3, '');

-- --------------------------------------------------------

--
-- 表的结构 `liv_user`
--

CREATE TABLE `liv_user` (
  `id` int(10) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `order_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台用户表';

--
-- 转存表中的数据 `liv_user`
--

INSERT INTO `liv_user` (`id`, `username`, `password`, `salt`, `create_time`, `order_id`) VALUES
(1, 'admin', '6b3e3f74a9ec2fbc517f50b483c5d4fa', 'aqdlt', 1398517782, 1);

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_login`
--

CREATE TABLE `liv_user_login` (
  `user_id` int(10) NOT NULL DEFAULT '0',
  `username` varchar(60) NOT NULL,
  `token` char(32) NOT NULL,
  `login_time` int(11) NOT NULL DEFAULT '0',
  `ip` char(30) DEFAULT NULL,
  `visit_client` tinyint(1) DEFAULT '0' COMMENT '登录客户端标识0：ios 1：安卓'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='用户登录记录';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `liv_announcement`
--
ALTER TABLE `liv_announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_complain`
--
ALTER TABLE `liv_complain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_config`
--
ALTER TABLE `liv_config`
  ADD UNIQUE KEY `dinner_time` (`name`);

--
-- Indexes for table `liv_food_order`
--
ALTER TABLE `liv_food_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_food_order_log`
--
ALTER TABLE `liv_food_order_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_food_sort`
--
ALTER TABLE `liv_food_sort`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_material`
--
ALTER TABLE `liv_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `liv_members`
--
ALTER TABLE `liv_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_menus`
--
ALTER TABLE `liv_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_message`
--
ALTER TABLE `liv_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_record_money`
--
ALTER TABLE `liv_record_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_reply`
--
ALTER TABLE `liv_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_shops`
--
ALTER TABLE `liv_shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_user`
--
ALTER TABLE `liv_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liv_user_login`
--
ALTER TABLE `liv_user_login`
  ADD PRIMARY KEY (`token`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `liv_announcement`
--
ALTER TABLE `liv_announcement`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `liv_complain`
--
ALTER TABLE `liv_complain`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `liv_food_order`
--
ALTER TABLE `liv_food_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `liv_food_order_log`
--
ALTER TABLE `liv_food_order_log`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `liv_food_sort`
--
ALTER TABLE `liv_food_sort`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `liv_material`
--
ALTER TABLE `liv_material`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `liv_members`
--
ALTER TABLE `liv_members`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `liv_menus`
--
ALTER TABLE `liv_menus`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- 使用表AUTO_INCREMENT `liv_message`
--
ALTER TABLE `liv_message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `liv_record_money`
--
ALTER TABLE `liv_record_money`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `liv_reply`
--
ALTER TABLE `liv_reply`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `liv_shops`
--
ALTER TABLE `liv_shops`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `liv_user`
--
ALTER TABLE `liv_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
