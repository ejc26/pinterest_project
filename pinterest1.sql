-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2013 at 06:47 AM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `pinterest`
--
CREATE DATABASE IF NOT EXISTS `pinterest1` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pinterest1`;
-- --------------------------------------------------------

--
-- Table structure for table `Board`
--

CREATE TABLE `Board` (
  `bid` int(8) NOT NULL AUTO_INCREMENT,
  `uid` int(8) NOT NULL,
  `bname` varchar(80) DEFAULT NULL,
  `bprivacy` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `board_ibfk_1` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=213 ;

--
-- Dumping data for table `Board`
--

INSERT INTO `Board` (`bid`, `uid`, `bname`, `bprivacy`) VALUES
(201, 5, 'Furniture ', '0'),
(202, 5, 'Dream Vacation ', 'friends'),
(203, 3, 'Super Dinosaurs ', '0'),
(204, 3, 'pirates', '0'),
(205, 1, 'Jurassic Park', '0'),
(206, 4, ' Shrek', '0'),
(207, 2, 'Cute Dinosaurs', '0'),
(208, 1, 'elle decor ', '0'),
(209, 4, ' Mediterranean ', '0'),
(210, 2, ' Beautiful scenery ', '0'),
(211, 1, 'Dinosaur world ', '0'),
(212, 6, 'lala', 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `uid` int(8) NOT NULL,
  `pid` int(8) NOT NULL,
  `bid` int(8) NOT NULL,
  `ctext` varchar(400) DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`pid`,`bid`),
  KEY `comment_ibfk_2` (`pid`),
  KEY `comment_ibfk_3` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`uid`, `pid`, `bid`, `ctext`, `ctime`) VALUES
(1, 35, 206, 'lalla', '2013-12-13 22:34:05'),
(1, 42, 202, 'BEAUTIFUL', '2013-12-14 04:52:20'),
(1, 43, 202, 'nice place', '2013-12-13 17:28:48'),
(5, 5, 208, 'nice house~:)', '2013-12-12 22:17:46');

--
-- Triggers `Comment`
--
DROP TRIGGER IF EXISTS `check_privacy`;
DELIMITER //
CREATE TRIGGER `check_privacy` AFTER INSERT ON `Comment`
 FOR EACH ROW begin
if NEW.bid in (
    (select bid
     from board
     where bprivacy = 'friends' AND uid <> NEW.uid and
     uid not in (SELECT fromid FROM friendship WHERE toid = NEW.uid AND status = 'accept') and
     uid not in (SELECT toid FROM friendship WHERE fromid = NEW.uid AND status = 'accept')))
then delete from NEW;
end if;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Follow`
--

CREATE TABLE `Follow` (
  `fid` int(8) NOT NULL,
  `bid` int(8) NOT NULL,
  `followtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fid`,`bid`),
  KEY `follow_ibfk_2` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Follow`
--

INSERT INTO `Follow` (`fid`, `bid`, `followtime`) VALUES
(101, 202, '0000-00-00 00:00:00'),
(103, 201, '0000-00-00 00:00:00'),
(103, 208, '0000-00-00 00:00:00'),
(107, 209, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Followstream`
--

CREATE TABLE `Followstream` (
  `fid` int(8) NOT NULL AUTO_INCREMENT,
  `uid` int(8) NOT NULL,
  `fname` varchar(80) NOT NULL,
  `fprivacy` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`fid`),
  KEY `followstream_ibfk_1` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

--
-- Dumping data for table `Followstream`
--

INSERT INTO `Followstream` (`fid`, `uid`, `fname`, `fprivacy`) VALUES
(101, 5, 'Vacation', 'NULL'),
(103, 5, 'dream home', 'friends'),
(107, 1, 'enhui', '0');

-- --------------------------------------------------------

--
-- Table structure for table `Friendship`
--

CREATE TABLE `Friendship` (
  `fromid` int(8) NOT NULL,
  `toid` int(8) NOT NULL,
  `requesttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fromid`,`toid`,`requesttime`),
  KEY `friendship_ibfk_2` (`toid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Friendship`
--

INSERT INTO `Friendship` (`fromid`, `toid`, `requesttime`, `status`) VALUES
(1, 5, '2013-12-12 23:35:59', 'accept'),
(6, 1, '2013-12-13 22:33:20', 'accept');

-- --------------------------------------------------------

--
-- Table structure for table `Likenum`
--

CREATE TABLE `Likenum` (
  `uid` int(8) NOT NULL,
  `pid` int(8) NOT NULL,
  `ltime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`pid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Likenum`
--

INSERT INTO `Likenum` (`uid`, `pid`, `ltime`) VALUES
(1, 5, '2013-12-12 23:10:47'),
(1, 35, '2013-12-13 22:34:13'),
(1, 46, '2013-12-13 00:31:17'),
(2, 6, '2013-12-13 22:27:11'),
(5, 5, '2013-12-12 23:12:49'),
(5, 6, '2013-12-12 23:12:51'),
(5, 7, '2013-12-12 23:12:54'),
(6, 6, '2013-12-13 22:36:09'),
(6, 7, '2013-12-13 22:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `Picture`
--

CREATE TABLE `Picture` (
  `pid` int(8) NOT NULL AUTO_INCREMENT,
  `pname` varchar(80) DEFAULT NULL,
  `URL` varchar(200) DEFAULT NULL,
  `local` varchar(200) DEFAULT NULL,
  `descript` varchar(200) DEFAULT NULL,
  `tag` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `Picture`
--

INSERT INTO `Picture` (`pid`, `pname`, `URL`, `local`, `descript`, `tag`) VALUES
(3, 'Jurassic1', 'http://media-cache-ak0.pinimg.com/originals/8e/83/88/8e83881764b3b758de9c233af4077a84.jpg', 'http://localhost:8888/pinterest/pictures/Jurassic1.jpg', 'Jurassic Park stills', ''),
(4, 'Jurassic3', 'http://media-cache-ec0.pinimg.com/originals/db/cd/8d/dbcd8d5764c4c5514a9546bb6d29d6b6.jpg', 'http://localhost:8888/pinterest/pictures/Jurassic3.jpg', 'Jurassic Park stills', ''),
(5, 'decor', 'http://media-cache-ec0.pinimg.com/originals/c6/b4/64/c6b464cedf2f9aed03491dc6c5ae3ed7.jpg', 'http://localhost:8888/pinterest/pictures/decor.jpg', 'modern furniture', ''),
(6, 'decor2', 'http://st.houzz.com/simgs/c841bc930ee922af_4-6202/contemporary-bedroom.jpg', 'http://localhost:8888/pinterest/pictures/decor2.jpg', 'country style', ''),
(7, 'decor3', 'http://4.bp.blogspot.com/_TB33xDKDDB0/SnxP1LRtwII/AAAAAAAABBw/LL0fo-OevVQ/s400/green15-elle_decor.jpg', 'http://localhost:8888/pinterest/pictures/decor3.jpg', '', ''),
(10, 'dinosaur world1', 'http://media-cache-ak0.pinimg.com/originals/d6/fe/80/d6fe801d8680054877e223d097c33cf1.jpg', 'http://localhost:8888/pinterest/pictures/dinosaur%20world1.jpg', 'awsome', ''),
(11, 'dinosaur world2', 'http://www.tuscanaresort.com/wp-content/uploads/2013/08/Dino-world-billboard-sign.jpg', 'http://localhost:8888/pinterest/pictures/dinosaur%20world2.jpg', '', ''),
(16, 'cute dinosaur1', 'http://imgs.tuts.dragoart.com/how-to-draw-a-cute-dinosaur_1_000000004264_5.jpg', 'http://localhost:8888/pinterest/pictures/cute%20dinosaur1.jpg', 'cute', ''),
(19, 'cutedinosaur2', 'http://us.123rf.com/400wm/400/400/red33/red330801/red33080100074/2391607-cute-dinosaur-vector-illustration.jpg', 'http://localhost:8888/pinterest/pictures/cutedinosaur2.jpg', 'cute', ''),
(20, 'cutedinosaur3', 'http://cloud.graphicleftovers.com/12009/1367028/cartoon-dinosaur-doctor.jpg', 'http://localhost:8888/pinterest/pictures/cutedinosaur3.jpg', 'cute', ''),
(21, 'scenery1', 'http://www.artrenewal.org/artwork/607/607/12743/view_of_delft-large.jpg', 'http://localhost:8888/pinterest/pictures/scenery1.jpg', 'beautiful scenery', ''),
(22, 'scenery2', 'http://www.wallpapersdb.org/wallpapers/beach/beautiful_sea_view_1600x1200.jpg', 'http://localhost:8888/pinterest/pictures/scenery2.jpg', 'beautiful scenery', ''),
(24, 'scenery3', 'http://media-cache-ec0.pinimg.com/originals/ed/47/a5/ed47a5e94a5c4903dab69711f13b2056.jpg', 'http://localhost:8888/pinterest/pictures/scenery3.jpg', 'amazing!', ''),
(25, 'superdinosaur1', 'http://media-cache-ak0.pinimg.com/originals/e1/6e/c7/e16ec73f70e27d29435e69598190a9c2.jpg', 'http://localhost:8888/pinterest/pictures/superdinosaur1.jpg', 'stegosaurus', ''),
(26, 'superdinosaur2', 'http://images2.wikia.nocookie.net/__cb20130913083357/dino/images/c/ce/Tyrannosaurus_Rex_colored.png', 'http://localhost:8888/pinterest/pictures/superdinosaur2.png', 'tyrannosaurus', ''),
(27, 'superdinosaur3', 'http://images.nationalgeographic.com/wpf/media-live/photos/000/210/cache/new-pterosaur-alanqa_21064_600x450.jpg', 'http://localhost:8888/pinterest/pictures/superdinosaur3.jpg', 'Pterosaur ', ''),
(28, 'superdinosaur4', 'http://3.bp.blogspot.com/-xZQHyonKzcM/TcFKJAxMTJI/AAAAAAAABtc/KGCvVzoMHf0/s1600/Dinosaurus-Brontosaurus-Dinner-welcome21.jpg', 'http://localhost:8888/pinterest/pictures/superdinosaur4.jpg', 'brontosaurus', ''),
(29, 'pirate1', 'http://www.getintouni.info/wp-content/uploads/2013/09/pirate-ship.jpg', 'http://localhost:8888/pinterest/pictures/pirate1.jpg', 'pirate ship', ''),
(31, 'pirate2', 'http://content5.videojug.com/2f/2ff95aa1-e93a-bb50-4264-ff0008ce5242/how-to-do-pirate-makeup.WidePlayer.jpg', 'http://localhost:8888/pinterest/pictures/pirate2.jpg', 'pirate makeup', ''),
(32, 'pirate3', 'http://media-cache-ak0.pinimg.com/originals/73/85/ea/7385eaab7006305a279bf134eb3d172b.jpg', 'http://localhost:8888/pinterest/pictures/pirate3.jpg', 'pirate of the Caribbean', ''),
(33, 'shrek1', 'http://www.dan-dare.org/FreeFun/Images/CartoonsMoviesTV/ShrekWallpaper800.jpg', 'http://localhost:8888/pinterest/pictures/shrek1.jpg', '', ''),
(34, 'shrek2', 'http://static3.wikia.nocookie.net/__cb20131022141340/shrek/images/4/45/Shrek-with-friends-shrek-30165391-1920-1200.jpg', 'http://localhost:8888/pinterest/pictures/shrek2.jpg', 'cool', ''),
(35, 'shrek3', 'http://www.picgifs.com/graphics/s/shrek/graphics-shrek-744453.jpg', 'http://localhost:8888/pinterest/pictures/shrek3.jpg', '', ''),
(36, 'mediterranena1', 'http://www.venere.com/blog/images/majorca-beaches.jpg', 'http://localhost:8888/pinterest/pictures/mediterranena1.jpg', 'my dream place', ''),
(37, 'mediterranena2', 'http://us.123rf.com/400wm/400/400/photopiano/photopiano1101/photopiano110100147/8749940-blue-beach-landscape-with-mediterranean-sea--cloudy-sky.jpg', 'http://localhost:8888/pinterest/pictures/mediterranena2.jpg', 'amazing view!', ''),
(38, 'furniture1', 'http://media-cache-ak0.pinimg.com/originals/7f/78/03/7f7803c798f5d673e0a902b9389b7530.jpg', 'http://localhost:8888/pinterest/pictures/furniture1.jpg', 'modular bedroom', ''),
(39, 'furniture2', 'http://www.ideashomedesign.net/wp-content/uploads/2012/01/English-Antique-Furniture-Styles.jpg', 'http://localhost:8888/pinterest/pictures/furniture2.jpg', 'old fashioned', ''),
(40, 'furniture3', 'http://cdn.homedit.com/wp-content/uploads/2012/11/colorful-chairs.jpg', 'http://localhost:8888/pinterest/pictures/furniture3.jpg', 'colorful chair', ''),
(41, 'furniture4', 'http://us.123rf.com/400wm/400/400/vlavitan/vlavitan1203/vlavitan120300859/12975602-modern-interior-design-of-living-room-with-a-nice-sofa-and-a-vase-on-the-floor.jpg', 'http://localhost:8888/pinterest/pictures/furniture4.jpg', 'nice sofa', ''),
(42, 'vacation1', 'http://www.wallpaper23.com/data/nature/summer/1680x1050_ionian-sea-vacation.jpg', 'http://localhost:8888/pinterest/pictures/vacation1.jpg', 'amazing!', ''),
(43, 'vacation2', 'http://therecoveringpolitician.com/wp-content/uploads/2013/04/beach_vacation-dsc04627.jpg', 'http://localhost:8888/pinterest/pictures/vacation2.jpg', 'beautful beach', ''),
(44, 'vacation3', 'http://s3.favim.com/orig/40/bora-bora-magic-paradise-sea-vacation-Favim.com-331454.jpg', 'http://localhost:8888/pinterest/pictures/vacation3.jpg', '', ''),
(45, 'baby', 'https://girlsguideto-production.s3.amazonaws.com/uploads/content/6-rules-to-remember-when-naming-your-baby/cute-baby.jpg', 'http://localhost:8888/pinterest/pictures/baby.jpg', 'cute', ''),
(46, 'baby', 'https://girlsguideto-production.s3.amazonaws.com/uploads/content/6-rules-to-remember-when-naming-your-baby/cute-baby.jpg', 'http://localhost:8888/pinterest/pictures/baby.jpg', '', ''),
(47, 'cute', 'http://imgs.steps.dragoart.com/how-to-draw-an-anime-cartoon-puppy-step-5_1_000000007593_5.jpg', 'http://localhost:8888/pinterest/pictures/cute.jpg', '', ''),
(48, 'dog', 'http://imgs.steps.dragoart.com/how-to-draw-an-anime-cartoon-puppy-step-5_1_000000007593_5.jpg', 'http://localhost:8888/pinterest/pictures/dog.jpg', 'cartoon', 'cute'),
(49, 'bingming', 'http://imgs.steps.dragoart.com/how-to-draw-an-anime-cartoon-puppy-step-5_1_000000007593_5.jpg', 'http://localhost:8888/pinterest/pictures/bingming.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `Pin`
--

CREATE TABLE `Pin` (
  `pid` int(8) NOT NULL,
  `bid` int(8) NOT NULL,
  `ptime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `prebid` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`,`bid`),
  KEY `pin_ibfk_1` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Pin`
--

INSERT INTO `Pin` (`pid`, `bid`, `ptime`, `prebid`) VALUES
(3, 205, '2013-12-12 20:31:00', 0),
(4, 205, '2013-12-12 20:32:41', 0),
(5, 201, '2013-12-12 22:17:12', 208),
(5, 208, '2013-12-12 20:36:38', 0),
(6, 208, '2013-12-12 20:41:52', 0),
(7, 208, '2013-12-12 20:43:06', 0),
(10, 211, '2013-12-12 20:49:59', 0),
(11, 211, '2013-12-12 20:50:52', 0),
(16, 207, '2013-12-12 21:08:54', 0),
(19, 207, '2013-12-12 21:23:23', 0),
(20, 207, '2013-12-12 21:24:17', 0),
(21, 210, '2013-12-12 21:25:16', 0),
(22, 210, '2013-12-12 21:25:43', 0),
(24, 210, '2013-12-12 21:27:59', 0),
(25, 203, '2013-12-12 21:33:14', 0),
(26, 203, '2013-12-12 21:33:44', 0),
(27, 203, '2013-12-12 21:34:36', 0),
(28, 203, '2013-12-12 21:36:08', 0),
(29, 204, '2013-12-12 21:36:46', 0),
(31, 204, '2013-12-12 21:38:13', 0),
(32, 204, '2013-12-12 21:40:52', 0),
(33, 206, '2013-12-12 21:42:29', 0),
(34, 206, '2013-12-12 21:43:13', 0),
(35, 205, '2013-12-13 22:34:27', 206),
(35, 206, '2013-12-12 21:43:38', 0),
(36, 209, '2013-12-12 21:44:41', 0),
(37, 209, '2013-12-12 21:45:23', 0),
(38, 201, '2013-12-12 21:50:35', 0),
(39, 201, '2013-12-12 21:51:07', 0),
(40, 201, '2013-12-12 21:51:44', 0),
(41, 201, '2013-12-12 21:52:08', 0),
(42, 202, '2013-12-12 21:52:48', 0),
(42, 210, '2013-12-12 23:17:31', 202),
(43, 202, '2013-12-12 21:53:29', 0),
(44, 202, '2013-12-12 21:54:02', 0),
(47, 212, '2013-12-13 22:32:04', 0),
(48, 211, '2013-12-13 22:51:09', 0),
(49, 208, '2013-12-14 04:41:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `uid` int(8) NOT NULL AUTO_INCREMENT,
  `uname` varchar(60) NOT NULL,
  `pwd` varchar(80) NOT NULL,
  `email` varchar(40) NOT NULL,
  `bday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `interest` varchar(200) DEFAULT NULL,
  `facebook` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`uid`, `uname`, `pwd`, `email`, `bday`, `gender`, `interest`, `facebook`) VALUES
(1, 'summer', '123', ' summer123@students.poly.edu ', '1999-08-08', 'female', 'movie, home decor', 'https://www.facebook.com/profile.php?id=100002281974166&fref=ts'),
(2, 'xinrui', '456', 'xinrui@nyu.edu ', '1995-11-18', 'female', ' painting, animal', ' https://www.facebook.com/profile.php?id=100004209434489&fref=ts'),
(3, 'timmy', 'w4w', 'timmyloveit@yahoo.com ', '2006-11-30', 'male', ' dinosaurs, pirates', 'https://www.facebook.com/pages/Timmy/420348258043056 '),
(4, 'christin', '331!', 'christinami@hotmail.com ', '2000-01-03', 'female', 'travel, movie', ' https://www.facebook.com/enhui.kim.5?fref=ts '),
(5, 'erica', 'best', ' erica33@gmail.com ', '2003-02-22', 'female', ' Furniture, Dream Vacations', ' https://www.facebook.com/erica.calardo.art'),
(6, 'gaoxin', '321', 'dfjadskjf', NULL, NULL, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Board`
--
ALTER TABLE `Board`
  ADD CONSTRAINT `board_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `Picture` (`pid`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`bid`) REFERENCES `Board` (`bid`) ON DELETE CASCADE;

--
-- Constraints for table `Follow`
--
ALTER TABLE `Follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `Followstream` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `Board` (`bid`) ON DELETE CASCADE;

--
-- Constraints for table `Followstream`
--
ALTER TABLE `Followstream`
  ADD CONSTRAINT `followstream_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `Friendship`
--
ALTER TABLE `Friendship`
  ADD CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`fromid`) REFERENCES `User` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`toid`) REFERENCES `User` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `Likenum`
--
ALTER TABLE `Likenum`
  ADD CONSTRAINT `likenum_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`),
  ADD CONSTRAINT `likenum_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `Picture` (`pid`);

--
-- Constraints for table `Pin`
--
ALTER TABLE `Pin`
  ADD CONSTRAINT `pin_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `Board` (`bid`) ON DELETE CASCADE,
  ADD CONSTRAINT `pin_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `Picture` (`pid`) ON DELETE CASCADE;
