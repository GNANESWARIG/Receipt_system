-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2024 at 10:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `student` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `reg_no` VARCHAR(20) NOT NULL,
    `stud_name` VARCHAR(100) NOT NULL,
    `sex` CHAR(1) NOT NULL CHECK (`sex` IN ('M', 'F')),
    `father_name` VARCHAR(100) NOT NULL,
    `year` INT NOT NULL CHECK (`year` BETWEEN 1 AND 4),
    `degree_branch` VARCHAR(50) NOT NULL,
    `rec_no1` BIGINT(10) NOT NULL,
    `quota` VARCHAR(50),
    `mode` VARCHAR(20),
    `tuti` DECIMAL(10, 2),
    `dev` DECIMAL(10, 2),
    `trai_pl` DECIMAL(10, 2),
    `cau_dep` DECIMAL(10, 2),
      `rec_no2` BIGINT(10) NOT NULL,
    `hostel` DECIMAL(10,2),
    `online` DECIMAL(10,2),
    `bus` DECIMAL(10,2),
    `mess` DECIMAL(10,2),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`reg_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
