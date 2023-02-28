/*
 Navicat MySQL Data Transfer

 Source Server         : DigitalOceon
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : 139.59.198.237:3306
 Source Schema         : bpmain

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 28/02/2023 11:47:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for health_check
-- ----------------------------
DROP TABLE IF EXISTS `health_check`;
CREATE TABLE `health_check`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `datetime` datetime NULL DEFAULT NULL,
  `sys` int NULL DEFAULT NULL,
  `dia` int NULL DEFAULT NULL,
  `step` int NULL DEFAULT NULL,
  `pul` int NULL DEFAULT NULL,
  `other` varchar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL,
  `userid` int NULL DEFAULT NULL,
  `deleted` bit(1) NULL DEFAULT b'0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4585 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_520_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
