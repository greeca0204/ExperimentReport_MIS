set charset utf8;
CREATE TABLE `admin` (
  `user` varchar(12) NOT NULL,
  `psw` varchar(33) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into `admin`(`user`,`psw`,`name`,`mail`,`mobile`) values('admin','db69fc039dcbd2962cb4d28f5891aae1','admin','admin@admin.com','admin');
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cor_no` varchar(12) NOT NULL,
  `term` varchar(11) NOT NULL DEFAULT '0',
  `tea_no` varchar(12) NOT NULL,
  `cor_name` varchar(50) NOT NULL,
  `usual_rate` tinyint(4) DEFAULT NULL,
  `report_rate` tinyint(4) DEFAULT NULL,
  `exam_rate` tinyint(4) DEFAULT NULL,
  `select_time` date DEFAULT NULL,
  `report_time` date DEFAULT NULL,
  `close_time` date DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cor_no` (`cor_no`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
insert into `course`(`id`,`cor_no`,`term`,`tea_no`,`cor_name`,`usual_rate`,`report_rate`,`exam_rate`,`select_time`,`report_time`,`close_time`,`grade`) values('20','A001','2015-2','teacher','数据结构','10','20','70','2016-02-02','','2016-02-02','2013');
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groups` varchar(2) DEFAULT NULL,
  `cor_no` varchar(12) DEFAULT NULL,
  `week_start` varchar(2) DEFAULT NULL,
  `week_end` varchar(2) DEFAULT NULL,
  `week_nums` varchar(7) DEFAULT NULL,
  `lesson_seq` char(1) DEFAULT NULL,
  `num` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_no` varchar(12) NOT NULL,
  `item_name` varchar(80) NOT NULL,
  `body` varchar(1000) NOT NULL,
  `report_format` varchar(1000) NOT NULL,
  `cor_no` varchar(12) NOT NULL,
  `exam_rate` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
CREATE TABLE `remark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tea_no` varchar(12) NOT NULL DEFAULT '',
  `no` varchar(25) DEFAULT NULL,
  `body` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
CREATE TABLE `reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(12) DEFAULT NULL,
  `body` varchar(600) NOT NULL,
  `reply_time` datetime DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;
CREATE TABLE `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cor_no` varchar(12) DEFAULT NULL,
  `stu_no` varchar(12) NOT NULL,
  `item_no` varchar(12) NOT NULL,
  `date` date DEFAULT NULL,
  `body` varchar(2000) NOT NULL,
  `item_mark` tinyint(4) DEFAULT NULL,
  `remark` varchar(60) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
CREATE TABLE `sel_cor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_no` varchar(12) NOT NULL,
  `cor_no` varchar(12) NOT NULL,
  `groups` varchar(2) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `mark` tinyint(4) DEFAULT NULL,
  `usual_mark` tinyint(4) DEFAULT NULL,
  `report_mark` tinyint(4) DEFAULT NULL,
  `exam_mark` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
CREATE TABLE `sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE `stu` (
  `stu_no` varchar(12) NOT NULL,
  `psw` varchar(33) NOT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `name` varchar(10) DEFAULT NULL,
  `statue` tinyint(4) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `class_no` varchar(12) DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`stu_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102001','8324d4537975d9bf4272644b06f69363','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102002','4631c024f7fceb6a4c3fd04cdb1a53a5','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102003','6305db31cc33ba69a8353d0cc38a9873','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102004','2d7d43d2a0fc9e61386ff39621503e35','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102005','9f9042269888bf2fc5bec18e3fbe6e08','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102006','bf88896add5d13d49df04d653d7414d9','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102007','de9a9da6b815d216ec3dc13b68f983a7','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102008','73d158f37924165aee079fcc4d1110d4','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102009','4b86b1833cc1c9d414daad3ba089bee0','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102010','e9d99320047f7002fb066c9cc87322a5','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102011','5a8b1803ac95a3b1a3242a659a9fba6b','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102012','9b6b06369cdaa3552440103e86adbb96','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102013','d8ac7e847da26d6d70e51c87199a699d','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102014','45d2cc06bb53a36d0735249e11d64696','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102015','8976605b7384f0c257ffb2c3c2d67ad4','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102016','930b99149850514b895ac83b54c03b74','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102017','8d728be5967a6b7cca2a923a5c028fa4','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102018','cf1146de0c543903ac71d9b98929ece8','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102019','d182143a517ac75e5d51416e8438c2a1','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102020','412a2aa34c2a2e9c83306b36ed2cc098','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102021','c1b94b26b01513e2344e85c946259994','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102022','a9735ea45cd06d1bd30d480ddc5ff1a1','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102023','5b264e32c1d8a0a9e330e8e755c4c625','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102024','9d398d6dc957ec5d13c9a889220ba861','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102025','73c61504c3f7c73251f96fd1dbb7c7f3','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102026','408096bd832f2907b86896c2087682bc','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102027','18d39eb5e4af4cfa5feabb7239683880','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102028','96eb3b94060367cf691979bd317aebc3','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102029','fce141cc4e2dd8771f6a44174f8af5b3','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102030','c2e244c5db2306ec232431eff05111b7','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102031','2b9dd81692beb1851b096a4cb718a472','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102032','a515fec845e70c9be6e2bc0d11c3f944','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102033','8df0cd666fd4c856126a67fac930abb2','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102034','c322d32ad2ed2b61d52d4e95658299cc','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102035','fd3c197e7fc5fd12c37f923d3a6fcdf3','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('1106102036','14eb98280b2a081ad0f2b55ca0fb424a','','','1','','计算机117','2011');
insert into `stu`(`stu_no`,`psw`,`mail`,`name`,`statue`,`mobile`,`class_no`,`grade`) values('admin123','db69fc039dcbd2962cb4d28f5891aae1','','','','','','');
CREATE TABLE `tea` (
  `tea_no` varchar(12) NOT NULL,
  `psw` varchar(33) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `department` tinyint(4) DEFAULT NULL,
  `skill` varchar(60) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`tea_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into `tea`(`tea_no`,`psw`,`name`,`mail`,`department`,`skill`,`mobile`) values('teacher','db69fc039dcbd2962cb4d28f5891aae1','','','','','');
CREATE TABLE `topic` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` varchar(4) DEFAULT NULL,
  `title` varchar(80) NOT NULL,
  `author` varchar(12) DEFAULT NULL,
  `body` varchar(1000) NOT NULL,
  `post_time` datetime DEFAULT NULL,
  `last_floor` int(11) DEFAULT NULL,
  `last_reply_time` datetime DEFAULT NULL,
  `visible` tinyint(4) DEFAULT NULL,
  `enable_post` tinyint(4) DEFAULT NULL,
  `top` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;
