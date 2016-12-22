/*
Navicat MySQL Data Transfer

Source Server         : 120.27.150.30
Source Server Version : 50173
Source Host           : 120.27.150.30:3306
Source Database       : cdk

Target Server Type    : MYSQL
Target Server Version : 50173
File Encoding         : 65001

Date: 2016-12-22 10:04:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `c_admin`
-- ----------------------------
DROP TABLE IF EXISTS `c_admin`;
CREATE TABLE `c_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL COMMENT '名称',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `head_src` varchar(100) DEFAULT '' COMMENT '头像',
  `last_login` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) DEFAULT NULL COMMENT '最后登录的ip',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '状态;0正常,1:离职',
  `powers` varchar(255) DEFAULT '',
  `isdel` tinyint(4) DEFAULT '0' COMMENT '0:未删除，1:已删除',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of c_admin
-- ----------------------------
INSERT INTO `c_admin` VALUES ('1', 'admin', 'Uv5MPSmSMcY=', null, '2016-12-22 09:58:22', '127.0.0.1', '0', null, '0', '2015-09-24 07:02:58', '2015-09-24 07:02:58', null, null);

-- ----------------------------
-- Table structure for `c_admin_action`
-- ----------------------------
DROP TABLE IF EXISTS `c_admin_action`;
CREATE TABLE `c_admin_action` (
  `actionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) unsigned DEFAULT '0',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '0为action',
  `name` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `orderid` int(10) NOT NULL DEFAULT '0',
  `isdel` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`actionid`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of c_admin_action
-- ----------------------------
INSERT INTO `c_admin_action` VALUES ('10', '0', '0', 'admin', '后台管理员', '1', '0');
INSERT INTO `c_admin_action` VALUES ('11', '1', '10', 'see', '管理员查看', '2', '0');
INSERT INTO `c_admin_action` VALUES ('12', '2', '10', 'edit', '管理员编辑', '3', '0');
INSERT INTO `c_admin_action` VALUES ('13', '1', '10', 'add', '管理员添加', '4', '0');
INSERT INTO `c_admin_action` VALUES ('14', '2', '10', 'delete', '管理员删除', '5', '0');
INSERT INTO `c_admin_action` VALUES ('40', '0', '0', 'cdk', 'cdk优惠券活动', '1', '0');
INSERT INTO `c_admin_action` VALUES ('41', '1', '40', 'index', '活动查看', '2', '0');
INSERT INTO `c_admin_action` VALUES ('42', '1', '40', 'add', '活动添加', '3', '0');
INSERT INTO `c_admin_action` VALUES ('43', '2', '40', 'edit', '活动编辑', '4', '0');

-- ----------------------------
-- Table structure for `c_cdk`
-- ----------------------------
DROP TABLE IF EXISTS `c_cdk`;
CREATE TABLE `c_cdk` (
  `cdk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cdk_code` varchar(100) DEFAULT '' COMMENT 'cdk code码',
  `type_id` int(10) unsigned DEFAULT NULL COMMENT '类型',
  `cdk_status` tinyint(3) unsigned DEFAULT '1' COMMENT '1:未领取,2:已领取,3:已使用',
  `cdk_receive_ip` varchar(50) DEFAULT '' COMMENT '领取ip',
  `cdk_receive_time` datetime DEFAULT NULL COMMENT '领取时间',
  `openid` varchar(255) DEFAULT '' COMMENT 'openid',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`cdk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of c_cdk
-- ----------------------------

-- ----------------------------
-- Table structure for `c_cdk_type`
-- ----------------------------
DROP TABLE IF EXISTS `c_cdk_type`;
CREATE TABLE `c_cdk_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_code` varchar(100) DEFAULT '' COMMENT '类型code, 用于生产url',
  `type_name` varchar(100) DEFAULT NULL COMMENT 'cdk类型名称',
  `type_desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `type_status` tinyint(4) DEFAULT NULL COMMENT '1:领取中, 2:已关闭',
  `type_url` varchar(255) DEFAULT '' COMMENT '生产url',
  `is_del` tinyint(4) DEFAULT NULL COMMENT '0,未删除 1:已删除',
  `create_id` int(11) unsigned DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_id` int(11) unsigned DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of c_cdk_type
-- ----------------------------
INSERT INTO `c_cdk_type` VALUES ('1', 'code11', '活动1', '活动描述', '1', 'http://www.qx.cc/code11', '0', '1', '2016-12-21 14:56:21', '1', '2016-12-21 16:05:17');
INSERT INTO `c_cdk_type` VALUES ('2', 'code2', '活动2', '1111', '1', 'http://www.qx.cc/code2', '0', '1', '2016-12-21 16:44:16', null, null);
