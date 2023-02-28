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

 Date: 28/02/2023 11:47:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for routing
-- ----------------------------
DROP TABLE IF EXISTS `routing`;
CREATE TABLE `routing`  (
  `id` int NOT NULL,
  `url` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_520_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of routing
-- ----------------------------
INSERT INTO `routing` VALUES (1, '/bp/bp/edit');

SET FOREIGN_KEY_CHECKS = 1;
