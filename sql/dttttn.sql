-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 07, 2025 lúc 05:15 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dtttn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(10) NOT NULL,
  `upload_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `status` enum('Posted','Writing','Pending','Deny') NOT NULL DEFAULT 'Pending',
  `tags` varchar(255) DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`post_id`, `unit_id`, `title`, `slug`, `image`, `type`, `status`, `tags`, `content`, `author_id`, `create_at`, `last_update`) VALUES
(40, 9, 'TỔNG KẾT CHƯƠNG TRÌNH “BƯỚC CHÂN MÙA HẠ”', 'tong-ket-chuong-trinh-buoc-chan-mua-ha', 'https://doantnttn.com/server/images/1-6857d9fc95e237.76256053.png', 3, 'Posted', NULL, '{\"time\":1750589236401,\"blocks\":[{\"id\":\"ocLu2VI2L9\",\"type\":\"paragraph\",\"data\":{\"text\":\"“Sự yêu thương cho đi là yêu thương còn mãi.”\"}},{\"id\":\"-fe5P1mP99\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857d9fc95e237.76256053.png\"}}},{\"id\":\"GHbFLmtJT2\",\"type\":\"paragraph\",\"data\":{\"text\":\"\\n\\nCâu lạc bộ Hiến Máu Nhân Đạo – Trường Đại học Tây Nguyên đã tổ chức chương trình “Bước Chân Mùa Hạ” tại Trường Tiểu học Hoàng Văn Thụ, xã Đắk Liêng, huyện Lắk, tỉnh Đắk Lắk, trao tặng hơn 500 quyển sách và nhiều phần quà thiết thực cho các em học sinh khó khăn và khuyết tật.\"}},{\"id\":\"L4QjdPeArW\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857da38baab12.81411387.png\"}}},{\"id\":\"j2ytP9TVU7\",\"type\":\"paragraph\",\"data\":{\"text\":\"CLB cũng tổ chức buổi sinh hoạt “Phòng chống đuối nước” nhằm trang bị kiến thức cho các em. Đồng thời tổ chức nhiều trò chơi hấp dẫn, mang đến không khí vui tươi, ấm áp nhân dịp Quốc tế Thiếu nhi.\"}},{\"id\":\"3Gfmf8PucD\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857ded1614978.95023761.png\"}}},{\"id\":\"nDsb91YDPu\",\"type\":\"paragraph\",\"data\":{\"text\":\"Qua đây, Câu lạc bộ xin gửi lời cảm ơn sâu sắc đến các mạnh thường quân, tình nguyện viên, nhà trường và chính quyền địa phương đã đồng hành, giúp chương trình thành công tốt đẹp.\"}},{\"id\":\"kbVivrUpeG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Hành trình có thể kết thúc, nhưng hy vọng và tri thức vẫn sẽ tiếp tục đồng hành cùng các em trên con đường phía trước.\"}},{\"id\":\"M0tQ0UO5tl\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mọi thắc mắc xin liên hệ:\"}},{\"id\":\"cZP6SxuYii\",\"type\":\"paragraph\",\"data\":{\"text\":\"Fanpage CLB Hiến Máu Nhân Đạo – Trường ĐH Tây Nguyên\"}},{\"id\":\"0VVdiTn1H_\",\"type\":\"paragraph\",\"data\":{\"text\":\"Chủ nhiệm: Nguyễn Đức Huy – 0782 737 464\"}},{\"id\":\"AW31ufm7-o\",\"type\":\"paragraph\",\"data\":{\"text\":\"<a href=\\\"https:\\/\\/www.facebook.com\\/hashtag\\/buocchanmuaha?__eep__=6&amp;__cft__[0]=AZWiC5N7kmGsbcV-hBML79LtMEuU7BZlTiSzfPnropBV60oB5k_EnY9wrUg3JgF1ByKaVNIQMY2x9nUdzHMzpCCigeEGGEVXvI5a14BvzBucZCff3BkTKgUuK8pPHGffISp065shh6-UAsK8eUP8CMzaD_yD1m52MgKtha6xtPmoNTbZlxodkBTHEXKK3XwNM3mleQ117lntiGRhj4EO9qIn&amp;__tn__=*NK-R\\\">#BuocChanMuaHa<\\/a> <a href=\\\"https:\\/\\/www.facebook.com\\/hashtag\\/clbhi%E1%BA%BFnm%C3%A1unh%C3%A2n%C4%91%E1%BA%A1o?__eep__=6&amp;__cft__[0]=AZWiC5N7kmGsbcV-hBML79LtMEuU7BZlTiSzfPnropBV60oB5k_EnY9wrUg3JgF1ByKaVNIQMY2x9nUdzHMzpCCigeEGGEVXvI5a14BvzBucZCff3BkTKgUuK8pPHGffISp065shh6-UAsK8eUP8CMzaD_yD1m52MgKtha6xtPmoNTbZlxodkBTHEXKK3XwNM3mleQ117lntiGRhj4EO9qIn&amp;__tn__=*NK-R\\\">#CLBHiếnMáuNhânĐạo<\\/a>&nbsp;<a href=\\\"https:\\/\\/www.facebook.com\\/hashtag\\/clbhmndtdhtn?__eep__=6&amp;__cft__[0]=AZWiC5N7kmGsbcV-hBML79LtMEuU7BZlTiSzfPnropBV60oB5k_EnY9wrUg3JgF1ByKaVNIQMY2x9nUdzHMzpCCigeEGGEVXvI5a14BvzBucZCff3BkTKgUuK8pPHGffISp065shh6-UAsK8eUP8CMzaD_yD1m52MgKtha6xtPmoNTbZlxodkBTHEXKK3XwNM3mleQ117lntiGRhj4EO9qIn&amp;__tn__=*NK-R\\\">#clbhmndtdhtn<\\/a>\"}},{\"id\":\"Gnx7IfHHju\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df201842b1.23739408.png\"}}},{\"id\":\"E4dnTYCsBd\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df2962c669.57245872.png\"}}},{\"id\":\"xaFPb5PG1m\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df329217b1.33300368.png\"}}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 10:47:16', '2025-06-22 16:06:30'),
(41, 9, 'Kiểm tra chuyên đề công tác Đoàn và phong trào thanh niên đối với Đoàn Thanh niên Khoa Ngoại ngữ, Khoa Kinh tế, Khoa Y dược và khoa Sư phạm', 'kiem-tra-chuyen-de-cong-tac-doan-va-phong-trao-thanh-nien-doi-voi-doan-thanh-nien-khoa-ngoai-ngu-khoa-kinh-te-khoa-y-duoc-va-khoa-su-pham', 'https://doantnttn.com/server/images/1-6857df74bf7089.68611278.png', 3, 'Posted', NULL, '{\"time\":1750589345656,\"blocks\":[{\"id\":\"1FcyKJDVif\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df74bf7089.68611278.png\"}}},{\"id\":\"MZ-jn356qK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ngày 15\\/5\\/2025 Ban Kiểm tra Đoàn trường đã tiến hành kiểm tra kiểm tra 02 chuyên đề công tác Đoàn và phong trào thanh niên đối với 3 đơn vị Đoàn Thanh niên Ngoại ngữ, Khoa Kinh tế, Khoa Y dược và Khoa Sư phạm.\"}},{\"id\":\"JQP56JyCjO\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df7f1cabf0.71324841.png\"}}},{\"id\":\"8VaCgXmV99\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df88222781.77460417.png\"}}},{\"id\":\"DUpU31bOZe\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857df91c84ce8.82028642.png\"}}},{\"id\":\"3k1rP_3ejd\",\"type\":\"paragraph\",\"data\":{\"text\":\"Tại buổi kiểm tra các đồng chí  Bí thư Đoàn khoa đã tiến hành trình bày Báo cáo Kết quả công tác Đoàn và phong trào thanh niên trong việc triển khai Chương trình công tác Đoàn năm học 2024-2025. Theo đó, các đơn vị đã luôn chú trọng việc giáo dục chính trị, tư tưởng cho đoàn viên, thanh niên, nghiêm túc tham gia học tập, quán triệt các nghị quyết, chỉ thị, kết luận, quy định, hướng dẫn của Đảng và của Đoàn Thanh niên.&nbsp;\"}},{\"id\":\"_J8gBScPpP\",\"type\":\"paragraph\",\"data\":{\"text\":\"Thông qua hoạt động giám sát kiểm tra, Ủy ban Kiểm tra Đoàn trường đã kịp thời nhắc nhở những mặt còn hạn chế cần khắc phục đối với 3 đơn vị kiểm tra và tuyên dương cán bộ đoàn tại các đơn vị được giám sát đều có tính tự giác nêu cao tính gương mẫu, đi đầu, tinh thần trách nhiệm trên lĩnh vực công tác được phân công phụ trách\"}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 10:49:05', '2025-06-22 16:06:30'),
(42, 9, 'Cẩn trọng với các hình thức lừa đảo giao dịch thanh toán', 'can-trong-voi-cac-hinh-thuc-lua-dao-giao-dich-thanh-toan', 'https://doantnttn.com/server/images/1-6857dfcc833344.48029085.png', 2, 'Posted', NULL, '{\"time\":1750589390840,\"blocks\":[{\"id\":\"NsHOCSejIf\",\"type\":\"paragraph\",\"data\":{\"text\":\"&nbsp; Công nghệ tiên tiến đang mang đến sự tiện lợi và trải nghiệm tốt hơn trong mọi dịch vụ, nhất là dịch vụ tài chính. Tuy nhiên, rủi ro an ninh mạng, lừa đảo trong giao dịch thanh toán vẫn đang rình rập xung quanh. Do đó, bên cạnh việc nâng cao mức độ cảnh giác trước các hành thức lừa đảo ngày càng tinh vi, người dùng cũng cần chịu trách nhiệm với chính dữ liệu cá nhân khi đưa lên môi trường trực tuyến.&nbsp;&nbsp;\"}},{\"id\":\"ext-hDqUnb\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857dfcc833344.48029085.png\"}}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 10:49:51', '2025-06-22 16:06:30'),
(43, 9, 'Chuyên đề: \"Giữ gìn và phát huy bản sắc dân tộc Tây Nguyên\" năm 2025', 'chuyen-de-giu-gin-va-phat-huy-ban-sac-dan-toc-tay-nguyen-nam-2025', 'https://doantnttn.com/server/images/1-6857dff30c4bb6.59358717.png', 3, 'Posted', NULL, '{\"time\":1750589473688,\"blocks\":[{\"id\":\"GKit12eCAL\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857dff30c4bb6.59358717.png\"}}},{\"id\":\"KN5TudKQhG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Trong bối cảnh hội nhập quốc tế sâu rộng, việc giữ gìn và phát huy giá trị văn hóa dân tộc trở thành một nhiệm vụ quan trọng, đặc biệt đối với thế hệ trẻ. Đoàn viên, thanh niên, với vai trò là lực lượng nòng cốt, cần được trang bị đầy đủ kiến thức và ý thức về văn hóa dân tộc thông qua các hoạt động tuyên truyền, giáo dục đa dạng và hiệu quả.Tối ngày 26\\/5\\/2025 Đoàn Thanh niên - Hội sinh viên trường Đại học Tây Nguyên đã tổ chức Chuyên đề \\\"Giữ gìn và phát huy bản sắc dân tộc Tây Nguyên\\\" năm 2025 với gần 400 ĐVTN các khoa và trường THPT Thực hành Cao Nguyên tham gia tại chương trình.\"}},{\"id\":\"DniGUn4b_2\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nội dung chính được Tập trung vào các chủ đề như lịch sử, văn hóa, phong tục tập quán, các loại hình nghệ thuật truyền thống của dân tộc. Đoàn trường đã dẫn dắt các bạn Đoàn viên thanh niên đến với các lễ hội truyền thống của người đồng bào Ê đê, với các trang phục đặc trưng hoa văn, chất liệu và ý nghĩa của từng bộ trang phục., điệu múa cồng chiêng, và các bài viết về âm nhạc truyền thống của người Ê Đê, các loại nhạc cụ và các làn điệu dân ca.\"}},{\"id\":\"EPn2wv1-21\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e00f3acd52.29692002.png\"}}},{\"id\":\"l4QHvIPc9b\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e0145604b5.27021019.png\"}}},{\"id\":\"ukb6SX38-k\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e01acd4bb9.60589089.png\"}}},{\"id\":\"MGHdzYPHeV\",\"type\":\"paragraph\",\"data\":{\"text\":\"Giữ gìn và phát huy giá trị văn hóa dân tộc không chỉ là trách nhiệm mà còn là niềm tự hào của mỗi người dân Việt Nam, đặc biệt là thế hệ trẻ. Văn hóa dân tộc là bản sắc, là hồn cốt của một quốc gia, là những giá trị tinh thần được hun đúc qua hàng ngàn năm lịch sử. Đó là tiếng nói, chữ viết, là văn học, nghệ thuật, là phong tục tập quán, là cách ứng xử và đạo lý làm người. Qua buổi tổ chức chuyên đề \\\"Giữ gìn và phát huy bản sắc dân tộc Tây Nguyên\\\" năm 2025 này đã giúp cho các ĐVTN trường được hiểu thêm về các phong tục tập quán của người đồng bào Ê đê, hiểu về quá trình hình thành và phát triển của các dân tộc bản địa ở Tây Nguyên, từ đó trân trọng hơn những giá trị văn hóa truyền thống.\"}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 10:51:14', '2025-06-22 16:06:30'),
(44, 9, 'Lễ Tri Ân và Trưởng Thành - Khoảnh Khắc Bùng Cháy Của Hoài Niệm, Hân Hoan và Tri Ân!', 'le-tri-an-va-truong-thanh---khoanh-khac-bung-chay-cua-hoai-niem-han-hoan-va-tri-an', 'https://doantnttn.com/server/images/1-6857e04edba909.55542999.png', 4, 'Posted', NULL, '{\"time\":1750589997080,\"blocks\":[{\"id\":\"6D-O8ttTGi\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e04edba909.55542999.png\"}}},{\"id\":\"llGdJjFNzf\",\"type\":\"paragraph\",\"data\":{\"text\":\"&nbsp;Vậy là những ngày tháng cuối cùng của thời học sinh cấp 3 đang dần khép lại, nhường chỗ cho một chương mới đầy hứa hẹn. Hôm nay, chúng ta đã cùng nhau trải qua một buổi Lễ Tri Ân và Trưởng Thành thật ý nghĩa, đong đầy cảm xúc. Từ những nụ cười rạng rỡ đến những giọt nước mắt lăn dài, tất cả đã tạo nên một kỷ niệm khó quên.&nbsp;\"}},{\"id\":\"Yg5n83TMRx\",\"type\":\"paragraph\",\"data\":{\"text\":\" Mở Màn Bùng Nổ Với Âm Nhạc và Hoài Niệm &nbsp;\"}},{\"id\":\"EBBjsCnlc1\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e07be50316.44628249.png\"}}},{\"id\":\"IveEgkaZIi\",\"type\":\"paragraph\",\"data\":{\"text\":\"Buổi lễ bắt đầu với những tiết mục văn nghệ sôi động đến từ các CLB như CLB nhảy Luster,...Tiếng hát, tiếng đàn vang vọng khắp sân trường, như cuốn tất cả chúng ta trở về với những kỷ niệm đẹp đẽ của tuổi học trò. Từ những bài hát về tình bạn, tình thầy trò đến những ca khúc về ước mơ tuổi trẻ, tất cả đều chạm đến trái tim, khiến ai nấy cũng bồi hồi, xao xuyến.\"}},{\"id\":\"864biLnBFa\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e0b4c64078.77663997.png\"}}},{\"id\":\"yQV-z44Ns4\",\"type\":\"paragraph\",\"data\":{\"text\":\"Giây Phút Trưởng Thành Đầy Trang Trọng\"}},{\"id\":\"ylrLr9_yiG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Khoảnh khắc thiêng liêng nhất chính là khi Thầy Nguyễn Tiến Chương-phó hiệu trưởng nhà trường , đọc diễn văn tuyên bố trưởng thành cho học sinh lớp 12. Từng câu chữ đều như lời chúc mừng, lời động viên và cả những lời dặn dò đầy tâm huyết. Sau đó, Nhà trường đã trao tặng những bó hoa tươi thắm, một sự ghi nhận cho hành trình 12 năm đèn sách của chúng ta.\"}},{\"id\":\"5q2PCqhF37\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e123aed4f9.81371562.png\"}}},{\"id\":\"gEFYdpeSon\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lời Tri Ân Sâu Sắc Từ Phụ Huynh và Học Sinh\"}},{\"id\":\"95NxNpyPry\",\"type\":\"paragraph\",\"data\":{\"text\":\"Không thể không nhắc đến những lời phát biểu đầy xúc động của Bác Bùi Quang Trương Tâm-đại diện Hội Cha mẹ học sinh. Những chia sẻ chân thành về tình yêu thương, sự hy sinh của cha mẹ đã khiến không ít bạn học sinh rơi lệ. Hội Phụ huynh cũng đã gửi tặng những bó hoa tươi thắm đến Nhà trường, như một lời cảm ơn sâu sắc trong hành trình trồng người đầy nhiệt huyết.\"}},{\"id\":\"a9v4gGzKRg\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e142d7daa7.77911214.png\"}}},{\"id\":\"Ja0vJDGhCD\",\"type\":\"paragraph\",\"data\":{\"text\":\"Sau đó, đại diện học sinh - bạn Lưu Phương Thảo 12A3, đã có những lời phát biểu chân thành. Đó hẳn là những cảm xúc từ tận đáy lòng về tình thầy trò, tình bạn bè, và cả những dự định cho tương lai đã được gửi gắm trong từng câu nói. Đây thực sự là khoảnh khắc chúng ta nhìn lại cả một chặng đường đã qua, để rồi bước tiếp với hành trang đầy tự tin.\"}},{\"id\":\"pBg0X04b9b\",\"type\":\"paragraph\",\"data\":{\"text\":\"Tri Ân Thầy Cô, Cha Mẹ\"}},{\"id\":\"3Ez3cPEqvm\",\"type\":\"paragraph\",\"data\":{\"text\":\"Các bạn học sinh đã cùng nhau tặng hoa tri ân thầy cô và cha mẹ. Những cái ôm, ánh mắt đầy cảm xúc thay lời cảm ơn. Lúc này, ai cũng nhận ra rằng để có được ngày hôm nay là nhờ tình yêu thương, sự dạy dỗ và hi sinh thầm lặng của thầy cô, cha mẹ. Thật sự rất biết ơn!\"}},{\"id\":\"fyUHZUskne\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lời Chúc Mừng và Động Viên Từ Người Cô Thân Yêu - Cô Hồng \"}},{\"id\":\"vaq7dlWQUJ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Cuối cùng, Cô Hồng - đại diện giáo viên Nhà trường, đã có những lời phát biểu đầy ấm áp. Cô chia sẻ những kỷ niệm, những lời dặn dò và cả những lời chúc tốt đẹp nhất cho tương lai của mỗi chúng ta. Giọng nói của cô như tiếp thêm sức mạnh, niềm tin để chúng ta vững bước trên con đường phía trước. Xin chân thành cảm ơn cô! \\n\\n\"}},{\"id\":\"cqJc9M3-GD\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e1f1c2e4a9.08964708.png\"}}},{\"id\":\"qydqP-t7f-\",\"type\":\"paragraph\",\"data\":{\"text\":\"Buổi Lễ Tri Ân và Trưởng Thành đã khép lại, nhưng những cảm xúc, những kỷ niệm đẹp đẽ sẽ còn mãi trong tâm trí mỗi chúng ta. Đây không chỉ là một buổi lễ, mà còn là một dấu mốc quan trọng, đánh dấu sự trưởng thành của mỗi học sinh lớp 12. Hãy cùng nhau giữ gìn những ký ức này và tự tin bước vào hành trình mới nhé! Chúc tất cả chúng ta sẽ đạt được những thành công rực rỡ trong tương lai!\"}},{\"id\":\"9gjR9te69M\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e2260f29c9.41040020.png\"}}},{\"id\":\"CdVHPNdnts\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e20d075d76.21512135.png\"}}},{\"id\":\"aAY_dX38zz\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e2145a57b3.19820207.png\"}}},{\"id\":\"iTlAdLtxml\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e21c0eaef9.15373428.png\"}}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 10:59:57', '2025-06-22 16:06:30'),
(45, 9, 'RA QUÂN CHIẾN DỊCH \"KỲ NGHỈ HỒNG\" NĂM 2025 VÀ \"THÔNG ĐIỆP YÊU THƯƠNG MÙA VII\"', 'ra-quan-chien-dich-ky-nghi-hong-nam-2025-va-thong-diep-yeu-thuong-mua-vii', 'https://doantnttn.com/server/images/1-6857e29bc97638.48868562.png', 4, 'Posted', NULL, '{\"time\":1750590638088,\"blocks\":[{\"id\":\"RyabVStqZz\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e29bc97638.48868562.png\"}}},{\"id\":\"dPU5adgT25\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ngày 28\\/05\\/2025, Đoàn Thanh niên - Hội sinh viên trường Đại học Tây Nguyên đã chỉ đạo Đoàn Thanh niên - Hội sinh viên Khoa Y dược tổ chức ra quân Chiến dịch \\\" Kỳ nghỉ hồng\\\" và thực hiện chương trình “Thông điệp yêu thương” mùa thứ 7 phối hợp cùng các đơn vị đồng hành tổ chức thành công với thật nhiều dấu ấn ý nghĩa, mang theo hàng trăm phần quà và niềm vui đến với các em nhỏ và người dân tại Buôn Ea Rớk - CưÊLang - Eakar.\"}},{\"id\":\"_-YN36RdfY\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e2bdcf7d45.44744972.png\"}}},{\"id\":\"shmT8CWr4o\",\"type\":\"paragraph\",\"data\":{\"text\":\"Với sự góp mặt của:\"}},{\"id\":\"z_WoBgxhFl\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"15 Cán bộ nhân viên y tế đến từ Khoa Y Dược và Bệnh viện Trường Đại học Tây Nguyên\",\"meta\":[],\"items\":[]}]}},{\"id\":\"VBKuSwaoch\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"80 Sinh viên Khoa Y Dược và các đơn vị Đoàn khoa tham gia \",\"meta\":[],\"items\":[]}]}},{\"id\":\"LZfjVXBQ1Z\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"10 Khách mời đến từ Bộ môn Hóa học và Nhà tài trợ\",\"meta\":[],\"items\":[]}]}},{\"id\":\"qk12AAbZ6S\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"meta\":{\"counterType\":\"numeric\"},\"items\":[{\"content\":\"Tại điểm trường Tiểu học Trần Bình Trọng:\",\"meta\":[],\"items\":[]}]}},{\"id\":\"SkX1Phkh0m\",\"type\":\"paragraph\",\"data\":{\"text\":\"Trao tặng:\"}},{\"id\":\"XcBqymQErP\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"297 phần quà Khuyến học (balo, bình nước, bánh kẹo)\",\"meta\":[],\"items\":[]}]}},{\"id\":\"aSOOtHctEa\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"06 suất Học bổng \\\"Thông điệp yêu thương\\\":\",\"meta\":[],\"items\":[]},{\"content\":\"5 suất hoc bổng \'Tiếp sức đến trường trị giá  5.000.000 VNĐ + quà Khuyến học\",\"meta\":[],\"items\":[]},{\"content\":\"5 suất 1.000.000 VNĐ + quà Khuyến học + Gạo cho các gia đình có hoàn cảnh đặc biệt:\",\"meta\":[],\"items\":[{\"content\":\"1 em nhỏ khuyết tật – con ông Y Jul Niê và bà H’Đăm Byă, sống trong nhà tình thương, đang nuôi 2 con ăn học\",\"meta\":[],\"items\":[]},{\"content\":\"1 em là con bà H’Loan Niê – chồng mất do Covid, một mình nuôi 9 người con\",\"meta\":[],\"items\":[]},{\"content\":\"1 em là con bà H’Ren Niê – chồng mất do tai nạn, nuôi 3 con nhỏ, ở nhờ gian sau nhà chị gái\",\"meta\":[],\"items\":[]}]}]}},{\"id\":\"OGaE_Hj6rS\",\"type\":\"paragraph\",\"data\":{\"text\":\"Đại diện Chương trình – Thầy Ngư Danh Sơn, Bí thư Đoàn khoa Y Dược, đã đến tận nhà để thăm hỏi, lắng nghe, động viên và trao tận tay những món quà đầy nghĩa tình.\"}},{\"id\":\"R02SN6RW3v\",\"type\":\"paragraph\",\"data\":{\"text\":\"Tuyên truyền giáo dục sức khỏe:\"}},{\"id\":\"2VMQjZPDYs\",\"type\":\"paragraph\",\"data\":{\"text\":\"&nbsp;Hướng dẫn vệ sinh tay và rửa tay đúng cách, đặc biệt là phòng chống bệnh sởi\"}},{\"id\":\"F70XaTQTuj\",\"type\":\"paragraph\",\"data\":{\"text\":\"Vẽ sân chơi cho em tại sân trường:\"}},{\"id\":\"BJEjOYn42R\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"Các trò chơi dân gian như: nhảy lò cò, ô ăn quan (2-3-4 người), trò gương soi\",\"meta\":[],\"items\":[]}]}},{\"id\":\"cHNRYJ_pEQ\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"Tạo không gian vui chơi và rèn luyện\",\"meta\":[],\"items\":[]}]}},{\"id\":\"2c0_0A-BgN\",\"type\":\"paragraph\",\"data\":{\"text\":\"Tổ chức:\"}},{\"id\":\"4McdvLTeTC\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"Trò chơi nhận quà nhân dịp 1\\/6\",\"meta\":[],\"items\":[]},{\"content\":\"Cắt tóc miễn phí cho các em nhỏ- \",\"meta\":[],\"items\":[]},{\"content\":\"Quầy nước cam, nước chanh miễn phí  giúp các em giải nhiệt- \",\"meta\":[],\"items\":[]},{\"content\":\"Gian hàng 0 đồng để các em lựa chọn quần áo, vật dụng cần thiết.\",\"meta\":[],\"items\":[]}]}},{\"id\":\"yE0OsNSrGo\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e3d9730ea4.66738291.png\"}}},{\"id\":\"kMuIiPRUIx\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e3ead65199.70834202.png\"}}},{\"id\":\"CuyX1R7pG1\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e3f9999214.05997883.png\"}}},{\"id\":\"Lsw4k4wcgc\",\"type\":\"paragraph\",\"data\":{\"text\":\"2. Tại nhà Cộng đồng Buôn EaRớk:\"}},{\"id\":\"x4koE7xgxO\",\"type\":\"paragraph\",\"data\":{\"text\":\"Trao tặng:\"}},{\"id\":\"FP23aAG5fu\",\"type\":\"paragraph\",\"data\":{\"text\":\"112 hộ dân thuộc diện hộ nghèo: mỗi hộ 5kg gạo + nhu yếu phẩm (mắm, muối, đường)\"}},{\"id\":\"v8cnyVJzfs\",\"type\":\"paragraph\",\"data\":{\"text\":\"Khám chữa bệnh &amp; cấp phát thuốc miễn phí cho:\"}},{\"id\":\"loylvt2oDn\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"Người lớn và trẻ em tại buôn\",\"meta\":[],\"items\":[]}]}},{\"id\":\"_fFBS3IvaD\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"meta\":[],\"items\":[{\"content\":\"Xét nghiệm tầm soát viêm gan B miễn phí\\n\",\"meta\":[],\"items\":[]}]}},{\"id\":\"opqh-BNijp\",\"type\":\"paragraph\",\"data\":{\"text\":\"Gian hàng 0 đồng: người dân tự chọn quần áo, vật dụng\"}},{\"id\":\"vrtJtFPC_J\",\"type\":\"paragraph\",\"data\":{\"text\":\"Cắt tóc miễn phí cho người dân\"}},{\"id\":\"pB0t9hJvm7\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ngoài ra, hưởng ứng lời kêu gọi của CLB Ngoại ngữ – Bee Club, chương trình đã trích 400.000 VNĐ để ủng hộ cho 1 bạn sinh viên ngành Ngôn ngữ Anh, đang gặp hoàn cảnh khó khăn.\"}},{\"id\":\"C2OUiG-LIm\",\"type\":\"paragraph\",\"data\":{\"text\":\"Hiện tại, bạn rất cần sự đồng hành và hỗ trợ từ cộng đồng.\"}},{\"id\":\"g2bmYMlfLh\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e49907a976.27325953.png\"}}},{\"id\":\"Owrs0hnJbq\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e4a9365713.63129744.png\"}}},{\"id\":\"19dgULGae2\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e4a2ad7c79.82025269.png\"}}},{\"id\":\"wNrQOlIM30\",\"type\":\"image\",\"data\":{\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false,\"file\":{\"url\":\"https:\\/\\/doantnttn.com\\/server\\/images\\/1-6857e4abec4d77.62473976.png\"}}}],\"version\":\"2.31.0-rc.7\"}', 1, '2025-06-22 11:10:38', '2025-06-22 16:06:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `type_of_post`
--

CREATE TABLE `type_of_post` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `type_of_post`
--

INSERT INTO `type_of_post` (`type_id`, `type_name`) VALUES
(1, 'Tin tức chung'),
(2, 'Thông báo'),
(3, 'Sự kiện'),
(4, 'Tin nổi bật'),
(5, 'Lịch tuần'),
(6, 'Thi dua'),
(7, 'Đoàn viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_name`) VALUES
(1, 'Khoa Kinh tế'),
(2, 'Khoa Khoa học TN&CN'),
(3, 'Khoa Y Dược'),
(4, 'Khoa Nông nghiệp'),
(5, 'Khoa Ngoại Ngữ'),
(6, 'Khoa Sư phạm'),
(7, 'Khoa Lý luận chính trị'),
(8, 'Trường THPT TH Cao Nguyên'),
(9, 'ĐOÀN TNCSHCM TRƯỜNG ĐẠI HỌC TÂY NGUYÊN');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT './avata/avata-default.jpg',
  `date_of_birth` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_status` enum('Active','Inactive','Banned') NOT NULL,
  `role` enum('Admin','Manager','Author','User') DEFAULT 'User',
  `address` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL DEFAULT 'Other',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `session_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_account`
--

INSERT INTO `user_account` (`user_id`, `unit_id`, `username`, `password`, `fullname`, `email`, `phone_number`, `profile_picture`, `date_of_birth`, `created_at`, `account_status`, `role`, `address`, `gender`, `email_verified`, `session_token`) VALUES
(1, 9, 'quanglam', '$2y$10$MXxnJed4.JprvlOqERNbxeMQiryYmqLAaAQPZXb6eWx2my40mwsR2', '', '', NULL, './avata/avatar-default.jpg', '0000-00-00', '2025-02-21 03:00:21', 'Active', 'Admin', NULL, 'Other', 0, NULL),
(2, 9, 'admin', '$2y$10$ACdssLaDkFmeY5z7zbe0EuGVP0Jq9Lj5H2RQ7Amv8fFy1B54Oyxga', '', '', NULL, './avata/avatar-default.jpg', '0000-00-00', '2025-04-17 03:15:57', 'Active', 'Admin', NULL, 'Other', 0, NULL),
(3, 1, 'doantn_kte', '$2y$10$yRPmuMM/N.Pwj6uWFBW3JOZXPtULOGEHRa.Nz6t4ifzGCL4BePTZK', 'Khoa Kinh tế', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(4, 2, 'doantn_khtncn', '$2y$10$5Qj/On6yl6HSd9PhEUoZUOy8mLw9ujjuUZNeRP0YF7hDGHG4sTlcC', 'Khoa Khoa học TN&CN', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(5, 3, 'doantn_yduoc', '$2y$10$iZOBbkTCshqmjoeoPpcrK.YJ7LBhLH2dDfYf8d1dCuwPcyBpAHWui', 'Khoa Y Dược', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(6, 4, 'doantn_nnghiep', '$2y$10$fbYnD250S3LETMTGzCMpoeRvd/gxGZna9G8Yfzdw0Whd6E/rqaQSS', 'Khoa Nông nghiệp', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(7, 5, 'doantn_nngu', '$2y$10$TAYH7gTPP/R3CJJuE9Tm0OKs0aClrEMG4ZLqp/XHby4u8xAdM55MO', 'Khoa Ngoại ngữ', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(8, 6, 'doantn_spham', '$2y$10$Waiz3iNRIUoJ/yM8fEhnE.3RBhygeuURlUS/0DSycgZo63ikO3JaO', 'Khoa Sư Phạm', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(9, 7, 'doantn_llct', '$2y$10$MRNOe8lq8qDnO08vj4HVzOP1fifAlkc0fB4E08knlEFEkZ8/GoG5G', 'Khoa Lý luận chính trị', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL),
(10, 8, 'doantn_thptthcn', '$2y$10$b.pI1vKq47e.H6p9jcjvHuzjmVtg6FevOuvxYstl6GGXu26pFvJ0u', 'Trường THPT TH Cao Nguyên', NULL, NULL, './avata/avata-default.jpg', '2025-06-22', '2025-06-22 15:57:51', 'Active', 'Author', NULL, 'Other', 0, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `slug_U` (`slug`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Chỉ mục cho bảng `type_of_post`
--
ALTER TABLE `type_of_post`
  ADD PRIMARY KEY (`type_id`);

--
-- Chỉ mục cho bảng `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Chỉ mục cho bảng `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `type_of_post`
--
ALTER TABLE `type_of_post`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

--
-- Các ràng buộc cho bảng `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `user_account_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
