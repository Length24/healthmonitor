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

 Date: 28/02/2023 11:47:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for graph_settings
-- ----------------------------
DROP TABLE IF EXISTS `graph_settings`;
CREATE TABLE `graph_settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_520_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of graph_settings
-- ----------------------------
INSERT INTO `graph_settings` VALUES (1, 'bar', 'Bar Chart 1');
INSERT INTO `graph_settings` VALUES (2, 'column', 'Column Chart 2');
INSERT INTO `graph_settings` VALUES (3, 'line', 'Line Chart 3');

SET FOREIGN_KEY_CHECKS = 1;
