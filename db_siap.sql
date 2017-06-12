-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 12, 2017 at 04:12 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_siap`
--

-- --------------------------------------------------------

--
-- Table structure for table `att_log`
--

CREATE TABLE IF NOT EXISTS `att_log` (
  `sn` varchar(30) NOT NULL,
  `scan_date` datetime NOT NULL,
  `pin` varchar(32) NOT NULL,
  `verifymode` int(11) NOT NULL,
  `inoutmode` int(11) NOT NULL DEFAULT '0',
  `reserved` int(11) NOT NULL DEFAULT '0',
  `work_code` int(11) NOT NULL DEFAULT '0',
  `att_id` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`,`scan_date`,`pin`),
  KEY `pin` (`pin`),
  KEY `sn` (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `att_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `cuti_normatif`
--

CREATE TABLE IF NOT EXISTS `cuti_normatif` (
  `cuti_n_id` int(11) NOT NULL DEFAULT '0',
  `cuti_n_nama` varchar(100) NOT NULL,
  `cuti_n_lama` smallint(6) NOT NULL DEFAULT '0',
  `nominal` float NOT NULL DEFAULT '0',
  `jns_bayar` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cuti_n_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cuti_normatif`
--

INSERT INTO `cuti_normatif` (`cuti_n_id`, `cuti_n_nama`, `cuti_n_lama`, `nominal`, `jns_bayar`) VALUES
(1, 'Cuti Hamil', 45, 0, 1),
(2, 'Cuti Menikah', 3, 0, 0),
(3, 'Cuti Khitan Anak', 2, 0, 1),
(4, 'Cuti Menikahkan Anak', 2, 0, 1),
(5, 'Cuti Keluarga Meninggal', 1, 0, 1),
(6, 'Cuti Pindah Rumah', 3, 0, 1),
(7, 'Cuti Membabtiskan Anak', 2, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `sn` varchar(30) NOT NULL DEFAULT '',
  `activation_code` varchar(50) NOT NULL,
  `act_code_realtime` varchar(50) DEFAULT NULL,
  `device_name` varchar(100) DEFAULT '',
  `comm_key` mediumint(9) DEFAULT '0' COMMENT 'password mesin',
  `dev_id` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'no mesin',
  `comm_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: ethernet, 1: usb, 2: serial',
  `ip_address` varchar(30) DEFAULT '',
  `id_type` int(11) NOT NULL DEFAULT '0',
  `dev_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Jenis mesin = 0: ZK, 1: Hanvon, 2: Realand',
  `serial_port` varchar(30) DEFAULT '',
  `baud_rate` varchar(15) DEFAULT '',
  `ethernet_port` varchar(30) NOT NULL DEFAULT '4370',
  `layar` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: TFT, 1: BW',
  `alg_ver` tinyint(4) NOT NULL DEFAULT '10' COMMENT '9 & 10',
  `use_realtime` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'yes/no',
  `group_realtime` tinyint(4) NOT NULL DEFAULT '0',
  `last_download` date DEFAULT NULL,
  `ATTLOGStamp` varchar(50) NOT NULL DEFAULT '0',
  `OPERLOGStamp` varchar(50) NOT NULL DEFAULT '0',
  `ATTPHOTOStamp` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`sn`, `activation_code`, `act_code_realtime`, `device_name`, `comm_key`, `dev_id`, `comm_type`, `ip_address`, `id_type`, `dev_type`, `serial_port`, `baud_rate`, `ethernet_port`, `layar`, `alg_ver`, `use_realtime`, `group_realtime`, `last_download`, `ATTLOGStamp`, `OPERLOGStamp`, `ATTPHOTOStamp`) VALUES
('2251016030265', '8740A-CAD2-D7980-B561-9269C-992F-AAD64', NULL, 'Mesin 1', 0, 1, 0, '192.168.1.201', 6, 3, '', '', '5500', 0, 10, 0, 0, '2017-06-11', '0', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `dev_type`
--

CREATE TABLE IF NOT EXISTS `dev_type` (
  `dev_type` int(10) NOT NULL,
  `id_type` int(10) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_type`
--

INSERT INTO `dev_type` (`dev_type`, `id_type`, `type`) VALUES
(0, 1, 'New Premier Series'),
(0, 2, 'Elegant Series'),
(0, 3, 'Hybrid+ Series'),
(2, 4, 'Neo-151NC'),
(2, 5, 'SF-1000CNB'),
(3, 6, 'Revo 151 BNC'),
(2, 7, 'Neo-A152 NC'),
(3, 8, 'Revo FF 153 BNC'),
(4, 9, 'Livo 151 B'),
(3, 10, 'Revo D-152B'),
(3, 11, 'Revo FF-157NB'),
(3, 12, 'Revo-155BNC'),
(3, 13, 'Revo-156BNC'),
(3, 14, 'Revo Duo-158BNC'),
(0, 15, 'Hybrid Pro Series');

-- --------------------------------------------------------

--
-- Table structure for table `ganti_jdw_d`
--

CREATE TABLE IF NOT EXISTS `ganti_jdw_d` (
  `ganti_jdw_id` int(11) NOT NULL,
  `tgl_ganti_jdw` date NOT NULL,
  `jns_ganti_jdw` tinyint(4) NOT NULL COMMENT '0: Ganti Jadwal Kerja, 1: Ganti Jadwal Bagian, 2: Ganti Jadwal Pegawai (sesuai prioritas rendah ke tinggi)',
  `jdw_kerja_m_id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL DEFAULT '0' COMMENT '0: Selain Pegawai',
  PRIMARY KEY (`ganti_jdw_id`,`tgl_ganti_jdw`,`pegawai_id`,`jns_ganti_jdw`,`jdw_kerja_m_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jdw_d`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jdw_jk`
--

CREATE TABLE IF NOT EXISTS `ganti_jdw_jk` (
  `ganti_jdw_id` int(11) NOT NULL,
  `jdw_kerja_m_id1` int(11) NOT NULL,
  `jdw_kerja_m_id2` int(11) NOT NULL,
  `tgl_awal` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  PRIMARY KEY (`ganti_jdw_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jdw_jk`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jdw_pegawai`
--

CREATE TABLE IF NOT EXISTS `ganti_jdw_pegawai` (
  `ganti_jdw_id` int(11) NOT NULL DEFAULT '0',
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `tgl_awal` date NOT NULL DEFAULT '0000-00-00',
  `tgl_akhir` date NOT NULL DEFAULT '0000-00-00',
  `jdw_kerja_m_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Jadwal pengganti',
  `keterangan` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ganti_jdw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `ganti_jdw_pegawai`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jdw_pembagian`
--

CREATE TABLE IF NOT EXISTS `ganti_jdw_pembagian` (
  `ganti_jdw_id` int(11) NOT NULL DEFAULT '0',
  `pembagian1_id` int(11) NOT NULL DEFAULT '0',
  `pembagian2_id` int(11) NOT NULL DEFAULT '0',
  `pembagian3_id` int(11) NOT NULL DEFAULT '0',
  `tgl_awal` date NOT NULL DEFAULT '0000-00-00',
  `tgl_akhir` date NOT NULL DEFAULT '0000-00-00',
  `jdw_kerja_m_id` int(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ganti_jdw_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jdw_pembagian`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jk`
--

CREATE TABLE IF NOT EXISTS `ganti_jk` (
  `ganti_jk_id` int(11) NOT NULL,
  `jk_id1` int(11) NOT NULL,
  `jk_id2` int(11) NOT NULL,
  `tgl_awal` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `keterangan` varchar(200) NOT NULL,
  PRIMARY KEY (`ganti_jk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jk`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jk_d`
--

CREATE TABLE IF NOT EXISTS `ganti_jk_d` (
  `ganti_jk_id` int(11) NOT NULL,
  `tgl_ganti_jk` date NOT NULL,
  `jns_ganti_jk` tinyint(4) NOT NULL COMMENT '0: Ganti Jam Kerja, 1: Ganti Jam Bagian, 2: Ganti Jam Pegawai (sesuai prioritas rendah ke tinggi)',
  `jk_id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL DEFAULT '0' COMMENT '0: Selain Pegawai',
  `libur` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ganti_jk_id`,`tgl_ganti_jk`,`pegawai_id`,`jns_ganti_jk`,`jk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jk_d`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jk_pegawai`
--

CREATE TABLE IF NOT EXISTS `ganti_jk_pegawai` (
  `ganti_jk_id` int(11) NOT NULL DEFAULT '0',
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `tgl_awal` date NOT NULL DEFAULT '0000-00-00',
  `tgl_akhir` date NOT NULL DEFAULT '0000-00-00',
  `jk_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Jam Kerja pengganti',
  `keterangan` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ganti_jk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `ganti_jk_pegawai`
--


-- --------------------------------------------------------

--
-- Table structure for table `ganti_jk_pembagian`
--

CREATE TABLE IF NOT EXISTS `ganti_jk_pembagian` (
  `ganti_jk_id` int(11) NOT NULL DEFAULT '0',
  `pembagian1_id` int(11) NOT NULL DEFAULT '0',
  `pembagian2_id` int(11) NOT NULL DEFAULT '0',
  `pembagian3_id` int(11) NOT NULL DEFAULT '0',
  `tgl_awal` date NOT NULL DEFAULT '0000-00-00',
  `tgl_akhir` date NOT NULL DEFAULT '0000-00-00',
  `jk_id` int(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ganti_jk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ganti_jk_pembagian`
--


-- --------------------------------------------------------

--
-- Table structure for table `grp_user_d`
--

CREATE TABLE IF NOT EXISTS `grp_user_d` (
  `grp_user_id` varchar(100) NOT NULL DEFAULT '',
  `tree_id` varchar(255) NOT NULL,
  `level_tree` smallint(3) NOT NULL DEFAULT '0',
  `com_id` varchar(100) NOT NULL,
  `com_form` varchar(100) NOT NULL,
  `com_name` varchar(100) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `urutan` smallint(3) NOT NULL,
  `app_name` varchar(100) NOT NULL DEFAULT '',
  UNIQUE KEY `com_id` (`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grp_user_d`
--

INSERT INTO `grp_user_d` (`grp_user_id`, `tree_id`, `level_tree`, `com_id`, `com_form`, `com_name`, `caption`, `urutan`, `app_name`) VALUES
('/1/2/3/', 'Fingerspot Personnel', 0, '1', 'frm_main', '', 'Fingerspot Personnel', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pegawai', 1, '1/1', 'frm_main', 'btn_pegawai', 'Pegawai', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif', 2, '1/1/1', 'frm_pegawai', 'spnl_aktif', 'Pegawai Aktif', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Tambah Data', 3, '1/1/1/1', 'frm_pegawai', 'btn_tambah', 'Tambah Pegawai', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Ubah Data', 3, '1/1/1/2', 'frm_pegawai', 'dbg_pegawaiubah', 'Ubah Pegawai', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Hapus Data', 3, '1/1/1/3', 'frm_pegawai', 'dbg_pegawaihapus', 'Hapus Pegawai', 3, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Ganti Status', 3, '1/1/1/4', 'frm_pegawai', 'btn_ganti_stat', 'Ganti Status', 4, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Pengecualian', 3, '1/1/1/5', 'frm_pegawai', 'btn_pengecualian', 'Pengecualian', 5, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai non Aktif', 2, '1/1/2', 'frm_pegawai', 'spnl_non_aktif', 'Pegawai non Aktif', 2, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai non Aktif\\Aktifkan Pegawai', 3, '1/1/2/1', 'frm_pegawai', 'btn_aktifkan', 'Aktifkan Pegawai', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai non Aktif\\Berhentikan Pegawai', 3, '1/1/2/2', 'frm_pegawai', 'btn_berhenti', 'Berhentikan Pegawai', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pegawai\\Pegawai Berhenti', 2, '1/1/3', 'frm_pegawai', 'spnl_berhenti', 'Pegawai Berhenti', 3, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pegawai\\Pegawai Berhenti\\Hapus Pegawai', 3, '1/1/3/1', 'frm_pegawai', 'btn_hapus', 'Hapus', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Segera Habis Kontrak', 2, '1/1/4', 'frm_pegawai', 'spnl_habis', 'Pegawai Segera Habis Kontrak', 4, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pegawai\\Pegawai Segera Habis Kontrak\\Nonaktifkan Pegawai', 3, '1/1/4/1', 'frm_pegawai', 'btn_non_aktifkan', 'Nonaktifkan Pegawai', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Segera Habis Kontrak\\Kontrak Kerja', 3, '1/1/4/2', 'frm_pegawai', 'btn_kontrak_kerja', 'Kontrak Kerja', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian', 1, '1/2', 'frm_main', 'btn_pengecualian', 'Pengecualian', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Izin, Terlambat, Cuti - [Kolektif]', 2, '1/2/1', 'frm_pengecualian', 'spnl_itc', 'Ijin, Terlambat, Cuti - [Kolektif]', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Izin, Terlambat, Cuti - [Kolektif]\\Tambah Izin', 3, '1/2/1/1', 'frm_pengecualian', 'btn_add_ict', 'Tambah Izin', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Izin, Terlambat, Cuti - [Personal]', 2, '1/2/2', 'frm_pengecualian', 'spnl_itc_personal', 'Ijin, Terlambat, Cuti - [Personal]', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Izin, Terlambat, Cuti - [Personal]\\Tambah Izin', 3, '1/2/2/1', 'frm_pengecualian', 'btn_add_ict_personal', 'Tambah Izin', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Izin, Terlambat, Cuti - [Personal]\\Ganti Status', 3, '1/2/2/2', 'frm_pengecualian', 'dbg_izin_personalizin_status', 'Ganti Status', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja', 2, '1/2/3', 'frm_pengecualian', 'spnl_ganti_jadwal', 'Ganti Jadwal Kerja', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja\\Tambah Data', 3, '1/2/3/1', 'frm_pengecualian', 'btn_add_ganti_jdw', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja\\Ubah Data', 3, '1/2/3/2', 'frm_pengecualian', 'dbg_ganti_jadwalubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja\\Hapus Data', 3, '1/2/3/3', 'frm_pengecualian', 'dbg_ganti_jadwalhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Bagian', 2, '1/2/4', 'frm_pengecualian', 'spnl_ganti_jadwal_bag', 'Ganti Jadwal Kerja per Bagian', 4, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Bagian\\Tambah Data', 3, '1/2/4/1', 'frm_pengecualian', 'btn_add_ganti_jdw_bag', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Bagian\\Ubah Data', 3, '1/2/4/2', 'frm_pengecualian', 'dbg_ganti_jdw_bagubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Bagian\\Hapus Data', 3, '1/2/4/3', 'frm_pengecualian', 'dbg_ganti_jdw_baghapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Pegawai', 2, '1/2/5', 'frm_pengecualian', 'spnl_ganti_jadwal_peg', 'Ganti Jadwal Kerja per Pegawai', 5, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Pegawai\\Tambah Data', 3, '1/2/5/1', 'frm_pengecualian', 'btn_add_ganti_jdw_peg', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Pegawai\\Ubah Data', 3, '1/2/5/2', 'frm_pengecualian', 'dbg_ganti_jdw_pegubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jadwal Kerja per Pegawai\\Hapus Data', 3, '1/2/5/3', 'frm_pengecualian', 'dbg_ganti_jdw_peghapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja', 2, '1/2/6', 'frm_pengecualian', 'spnl_ganti_jam', 'Ganti Jam Kerja', 6, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja\\Tambah Data', 3, '1/2/6/1', 'frm_pengecualian', 'btn_add_ganti_jam', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja\\Ubah Data', 3, '1/2/6/2', 'frm_pengecualian', 'dbg_ganti_jamubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja\\Hapus Data', 3, '1/2/6/3', 'frm_pengecualian', 'dbg_ganti_jamhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Bagian', 2, '1/2/7', 'frm_pengecualian', 'spnl_ganti_jam_bag', 'Ganti Jam Kerja per Bagian', 7, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Bagian\\Tambah Data', 3, '1/2/7/1', 'frm_pengecualian', 'btn_add_ganti_jam_bag', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Bagian\\Ubah Data', 3, '1/2/7/2', 'frm_pengecualian', 'dbg_ganti_jam_bagubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Bagian\\Hapus Data', 3, '1/2/7/3', 'frm_pengecualian', 'dbg_ganti_jam_baghapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Pegawai', 2, '1/2/8', 'frm_pengecualian', 'spnl_ganti_jam_peg', 'Ganti Jam Kerja per Pegawai', 8, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Pegawai\\Tambah Data', 3, '1/2/8/1', 'frm_pengecualian', 'btn_add_ganti_jam_peg', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Pegawai\\Ubah Data', 3, '1/2/8/2', 'frm_pengecualian', 'dbg_ganti_jam_pegubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Ganti Jam Kerja per Pegawai\\Hapus Data', 3, '1/2/8/3', 'frm_pengecualian', 'dbg_ganti_jam_peghapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Tukar Jam Kerja', 2, '1/2/9', 'frm_pengecualian', 'spnl_tukar_jam', 'Tukar Jam Kerja', 9, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Tukar Jam Kerja\\Tambah Data', 3, '1/2/9/1', 'frm_pengecualian', 'btn_add_tukar', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Tukar Jam Kerja\\Ubah Data', 3, '1/2/9/2', 'frm_pengecualian', 'dbg_tukarubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Tukar Jam Kerja\\Hapus Data', 3, '1/2/9/3', 'frm_pengecualian', 'dbg_tukarhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Lembur', 2, '1/2/10', 'frm_pengecualian', 'spnl_lembur', 'Lembur', 10, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Lembur\\Tambah Data', 3, '1/2/10/1', 'frm_pengecualian', 'btn_add_lembur', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Lembur\\Ubah Data', 3, '1/2/10/2', 'frm_pengecualian', 'dbg_lemburubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Lembur\\Hapus Data', 3, '1/2/10/3', 'frm_pengecualian', 'dbg_lemburhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Kerja Extra Per Hari', 2, '1/2/11', 'frm_pengecualian', 'spnl_kerja_ex', 'Kerja Extra Per Hari', 11, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengecualian\\Kerja Extra Per Hari\\Tambah Data', 3, '1/2/11/1', 'frm_pengecualian', 'btn_add_kerja_ex', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengecualian\\Kerja Extra Per Hari\\Ubah Data', 3, '1/2/11/2', 'frm_pengecualian', 'dbg_kerja_exubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengecualian\\Kerja Extra Per Hari\\Hapus Data', 3, '1/2/11/3', 'frm_pengecualian', 'dbg_kerja_exhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Mesin', 1, '1/3', 'frm_main', 'btn_mesin', 'Mesin', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Mesin\\Export', 2, '1/3/1', 'frm_mesin', 'btn_export_mesin', 'Export', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Mesin\\Cetak', 2, '1/3/2', 'frm_mesin', 'btn_print_mesin', 'Cetak', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Mesin\\Download User', 2, '1/3/3', 'frm_mesin', 'btn_download', 'Download User', 3, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Mesin\\Upload User', 2, '1/3/4', 'frm_mesin', 'btn_upload', 'Upload User', 4, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Mesin\\Hapus User di Mesin', 2, '1/3/5', 'frm_mesin', 'btn_del_user_dev', 'Hapus User di Mesin', 5, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Mesin\\Download Scanlog', 2, '1/3/6', 'frm_mesin', 'btn_dl_scanlog', 'Download Scanlog', 6, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Mesin\\Pengaturan Mesin', 2, '1/3/7', 'frm_mesin', 'btn_setting', 'Pengaturan Mesin', 7, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Laporan', 1, '1/4', 'frm_main', 'btn_laporan', 'Laporan', 4, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Laporan\\Export', 2, '1/4/1', 'frm_laporan', 'btn_export_laporan', 'Export', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Laporan\\Cetak', 2, '1/4/2', 'frm_laporan', 'btn_print_laporan', 'Cetak', 2, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Laporan\\Proses Absensi', 2, '1/4/3', 'frm_laporan', 'btn_proses_laporan', 'Proses Absensi', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan', 1, '1/5', 'frm_main', 'btn_pengaturan', 'Pengaturan', 5, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Umum', 2, '1/5/1', 'frm_pengaturan', 'spnl_umum', 'Umum', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jam Kerja', 2, '1/5/2', 'frm_pengaturan', 'spnl_jam_kerja', 'Jam Kerja', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jam Kerja\\Tambah Data', 3, '1/5/2/1', 'frm_pengaturan', 'btn_add_jam_kerja', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Jam Kerja\\Ubah Data', 3, '1/5/2/2', 'frm_pengaturan', 'btn_ubah_jam_kerja', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Jam Kerja\\Hapus Data', 3, '1/5/2/3', 'frm_pengaturan', 'btn_del_jam_kerja', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Normal', 2, '1/5/3', 'frm_pengaturan', 'spnl_jadwal_kerja_normal', 'Jadwal Kerja Normal', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Normal\\Tambah Data', 3, '1/5/3/1', 'frm_pengaturan', 'btn_add_jdw', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Normal\\Ubah Data', 3, '1/5/3/2', 'frm_pengaturan', 'btn_ubah_jdw', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Normal\\Hapus Data', 3, '1/5/3/3', 'frm_pengaturan', 'btn_del_jdw', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Berpola', 2, '1/5/4', 'frm_pengaturan', 'spnl_jadwal_kerja_pola', 'Jadwal Kerja Berpola', 4, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Berpola\\Tambah Data', 3, '1/5/4/1', 'frm_pengaturan', 'btn_add_jdw_p', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Berpola\\Ubah Data', 3, '1/5/4/2', 'frm_pengaturan', 'btn_edit_jdw_p', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Berpola\\Hapus Data', 3, '1/5/4/3', 'frm_pengaturan', 'btn_del_jdw_p', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Auto', 2, '1/5/5', 'frm_pengaturan', 'spnl_jdw_auto', 'Jadwal Kerja Auto', 5, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Auto\\Tambah Data', 3, '1/5/5/1', 'frm_pengaturan', 'btn_add_jdw_auto', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Auto\\Ubah Data', 3, '1/5/5/2', 'frm_pengaturan', 'btn_edit_jdw_auto', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Jadwal Kerja Auto\\Hapus Data', 3, '1/5/5/3', 'frm_pengaturan', 'btn_del_jdw_auto', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai', 2, '1/5/6', 'frm_pengaturan', 'spnl_pembagian', 'Pembagian Pegawai', 6, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 1', 3, '1/5/6/1', 'frm_pengaturan', 'tsb_pemb1', 'Pembagian 1', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 1\\Tambah Data', 4, '1/5/6/1/1', 'frm_pengaturan', 'btn_add_p1', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 1\\Ubah Data', 4, '1/5/6/1/2', 'frm_pengaturan', 'dbg_pembagian1ubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 1\\Hapus Data', 4, '1/5/6/1/3', 'frm_pengaturan', 'dbg_pembagian1hapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 2', 3, '1/5/6/2', 'frm_pengaturan', 'tsb_pemb2', 'Pembagian 2', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 2\\Tambah Data', 4, '1/5/6/2/1', 'frm_pengaturan', 'btn_add_p2', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 2\\Ubah Data', 4, '1/5/6/2/2', 'frm_pengaturan', 'dbg_pembagian2ubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 2\\Hapus Data', 4, '1/5/6/2/3', 'frm_pengaturan', 'dbg_pembagian2hapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 3', 3, '1/5/6/3', 'frm_pengaturan', 'tsb_pemb3', 'Pembagian 3', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 3\\Tambah Data', 4, '1/5/6/3/1', 'frm_pengaturan', 'btn_add_p3', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 3\\Ubah Data', 4, '1/5/6/3/2', 'frm_pengaturan', 'dbg_pembagian3ubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Pembagian Pegawai\\Pembagian 3\\Hapus Data', 4, '1/5/6/3/3', 'frm_pengaturan', 'dbg_pembagian3hapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Cuti Normatif', 2, '1/5/7', 'frm_pengaturan', 'spnl_cuti_norm', 'Cuti Normatif', 7, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Cuti Normatif\\Tambah Data', 3, '1/5/7/1', 'frm_pengaturan', 'btn_add_cuti_n', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Cuti Normatif\\Ubah Data', 3, '1/5/7/2', 'frm_pengaturan', 'dbg_cuti_normubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Cuti Normatif\\Hapus Data', 3, '1/5/7/3', 'frm_pengaturan', 'dbg_cuti_normhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jatah Cuti Pegawai', 2, '1/5/8', 'frm_pengaturan', 'spnl_jatah_cuti', 'Jatah Cuti Pegawai', 8, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Jatah Cuti Pegawai\\Tambah Data', 3, '1/5/8/1', 'frm_pengaturan', 'btn_tambah_jatah_c', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Jatah Cuti Pegawai\\Ubah Data', 3, '1/5/8/2', 'frm_pengaturan', 'dbg_jatah_cutiubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Jatah Cuti Pegawai\\Hapus History', 3, '1/5/8/3', 'frm_pengaturan', 'dbg_jatah_c_historyhapus', 'Hapus History', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Kategori Keterangan Izin', 2, '1/5/9', 'frm_pengaturan', 'spnl_ket_izin', 'Kategori Keterangan Izin', 9, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Kategori Keterangan Izin\\Tambah Data', 3, '1/5/9/1', 'frm_pengaturan', 'btn_add_ket_izin', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Kategori Keterangan Izin\\Ubah Data', 3, '1/5/9/2', 'frm_pengaturan', 'dbg_kat_izinubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Kategori Keterangan Izin\\Hapus Data', 3, '1/5/9/3', 'frm_pengaturan', 'dbg_kat_izinhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Mesin', 2, '1/5/10', 'frm_pengaturan', 'spnl_mesin', 'Mesin', 10, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Mesin\\Tambah Data', 3, '1/5/10/1', 'frm_pengaturan', 'btn_add_mesin', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Mesin\\Ubah Data', 3, '1/5/10/2', 'frm_pengaturan', 'dbg_mesinubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Mesin\\Hapus Data', 3, '1/5/10/3', 'frm_pengaturan', 'dbg_mesinhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Hari Libur - Cuti Bersama', 2, '1/5/11', 'frm_pengaturan', 'spnl_libur_cuti', 'Hari Libur - Cuti Bersama', 11, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Hari Libur - Cuti Bersama\\Tambah Data', 3, '1/5/11/1', 'frm_pengaturan', 'btn_add_libur_cuti', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Hari Libur - Cuti Bersama\\Ubah Data', 3, '1/5/11/2', 'frm_pengaturan', 'dbg_hr_liburubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Hari Libur - Cuti Bersama\\Hapus Data', 3, '1/5/11/3', 'frm_pengaturan', 'dbg_hr_liburhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Import', 2, '1/5/12', 'frm_pengaturan', 'spnl_import', 'Import', 12, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pengguna', 2, '1/5/13', 'frm_pengaturan', 'spnl_pengguna', 'Pengguna', 13, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\Grup Akses', 3, '1/5/13/1', 'frm_pengaturan', 'tsb_grup', 'Grup Akses', 1, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\Grup Akses\\Tambah Data', 3, '1/5/13/1/1', 'frm_pengaturan', 'btn_add_grup', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\Grup Akses\\Ubah Data', 3, '1/5/13/1/2', 'frm_pengaturan', 'dbg_group_aksesubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\Grup Akses\\Hapus Data', 3, '1/5/13/1/3', 'frm_pengaturan', 'dbg_group_akseshapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\User Login', 3, '1/5/13/2', 'frm_pengaturan', 'tsb_pengguna', 'User Login', 2, 'Fingerspot Personnel'),
('/1/2/3/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\User Login\\Tambah Data', 3, '1/5/13/2/1', 'frm_pengaturan', 'btn_add_user', 'Tambah Data', 1, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\User Login\\Ubah Data', 3, '1/5/13/2/2', 'frm_pengaturan', 'dbg_userubah', 'Ubah Data', 2, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Pengaturan\\Pengguna\\User Login\\Hapus Data', 3, '1/5/13/2/3', 'frm_pengaturan', 'dbg_userhapus', 'Hapus Data', 3, 'Fingerspot Personnel'),
('/1/', 'Fingerspot Personnel\\Penggajian', 1, '1/6', 'frm_main', 'btn_penggajian', 'Penggajian', 6, 'Fingerspot Personnel'),
('/1/2/', 'Fingerspot Personnel\\Pegawai\\Pegawai Aktif\\Jadwal Pegawai', 3, '1/1/1/6', 'frm_pegawai', 'btn_jadwal_peg', 'Jadwal Pegawai', 6, 'Fingerspot Personnel');

-- --------------------------------------------------------

--
-- Table structure for table `grp_user_m`
--

CREATE TABLE IF NOT EXISTS `grp_user_m` (
  `grp_user_id` int(11) NOT NULL,
  `grp_user_name` varchar(100) NOT NULL DEFAULT '',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(100) NOT NULL,
  `detector` text NOT NULL,
  PRIMARY KEY (`grp_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grp_user_m`
--

INSERT INTO `grp_user_m` (`grp_user_id`, `grp_user_name`, `lastupdate_date`, `lastupdate_user`, `detector`) VALUES
(3, 'Operator', '2015-04-30 10:03:22', 'Fingerspot', '#MysxOCpueXFUL1ZzNA==#MysxLzE4Km55cVQvVnM0#MysxLzEvMTgqbnlxVC9WczQ=#MysxLzEvMS8xOCpueXFUL1ZzNA==#MysxLzI4Km55cVQvVnM0#MysxLzIvMTgqbnlxVC9WczQ=#MysxLzIvMS8xOCpueXFUL1ZzNA==#MysxLzIvMjgqbnlxVC9WczQ=#MysxLzIvMi8xOCpueXFUL1ZzNA==#MysxLzIvMzgqbnlxVC9WczQ=#MysxLzIvMy8xOCpueXFUL1ZzNA==#MysxLzIvNDgqbnlxVC9WczQ=#MysxLzIvNC8xOCpueXFUL1ZzNA==#MysxLzIvNTgqbnlxVC9WczQ=#MysxLzIvNS8xOCpueXFUL1ZzNA==#MysxLzIvNjgqbnlxVC9WczQ=#MysxLzIvNi8xOCpueXFUL1ZzNA==#MysxLzIvNzgqbnlxVC9WczQ=#MysxLzIvNy8xOCpueXFUL1ZzNA==#MysxLzIvODgqbnlxVC9WczQ=#MysxLzIvOC8xOCpueXFUL1ZzNA==#MysxLzIvOTgqbnlxVC9WczQ=#MysxLzIvOS8xOCpueXFUL1ZzNA==#MysxLzIvMTA4Km55cVQvVnM0#MysxLzIvMTAvMTgqbnlxVC9WczQ=#MysxLzIvMTE4Km55cVQvVnM0#MysxLzIvMTEvMTgqbnlxVC9WczQ=#MysxLzM4Km55cVQvVnM0#MysxLzMvMTgqbnlxVC9WczQ=#MysxLzMvMjgqbnlxVC9WczQ=#MysxLzMvMzgqbnlxVC9WczQ=#MysxLzQ4Km55cVQvVnM0#MysxLzQvMTgqbnlxVC9WczQ=#MysxLzQvMjgqbnlxVC9WczQ=#MysxLzU4Km55cVQvVnM0#MysxLzUvMjgqbnlxVC9WczQ=#MysxLzUvMi8xOCpueXFUL1ZzNA==#MysxLzUvMzgqbnlxVC9WczQ=#MysxLzUvMy8xOCpueXFUL1ZzNA==#MysxLzUvNDgqbnlxVC9WczQ=#MysxLzUvNC8xOCpueXFUL1ZzNA==#MysxLzUvNTgqbnlxVC9WczQ=#MysxLzUvNS8xOCpueXFUL1ZzNA==#MysxLzUvNjgqbnlxVC9WczQ=#MysxLzUvNi8xOCpueXFUL1ZzNA==#MysxLzUvNi8xLzE4Km55cVQvVnM0#MysxLzUvNi8yOCpueXFUL1ZzNA==#MysxLzUvNi8yLzE4Km55cVQvVnM0#MysxLzUvNi8zOCpueXFUL1ZzNA==#MysxLzUvNi8zLzE4Km55cVQvVnM0#MysxLzUvNzgqbnlxVC9WczQ=#MysxLzUvNy8xOCpueXFUL1ZzNA==#MysxLzUvODgqbnlxVC9WczQ=#MysxLzUvOC8xOCpueXFUL1ZzNA==#MysxLzUvOTgqbnlxVC9WczQ=#MysxLzUvOS8xOCpueXFUL1ZzNA==#MysxLzUvMTA4Km55cVQvVnM0#MysxLzUvMTAvMTgqbnlxVC9WczQ=#MysxLzUvMTE4Km55cVQvVnM0#MysxLzUvMTEvMTgqbnlxVC9WczQ=#MysxLzUvMTI4Km55cVQvVnM0#MysxLzUvMTM4Km55cVQvVnM0#MysxLzUvMTMvMTgqbnlxVC9WczQ=#MysxLzUvMTMvMS8xOCpueXFUL1ZzNA==#MysxLzUvMTMvMjgqbnlxVC9WczQ=#MysxLzUvMTMvMi8xOCpueXFUL1ZzNA=='),
(2, 'Sub Admin', '2015-04-29 09:57:39', 'Fingerspot', '#MisxOCpueXFUL1ZzNA==#MisxLzE4Km55cVQvVnM0#MisxLzEvMTgqbnlxVC9WczQ=#MisxLzEvMS8xOCpueXFUL1ZzNA==#MisxLzEvMS8yOCpueXFUL1ZzNA==#MisxLzEvMS80OCpueXFUL1ZzNA==#MisxLzEvMS81OCpueXFUL1ZzNA==#MisxLzEvMjgqbnlxVC9WczQ=#MisxLzEvMi8xOCpueXFUL1ZzNA==#MisxLzEvMi8yOCpueXFUL1ZzNA==#MisxLzEvNDgqbnlxVC9WczQ=#MisxLzEvNC8yOCpueXFUL1ZzNA==#MisxLzI4Km55cVQvVnM0#MisxLzIvMTgqbnlxVC9WczQ=#MisxLzIvMS8xOCpueXFUL1ZzNA==#MisxLzIvMjgqbnlxVC9WczQ=#MisxLzIvMi8xOCpueXFUL1ZzNA==#MisxLzIvMi8yOCpueXFUL1ZzNA==#MisxLzIvMzgqbnlxVC9WczQ=#MisxLzIvMy8xOCpueXFUL1ZzNA==#MisxLzIvMy8yOCpueXFUL1ZzNA==#MisxLzIvNDgqbnlxVC9WczQ=#MisxLzIvNC8xOCpueXFUL1ZzNA==#MisxLzIvNC8yOCpueXFUL1ZzNA==#MisxLzIvNTgqbnlxVC9WczQ=#MisxLzIvNS8xOCpueXFUL1ZzNA==#MisxLzIvNS8yOCpueXFUL1ZzNA==#MisxLzIvNjgqbnlxVC9WczQ=#MisxLzIvNi8xOCpueXFUL1ZzNA==#MisxLzIvNi8yOCpueXFUL1ZzNA==#MisxLzIvNzgqbnlxVC9WczQ=#MisxLzIvNy8xOCpueXFUL1ZzNA==#MisxLzIvNy8yOCpueXFUL1ZzNA==#MisxLzIvODgqbnlxVC9WczQ=#MisxLzIvOC8xOCpueXFUL1ZzNA==#MisxLzIvOC8yOCpueXFUL1ZzNA==#MisxLzIvOTgqbnlxVC9WczQ=#MisxLzIvOS8xOCpueXFUL1ZzNA==#MisxLzIvOS8yOCpueXFUL1ZzNA==#MisxLzIvMTA4Km55cVQvVnM0#MisxLzIvMTAvMTgqbnlxVC9WczQ=#MisxLzIvMTAvMjgqbnlxVC9WczQ=#MisxLzIvMTE4Km55cVQvVnM0#MisxLzIvMTEvMTgqbnlxVC9WczQ=#MisxLzIvMTEvMjgqbnlxVC9WczQ=#MisxLzM4Km55cVQvVnM0#MisxLzMvMTgqbnlxVC9WczQ=#MisxLzMvMjgqbnlxVC9WczQ=#MisxLzMvMzgqbnlxVC9WczQ=#MisxLzMvNDgqbnlxVC9WczQ=#MisxLzMvNTgqbnlxVC9WczQ=#MisxLzMvNjgqbnlxVC9WczQ=#MisxLzQ4Km55cVQvVnM0#MisxLzQvMTgqbnlxVC9WczQ=#MisxLzQvMjgqbnlxVC9WczQ=#MisxLzQvMzgqbnlxVC9WczQ=#MisxLzU4Km55cVQvVnM0#MisxLzUvMTgqbnlxVC9WczQ=#MisxLzUvMjgqbnlxVC9WczQ=#MisxLzUvMi8xOCpueXFUL1ZzNA==#MisxLzUvMi8yOCpueXFUL1ZzNA==#MisxLzUvMzgqbnlxVC9WczQ=#MisxLzUvMy8xOCpueXFUL1ZzNA==#MisxLzUvMy8yOCpueXFUL1ZzNA==#MisxLzUvNDgqbnlxVC9WczQ=#MisxLzUvNC8xOCpueXFUL1ZzNA==#MisxLzUvNC8yOCpueXFUL1ZzNA==#MisxLzUvNTgqbnlxVC9WczQ=#MisxLzUvNS8xOCpueXFUL1ZzNA==#MisxLzUvNS8yOCpueXFUL1ZzNA==#MisxLzUvNjgqbnlxVC9WczQ=#MisxLzUvNi8xOCpueXFUL1ZzNA==#MisxLzUvNi8xLzE4Km55cVQvVnM0#MisxLzUvNi8xLzI4Km55cVQvVnM0#MisxLzUvNi8yOCpueXFUL1ZzNA==#MisxLzUvNi8yLzE4Km55cVQvVnM0#MisxLzUvNi8yLzI4Km55cVQvVnM0#MisxLzUvNi8zOCpueXFUL1ZzNA==#MisxLzUvNi8zLzE4Km55cVQvVnM0#MisxLzUvNi8zLzI4Km55cVQvVnM0#MisxLzUvNzgqbnlxVC9WczQ=#MisxLzUvNy8xOCpueXFUL1ZzNA==#MisxLzUvNy8yOCpueXFUL1ZzNA==#MisxLzUvODgqbnlxVC9WczQ=#MisxLzUvOC8xOCpueXFUL1ZzNA==#MisxLzUvOC8yOCpueXFUL1ZzNA==#MisxLzUvOTgqbnlxVC9WczQ=#MisxLzUvOS8xOCpueXFUL1ZzNA==#MisxLzUvOS8yOCpueXFUL1ZzNA==#MisxLzUvMTA4Km55cVQvVnM0#MisxLzUvMTAvMTgqbnlxVC9WczQ=#MisxLzUvMTAvMjgqbnlxVC9WczQ=#MisxLzUvMTE4Km55cVQvVnM0#MisxLzUvMTEvMTgqbnlxVC9WczQ=#MisxLzUvMTEvMjgqbnlxVC9WczQ=#MisxLzUvMTI4Km55cVQvVnM0#MisxLzUvMTM4Km55cVQvVnM0#MisxLzUvMTMvMTgqbnlxVC9WczQ=#MisxLzUvMTMvMS8xOCpueXFUL1ZzNA==#MisxLzUvMTMvMS8yOCpueXFUL1ZzNA==#MisxLzUvMTMvMjgqbnlxVC9WczQ=#MisxLzUvMTMvMi8xOCpueXFUL1ZzNA==#MisxLzUvMTMvMi8yOCpueXFUL1ZzNA==#MisxLzEvMS82OCpueXFUL1ZzNA=='),
(1, 'Administrator', '2015-06-08 13:58:24', 'Fingerspot', '#MSsxOCpueXFUL1ZzNA==#MSsxLzE4Km55cVQvVnM0#MSsxLzEvMTgqbnlxVC9WczQ=#MSsxLzEvMS8xOCpueXFUL1ZzNA==#MSsxLzEvMS8yOCpueXFUL1ZzNA==#MSsxLzEvMS8zOCpueXFUL1ZzNA==#MSsxLzEvMS80OCpueXFUL1ZzNA==#MSsxLzEvMS81OCpueXFUL1ZzNA==#MSsxLzEvMjgqbnlxVC9WczQ=#MSsxLzEvMi8xOCpueXFUL1ZzNA==#MSsxLzEvMi8yOCpueXFUL1ZzNA==#MSsxLzEvMzgqbnlxVC9WczQ=#MSsxLzEvMy8xOCpueXFUL1ZzNA==#MSsxLzEvNDgqbnlxVC9WczQ=#MSsxLzEvNC8xOCpueXFUL1ZzNA==#MSsxLzEvNC8yOCpueXFUL1ZzNA==#MSsxLzI4Km55cVQvVnM0#MSsxLzIvMTgqbnlxVC9WczQ=#MSsxLzIvMS8xOCpueXFUL1ZzNA==#MSsxLzIvMjgqbnlxVC9WczQ=#MSsxLzIvMi8xOCpueXFUL1ZzNA==#MSsxLzIvMi8yOCpueXFUL1ZzNA==#MSsxLzIvMzgqbnlxVC9WczQ=#MSsxLzIvMy8xOCpueXFUL1ZzNA==#MSsxLzIvMy8yOCpueXFUL1ZzNA==#MSsxLzIvMy8zOCpueXFUL1ZzNA==#MSsxLzIvNDgqbnlxVC9WczQ=#MSsxLzIvNC8xOCpueXFUL1ZzNA==#MSsxLzIvNC8yOCpueXFUL1ZzNA==#MSsxLzIvNC8zOCpueXFUL1ZzNA==#MSsxLzIvNTgqbnlxVC9WczQ=#MSsxLzIvNS8xOCpueXFUL1ZzNA==#MSsxLzIvNS8yOCpueXFUL1ZzNA==#MSsxLzIvNS8zOCpueXFUL1ZzNA==#MSsxLzIvNjgqbnlxVC9WczQ=#MSsxLzIvNi8xOCpueXFUL1ZzNA==#MSsxLzIvNi8yOCpueXFUL1ZzNA==#MSsxLzIvNi8zOCpueXFUL1ZzNA==#MSsxLzIvNzgqbnlxVC9WczQ=#MSsxLzIvNy8xOCpueXFUL1ZzNA==#MSsxLzIvNy8yOCpueXFUL1ZzNA==#MSsxLzIvNy8zOCpueXFUL1ZzNA==#MSsxLzIvODgqbnlxVC9WczQ=#MSsxLzIvOC8xOCpueXFUL1ZzNA==#MSsxLzIvOC8yOCpueXFUL1ZzNA==#MSsxLzIvOC8zOCpueXFUL1ZzNA==#MSsxLzIvOTgqbnlxVC9WczQ=#MSsxLzIvOS8xOCpueXFUL1ZzNA==#MSsxLzIvOS8yOCpueXFUL1ZzNA==#MSsxLzIvOS8zOCpueXFUL1ZzNA==#MSsxLzIvMTA4Km55cVQvVnM0#MSsxLzIvMTAvMTgqbnlxVC9WczQ=#MSsxLzIvMTAvMjgqbnlxVC9WczQ=#MSsxLzIvMTAvMzgqbnlxVC9WczQ=#MSsxLzIvMTE4Km55cVQvVnM0#MSsxLzIvMTEvMTgqbnlxVC9WczQ=#MSsxLzIvMTEvMjgqbnlxVC9WczQ=#MSsxLzIvMTEvMzgqbnlxVC9WczQ=#MSsxLzM4Km55cVQvVnM0#MSsxLzMvMTgqbnlxVC9WczQ=#MSsxLzMvMjgqbnlxVC9WczQ=#MSsxLzMvMzgqbnlxVC9WczQ=#MSsxLzMvNDgqbnlxVC9WczQ=#MSsxLzMvNTgqbnlxVC9WczQ=#MSsxLzMvNjgqbnlxVC9WczQ=#MSsxLzMvNzgqbnlxVC9WczQ=#MSsxLzQ4Km55cVQvVnM0#MSsxLzQvMTgqbnlxVC9WczQ=#MSsxLzQvMjgqbnlxVC9WczQ=#MSsxLzQvMzgqbnlxVC9WczQ=#MSsxLzU4Km55cVQvVnM0#MSsxLzUvMTgqbnlxVC9WczQ=#MSsxLzUvMjgqbnlxVC9WczQ=#MSsxLzUvMi8xOCpueXFUL1ZzNA==#MSsxLzUvMi8yOCpueXFUL1ZzNA==#MSsxLzUvMi8zOCpueXFUL1ZzNA==#MSsxLzUvMzgqbnlxVC9WczQ=#MSsxLzUvMy8xOCpueXFUL1ZzNA==#MSsxLzUvMy8yOCpueXFUL1ZzNA==#MSsxLzUvMy8zOCpueXFUL1ZzNA==#MSsxLzUvNDgqbnlxVC9WczQ=#MSsxLzUvNC8xOCpueXFUL1ZzNA==#MSsxLzUvNC8yOCpueXFUL1ZzNA==#MSsxLzUvNC8zOCpueXFUL1ZzNA==#MSsxLzUvNTgqbnlxVC9WczQ=#MSsxLzUvNS8xOCpueXFUL1ZzNA==#MSsxLzUvNS8yOCpueXFUL1ZzNA==#MSsxLzUvNS8zOCpueXFUL1ZzNA==#MSsxLzUvNjgqbnlxVC9WczQ=#MSsxLzUvNi8xOCpueXFUL1ZzNA==#MSsxLzUvNi8xLzE4Km55cVQvVnM0#MSsxLzUvNi8xLzI4Km55cVQvVnM0#MSsxLzUvNi8xLzM4Km55cVQvVnM0#MSsxLzUvNi8yOCpueXFUL1ZzNA==#MSsxLzUvNi8yLzE4Km55cVQvVnM0#MSsxLzUvNi8yLzI4Km55cVQvVnM0#MSsxLzUvNi8yLzM4Km55cVQvVnM0#MSsxLzUvNi8zOCpueXFUL1ZzNA==#MSsxLzUvNi8zLzE4Km55cVQvVnM0#MSsxLzUvNi8zLzI4Km55cVQvVnM0#MSsxLzUvNi8zLzM4Km55cVQvVnM0#MSsxLzUvNzgqbnlxVC9WczQ=#MSsxLzUvNy8xOCpueXFUL1ZzNA==#MSsxLzUvNy8yOCpueXFUL1ZzNA==#MSsxLzUvNy8zOCpueXFUL1ZzNA==#MSsxLzUvODgqbnlxVC9WczQ=#MSsxLzUvOC8xOCpueXFUL1ZzNA==#MSsxLzUvOC8yOCpueXFUL1ZzNA==#MSsxLzUvOC8zOCpueXFUL1ZzNA==#MSsxLzUvOTgqbnlxVC9WczQ=#MSsxLzUvOS8xOCpueXFUL1ZzNA==#MSsxLzUvOS8yOCpueXFUL1ZzNA==#MSsxLzUvOS8zOCpueXFUL1ZzNA==#MSsxLzUvMTA4Km55cVQvVnM0#MSsxLzUvMTAvMTgqbnlxVC9WczQ=#MSsxLzUvMTAvMjgqbnlxVC9WczQ=#MSsxLzUvMTAvMzgqbnlxVC9WczQ=#MSsxLzUvMTE4Km55cVQvVnM0#MSsxLzUvMTEvMTgqbnlxVC9WczQ=#MSsxLzUvMTEvMjgqbnlxVC9WczQ=#MSsxLzUvMTEvMzgqbnlxVC9WczQ=#MSsxLzUvMTI4Km55cVQvVnM0#MSsxLzUvMTM4Km55cVQvVnM0#MSsxLzUvMTMvMTgqbnlxVC9WczQ=#MSsxLzUvMTMvMS8xOCpueXFUL1ZzNA==#MSsxLzUvMTMvMS8yOCpueXFUL1ZzNA==#MSsxLzUvMTMvMS8zOCpueXFUL1ZzNA==#MSsxLzUvMTMvMjgqbnlxVC9WczQ=#MSsxLzUvMTMvMi8xOCpueXFUL1ZzNA==#MSsxLzUvMTMvMi8yOCpueXFUL1ZzNA==#MSsxLzUvMTMvMi8zOCpueXFUL1ZzNA==#MSsxLzY4Km55cVQvVnM0#MSsxLzEvMS82OCpueXFUL1ZzNA==');

-- --------------------------------------------------------

--
-- Table structure for table `index_ot`
--

CREATE TABLE IF NOT EXISTS `index_ot` (
  `index_id` tinyint(4) NOT NULL,
  `type_ot` tinyint(4) NOT NULL DEFAULT '0',
  `from_ot` smallint(6) NOT NULL,
  `to_ot` smallint(6) NOT NULL,
  `multiplier` float NOT NULL,
  PRIMARY KEY (`index_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `index_ot`
--

INSERT INTO `index_ot` (`index_id`, `type_ot`, `from_ot`, `to_ot`, `multiplier`) VALUES
(1, 4, 0, 60, 1),
(2, 4, 60, 120, 2),
(3, 4, 120, 1440, 3);

-- --------------------------------------------------------

--
-- Table structure for table `index_type`
--

CREATE TABLE IF NOT EXISTS `index_type` (
  `type_ot` tinyint(4) NOT NULL DEFAULT '0',
  `type_name` varchar(50) NOT NULL,
  PRIMARY KEY (`type_ot`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `index_type`
--

INSERT INTO `index_type` (`type_ot`, `type_name`) VALUES
(0, 'Hari kerja normal '),
(1, 'Hari libur rutin'),
(2, 'Hari libur umum'),
(3, 'Hari libur umum terpendek'),
(4, 'Semua hari sama');

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE IF NOT EXISTS `izin` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `izin_urutan` smallint(5) NOT NULL DEFAULT '0',
  `izin_tgl_pengajuan` date NOT NULL,
  `izin_tgl` date NOT NULL,
  `izin_jenis_id` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Foreign key ke tabel jns_izin',
  `izin_catatan` varchar(255) DEFAULT NULL,
  `izin_status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0:tidak di izinkan; 1: diizinkan',
  `izin_tinggal_t1` time DEFAULT NULL,
  `izin_tinggal_t2` time DEFAULT NULL,
  `cuti_n_id` int(11) DEFAULT '0',
  `izin_ket_lain` varchar(100) DEFAULT NULL,
  `izin_noscan_time` time DEFAULT NULL,
  `kat_izin_id` int(11) DEFAULT '0',
  `ket_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pegawai_id`,`izin_tgl`,`izin_jenis_id`,`izin_status`,`izin_urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `izin`
--


-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja`
--

CREATE TABLE IF NOT EXISTS `jam_kerja` (
  `jk_id` int(11) NOT NULL DEFAULT '0',
  `jk_name` varchar(100) NOT NULL DEFAULT '',
  `jk_kode` varchar(10) NOT NULL DEFAULT '',
  `use_set` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No',
  `jk_bcin` time NOT NULL DEFAULT '00:00:00',
  `jk_cin` smallint(6) NOT NULL DEFAULT '0',
  `jk_ecin` smallint(6) NOT NULL DEFAULT '0',
  `jk_tol_late` smallint(6) NOT NULL DEFAULT '0',
  `jk_use_ist` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No',
  `jk_ist1` time NOT NULL DEFAULT '00:00:00',
  `jk_ist2` time NOT NULL DEFAULT '00:00:00',
  `jk_tol_early` smallint(6) NOT NULL DEFAULT '0',
  `jk_bcout` smallint(6) NOT NULL DEFAULT '0',
  `jk_cout` smallint(6) NOT NULL DEFAULT '0',
  `jk_ecout` time NOT NULL DEFAULT '00:00:00',
  `use_eot` tinyint(4) NOT NULL DEFAULT '0',
  `min_eot` smallint(6) NOT NULL DEFAULT '0',
  `max_eot` smallint(6) NOT NULL DEFAULT '0',
  `reduce_eot` smallint(6) NOT NULL DEFAULT '0',
  `jk_durasi` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1: Efektif, 2: Aktual',
  `jk_countas` float NOT NULL DEFAULT '0',
  `jk_min_countas` smallint(6) NOT NULL DEFAULT '0',
  `jk_ket` varchar(100) DEFAULT '',
  PRIMARY KEY (`jk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jam_kerja`
--


-- --------------------------------------------------------

--
-- Table structure for table `jam_kerja_extra`
--

CREATE TABLE IF NOT EXISTS `jam_kerja_extra` (
  `jke_tanggal` date NOT NULL DEFAULT '0000-00-00',
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `jk_id` int(11) NOT NULL DEFAULT '0',
  `jke_libur` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  PRIMARY KEY (`jke_tanggal`,`pegawai_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `jam_kerja_extra`
--


-- --------------------------------------------------------

--
-- Table structure for table `jatah_cuti`
--

CREATE TABLE IF NOT EXISTS `jatah_cuti` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `jatah_c_mulai` date NOT NULL DEFAULT '0000-00-00',
  `jatah_c_akhir` date NOT NULL DEFAULT '0000-00-00',
  `jatah_c_jml` smallint(6) DEFAULT '0',
  `jatah_c_hak_jml` smallint(6) DEFAULT '0',
  `jatah_c_ambil_jml` smallint(6) DEFAULT '0',
  `jatah_c_utang_jml` smallint(6) DEFAULT '0',
  PRIMARY KEY (`pegawai_id`,`jatah_c_mulai`,`jatah_c_akhir`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `jatah_cuti`
--


-- --------------------------------------------------------

--
-- Table structure for table `jdw_kerja_d`
--

CREATE TABLE IF NOT EXISTS `jdw_kerja_d` (
  `jdw_kerja_m_id` int(11) NOT NULL DEFAULT '0',
  `jdw_kerja_d_idx` smallint(6) NOT NULL DEFAULT '0' COMMENT '1:minggu; 2:senin; dst',
  `jk_id` int(11) NOT NULL DEFAULT '0',
  `jdw_kerja_d_hari` varchar(50) DEFAULT NULL,
  `jdw_kerja_d_libur` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`jdw_kerja_m_id`,`jdw_kerja_d_idx`,`jk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `jdw_kerja_d`
--


-- --------------------------------------------------------

--
-- Table structure for table `jdw_kerja_m`
--

CREATE TABLE IF NOT EXISTS `jdw_kerja_m` (
  `jdw_kerja_m_id` int(11) NOT NULL DEFAULT '0',
  `jdw_kerja_m_kode` varchar(5) DEFAULT NULL,
  `jdw_kerja_m_name` varchar(100) DEFAULT NULL,
  `jdw_kerja_m_keterangan` varchar(255) DEFAULT NULL,
  `jdw_kerja_m_periode` smallint(6) DEFAULT '0',
  `jdw_kerja_m_mulai` date DEFAULT NULL,
  `jdw_kerja_m_type` tinyint(3) DEFAULT '0' COMMENT '0: Normal; 1: Pola; 2: Auto',
  `use_sama` tinyint(3) DEFAULT '-1' COMMENT 'Jam kerja setiap hari sama / tidak',
  PRIMARY KEY (`jdw_kerja_m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `jdw_kerja_m`
--


-- --------------------------------------------------------

--
-- Table structure for table `jdw_kerja_pegawai`
--

CREATE TABLE IF NOT EXISTS `jdw_kerja_pegawai` (
  `pegawai_id` int(11) NOT NULL,
  `jdw_kerja_m_id` int(11) NOT NULL,
  `jdw_kerja_m_mulai` date NOT NULL,
  PRIMARY KEY (`pegawai_id`,`jdw_kerja_m_id`,`jdw_kerja_m_mulai`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jdw_kerja_pegawai`
--


-- --------------------------------------------------------

--
-- Table structure for table `jns_izin`
--

CREATE TABLE IF NOT EXISTS `jns_izin` (
  `izin_jenis_id` smallint(6) NOT NULL,
  `izin_jenis_name` varchar(200) NOT NULL,
  `flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Default, 1: Normatif',
  PRIMARY KEY (`izin_jenis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jns_izin`
--

INSERT INTO `jns_izin` (`izin_jenis_id`, `izin_jenis_name`, `flag`) VALUES
(10, 'Izin tidak masuk', 0),
(20, 'Izin pulang awal', 0),
(30, 'Izin datang terlambat', 0),
(40, 'Sakit dengan surat dokter', 0),
(50, 'Sakit tanpa surat dokter', 0),
(60, 'Izin meninggalkan tempat kerja', 0),
(70, 'Izin dinas (perjalanan dinas)', 0),
(71, 'Izin dinas (datang terlambat)', 0),
(72, 'Izin dinas (pulang awal)', 0),
(80, 'Cuti normatif', 0),
(90, 'Cuti pribadi', 0),
(100, 'Tidak scan (masuk)', 0),
(101, 'Tidak scan (pulang)', 0),
(102, 'Tidak scan (mulai istirahat)', 0),
(103, 'Tidak scan (selesai istirahat)', 0),
(104, 'Tidak scan (mulai lembur)', 0),
(105, 'Tidak scan (selesai lembur)', 0),
(110, 'Lain-lain', 0),
(120, 'Libur', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_izin`
--

CREATE TABLE IF NOT EXISTS `kategori_izin` (
  `kat_izin_id` int(11) NOT NULL DEFAULT '0',
  `kat_izin_nama` varchar(100) DEFAULT NULL,
  `izin_jenis_id` smallint(6) DEFAULT '0',
  PRIMARY KEY (`kat_izin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `kategori_izin`
--

INSERT INTO `kategori_izin` (`kat_izin_id`, `kat_izin_nama`, `izin_jenis_id`) VALUES
(1, 'Kebanjiran', 10),
(2, 'Mengurus NPWP', 20),
(3, 'Sepeda Motor Mogok', 30),
(4, 'Sakit Rawat Jalan', 40),
(5, 'Disetujui Atasan', 50),
(6, 'Mengurus BPJS Pegawai', 60),
(7, 'Training Ke Kantor Cabang', 70),
(8, 'Delay Penerbangan', 71),
(9, 'Mengikuti Jadwal Keberangkatan Pesawat', 72),
(10, 'Cuti Normatif Nikah Disetujui Atasan', 80),
(11, 'Disetujui Atasan', 90),
(12, 'Lupa Scan Masuk', 100),
(13, 'Lupa Scan Pulang', 101),
(14, 'Lupa Scan Mulai Istirahat', 102),
(15, 'Lupa Scan Selesai Istirahat', 103),
(16, 'Lupa Scan Mulai Lembur', 104),
(17, 'Lupa Scan Selesai Lembur', 105),
(18, 'Bencana Alam', 110),
(19, 'Keperluan Pribadi', 120);

-- --------------------------------------------------------

--
-- Table structure for table `kontrak_kerja`
--

CREATE TABLE IF NOT EXISTS `kontrak_kerja` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `kontrak_start` date NOT NULL DEFAULT '0000-00-00',
  `kontrak_end` date NOT NULL DEFAULT '0000-00-00',
  `kontrak_status` tinyint(3) DEFAULT '0' COMMENT '0: kontrak; 1: tetap',
  `kontrak_aktif` tinyint(3) DEFAULT '-1',
  PRIMARY KEY (`pegawai_id`,`kontrak_start`,`kontrak_end`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `kontrak_kerja`
--


-- --------------------------------------------------------

--
-- Table structure for table `lembur`
--

CREATE TABLE IF NOT EXISTS `lembur` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `lembur_tgl` date NOT NULL DEFAULT '0000-00-00',
  `lembur_mulai` time NOT NULL DEFAULT '00:00:00',
  `lembur_selesai` time NOT NULL DEFAULT '00:00:00',
  `lembur_urut` tinyint(4) NOT NULL,
  `type_ot` tinyint(4) NOT NULL DEFAULT '-1',
  `lembur_durasi_min` smallint(6) DEFAULT '0',
  `lembur_durasi_max` smallint(6) DEFAULT '0',
  `lembur_keperluan` varchar(100) DEFAULT '',
  PRIMARY KEY (`pegawai_id`,`lembur_tgl`,`lembur_mulai`,`lembur_selesai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `lembur`
--


-- --------------------------------------------------------

--
-- Table structure for table `libur`
--

CREATE TABLE IF NOT EXISTS `libur` (
  `libur_tgl` date NOT NULL,
  `libur_keterangan` varchar(255) DEFAULT '',
  `libur_status` tinyint(4) DEFAULT '0' COMMENT '1: Hari Libur; 2: Cuti Bersama',
  PRIMARY KEY (`libur_tgl`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `libur`
--


-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE IF NOT EXISTS `pegawai` (
  `pegawai_id` int(11) NOT NULL AUTO_INCREMENT,
  `pegawai_pin` varchar(32) NOT NULL,
  `pegawai_nip` varchar(30) DEFAULT NULL,
  `pegawai_nama` varchar(255) NOT NULL,
  `pegawai_pwd` varchar(10) DEFAULT '',
  `pegawai_rfid` varchar(32) DEFAULT '',
  `pegawai_privilege` varchar(50) DEFAULT '0' COMMENT '-1: Invalid, 0: User,  1: Operator, 2: Sub Admin, 3: Admin',
  `pegawai_telp` varchar(20) DEFAULT NULL,
  `pegawai_status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0:Non Aktif; 1:Aktif; 2:Berhenti',
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `pembagian1_id` int(11) DEFAULT '0',
  `pembagian2_id` int(11) DEFAULT '0',
  `pembagian3_id` int(11) DEFAULT '0',
  `tgl_mulai_kerja` date DEFAULT NULL,
  `tgl_resign` date DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Laki-laki, 2:Perempuan',
  `tgl_masuk_pertama` date DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT '',
  `tmp_img` mediumtext,
  `nama_bank` varchar(50) DEFAULT '',
  `nama_rek` varchar(100) DEFAULT '',
  `no_rek` varchar(20) DEFAULT '',
  PRIMARY KEY (`pegawai_id`),
  UNIQUE KEY `pegawai_pin` (`pegawai_pin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`pegawai_id`, `pegawai_pin`, `pegawai_nip`, `pegawai_nama`, `pegawai_pwd`, `pegawai_rfid`, `pegawai_privilege`, `pegawai_telp`, `pegawai_status`, `tempat_lahir`, `tgl_lahir`, `pembagian1_id`, `pembagian2_id`, `pembagian3_id`, `tgl_mulai_kerja`, `tgl_resign`, `gender`, `tgl_masuk_pertama`, `photo_path`, `tmp_img`, `nama_bank`, `nama_rek`, `no_rek`) VALUES
(1, '1', '0001', 'Syariful Alim', '', '', '0', NULL, 1, NULL, NULL, 4, 4, 1, NULL, NULL, 1, NULL, 'syariful_alim.png', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai_d`
--

CREATE TABLE IF NOT EXISTS `pegawai_d` (
  `pegawai_id` int(10) NOT NULL,
  `pend_id` int(10) DEFAULT NULL,
  `gol_darah` tinyint(4) DEFAULT NULL COMMENT '1:A+, 2:B+, 3:O+, 4:AB+, 5:A-, 6:B-, 7:O-, 8:AB-',
  `stat_nikah` tinyint(4) DEFAULT NULL COMMENT '1:sudah menikah, 2:belum menikah, 3:duda/janda meninggal, 4:duda/janda cerai',
  `jml_anak` tinyint(4) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telp_extra` varchar(20) DEFAULT '',
  `hubungan` tinyint(4) DEFAULT NULL COMMENT '1:Keluarga, 2:Pasangan, 3:Saudara, 4:Teman, 5:Tetangga, 6:Lainnya',
  `nama_hubungan` varchar(200) DEFAULT NULL,
  `agama` tinyint(4) DEFAULT NULL COMMENT '1:Islam, 2:Katolik, 3:Protestan, 4:Hindu, 5:Budha, 6:Lainnya',
  UNIQUE KEY `pegawai_id` (`pegawai_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pegawai_d`
--

INSERT INTO `pegawai_d` (`pegawai_id`, `pend_id`, `gol_darah`, `stat_nikah`, `jml_anak`, `alamat`, `telp_extra`, `hubungan`, `nama_hubungan`, `agama`) VALUES
(1, 5, 1, 1, 2, 'Perumahan Bukit Bambe CA-01, Gresik', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembagian1`
--

CREATE TABLE IF NOT EXISTS `pembagian1` (
  `pembagian1_id` int(11) NOT NULL AUTO_INCREMENT,
  `pembagian1_nama` varchar(100) DEFAULT NULL,
  `pembagian1_ket` varchar(255) DEFAULT '',
  PRIMARY KEY (`pembagian1_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pembagian1`
--

INSERT INTO `pembagian1` (`pembagian1_id`, `pembagian1_nama`, `pembagian1_ket`) VALUES
(1, 'Manager', ''),
(2, 'Staff', ''),
(3, 'Operator', ''),
(4, 'Direktur', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembagian2`
--

CREATE TABLE IF NOT EXISTS `pembagian2` (
  `pembagian2_id` int(11) NOT NULL AUTO_INCREMENT,
  `pembagian2_nama` varchar(100) DEFAULT NULL,
  `pembagian2_ket` varchar(255) DEFAULT '',
  PRIMARY KEY (`pembagian2_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pembagian2`
--

INSERT INTO `pembagian2` (`pembagian2_id`, `pembagian2_nama`, `pembagian2_ket`) VALUES
(1, 'HRD', ''),
(2, 'Keuangan', ''),
(3, 'Penjualan', ''),
(4, 'IT', '');

-- --------------------------------------------------------

--
-- Table structure for table `pembagian3`
--

CREATE TABLE IF NOT EXISTS `pembagian3` (
  `pembagian3_id` int(11) NOT NULL AUTO_INCREMENT,
  `pembagian3_nama` varchar(100) DEFAULT NULL,
  `pembagian3_ket` varchar(255) DEFAULT '',
  PRIMARY KEY (`pembagian3_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=6 ;

--
-- Dumping data for table `pembagian3`
--

INSERT INTO `pembagian3` (`pembagian3_id`, `pembagian3_nama`, `pembagian3_ket`) VALUES
(1, 'Head Office', ''),
(2, 'Surabaya', ''),
(3, 'Jakarta', ''),
(4, 'Denpasar', ''),
(5, 'Jogja', '');

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan`
--

CREATE TABLE IF NOT EXISTS `pendidikan` (
  `pend_id` int(10) NOT NULL,
  `pend_name` varchar(20) NOT NULL,
  PRIMARY KEY (`pend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pendidikan`
--

INSERT INTO `pendidikan` (`pend_id`, `pend_name`) VALUES
(10, 'SD'),
(20, 'SMP'),
(30, 'SMA'),
(40, 'S1'),
(50, 'S2'),
(60, 'S3');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `param_name` varchar(100) NOT NULL DEFAULT '',
  `param_value` varchar(100) DEFAULT '',
  `keterangan` varchar(100) DEFAULT '',
  PRIMARY KEY (`param_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`param_name`, `param_value`, `keterangan`) VALUES
('active_row_color', 'clYellow', ''),
('active_row_fcolor', 'clBlack', ''),
('apikey', '', ''),
('auto_dl_enable', '0', ''),
('auto_dl_time', '10:00;16:00', ''),
('count_as_overtime', '0', ''),
('defDurasiJKkemen', '480', ''),
('del_scan_after_dl', '0', ''),
('durasi_kerja1', '0', ''),
('durasi_kerja2', '0', ''),
('durasi_kerja3', '0', ''),
('durasi_kerja_m1', '0', ''),
('durasi_kerja_m2', '0', ''),
('durasi_kerja_m3', '0', ''),
('early_tolerance', '15', ''),
('enable_device_default', '-1', ''),
('interval_habis_kontrak', '60', ''),
('last_optimized', '2017-06-11', ''),
('late_tolerance', '15', ''),
('max_break', '60', ''),
('max_overtime', '', ''),
('max_overtime_t', '1440', ''),
('minim_count_as', '240', ''),
('minim_overtime', '60', ''),
('nama_perusahaan', 'Fingerspot', ''),
('ot_libur_rutin', '-1', ''),
('ot_libur_umum', '-1', ''),
('pembagian1_judul', 'Jabatan', ''),
('pembagian2_judul', 'Departemen', ''),
('pembagian3_judul', 'Kantor', ''),
('range_after_in', '60', ''),
('range_after_out', '60', ''),
('range_before_in', '60', ''),
('range_before_out', '60', ''),
('same_all_day', '-1', ''),
('set_lock_dev', '0', ''),
('shortcut_delete', 'D', ''),
('shortcut_edit', 'E', ''),
('shortcut_new', 'A', ''),
('unsetting_row_color', '$00FFFF80', ''),
('unsetting_row_fcolor', 'clBlack', ''),
('use_index_ot', '0', ''),
('use_payroll_module', '-1', '');

-- --------------------------------------------------------

--
-- Table structure for table `shift_result`
--

CREATE TABLE IF NOT EXISTS `shift_result` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  `tgl_shift` date NOT NULL,
  `khusus_lembur` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Selain Lembur, 1: Khusus Lembur',
  `khusus_extra` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Selain Extra, 1: Khusus Extra',
  `temp_id_auto` int(11) NOT NULL DEFAULT '0' COMMENT '0: Isian default; Selain 0: Untuk shift auto',
  `jdw_kerja_m_id` int(11) NOT NULL DEFAULT '0',
  `jk_id` int(11) NOT NULL DEFAULT '0',
  `jns_dok` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Jadwal Kerja, 1: Izin, 2: Jam Kerja Extra, 3:Lembur',
  `izin_jenis_id` smallint(6) NOT NULL DEFAULT '0',
  `cuti_n_id` int(11) NOT NULL DEFAULT '0',
  `libur_umum` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  `libur_rutin` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  `jk_ot` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  `scan_in` datetime NOT NULL,
  `att_id_in` varchar(50) NOT NULL DEFAULT '0',
  `late_permission` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  `late_minute` smallint(6) NOT NULL DEFAULT '0',
  `late` float NOT NULL DEFAULT '0',
  `break_out` datetime NOT NULL,
  `att_id_break1` varchar(50) NOT NULL DEFAULT '0',
  `break_in` datetime NOT NULL,
  `att_id_break2` varchar(50) NOT NULL DEFAULT '0',
  `break_minute` smallint(6) NOT NULL DEFAULT '0',
  `break` float NOT NULL DEFAULT '0',
  `break_ot_minute` smallint(6) NOT NULL DEFAULT '0',
  `break_ot` float NOT NULL DEFAULT '0',
  `early_permission` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'YES/NO',
  `early_minute` smallint(6) NOT NULL DEFAULT '0',
  `early` float NOT NULL DEFAULT '0',
  `scan_out` datetime NOT NULL,
  `att_id_out` varchar(50) NOT NULL DEFAULT '0',
  `durasi_minute` smallint(6) NOT NULL DEFAULT '0',
  `durasi` float NOT NULL DEFAULT '0',
  `durasi_eot_minute` smallint(6) NOT NULL DEFAULT '0',
  `jk_count_as` float NOT NULL DEFAULT '0',
  `status_jk` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No',
  `keterangan` text,
  PRIMARY KEY (`pegawai_id`,`tgl_shift`,`khusus_lembur`,`khusus_extra`,`temp_id_auto`),
  KEY `jdw_kerja_m_id` (`jdw_kerja_m_id`),
  KEY `jk_id` (`jk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shift_result`
--


-- --------------------------------------------------------

--
-- Table structure for table `sms_group`
--

CREATE TABLE IF NOT EXISTS `sms_group` (
  `group_id` int(10) DEFAULT NULL,
  `group_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_group`
--


-- --------------------------------------------------------

--
-- Table structure for table `sms_group_member`
--

CREATE TABLE IF NOT EXISTS `sms_group_member` (
  `group_id` int(10) DEFAULT NULL,
  `pegawai_pin` varchar(32) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_group_member`
--


-- --------------------------------------------------------

--
-- Table structure for table `sms_recipient`
--

CREATE TABLE IF NOT EXISTS `sms_recipient` (
  `nama` varchar(50) DEFAULT NULL,
  `nomor_telp` varchar(20) DEFAULT NULL,
  `pegawai_pin` varchar(32) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0:penerima pribadi; 1:penerima group'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_recipient`
--


-- --------------------------------------------------------

--
-- Table structure for table `temp_pegawai`
--

CREATE TABLE IF NOT EXISTS `temp_pegawai` (
  `pegawai_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pegawai_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_pegawai`
--


-- --------------------------------------------------------

--
-- Table structure for table `tmp`
--

CREATE TABLE IF NOT EXISTS `tmp` (
  `pin` varchar(32) NOT NULL,
  `finger_idx` tinyint(4) NOT NULL,
  `alg_ver` tinyint(4) NOT NULL COMMENT 'ZK : 9&10, Realand : 19, Ebio : 29, HY : 39',
  `template1` text NOT NULL,
  PRIMARY KEY (`pin`,`finger_idx`,`alg_ver`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp`
--

INSERT INTO `tmp` (`pin`, `finger_idx`, `alg_ver`, `template1`) VALUES
('8888', 5, 10, 'TFVTUzIxAAAFFhUECAUHCc7QAAAdF2kBAAAAhDssmhYxABsPvwDxAJwZlwBZAI8PLABlFh0PNwBnAKsPkRZvACIPTgBGAGcZbgCDAHAPNwCCFiAPnQCIAOsPCBaKAGMPVQBOAF4ZfACXAH8PrQCYFkwP5wCgAOkPdxakAD8P1wBsADQZRACwAD0POQC1FiMPNwC9AIUPkBbDAFYPDwAGAEMZogDGALYPvQDJFrkPmwDRAJAPvRbTAEUP9QAXACgZKQDkADQPGADqFkIP9ADwAP8PjBbwAGsPPAAzAC0YEQD+AC0PUQAAF2gPgQAIARgPVBYnAZ8PxgDuAWcZ5wAuAdAOSAAzF30PMgA4AWUPHRY9AYwPKgD7AZwZwgA/AdkPbwBTF/QPboSuj2r7a5ZKDgt/NW1/dyp2n/4Hh6fz/w0yAnYOIZWR9w+3RR0j9Mb1OfKYJ4ABsPcWBpYDwA/Hp9rAA/ZfHoYnze1EExnvXUGwNqwEURMp0tX3BP10GSQP3PcdwTvROBNbEi4Tpf338hPw+Puq9eby5AW76JMPNaBdAFugMxxjFvvmJWCQYORkv2TOZYJ6wKDYFiwT0ZK98wvyk+IfDbLubQ9rCffkQAoaGtsADPoPGpJvQQZlkisZ/OHT/4uJMvmLBxL9kI3FAl/sWHV0YNpgTgFPCFZ5YJ8jlHdtNnDb+n6NK4u7oMsUgQtcAc8B+fXt7yLvye1sDdP8bXnP5ZMcbx0ue2eVl+ggI5QCIEYBAicnmR0BmQAiw/4ERXYRAbAAGmJlzQCnFxdCWgwAjMMWxdY/wHj/CACuCQbpMFwJAFQTxcInKxUAMhv6PgT9UShL/3EMACrmAMfo/sDA/cH+8gsFPS73/0Q1QsgApCcWQ///wGmCCQXVOR5ZZP8VxRFF5j7/Szj//jrAUenAGAAOSfSQ/05WNEf/wFjAyQCzXB9K/sH+gzoKBaRNJMFMwFrOAJZOkcDCwnt+0AAGTeovUzPA/YnAN9YFAOxkHm3AADZzccCDJAA7oPTFJzkqwP//wDrA+9f8wPvD+8E7Ozjo/v4FADRrqHgLFpNwk5DDwELCjQkBmHEeNUaB/sXqOcAnPP//7TgcFgB35C//TPY8xetV/0TACgCXgfTr/f/9/f7ABRYFXINpkIDC/wTDhZTAhDQATIenhIZsw4CdwsH+DWqIameLmcSBwwTAx9bCgQQA9Irnah8WUItggHXDB/2SgcPCxHDEwAYUBYmNLT3//cA6/vjU//36wf394QsFQ45chMb+wwTBwRgBeZSJx8MAoMXSxsWiDgBsXwb+6/z5/v379jn/GhwBaKFXx8EGys/QxcUIAOuj7P/HQvgDAQ/9TAUEBXilU8n+BwAjpDXU/sFHAwBybDH4BQHWrDTAwI8x+tb6NcI6BgAerDXWwVIKAEWzhYvE1a4DAP20KQcZBQe+zDU1Ki45/0Un/zcMADfFg8PGYsXCxMLDCsULwlqCwcHCkwXFEsJQwMDCFgCcCL3GasPGxsPCwwF3xtTHxsLDBAA81CI4BACe1Uz9ggQF4tcwTwYAKSI3gdQEANryRsD0BwX28z0uRwYAPfI16PorBQDy8///JBMRDgE0woPGEFkeDcIEEAYW6JIABv4kSf1KBNUILDaEDRDCLGL9/zDW/DgREMgvn/74UT/A/E37xcMQCVAbwsM6AxAKS3XoBBDDV3daxhDaSF/2BBAbW8M3BgayY4DCBBCfZQUr'),
('8888', 5, 19, '04:2C:13:00:06:C3:B8:1D:87:A9:17:00:72:FB:B8:25:85:79:98:7A:09:24:BB:2C:85:5A:98:87:FE:23:B6:3F:8E:59:A8:85:5E:2C:B9:AA:8D:EA:98:85:8A:DC:B9:3D:C8:DA:A8:8E:89:EC:B8:50:0B:8A:AC:8D:D1:24:BB:3C:C7:BB:A0:C8:C2:84:85:32:86:0A:00:00:B9:1C:87:0C:56:0A:00:00:EE:4C:79:56:C9:0B:00:00:35:55:BA:48:87:7A:BC:0B:2A:05:B9:CD:47:7A:A8:C7:B9:8A:B8:87:86:78:A4:87:D9:CA:85:69:05:08:00:00:19:83:B8:86:47:6A:88:47:5D:43:B6:5F:09:79:A4:86:5A:7B:77:73:4E:09:00:00:79:7B:84:54:05:09:00:00:85:83:B6:59:8A:98:90:47:AE:FB:67:85:0D:0C:00:00:32:BC:85:3E:8A:09:00:00:32:BC:B8:44:D2:A7:88:09:59:94:84:41:45:09:00:00:9D:2C:87:9C:1A:09:00:00:E6:CC:86:1D:08:0A:00:00:F9:54:78:61:89:0A:00:00:69:5D:BA:BC:46:2A:7D:8A:99:F5:79:55:07:0B:00:00:A5:95:BA:54:05:6A:A4:D2:BA:3D:85:A4:84:07:00:00:4D:62:89:8E:04:E7:0B:00:82:BA:B9:1E:C2:57:A0:46:3D:B5:84:2A:05:B7:7C:01:0E:12:89:19:85:26:0B:00:16:CA:89:8F:82:17:0B:00:C1:7A:8B:14:83:CA:07:00:45:B3:8B:1D:06:3B:08:00:A9:83:8B:21:81:89:07:00:25:3C:8C:9B:87:29:08:00:3A:14:8C:30:87:39:08:00:79:24:8C:AF:86:0B:07:00:BA:1C:8C:32:85:6B:07:00:41:24:84:45:84:8A:02:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:17:86:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:00:B4:86:'),
('8888', 5, 39, '1E:14:0B:17:13:12:1B:0C:52:52:51:50:57:56:55:54:4B:4A:49:48:4F:4E:4D:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:6F:6E:6D:6C:63:62:61:60:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:7B:F5:17:0C:77:4E:2E:6F:59:50:7D:7C:7F:7D:C3:07:3A:3A:3F:3F:29:3D:30:32:3F:23:38:34:22:33:2C:3E:38:3E:26:3A:AD:BC:B1:55:B8:46:B5:7B:78:8C:B1:74:1E:8E:91:1C:B7:8D:E8:E8:E6:5A:B7:BE:88:BE:53:70:6B:49:42:79:7A:67:6D:CC:C3:C2:C1:C0:C7:C6:C5:C4:FB:FA:F9:F8:FF:FE:FD:FC:F3:F2:F1:F0:F7:F6:F5:F4:EB:EA:E9:E8:80:67:6B:8C:85:85:7D:61:87:7E:A6:62:E6:37:3A:36:29:D8:F8:28:1E:3D:39:20:2F:54:4F:A9:B1:37:37:34:4F:5E:55:8C:83:82:81:80:87:86:85:84:BB:BA:B9:B8:BF:BE:BD:BC:B3:B2:B1:B0:B7:B6:B5:B4:AB:AA:A9:A8:D7:16:FC:2C:73:02:09:60:3F:66:D5:1C:8A:8A:89:99:36:CE:F5:84:93:C8:8B:0A:B5:34:DE:31:AE:57:D4:E5:72:6B:E8:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:90:91:92:93:9C:9D:9E:9F:98:99:9A:9B:E4:E5:E6:E7:E0:E1:E2:E3:EC:ED:EE:EF:E8:19:15:14:8B:F5:F6:0A:0F:0E:0D:0C:FB:FD:2E:00:07:06:05:84:C4:95:3B:38:3F:3E:3D:C4:CC:15:30:30:37:36:B5:CB:94:3F:29:28:2F:2E:D5:D3:35:23:21:20:27:A6:DA:1B:DB:DA:D9:D8:DF:26:22:DF:D3:D2:D1:D0:57:29:2A:D4:CB:CA:C9:C8:37:31:C6:CC:C3:C2:C1:40:38:39:C5:C4:DB:FA:F9:00:00:F1:FD:FC:F1:F2:71:0F:48:F6:F5:D4:EB:EA:11:17:E0:EE:AD:EC:E3:62:1E:1F:E7:E6:E5:E4:9B:62:66:9B:9F:9E:9D:9C:53:6D:6E:90:97:96:95:94:77:75:86:88:8F:8E:2D:4E:7C:7D:81:80:87:86:AF:78:44:85:B9:B8:BF:1E:7F:43:4C:F5:B1:B0:37:9C:4A:4B:14:C2:A8:BD:05:56:52:53:48:1D:F1:01:4D:59:5A:5B:A4:70:4C:B2:A0:A1:A2:A3:AC:AD:FE:AF:A8:A9:AA:AB:B4:B5:B6:B7:B0:34:DC:54:5B:42:41:40:A2:E6:97:A7:8F:B1:4C:89:E9:A8:8F:69:84:92:74:84:39:8F:C8:9F:25:66:69:65:5B:41:66:8C:43:3D:4D:33:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:0B:0A:09:08:0F:0E:0D:0C:03:02:01:00:07:06:05:04:3B:3A:39:38:3F:3E:3D:3C:33:32:31:30:37:36:35:34:2B:2A:29:28:2F:2E:2D:2C:23:22:21:20:27:26:25:24:DB:DA:D9:D8:DF:DE:DD:DC:D3:D2:D1:D0:D7:D6:D5:D4:CB:CA:C9:C8:CF:CE:CD:CC:C3:C2:C1:C0:C7:C6:C5:C4:FB:FA:F9:F8:FF:FE:FD:FC:F3:F2:F1:F0:F7:F6:F5:F4:EB:EA:E9:E8:EF:EE:ED:EC:E3:E2:E1:E0:E7:E6:E5:E4:9B:9A:99:98:9F:9E:9D:9C:93:92:91:90:97:96:95:94:8B:8A:89:88:8F:8E:8D:8C:83:82:81:80:87:86:85:84:BB:BA:B9:B8:BF:BE:BD:BC:B3:B2:B1:B0:B7:B6:B5:B4:AB:AA:A9:A8:AF:AE:AD:AC:A3:A2:A1:A0:A7:A6:A5:A4:5B:5A:59:58:5F:5E:5D:5C:53:52:51:50:57:56:55:54:4B:4A:49:48:4F:4E:4D:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:6F:6E:6D:6C:63:62:61:60:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:0B:0A:09:08:0F:0E:0D:0C:03:02:01:00:07:06:05:04:3B:3A:39:38:3F:3E:3D:3C:33:32:31:30:37:36:35:34:2B:2A:29:28:2F:2E:2D:2C:23:22:21:20:27:26:25:24:DB:DA:D9:D8:DF:DE:DD:DC:D3:D2:D1:D0:D7:D6:D5:D4:CB:CA:C9:C8:CF:CE:CD:CC:C3:C2:C1:C0:C7:C6:C5:C4:FB:FA:F9:F8:FF:FE:FD:FC:F3:F2:F1:F0:F7:F6:F5:F4:EB:EA:E9:E8:EF:EE:ED:EC:E3:E2:E1:E0:E7:E6:E5:E4:9B:9A:99:98:9F:9E:9D:9C:93:92:91:90:97:96:95:94:8B:8A:89:88:8F:8E:8D:8C:83:82:81:80:87:86:85:84:BB:BA:B9:B8:BF:BE:BD:BC:B3:B2:B1:B0:B7:B6:B5:B4:AB:AA:A9:A8:AF:AE:AD:AC:A3:A2:A1:A0:A7:A6:A5:A4:5B:5A:59:58:5F:5E:5D:5C:53:52:51:50:57:56:55:54:4B:4A:49:48:4F:4E:4D:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:6F:6E:6D:6C:63:62:61:60:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:0B:0A:09:08:0F:0E:0D:0C:03:02:01:00:07:06:05:04:3B:3A:39:38:3F:3E:3D:3C:33:32:31:30:37:36:35:34:2B:2A:29:28:2F:2E:2D:2C:23:22:21:20:27:26:25:24:DB:DA:D9:D8:DF:DE:DD:DC:D3:D2:D1:D0:D7:D6:D5:D4:CB:CA:C9:C8:CF:CE:CD:CC:C3:C2:C1:C0:C7:C6:C5:C4:FB:FA:F9:F8:FF:FE:FD:FC:F3:F2:F1:F0:F7:F6:F5:F4:EB:EA:E9:E8:EF:EE:ED:EC:E3:E2:E1:E0:E7:E6:E5:E4:9B:9A:99:98:9F:9E:9D:9C:93:92:91:90:97:96:95:94:8B:8A:89:88:8F:8E:8D:8C:83:82:81:80:87:86:85:84:BB:BA:B9:B8:BF:BE:BD:BC:B3:B2:B1:B0:B7:B6:B5:B4:AB:AA:A9:A8:AF:AE:AD:AC:A3:A2:A1:A0:A7:A6:A5:A4:5B:5A:59:58:5F:5E:5D:5C:53:52:51:50:57:56:55:54:4B:4A:49:48:4F:4E:4D:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:6F:6E:6D:6C:63:62:61:60:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:0B:0A:09:08:0F:0E:0D:0C:03:02:01:00:07:06:05:04:3B:3A:39:38:3F:3E:3D:3C:33:32:31:30:37:36:35:34:2B:2A:29:28:2F:2E:2D:2C:23:22:21:20:27:26:25:24:DB:DA:D9:D8:DF:DE:DD:DC:D3:D2:D1:D0:D7:D6:D5:D4:CB:CA:C9:C8:CF:CE:CD:CC:C3:C2:C1:C0:C7:C6:C5:C4:FB:FA:F9:F8:FF:FE:FD:FC:F3:F2:F1:F0:F7:F6:F5:F4:EB:EA:E9:E8:EF:EE:ED:EC:E3:E2:E1:E0:E7:E6:E5:E4:9B:9A:99:98:9F:9E:9D:9C:93:92:91:90:97:96:95:94:8B:8A:89:88:8F:8E:8D:8C:83:82:81:80:87:86:85:84:BB:BA:B9:B8:BF:BE:BD:BC:B3:B2:B1:B0:B7:B6:B5:B4:AB:AA:A9:A8:AF:AE:AD:AC:A3:A2:A1:A0:A7:A6:A5:A4:5B:5A:59:58:5F:5E:5D:5C:53:52:51:50:57:56:55:54:4B:4A:49:48:4F:4E:4D:4C:43:42:41:40:47:46:45:44:7B:7A:79:78:7F:7E:7D:7C:73:72:71:70:77:76:75:74:6B:6A:69:68:6F:6E:6D:6C:63:62:61:60:67:66:65:64:1B:1A:19:18:1F:1E:1D:1C:13:12:11:10:17:16:15:14:0B:0A:09:08:0F:0E:0D:0C:03:02:01:00:07:06:05:04:3B:3A:39:38:3F:3E:3D:3C:33:32:31:30:37:36:35:34:2B:2A:29:28:2F:2E:2D:2C:23:22:21:20:27:26:25:24:DB:DA:D9:D8:DF:DE:DD:DC:D3:D2:D1:D0:D7:D6:D5:D4:');

-- --------------------------------------------------------

--
-- Table structure for table `tmp_uareu`
--

CREATE TABLE IF NOT EXISTS `tmp_uareu` (
  `pin` varchar(32) NOT NULL,
  `finger_idx` tinyint(4) NOT NULL,
  `alg_ver` tinyint(4) NOT NULL,
  `template1` text NOT NULL,
  PRIMARY KEY (`pin`,`finger_idx`,`alg_ver`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp_uareu`
--


-- --------------------------------------------------------

--
-- Table structure for table `tukar_jam`
--

CREATE TABLE IF NOT EXISTS `tukar_jam` (
  `tukar_tgl` date NOT NULL,
  `pegawai_id1` int(11) NOT NULL DEFAULT '0',
  `pegawai_id2` int(11) NOT NULL DEFAULT '0',
  `alasan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tukar_tgl`,`pegawai_id1`,`pegawai_id2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tukar_jam`
--


-- --------------------------------------------------------

--
-- Table structure for table `t_audit_trail`
--

CREATE TABLE IF NOT EXISTS `t_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `script` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `t_audit_trail`
--

INSERT INTO `t_audit_trail` (`id`, `datetime`, `script`, `user`, `action`, `table`, `field`, `keyvalue`, `oldvalue`, `newvalue`) VALUES
(1, '2017-06-11 19:58:43', '/siap/login.php', 'admin', 'login', '::1', '', '', '', ''),
(2, '2017-06-11 20:00:08', '/siap/logout.php', 'admin', 'logout', '::1', '', '', '', ''),
(3, '2017-06-11 20:00:16', '/siap/login.php', 'admin', 'login', '::1', '', '', '', ''),
(4, '2017-06-11 20:00:47', '/siap/logout.php', 'admin', 'logout', '::1', '', '', '', ''),
(5, '2017-06-11 20:02:02', '/siap/login.php', 'admin', 'login', '::1', '', '', '', ''),
(6, '2017-06-11 21:04:59', '/siap/pembagian1addopt.php', '3', 'A', 'pembagian1', 'pembagian1_nama', '4', '', 'Direktur'),
(7, '2017-06-11 21:04:59', '/siap/pembagian1addopt.php', '3', 'A', 'pembagian1', 'pembagian1_ket', '4', '', NULL),
(8, '2017-06-11 21:04:59', '/siap/pembagian1addopt.php', '3', 'A', 'pembagian1', 'pembagian1_id', '4', '', '4'),
(9, '2017-06-11 21:18:36', '/siap/logout.php', 'admin', 'logout', '::1', '', '', '', ''),
(10, '2017-06-11 21:18:41', '/siap/login.php', 'admin', 'login', '::1', '', '', '', ''),
(11, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_pin', '1', '', '1'),
(12, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_nip', '1', '', '0001'),
(13, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_nama', '1', '', 'Syariful Alim'),
(14, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_telp', '1', '', NULL),
(15, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_status', '1', '', '1'),
(16, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'tempat_lahir', '1', '', NULL),
(17, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'tgl_lahir', '1', '', NULL),
(18, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'tgl_mulai_kerja', '1', '', NULL),
(19, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'tgl_resign', '1', '', NULL),
(20, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'gender', '1', '', '1'),
(21, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'tgl_masuk_pertama', '1', '', NULL),
(22, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'photo_path', '1', '', 'siap_logo.png'),
(23, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'nama_bank', '1', '', NULL),
(24, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'nama_rek', '1', '', NULL),
(25, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'no_rek', '1', '', NULL),
(26, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', 'A', 'pegawai', 'pegawai_id', '1', '', '1'),
(27, '2017-06-11 22:43:49', '/siap/pegawaiadd.php', '3', '*** Batch insert begin ***', 'pegawai_d', '', '', '', ''),
(28, '2017-06-11 22:50:29', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian1_id', '1', NULL, '0'),
(29, '2017-06-11 22:50:29', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian2_id', '1', NULL, '0'),
(30, '2017-06-11 22:50:29', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian3_id', '1', NULL, '0'),
(31, '2017-06-11 22:50:29', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'photo_path', '1', 'syariful_alim.png', 'siap_logo.png'),
(32, '2017-06-11 22:53:32', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian1_id', '1', '4', NULL),
(33, '2017-06-11 22:53:32', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian2_id', '1', '4', NULL),
(34, '2017-06-11 22:53:32', '/siap/pegawaiedit.php', '3', 'U', 'pegawai', 'pembagian3_id', '1', '1', NULL),
(35, '2017-06-11 23:30:08', '/siap/pegawaiedit.php', '3', '*** Batch update begin ***', 'pegawai_d', '', '', '', ''),
(36, '2017-06-11 23:30:08', '/siap/pegawaiedit.php', '3', '*** Batch update successful ***', 'pegawai_d', '', '', '', ''),
(37, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', '*** Batch update begin ***', 'pegawai_d', '', '', '', ''),
(38, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'pend_id', '1', '', '5'),
(39, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'gol_darah', '1', '', '1'),
(40, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'stat_nikah', '1', '', '1'),
(41, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'agama', '1', '', '1'),
(42, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'jml_anak', '1', '', '0'),
(43, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'alamat', '1', '', NULL),
(44, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'nama_hubungan', '1', '', NULL),
(45, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'telp_extra', '1', '', NULL),
(46, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'hubungan', '1', '', NULL),
(47, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', 'A', 'pegawai_d', 'pegawai_id', '1', '', '1'),
(48, '2017-06-11 23:31:03', '/siap/pegawaiedit.php', '3', '*** Batch update successful ***', 'pegawai_d', '', '', '', ''),
(49, '2017-06-11 23:31:47', '/siap/pegawai_dedit.php', '3', 'U', 'pegawai_d', 'jml_anak', '1', '2', '0'),
(50, '2017-06-11 23:31:47', '/siap/pegawai_dedit.php', '3', 'U', 'pegawai_d', 'alamat', '1', 'Perumahan Bukit Bambe CA-01, Gresik', NULL),
(51, '2017-06-12 00:19:55', '/siap/login.php', 'admin', 'login', '::1', '', '', '', ''),
(52, '2017-06-12 02:25:56', '/siap/v_shiftedit.php', '3', 'U', 'v_shift', 'param_value', 'range_before_in', '62', '60'),
(53, '2017-06-12 02:51:23', '/siap/v_shiftedit.php', '3', 'U', 'v_shift', 'param_value', 'range_before_in', '63', '62'),
(54, '2017-06-12 02:51:51', '/siap/v_shiftedit.php', '3', 'U', 'v_shift', 'param_value', 'range_before_in', '60', '63');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `userlevel` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`user_id`, `username`, `password`, `userlevel`) VALUES
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', -1);

-- --------------------------------------------------------

--
-- Table structure for table `uareu_device`
--

CREATE TABLE IF NOT EXISTS `uareu_device` (
  `device_id` int(11) NOT NULL AUTO_INCREMENT,
  `uau_device_name` varchar(100) DEFAULT NULL,
  `uau_serial_number` varchar(255) DEFAULT NULL,
  `uau_verification` varchar(255) DEFAULT NULL,
  `uau_activation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`device_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `uareu_device`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `login_id` varchar(50) NOT NULL,
  `log_date` datetime NOT NULL,
  `module` int(11) NOT NULL COMMENT '0: Pengaturan, 1: Pegawai, 2: Mesin, 3: Pengecualian, 4: Laporan, 5: Proses',
  `tipe_log` tinyint(4) NOT NULL COMMENT '0: Tambah, 1: Ubah, 2: Hapus, 3: Buka Pintu',
  `nama_data` varchar(250) NOT NULL,
  `log_note` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`login_id`, `log_date`, `module`, `tipe_log`, `nama_data`, `log_note`) VALUES
('admin', '2017-06-11 19:21:00', 5, 1, '', 'Login user aplikasi "admin" '),
('admin', '2017-06-11 19:21:00', 5, 1, '', 'Login user aplikasi "admin" '),
('admin', '2017-06-11 19:22:00', 5, 3, '', 'Log out user aplikasi "admin" '),
('admin', '2017-06-11 19:23:00', 5, 1, '', 'Login user aplikasi "admin" '),
('admin', '2017-06-11 19:26:00', 5, 1, '', 'Login user aplikasi "admin" '),
('admin', '2017-06-11 19:40:00', 5, 2, '', 'Mengubah pengaturan umum'),
('admin', '2017-06-11 19:41:00', 5, 3, '', 'Log out user aplikasi "admin" '),
('Out', '2017-06-11 19:41:00', 5, 1, '', 'Login user aplikasi "Out" '),
('admin', '2017-06-11 22:54:00', 5, 1, '', 'Login user aplikasi "admin" '),
('admin', '2017-06-12 02:48:00', 5, 1, '', 'Login user aplikasi "admin" ');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `login_id` varchar(50) NOT NULL,
  `login_pwd` varchar(32) NOT NULL,
  `grp_user_id` tinyint(3) NOT NULL DEFAULT '1',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`login_id`, `login_pwd`, `grp_user_id`, `lastupdate_date`, `lastupdate_user`) VALUES
('admin', '455CB4D4A6D53E8C2F7094B1B764184A', 1, '2017-06-11 00:00:00', 'admin'),
('fingerspot', 'C535676C1656F0768DCB9ADFB5B75D13', 1, '2014-06-25 11:56:47', 'Fingerspot'),
('sub admin', 'CE1A93177040C9A5E536BA80E609A82B', 2, '2015-05-30 00:00:00', 'admin'),
('operator', '6907A7A65E7BBBFFF470C0EED3DB1446', 3, '2015-05-30 00:00:00', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `versi_db`
--

CREATE TABLE IF NOT EXISTS `versi_db` (
  `no_id` smallint(6) NOT NULL,
  `versi_db` varchar(100) NOT NULL DEFAULT '@',
  `keterangan` text,
  PRIMARY KEY (`no_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `versi_db`
--

INSERT INTO `versi_db` (`no_id`, `versi_db`, `keterangan`) VALUES
(1, 'v.2014.05.07', ''),
(2, 'v.2015.05.30', NULL),
(3, 'v.2015.06.03', NULL),
(4, 'v.2015.08.27', NULL),
(5, 'v.2015.10.27', NULL),
(6, 'v.2015.11.14', NULL),
(7, 'v.2015.12.05', NULL),
(8, 'v.2015.12.15', NULL),
(9, 'v.2016.01.08', NULL),
(10, 'v.2016.02.25', NULL),
(11, 'v.2016.02.26', NULL),
(12, 'v.2016.03.15', NULL),
(13, 'v.2016.03.23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `v_shift`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `db_siap`.`v_shift` AS select `db_siap`.`setting`.`param_name` AS `param_name`,`db_siap`.`setting`.`param_value` AS `param_value`,(case when (`db_siap`.`setting`.`param_name` = 'range_before_in') then 'Durasi sebelum jam masuk' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_in') then 'Durasi setelah jam masuk' else (case when (`db_siap`.`setting`.`param_name` = 'range_before_out') then 'Durasi sebelum jam pulang' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_out') then 'Durasi setelah jam pulang' else (case when (`db_siap`.`setting`.`param_name` = 'late_tolerance') then 'Toleransi terlambat' else (case when (`db_siap`.`setting`.`param_name` = 'early_tolerance') then 'Toleransi pulang awal' else (case when (`db_siap`.`setting`.`param_name` = 'minim_count_as') then 'Hitung kerja 1/2 hari jika kerja minimal' else 'none' end) end) end) end) end) end) end) AS `disp_field`,(case when (`db_siap`.`setting`.`param_name` = 'range_before_in') then '1' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_in') then '2' else (case when (`db_siap`.`setting`.`param_name` = 'range_before_out') then '3' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_out') then '4' else (case when (`db_siap`.`setting`.`param_name` = 'late_tolerance') then '5' else (case when (`db_siap`.`setting`.`param_name` = 'early_tolerance') then '6' else (case when (`db_siap`.`setting`.`param_name` = 'minim_count_as') then '7' else 'none' end) end) end) end) end) end) end) AS `disp_no` from `db_siap`.`setting` where ((`db_siap`.`setting`.`param_name` = 'range_before_in') or (`db_siap`.`setting`.`param_name` = 'range_after_in') or (`db_siap`.`setting`.`param_name` = 'range_before_out') or (`db_siap`.`setting`.`param_name` = 'range_after_out') or (`db_siap`.`setting`.`param_name` = 'late_tolerance') or (`db_siap`.`setting`.`param_name` = 'early_tolerance') or (`db_siap`.`setting`.`param_name` = 'minim_count_as')) order by (case when (`db_siap`.`setting`.`param_name` = 'range_before_in') then '1' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_in') then '2' else (case when (`db_siap`.`setting`.`param_name` = 'range_before_out') then '3' else (case when (`db_siap`.`setting`.`param_name` = 'range_after_out') then '4' else (case when (`db_siap`.`setting`.`param_name` = 'late_tolerance') then '5' else (case when (`db_siap`.`setting`.`param_name` = 'early_tolerance') then '6' else (case when (`db_siap`.`setting`.`param_name` = 'minim_count_as') then '7' else 'none' end) end) end) end) end) end) end);

-- --------------------------------------------------------

--
-- Table structure for table `zx_bayar_kredit`
--

CREATE TABLE IF NOT EXISTS `zx_bayar_kredit` (
  `id_bayar` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `id_kredit` int(11) NOT NULL,
  `no_urut` smallint(6) NOT NULL,
  `tgl_jt` date NOT NULL,
  `debet` float NOT NULL,
  `angs_pokok` float NOT NULL,
  `bunga` float NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `keterangan` varchar(300) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id_bayar`),
  KEY `id_kredit` (`id_kredit`),
  KEY `tgl_jt` (`tgl_jt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zx_bayar_kredit`
--


-- --------------------------------------------------------

--
-- Table structure for table `zx_jns_krd`
--

CREATE TABLE IF NOT EXISTS `zx_jns_krd` (
  `krd_id` tinyint(4) NOT NULL,
  `krd_kode` varchar(10) NOT NULL,
  `krd_name` varchar(100) NOT NULL,
  `com_id` smallint(6) NOT NULL,
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`krd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zx_jns_krd`
--


-- --------------------------------------------------------

--
-- Table structure for table `zx_kredit_d`
--

CREATE TABLE IF NOT EXISTS `zx_kredit_d` (
  `id_kredit` int(11) NOT NULL,
  `no_urut` smallint(6) NOT NULL,
  `tgl_jt` date NOT NULL,
  `saldo_aw` float NOT NULL,
  `debet` float NOT NULL,
  `angs_pokok` float NOT NULL,
  `bunga` float NOT NULL,
  `saldo_akh` float NOT NULL,
  `proses_bayar` tinyint(4) NOT NULL DEFAULT '0',
  `keterangan` varchar(300) NOT NULL,
  PRIMARY KEY (`id_kredit`,`no_urut`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zx_kredit_d`
--


-- --------------------------------------------------------

--
-- Table structure for table `zx_kredit_m`
--

CREATE TABLE IF NOT EXISTS `zx_kredit_m` (
  `id_kredit` int(11) NOT NULL,
  `no_kredit` varchar(100) NOT NULL,
  `tgl_kredit` date NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `krd_id` tinyint(4) NOT NULL,
  `cara_hitung` tinyint(4) NOT NULL DEFAULT '0',
  `tot_kredit` float NOT NULL,
  `saldo_aw` float NOT NULL,
  `suku_bunga` double NOT NULL,
  `periode_bulan` smallint(6) NOT NULL,
  `angs_pokok` float NOT NULL,
  `angs_pertama` date NOT NULL,
  `tot_debet` float NOT NULL,
  `tot_angs_pokok` float NOT NULL,
  `tot_bunga` float NOT NULL,
  `def_pembulatan` smallint(6) NOT NULL,
  `jumlah_piutang` float NOT NULL,
  `approv_by` varchar(200) NOT NULL,
  `keterangan` varchar(1000) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_lunas` tinyint(4) NOT NULL DEFAULT '0',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kredit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zx_kredit_m`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_com`
--

CREATE TABLE IF NOT EXISTS `z_pay_com` (
  `com_id` smallint(6) NOT NULL,
  `com_kode` varchar(50) NOT NULL,
  `com_name` varchar(100) NOT NULL,
  `type_com` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Tunjangan, 1: Potongan',
  `fluctuate` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No (Berubah-rubah)',
  `no_urut` smallint(6) NOT NULL,
  `param` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No (Bagian dari formula)',
  `hitung` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Periode, 1: Harian',
  `dibayar` tinyint(4) NOT NULL DEFAULT '2' COMMENT '0: Harian, 1: Mingguan, 2: Bulanan, 3: Tahunan',
  `cara_bayar` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: Tunai, 1: Transfer Rekening',
  `pinjaman` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`com_id`),
  KEY `com_id` (`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_com`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_grp`
--

CREATE TABLE IF NOT EXISTS `z_pay_grp` (
  `grp_id` smallint(6) NOT NULL,
  `kode_grp` varchar(50) NOT NULL,
  `grp_name` varchar(100) NOT NULL,
  `use_pengurang` tinyint(4) NOT NULL DEFAULT '0',
  `type_pengurang` tinyint(4) NOT NULL DEFAULT '0',
  `pengurang_persen` float NOT NULL DEFAULT '0',
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_grp`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_grp_com`
--

CREATE TABLE IF NOT EXISTS `z_pay_grp_com` (
  `grp_id` smallint(6) NOT NULL,
  `com_id` smallint(6) NOT NULL,
  `no_urut_ref` smallint(6) NOT NULL,
  `use_if_sum` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No - (Use kondisi)',
  `use_kode_if` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1: Tidak pakai, 0: Field laporan, 1: Komponen gaji, 2: Jenis izin, 3: Cuti normatif',
  `id_kode_if` smallint(6) NOT NULL DEFAULT '0' COMMENT 'ID kode kondisi',
  `min_if` float NOT NULL DEFAULT '0',
  `max_if` float NOT NULL DEFAULT '0',
  `use_sum` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Yes/No - (Use rumus)',
  `use_kode_sum` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1: Tidak pakai, 0: Field laporan, 1: Komponen gaji, 2: Jenis izin, 3: Cuti normatif',
  `id_kode_sum` smallint(6) NOT NULL DEFAULT '0' COMMENT 'ID kode rumus',
  `operator_sum` varchar(50) NOT NULL DEFAULT '0' COMMENT '0: *, 1: /, 2: -, 3: +, 4: Tanpa Konstanta',
  `konstanta_sum` float NOT NULL DEFAULT '0',
  `operator_sum2` varchar(50) NOT NULL DEFAULT '0' COMMENT '0: *, 1: /, 2: -, 3: +, 4: Tidak pakai',
  `nilai_rp` float NOT NULL DEFAULT '0',
  `hitung` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Periode, 1: Perhari',
  `jenis` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Normal, 1: Bertingkat, 2: Menggantikan',
  PRIMARY KEY (`com_id`,`grp_id`,`no_urut_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_grp_com`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_grp_emp`
--

CREATE TABLE IF NOT EXISTS `z_pay_grp_emp` (
  `grp_id` smallint(6) NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `no_rek` varchar(50) NOT NULL,
  PRIMARY KEY (`grp_id`,`emp_id_auto`),
  KEY `grp_id` (`grp_id`),
  KEY `emp_id_auto` (`emp_id_auto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_grp_emp`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_money`
--

CREATE TABLE IF NOT EXISTS `z_pay_money` (
  `com_id` smallint(6) NOT NULL,
  `grp_id` smallint(6) NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `nilai_rp` float NOT NULL,
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`com_id`,`grp_id`,`emp_id_auto`),
  KEY `com_id` (`com_id`),
  KEY `emp_id_auto` (`emp_id_auto`),
  KEY `grp_id` (`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_money`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_process_d`
--

CREATE TABLE IF NOT EXISTS `z_pay_process_d` (
  `process_id` int(11) NOT NULL,
  `no_urut` smallint(6) NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `tot_payroll` float NOT NULL,
  PRIMARY KEY (`process_id`,`no_urut`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_process_d`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_process_m`
--

CREATE TABLE IF NOT EXISTS `z_pay_process_m` (
  `process_id` int(11) NOT NULL,
  `process_name` varchar(250) NOT NULL,
  `date1` date NOT NULL,
  `date2` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `round_value` float NOT NULL,
  `tot_process` float NOT NULL,
  `create_by` varchar(100) DEFAULT NULL,
  `check_by` varchar(100) DEFAULT NULL,
  `approve_by` varchar(100) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `lastupdate_date` datetime NOT NULL,
  `lastupdate_user` varchar(50) NOT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_process_m`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_process_sd`
--

CREATE TABLE IF NOT EXISTS `z_pay_process_sd` (
  `process_id` int(11) NOT NULL,
  `no_urut` smallint(6) NOT NULL,
  `no_urut_ref` smallint(6) NOT NULL,
  `emp_id_auto` int(11) NOT NULL,
  `com_id` smallint(6) NOT NULL,
  `kondisi` varchar(100) NOT NULL DEFAULT '0',
  `rumus` varchar(100) NOT NULL DEFAULT '0',
  `subtot_payroll` float NOT NULL,
  `jml_faktor` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`process_id`,`no_urut`,`no_urut_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_process_sd`
--


-- --------------------------------------------------------

--
-- Table structure for table `z_pay_report`
--

CREATE TABLE IF NOT EXISTS `z_pay_report` (
  `id_kode_report` tinyint(4) NOT NULL,
  `report_code` varchar(50) NOT NULL,
  `report_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_kode_report`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_pay_report`
--

INSERT INTO `z_pay_report` (`id_kode_report`, `report_code`, `report_name`) VALUES
(1, 'H', 'Kehadiran (jumlah)'),
(2, 'H', 'Kehadiran (jam)'),
(3, 'DT', 'Datang terlambat (jumlah)'),
(4, 'DT', 'Datang terlambat (menit)'),
(5, 'PC', 'Pulang awal (jumlah)'),
(6, 'PC', 'Pulang awal (menit)'),
(7, 'SK', 'Scan kerja 1 kali'),
(8, 'OT', 'Lembur (jumlah)'),
(9, 'OT', 'Lembur (jam)'),
(10, 'SL', 'Scan lembur 1 kali'),
(11, 'TH', 'Tidak hadir'),
(12, 'L', 'Libur'),
(13, 'IL', 'Istirahat lebih (jumlah)'),
(14, 'IL', 'Istirahat lebih (menit)');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
