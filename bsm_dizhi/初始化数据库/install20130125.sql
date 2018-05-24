DROP DATABASE IF EXISTS `bsm_dizhi`;
create database `bsm_dizhi`;
use `bsm_dizhi`;

DROP TABLE IF EXISTS `book`;
create table book
(
id      int auto_increment primary key,
name	varchar(50),
cbsj    date,
cbs	varchar(50),
zsmd	text
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dizhi`;
create table dizhi
(
id	int auto_increment primary key,
leibie	int,
youbian	char(10),
xingming	char(80) binary,
chengwei	char(20) binary,
dizhi	char(200) binary,
danwei	char(80) binary
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
load data local infile "c:/dizhi.txt" into table dizhi fields terminated by "@";