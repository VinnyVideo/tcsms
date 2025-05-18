

-- --------------------------------------------------------
-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 17, 2025 at 09:52 PM
-- Server version: 5.5.68-MariaDB
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mfgg_mainsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `tsms_admin_msg`
--

CREATE TABLE `tsms_admin_msg` (
  `mid` int(10) UNSIGNED NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(120) DEFAULT NULL,
  `message` mediumtext,
  `handled_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `handle_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `type` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `aux` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `admin_comment` mediumtext,
  `user_inform` tinyint(1) NOT NULL DEFAULT '0',
  `conversation` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `special_data` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_bookmarks`
--

CREATE TABLE `tsms_bookmarks` (
  `bid` int(64) UNSIGNED NOT NULL,
  `uid` int(11) UNSIGNED NOT NULL,
  `rid` int(11) UNSIGNED NOT NULL,
  `type` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_comments`
--

CREATE TABLE `tsms_comments` (
  `cid` int(10) UNSIGNED NOT NULL,
  `rid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `message` mediumtext,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(16) DEFAULT NULL,
  `hash` varchar(64) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Triggers `tsms_comments`
--
DELIMITER $$
CREATE TRIGGER `comment_hash_trigger` BEFORE INSERT ON `tsms_comments` FOR EACH ROW SET
  NEW.hash = MD5(concat(floor(UNIX_TIMESTAMP() / 10), NEW.uid, NEW.message, NEW.rid))
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_filter_group`
--

CREATE TABLE `tsms_filter_group` (
  `gid` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `keyword` varchar(16) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_filter_group`
--

INSERT INTO `tsms_filter_group` (`gid`, `name`, `keyword`) VALUES
(1, 'Type', 'TYPE'),
(2, 'Rip Type', 'RIP_TYPE'),
(3, 'Group', 'GEN_CAT'),
(4, 'Game', 'GAME'),
(5, 'Character', 'CHAR'),
(6, 'Completion', 'COMPLETION'),
(7, 'Game Type', 'GAME_TYPE'),
(8, 'Target Application', 'TARGET_APP'),
(9, 'Sound Format', 'SOUND_TYPE'),
(10, 'Contents', 'SOUND_CAT'),
(11, 'Misc Type', 'MISC_TYPE'),
(12, 'Base ROM', 'BASE_ROM'),
(13, 'ROM Region', 'ROM_REGION'),
(14, 'Hack Type', 'HACK_TYPE'),
(15, 'Franchise', 'FRANCHISE');

-- --------------------------------------------------------

--
-- Table structure for table `tsms_filter_list`
--

CREATE TABLE `tsms_filter_list` (
  `fid` int(10) UNSIGNED NOT NULL,
  `gid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `short_name` varchar(16) DEFAULT NULL,
  `search_tags` varchar(64) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_filter_list`
--

INSERT INTO `tsms_filter_list` (`fid`, `gid`, `name`, `short_name`, `search_tags`) VALUES
(1, 1, 'Sprite Sheets', 'SHEETS', ''),
(2, 1, 'Tilesets', 'TILES', ''),
(3, 1, 'Klik Libraries (Retired)', 'KLIK', ''),
(4, 2, 'Ripped', '', ''),
(5, 2, 'Edited', '', ''),
(6, 2, 'Original', '', ''),
(7, 3, 'Mario Bros', '', ''),
(8, 3, 'Friends', '', ''),
(9, 3, 'Enemies', '', ''),
(10, 3, 'Wario Bros', '', ''),
(11, 3, 'Backgrounds', '', ''),
(12, 3, 'Misc', '', ''),
(13, 4, 'Super Mario Bros. 1 (8-bit)', '', 's_SMB,SMB1,SMBDX'),
(14, 4, 'Super Mario Bros. 2 (8-bit)', '', 'SMB2'),
(15, 4, 'Super Mario Bros. 3 (8-bit)', '', 'SMB3'),
(16, 4, 'Super Mario World', '', 's_SMW,SMA2,SMB4'),
(17, 4, 'Yoshi\'s Island', '', 's_YI,SMW2,SMA3'),
(18, 4, 'Super Mario Bros. 1 (16-bit)', '', 'SMAS,s_SMB,SMB1'),
(19, 4, 'Super Mario Bros. 2 (16-bit)', '', 'SMAS,SMB2,s_SMA,SMA1'),
(20, 4, 'Super Mario Bros. 3 (16-bit)', '', 'SMAS,SMB3,SMA4'),
(21, 4, 'Super Mario 64 / Super Mario 64 DS', '', 'SM64,SM64DS'),
(22, 4, 'Yoshi\'s Story', '', 's_YS'),
(23, 4, 'Super Mario RPG', '', 'SMRPG'),
(24, 4, 'Mario & Luigi 1 (Superstar Saga/Bowser\'s Minions)', '', 'MLSS,s_ML,s_ML1,MLPT,MLPIT,s_ML2,s_M&L'),
(26, 4, 'Paper Mario series', '', 's_PM,s_PM2,PMTTYD,s_SPM,PM:SS,PM:CS'),
(28, 4, 'Super Mario Land', '', 'S_SML'),
(29, 4, 'Super Mario Land 2: Six Golden Coins', '', 'SML2'),
(30, 4, 'Super Princess Peach', '', 'S_SPP'),
(31, 5, 'Mario', '', ''),
(32, 5, 'Luigi', '', ''),
(33, 5, 'Princess Peach', '', ''),
(34, 5, 'Toad', '', ''),
(35, 5, 'Toadette', '', ''),
(36, 5, 'Yoshi', '', ''),
(37, 5, 'Bowser', '', ''),
(38, 5, 'Kamek / Magikoopas', '', ''),
(39, 5, 'Donkey Kong', '', 's_DK'),
(40, 5, 'Diddy Kong', '', 'S_DK'),
(41, 5, 'Toadsworth', '', ''),
(42, 5, 'Wario', '', ''),
(43, 5, 'Waluigi', '', ''),
(44, 5, 'Daisy', '', ''),
(45, 5, 'Birdo', '', ''),
(46, 6, 'Full Game', 'Full', 'FULL'),
(47, 6, 'Demo', '', ''),
(48, 6, 'Scrapped', '', ''),
(49, 7, 'Platform', '', ''),
(50, 7, 'Sports', '', ''),
(51, 7, 'Action', '', ''),
(52, 7, 'Puzzle', '', ''),
(53, 7, 'Minigame', '', ''),
(54, 7, 'Other', '', ''),
(56, 5, 'Bowser Jr. and the Koopalings', '', 's_JR'),
(57, 4, 'Donkey Kong Classics', '', 's_DK,s_DKJ,DKJR,s_DK3'),
(58, 4, 'Tetris Attack', '', 's_TA'),
(63, 4, 'Mario vs. Donkey Kong series', '', 'MVSDK'),
(67, 4, 'Mario Kart series', '', 's_SMK,s_MK,MK64,MKDD,MKSC,MKDS'),
(68, 4, 'Yoshi\'s Cookie', '', 's_YC'),
(69, 4, 'Yoshi\'s Safari', '', 'YSAF'),
(73, 8, 'The Games Factory / CnC / MMF Express', 'TGF', 's_tgf,s_cnc,mmfe,mmfx'),
(74, 8, 'Multimedia Fusion', 'MMF', 's_mmf'),
(75, 8, 'Game Maker 5.x', 'GM5', 's_gm,s_gm5'),
(76, 8, 'Game Maker 6.x', 'GM6', 's_gm,s_gm6'),
(77, 8, 'Other', 'OTHER', ''),
(78, 9, 'WAV Collections', 'WAV', 's_wav,s_sfx'),
(79, 9, 'MIDI Collections', 'MIDI', 'midi'),
(80, 10, 'Music', '', ''),
(81, 10, 'Sound Effects', 'SFX', 's_sfx'),
(82, 10, 'Voices', '', ''),
(83, 10, 'Instruments', '', ''),
(84, 9, 'IT Collections', 'IT', 's_it'),
(85, 9, 'XM Collections', 'XM', 's_xm'),
(86, 11, 'System Files', 'SYS', 's_dll'),
(87, 11, 'Fonts', 'FONT', 'font'),
(88, 11, 'Other', 'OTHER', ''),
(90, 4, 'New Super Mario Bros.', '', 'NSMB'),
(93, 4, 'Mario Bros.', '', 's_MB'),
(94, 4, 'Wrecking Crew series', '', 's_WC,WC98'),
(96, 4, 'Dr. Mario series', '', 's_DM,DM64,DM&PL,DMMC,s_DL'),
(98, 4, 'Wario Land', '', 's_WL,s_WL1,SML3'),
(99, 4, 'Wario Land 2', '', 's_WL2'),
(100, 4, 'Wario Land 3', '', 's_WL3'),
(101, 4, 'Wario Land 4', '', 's_WL4'),
(102, 4, 'WarioWare, Inc. series', '', 's_WW,s_WWT'),
(103, 4, 'Wario\'s Woods', '', 's_WW'),
(106, 4, 'Yoshi / Yoshi\'s Egg / Mario and Yoshi', '', 'YOSHI,s_YE,s_MY'),
(107, 4, 'Wario Blast: Featuring Bomberman', '', 's_WB'),
(108, 4, 'Mario Golf series', '', 's_MG,MGAT,MG64'),
(109, 4, 'Mario Tennis series', '', 's_MT,MTPT,MT64'),
(110, 4, 'Mario Party Advance', '', 's_MPA'),
(111, 4, 'Game & Watch Gallery series', '', 's_GWG,GWG1,GWG2,GWG3,GWG4'),
(115, 4, 'Yoshi Topsy-Turvy', '', 's_YTT'),
(116, 4, 'Mario Pinball Land', '', 'MPBL,s_MPL'),
(149, 4, 'Super Mario Sunshine', '', 's_SMS'),
(120, 4, 'Mario Party (N64)', '', 's_MP1,s_MP,s_MP2,s_MP3'),
(123, 4, 'Yoshi Touch & Go', '', 's_YTG,YTAG'),
(124, 4, 'Educational Mario Games', '', 's_MIM,s_MM,s_MTM,s_MTT,s_MEY'),
(148, 5, 'King Boo', '', 's_boo'),
(126, 4, 'Mario & Wario', '', 's_MW'),
(127, 4, 'Mario Paint', '', 's_MP'),
(129, 4, 'Mario Excitebike', '', 's_MEB'),
(131, 4, 'Mario Clash', '', 's_MC'),
(132, 4, 'Virtual Boy Wario Land', '', 'VBWL'),
(133, 4, 'OTHER', '', ''),
(134, 5, 'Wart', '', ''),
(136, 5, 'Cackletta', '', ''),
(137, 5, 'Fawful', '', ''),
(138, 5, 'Smithy', '', ''),
(140, 5, 'Tatanga', '', ''),
(141, 5, 'Piranha Plants / Petey Piranha', '', ''),
(142, 5, 'Grodus and the X-Nauts', '', ''),
(143, 5, 'Geno', '', ''),
(144, 5, 'Mallow', '', ''),
(145, 5, 'OTHER', '', ''),
(146, 7, 'Adventure', '', ''),
(147, 5, 'Paper Mario Allies', '', ''),
(150, 4, 'Super Smash Bros. series', '', 's_SSB,SSBM,SSBB,SSB4,SSBU,'),
(151, 5, 'Goomba', '', ''),
(152, 5, 'Koopa Troopa', '', ''),
(153, 5, 'Bullet Bill / Torpedo Ted', '', ''),
(154, 5, 'Boo', '', 's_boo'),
(155, 5, 'Shy Guy', '', ''),
(156, 5, 'Captain Syrup', '', ''),
(157, 5, 'Lakitu', '', ''),
(158, 5, 'Cheep Cheep', '', ''),
(159, 5, 'Bob-omb', '', ''),
(160, 5, 'Professor E. Gadd', '', ''),
(161, 4, 'Super Mario Bros. Deluxe', '', 'SMBDX'),
(162, 7, 'RPG', '', ''),
(163, 4, 'Luigi\'s Mansion', '', 's_LM'),
(164, 8, 'The Games Factory 2', 'TGF2', 'tgf2'),
(165, 8, 'Multimedia Fusion 2', 'MMF2', 'mmf2'),
(166, 5, 'Blooper', '', ''),
(167, 4, 'Super Mario Galaxy', '', 's_SMG'),
(168, 4, 'Yoshi\'s Island DS', '', 's_YI2,YIDS'),
(169, 5, 'Dry Bones', '', ''),
(170, 4, 'Wario: Master of Disguise', '', 'WMOD,s_WMD'),
(171, 5, 'Count Bleck', '', ''),
(172, 8, 'Game Maker 7.x', 'GM7', 's_gm,s_gm7'),
(174, 9, 'OGG Collections', 'OGG', 's_ogg'),
(175, 5, 'Hammer Bros.', '', ''),
(176, 9, 'SPC Collections', 'SPC', 's_spc'),
(178, 4, 'Mario Party DS', '', 'MPDS'),
(179, 5, 'Chain Chomp', '', ''),
(181, 1, 'Textures', 'TEXTURES', ''),
(186, 4, 'Wario Land: The Shake Dimension/Shake It!', '', ''),
(182, 5, 'Buzzy Beetles / Spinies', '', ''),
(183, 5, 'Pokey', '', ''),
(184, 5, 'Thwomps / Whomps', '', ''),
(185, 8, 'Game Maker 8.x', 'GM8', 's_gm,s_gm8'),
(187, 4, 'Super Mario Galaxy 2', '', 'SMG2'),
(188, 4, 'New Super Mario Bros. Wii', '', 'NSMBWii'),
(189, 4, 'Hotel Mario', '', ''),
(190, 5, 'Wiggler', '', ''),
(193, 4, 'Super Mario 3D Land', '', 'SM3DL'),
(194, 4, 'Mario & Luigi 2 (Partners in Time)', '', 's_ML2, PiT'),
(195, 4, 'Mario & Luigi 3 (Bowser\'s Inside Story/Bowser Jr.\'s Journey)', '', 's_BiS, ML3'),
(197, 8, 'Construct 2', 'CONSTRUCT2', 's_cc,s_ c2'),
(196, 8, 'Construct', 'CONSTRUCT', 's_cc,s_c2'),
(198, 4, 'Mario & Luigi 4 (Dream Team)', '', 'MLDT, ML4'),
(199, 8, 'Game Maker Studio', 'GMS', 's_gm,s_gms'),
(200, 4, 'Super Mario 3D World', '', 'SM3DW'),
(201, 4, 'Donkey Kong Country series', 'DKC', 's_DKC,s_ DK, DKC2, DKC3, DKCR, DKCTF'),
(202, 4, 'Captain Toad: Treasure Tracker', '', 'CTTT'),
(203, 4, 'Super Mario Maker series', '', 's_SMM, SMM2'),
(204, 4, 'Yoshi\'s New Island', '', 's_YNI'),
(205, 4, 'Mario & Luigi 5 (Paper Jam)', '', 's_ML5,s_ PJ'),
(206, 4, 'Yoshi\'s Woolly World', '', 's_YWW'),
(207, 4, 'New Super Mario Bros. U / New Super Luigi U', '', 'NSMBU, NSLU'),
(208, 4, 'New Super Mario Bros. 2', '', 'NSMB2'),
(209, 5, 'Nabbit', '', ''),
(210, 4, 'Luigi\'s Mansion: Dark Moon', '', 'LMDM,s_LM2'),
(241, 7, 'Hack', '', ''),
(211, 8, 'Clickteam Fusion 2.5', 'CTF25', 's_ctf'),
(212, 8, 'Clickteam Fusion 2.5 (Developer)', 'CTF25_dev', 's_ctf, dev'),
(213, 4, 'Mario Party: Island Tour (3DS)', '', 'MPIT,MP3DS'),
(214, 5, 'Rosalina', '', ''),
(215, 4, 'Mario Party: Star Rush', '', 'MP:SR'),
(216, 8, 'Game Maker Studio 2', 'GMS2', 's_gm,s_gms,gms2'),
(217, 4, 'Super Mario Odyssey', '', 's_SMO'),
(218, 5, 'Pauline', '', ''),
(219, 5, 'Cappy', '', ''),
(220, 5, 'Antasma', '', ''),
(221, 5, 'Shroobs', '', 'Shroob'),
(222, 5, 'Wanda', '', ''),
(223, 5, 'Foreman Spike', '', ''),
(224, 5, 'Stanley the Bug Man', '', 'Stanley'),
(225, 5, 'Broodals', '', 'Hariet Rango Spewart Hopper Madame Broode'),
(226, 5, 'Prince Peasley', '', ''),
(227, 5, 'Queen Bean', '', ''),
(228, 5, 'Star Spirits', '', ''),
(229, 5, 'Twink', '', ''),
(230, 5, 'Prince Dreambert', '', ''),
(231, 5, 'Francis', '', ''),
(232, 5, 'Captain Toad', '', ''),
(233, 5, 'Mouser', '', ''),
(234, 5, 'Clawgrip', '', ''),
(235, 5, 'Mimi', '', ''),
(236, 5, 'Nastasia', '', ''),
(237, 5, 'Dimentio', '', ''),
(238, 5, 'O\'Chunks', '', ''),
(239, 7, 'Shoot \'em Up', '', 'SHMUP'),
(240, 4, 'Mario Party (Post-N64)', '', 's_MP4,s_MP5,s_MP6,s_MP7,s_MP8,s_MP9,MP10'),
(242, 5, 'Donkey Kong Jr.', '', 'DK Jr'),
(243, 5, 'Sprixie Princesses', '', ''),
(244, 5, 'Chargin\' Chuck', '', ''),
(245, 5, 'Popple', '', ''),
(246, 5, 'Fry Guy', '', ''),
(247, 5, 'Tryclyde', '', ''),
(248, 5, 'Piantas', '', ''),
(249, 5, 'Nokis', '', ''),
(250, 5, 'Luma', '', ''),
(251, 4, 'Yoshi\'s Crafted World', '', 's_YCW'),
(252, 12, 'Super Mario World', '', 'H_SMW'),
(253, 12, 'Super Mario 64', '', 'H_SM64'),
(254, 12, 'OTHER (Please Specify)', '', 'H_OTHER'),
(255, 12, 'New Super Mario Bros', '', 'H_NSMB'),
(256, 12, 'New Super Mario Bros Wii', '', 'H_NSMBWii'),
(257, 12, 'Super Mario RPG', '', 'H_SMRPG'),
(258, 12, 'Super Mario Bros 1 (NES)', '', 'H_SMB1'),
(259, 12, 'Super Mario Bros 2 USA (NES)', '', 'H_SMB2US'),
(260, 12, 'Super Mario Bros 3 (NES)', '', 'H_SMB3'),
(261, 12, 'Super Mario Bros 2 JP (NES)', '', 'H_SMB2JP'),
(262, 13, 'North America (NTSC)', '', 'R_NA'),
(263, 13, 'Japan (NTSC)', '', 'R_JP'),
(264, 13, 'Europe (PAL)', '', 'R_EU'),
(265, 13, 'Other (Please Specify)', '', 'R_OTHER'),
(266, 14, 'Cosmetic', '', 'HT_COS'),
(267, 14, 'Tweak', '', 'HT_TWK'),
(268, 14, 'Full Conversion', '', 'HT_FCV'),
(269, 15, 'Mario', '', 'SR_MARIO'),
(270, 15, 'Legend of Zelda', '', 'SR_ZELDA'),
(271, 15, 'Metroid', '', 'SR_METROID'),
(272, 15, 'Kirby', '', 'SR_KIRBY'),
(273, 15, 'OTHER', '', 'SR_OTHER'),
(274, 11, 'Levels', 'LEVEL', ''),
(275, 15, 'CROSSOVER', '', 'SR_XOVER'),
(276, 12, 'Yoshi\'s Island', '', 'H_YI'),
(277, 12, 'Metroid Series (Please Specify)', '', 'H_METROID'),
(278, 12, 'Zelda Series (Please Specify)', '', 'H_ZELDA'),
(279, 12, 'Kirby Series (Please Specify)', '', 'H_KIRBY'),
(280, 15, 'Mega Man', '', 'SR_MEGAMAN'),
(281, 15, 'Pac-Man', '', 'SR_PACMAN'),
(282, 8, 'Godot', 'GD', 'godot'),
(283, 8, 'Unity', 'UNITY', 'unity'),
(284, 11, '3D Models', '3DM', '3d model'),
(285, 7, 'Comedy', '', 'lol'),
(286, 15, 'MOTHER/Earthbound', '', 'SR_MOTHER'),
(287, 15, 'Pokemon', '', 'SR_POKEMON'),
(288, 4, 'Luigi\'s Mansion 3', '', 's_LM3'),
(289, 15, 'Sonic the Hedgehog', '', 'SR_SONIC'),
(290, 4, 'Super Mario Bros. Wonder', '', 'SMBW');

-- --------------------------------------------------------

--
-- Table structure for table `tsms_filter_multi`
--

CREATE TABLE `tsms_filter_multi` (
  `fid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rid` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `tsms_filter_use`
--

CREATE TABLE `tsms_filter_use` (
  `mid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `gid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `store_keywords` tinyint(1) DEFAULT '0',
  `precedence` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_filter_use`
--

INSERT INTO `tsms_filter_use` (`mid`, `gid`, `store_keywords`, `precedence`) VALUES
(1, 1, 0, 1),
(1, 2, 0, 2),
(1, 3, 0, 3),
(1, 4, 0, 4),
(1, 5, 1, 5),
(2, 6, 0, 1),
(2, 7, 0, 2),
(4, 8, 0, 1),
(5, 9, 0, 1),
(5, 10, 0, 2),
(5, 4, 0, 3),
(6, 11, 0, 1),
(7, 6, 0, 1),
(7, 12, 0, 2),
(7, 13, 0, 3),
(7, 14, 0, 4),
(1, 15, 0, 6),
(2, 15, 0, 3),
(4, 15, 0, 2),
(5, 15, 0, 4),
(6, 15, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tsms_groups`
--

CREATE TABLE `tsms_groups` (
  `gid` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(32) DEFAULT NULL,
  `group_title` varchar(32) DEFAULT NULL,
  `moderator` tinyint(1) NOT NULL DEFAULT '0',
  `acp_access` tinyint(1) NOT NULL DEFAULT '0',
  `acp_modq` tinyint(1) NOT NULL DEFAULT '0',
  `acp_users` tinyint(1) NOT NULL DEFAULT '0',
  `acp_news` tinyint(1) NOT NULL DEFAULT '0',
  `acp_msg` tinyint(1) NOT NULL DEFAULT '0',
  `can_msg_users` tinyint(1) NOT NULL DEFAULT '0',
  `acp_super` tinyint(1) NOT NULL DEFAULT '0',
  `can_submit` tinyint(1) NOT NULL DEFAULT '0',
  `can_comment` tinyint(1) NOT NULL DEFAULT '0',
  `can_report` tinyint(1) NOT NULL DEFAULT '0',
  `can_modify` tinyint(1) NOT NULL DEFAULT '0',
  `can_msg` tinyint(1) NOT NULL DEFAULT '0',
  `use_bbcode` tinyint(1) NOT NULL DEFAULT '0',
  `edit_comment` tinyint(1) NOT NULL DEFAULT '0',
  `delete_comment` tinyint(1) NOT NULL DEFAULT '0',
  `msg_capacity` int(11) NOT NULL DEFAULT '0',
  `name_prefix` varchar(255) NOT NULL DEFAULT '',
  `name_suffix` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_groups`
--

INSERT INTO `tsms_groups` (`gid`, `group_name`, `group_title`, `moderator`, `acp_access`, `acp_modq`, `acp_users`, `acp_news`, `acp_msg`, `can_msg_users`, `acp_super`, `can_submit`, `can_comment`, `can_report`, `can_modify`, `can_msg`, `use_bbcode`, `edit_comment`, `delete_comment`, `msg_capacity`, `name_prefix`, `name_suffix`) VALUES
(1, 'Main Site Admin', 'Main Site Admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2000, '<span class=\'adminwrap\'>', '</span>'),
(2, 'Comment Moderator - OLD', 'Moderators', 1, 1, 0, 1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1000, '<span class=\'adminwrap\'>', '</span>'),
(3, 'QC Admin - OLD', 'Quality Control', 0, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1000, '<span class=\'adminwrap\'>', '</span>'),
(4, 'Guests', 'Guests', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', ''),
(5, 'Members', 'Members', 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1000, '', ''),
(10, 'Banned', 'Banned', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 100, '<s>', '</s>'),
(11, 'Restricted Members - OLD', 'Restricted Members', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 1, 0, 200, '', ''),
(12, 'Muted', 'Muted', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 500, '', ' <img border='),
(13, 'Suspended', 'Suspended', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 200, '', ''),
(14, 'Submission Ban', 'Banned', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 200, '', ''),
(15, 'SuperMuted', 'SuperMuted', 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 0, 200, '', ' <img border='),
(16, 'Security Ninja', 'Security Ninja', 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2000, '<span class=\'staffwrap\'>', '</span>'),
(18, 'MS Moderator', 'Main Site Moderator', 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1000, '<span class=\'adminwrap\'>', '</span>'),
(19, 'Site Developer', 'Site Developer', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2000, '<span class=\'staffwrap\'>', '</span>'),
(20, 'Other Staff', 'Non-Site Staff', 0, 1, 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 1000, '<span class=\'staffwrap\'>', '</span>');

-- --------------------------------------------------------

--
-- Table structure for table `tsms_likes`
--

CREATE TABLE `tsms_likes` (
  `lid` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cid` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_mail_log`
--

CREATE TABLE `tsms_mail_log` (
  `lid` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(16) DEFAULT NULL,
  `recipient` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_messages`
--

CREATE TABLE `tsms_messages` (
  `mid` int(10) UNSIGNED NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `receiver` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `owner` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `message` text,
  `msg_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `folder` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `conversation` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_modules`
--

CREATE TABLE `tsms_modules` (
  `mid` int(10) UNSIGNED NOT NULL,
  `module_name` varchar(32) DEFAULT NULL,
  `class_name` varchar(32) DEFAULT NULL,
  `full_name` varchar(64) DEFAULT NULL,
  `table_name` varchar(32) DEFAULT NULL,
  `module_file` varchar(32) DEFAULT NULL,
  `template` varchar(16) DEFAULT NULL,
  `num_decisions` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `proc_order` int(11) NOT NULL DEFAULT '0',
  `custom_update` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `children` varchar(128) DEFAULT NULL,
  `ext_files` tinyint(4) NOT NULL DEFAULT '0',
  `news_show` tinyint(1) NOT NULL DEFAULT '0',
  `news_show_collapsed` tinyint(1) NOT NULL DEFAULT '0',
  `news_upd` tinyint(1) NOT NULL DEFAULT '0',
  `news_upd_collapsed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_modules`
--

INSERT INTO `tsms_modules` (`mid`, `module_name`, `class_name`, `full_name`, `table_name`, `module_file`, `template`, `num_decisions`, `proc_order`, `custom_update`, `hidden`, `children`, `ext_files`, `news_show`, `news_show_collapsed`, `news_upd`, `news_upd_collapsed`) VALUES
(1, 'Graphics', 'mod_gfx', 'Graphics', 'res_gfx', 'gfx.php', 'mod_gfx', 2, 3, 0, 0, '', 2, 1, 0, 1, 1),
(2, 'Games', 'mod_games', 'Games', 'res_games', 'games.php', 'mod_games', 2, 1, 1, 0, '3', 3, 1, 0, 1, 1),
(3, 'Reviews', 'mod_reviews', 'Reviews', 'res_reviews', 'reviews.php', 'mod_reviews', 2, 7, 0, 1, '', 0, 1, 1, 0, 0),
(4, 'Howtos', 'mod_howtos', 'How-Tos', 'res_howtos', 'howtos.php', 'mod_howtos', 1, 4, 0, 0, '', 1, 1, 0, 1, 1),
(5, 'Sounds', 'mod_sounds', 'Sounds, Music', 'res_sounds', 'sounds.php', 'mod_sounds', 1, 5, 0, 0, '', 1, 1, 0, 1, 1),
(6, 'Misc', 'mod_misc', 'Miscellaneous', 'res_misc', 'misc.php', 'mod_misc', 2, 6, 0, 0, '', 1, 1, 0, 1, 1),
(7, 'Hacks', 'mod_hacks', 'Hacks & Mods', 'res_hacks', 'hacks.php', 'mod_hacks', 2, 2, 1, 0, '3', 3, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tsms_news`
--

CREATE TABLE `tsms_news` (
  `nid` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `message` mediumtext,
  `comments` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `update_tag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_panels`
--

CREATE TABLE `tsms_panels` (
  `pid` int(10) UNSIGNED NOT NULL,
  `eid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `panel_type` varchar(16) DEFAULT NULL,
  `panel_name` varchar(45) DEFAULT NULL,
  `restricted` varchar(128) DEFAULT NULL,
  `restrict_future` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `restricted_hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `hide_header` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `justify` char(1) DEFAULT NULL,
  `column` char(2) DEFAULT NULL,
  `row` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `strip_order` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `visible` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_strip` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_column_c` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_column_lr` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `can_fuse` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `strip_promote` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `fuse_up` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fuse_down` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_resources`
--

CREATE TABLE `tsms_resources` (
  `rid` int(10) UNSIGNED NOT NULL,
  `type` int(8) UNSIGNED NOT NULL DEFAULT '0',
  `eid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `description` mediumtext,
  `author_override` varchar(64) DEFAULT NULL,
  `website_override` varchar(128) DEFAULT NULL,
  `weburl_override` varchar(128) DEFAULT NULL,
  `created` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `updated` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `queue_code` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `ghost` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `update_reason` varchar(255) DEFAULT NULL,
  `accept_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `update_accept_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `decision` varchar(128) DEFAULT NULL,
  `catwords` mediumtext,
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `comment_date` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_games`
--

CREATE TABLE `tsms_res_games` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(128) DEFAULT NULL,
  `file_html5` varchar(128) DEFAULT NULL,
  `preview` varchar(128) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `plays` int(11) NOT NULL DEFAULT '0',
  `file_mime` varchar(64) DEFAULT NULL,
  `thumbnail` varchar(128) DEFAULT NULL,
  `num_revs` int(11) NOT NULL DEFAULT '0',
  `rev_score` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_gfx`
--

CREATE TABLE `tsms_res_gfx` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(64) DEFAULT NULL,
  `thumbnail` varchar(64) DEFAULT NULL,
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `downloads` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `file_mime` varchar(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_hacks`
--

CREATE TABLE `tsms_res_hacks` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(128) DEFAULT NULL,
  `preview` varchar(128) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `file_mime` varchar(64) DEFAULT NULL,
  `thumbnail` varchar(128) DEFAULT NULL,
  `num_revs` int(11) NOT NULL DEFAULT '0',
  `rev_score` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_howtos`
--

CREATE TABLE `tsms_res_howtos` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(128) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `file_mime` varchar(64) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_misc`
--

CREATE TABLE `tsms_res_misc` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(128) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `file_mime` varchar(64) DEFAULT NULL,
  `type1` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_reviews`
--

CREATE TABLE `tsms_res_reviews` (
  `eid` int(10) UNSIGNED NOT NULL,
  `gid` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `commentary` text,
  `pros` text,
  `cons` text,
  `gameplay` text,
  `graphics` text,
  `sound` text,
  `replay` text,
  `gameplay_score` tinyint(4) NOT NULL DEFAULT '0',
  `graphics_score` tinyint(4) NOT NULL DEFAULT '0',
  `sound_score` tinyint(4) NOT NULL DEFAULT '0',
  `replay_score` tinyint(4) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_res_sounds`
--

CREATE TABLE `tsms_res_sounds` (
  `eid` int(10) UNSIGNED NOT NULL,
  `file` varchar(128) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `file_mime` varchar(64) DEFAULT NULL,
  `type1` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_sec_images`
--

CREATE TABLE `tsms_sec_images` (
  `sessid` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `regcode` varchar(6) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_sessions`
--

CREATE TABLE `tsms_sessions` (
  `sessid` char(32) NOT NULL DEFAULT '',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cookie` char(32) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `user_agent` varchar(150) DEFAULT NULL,
  `location` varchar(72) NOT NULL,
  `sessdata` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_skins`
--

CREATE TABLE `tsms_skins` (
  `sid` mediumint(9) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `author` varchar(128) DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `default` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsms_skins`
--

INSERT INTO `tsms_skins` (`sid`, `name`, `author`, `hidden`, `default`) VALUES
(2, 'New MFGG', 'Thunder Dragon, Techokami', 0, 0),
(3, 'Classic MFGG', 'Kritter, Retriever II', 0, 0),
(4, 'NSMB', 'Black Squirrel, Char', 0, 0),
(5, 'WAHlloween', 'Pedigree', 0, 0),
(6, 'Default MFGG', 'Mors', 0, 1),
(7, 'The Lost Galaxy', 'Mors, Shadow Man', 0, 0),
(8, 'Blue Challenger', 'Mors', 0, 0),
(9, 'SMFGG', 'Mors', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tsms_staffchat`
--

CREATE TABLE `tsms_staffchat` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL,
  `date` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_users`
--

CREATE TABLE `tsms_users` (
  `uid` int(10) UNSIGNED NOT NULL,
  `gid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `weburl` varchar(128) DEFAULT NULL,
  `icon` varchar(128) DEFAULT NULL,
  `aim` varchar(32) DEFAULT NULL,
  `discord` varchar(128) DEFAULT NULL,
  `twitter` varchar(128) DEFAULT NULL,
  `steam` varchar(128) DEFAULT NULL,
  `reddit` varchar(128) DEFAULT NULL,
  `youtube` varchar(512) DEFAULT NULL,
  `bluesky` varchar(128) DEFAULT NULL,
  `twitch` varchar(128) DEFAULT NULL,
  `icq` varchar(32) DEFAULT NULL,
  `msn` varchar(128) DEFAULT NULL,
  `yim` varchar(32) DEFAULT NULL,
  `def_order_by` varchar(18) DEFAULT NULL,
  `def_order` varchar(4) DEFAULT NULL,
  `skin` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `registered_ip` varchar(15) DEFAULT NULL,
  `items_per_page` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `show_email` tinyint(1) NOT NULL DEFAULT '0',
  `first_submit` tinyint(1) NOT NULL DEFAULT '0',
  `cookie` varchar(32) DEFAULT NULL,
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `new_msgs` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `join_date` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `timezone` smallint(6) DEFAULT NULL,
  `dst` tinyint(1) NOT NULL DEFAULT '0',
  `disp_msg` tinyint(1) NOT NULL DEFAULT '0',
  `icon_dims` varchar(9) DEFAULT NULL,
  `cur_msgs` int(11) NOT NULL DEFAULT '0',
  `show_thumbs` tinyint(1) NOT NULL DEFAULT '0',
  `use_comment_msg` tinyint(1) NOT NULL DEFAULT '0',
  `use_comment_digest` tinyint(1) NOT NULL DEFAULT '0',
  `last_visit` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_ip` varchar(15) NOT NULL,
  `new_password` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsms_version`
--

CREATE TABLE `tsms_version` (
  `vid` int(10) UNSIGNED NOT NULL,
  `rid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `version` varchar(12) DEFAULT NULL,
  `change` varchar(255) DEFAULT NULL,
  `date` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tsms_admin_msg`
--
ALTER TABLE `tsms_admin_msg`
  ADD PRIMARY KEY (`mid`),
  ADD KEY `date` (`date`),
  ADD KEY `type` (`type`),
  ADD KEY `handled_by` (`handled_by`);

--
-- Indexes for table `tsms_bookmarks`
--
ALTER TABLE `tsms_bookmarks`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `tsms_comments`
--
ALTER TABLE `tsms_comments`
  ADD PRIMARY KEY (`cid`),
  ADD UNIQUE KEY `hash` (`hash`) USING BTREE,
  ADD KEY `rid` (`rid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`type`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `tsms_filter_group`
--
ALTER TABLE `tsms_filter_group`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `tsms_filter_list`
--
ALTER TABLE `tsms_filter_list`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `gid` (`gid`);

--
-- Indexes for table `tsms_filter_multi`
--
ALTER TABLE `tsms_filter_multi`
  ADD PRIMARY KEY (`fid`,`rid`);

--
-- Indexes for table `tsms_filter_use`
--
ALTER TABLE `tsms_filter_use`
  ADD PRIMARY KEY (`mid`,`gid`);

--
-- Indexes for table `tsms_groups`
--
ALTER TABLE `tsms_groups`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `tsms_likes`
--
ALTER TABLE `tsms_likes`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `tsms_mail_log`
--
ALTER TABLE `tsms_mail_log`
  ADD PRIMARY KEY (`lid`);

--
-- Indexes for table `tsms_messages`
--
ALTER TABLE `tsms_messages`
  ADD PRIMARY KEY (`mid`),
  ADD KEY `sender` (`sender`),
  ADD KEY `receiver` (`receiver`);

--
-- Indexes for table `tsms_modules`
--
ALTER TABLE `tsms_modules`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `tsms_news`
--
ALTER TABLE `tsms_news`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `tsms_panels`
--
ALTER TABLE `tsms_panels`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `eid` (`eid`);

--
-- Indexes for table `tsms_resources`
--
ALTER TABLE `tsms_resources`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`type`,`eid`);
ALTER TABLE `tsms_resources` ADD FULLTEXT KEY `title` (`title`,`description`,`catwords`);

--
-- Indexes for table `tsms_res_games`
--
ALTER TABLE `tsms_res_games`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_gfx`
--
ALTER TABLE `tsms_res_gfx`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_hacks`
--
ALTER TABLE `tsms_res_hacks`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_howtos`
--
ALTER TABLE `tsms_res_howtos`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_misc`
--
ALTER TABLE `tsms_res_misc`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_reviews`
--
ALTER TABLE `tsms_res_reviews`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_res_sounds`
--
ALTER TABLE `tsms_res_sounds`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tsms_sec_images`
--
ALTER TABLE `tsms_sec_images`
  ADD PRIMARY KEY (`sessid`);

--
-- Indexes for table `tsms_sessions`
--
ALTER TABLE `tsms_sessions`
  ADD PRIMARY KEY (`sessid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `tsms_skins`
--
ALTER TABLE `tsms_skins`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `tsms_staffchat`
--
ALTER TABLE `tsms_staffchat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsms_users`
--
ALTER TABLE `tsms_users`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `tsms_version`
--
ALTER TABLE `tsms_version`
  ADD PRIMARY KEY (`vid`),
  ADD KEY `rid` (`rid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tsms_admin_msg`
--
ALTER TABLE `tsms_admin_msg`
  MODIFY `mid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_bookmarks`
--
ALTER TABLE `tsms_bookmarks`
  MODIFY `bid` int(64) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_comments`
--
ALTER TABLE `tsms_comments`
  MODIFY `cid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_filter_group`
--
ALTER TABLE `tsms_filter_group`
  MODIFY `gid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tsms_filter_list`
--
ALTER TABLE `tsms_filter_list`
  MODIFY `fid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT for table `tsms_groups`
--
ALTER TABLE `tsms_groups`
  MODIFY `gid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tsms_likes`
--
ALTER TABLE `tsms_likes`
  MODIFY `lid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_mail_log`
--
ALTER TABLE `tsms_mail_log`
  MODIFY `lid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_messages`
--
ALTER TABLE `tsms_messages`
  MODIFY `mid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_modules`
--
ALTER TABLE `tsms_modules`
  MODIFY `mid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tsms_news`
--
ALTER TABLE `tsms_news`
  MODIFY `nid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_panels`
--
ALTER TABLE `tsms_panels`
  MODIFY `pid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_resources`
--
ALTER TABLE `tsms_resources`
  MODIFY `rid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_games`
--
ALTER TABLE `tsms_res_games`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_gfx`
--
ALTER TABLE `tsms_res_gfx`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_hacks`
--
ALTER TABLE `tsms_res_hacks`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_howtos`
--
ALTER TABLE `tsms_res_howtos`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_misc`
--
ALTER TABLE `tsms_res_misc`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_reviews`
--
ALTER TABLE `tsms_res_reviews`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_res_sounds`
--
ALTER TABLE `tsms_res_sounds`
  MODIFY `eid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_skins`
--
ALTER TABLE `tsms_skins`
  MODIFY `sid` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tsms_staffchat`
--
ALTER TABLE `tsms_staffchat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_users`
--
ALTER TABLE `tsms_users`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tsms_version`
--
ALTER TABLE `tsms_version`
  MODIFY `vid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3001;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
