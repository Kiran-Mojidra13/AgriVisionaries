CREATE TABLE `chatbot_hints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `reply` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `chatbot_hints`
INSERT INTO `chatbot_hints` (`id`, `question`, `reply`) VALUES
(1, 'HI||Hello||Hola', 'Hello, how are you.'),
(2, 'How are you', 'Good to see you again!'),
(3, 'what is your name||whats your name', 'My name is Vishal Bot'),
(4, 'what should I call you', 'You can call me Vishal Bot'),
(5, 'Where are you from', 'I\'m from India'),
(6, 'Bye||See you later||Have a Good Day', 'Sad to see you are going. Have a nice day');

-- Table structure for table `message`
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `message`
INSERT INTO `message` (`id`, `message`, `added_on`, `type`) VALUES
(1, 'Hi', '2020-04-22 12:41:04', 'user'),
(2, 'Hello, how are you.', '2020-04-22 12:41:05', 'bot'),
(3, 'what is your name', '2020-04-22 12:41:22', 'user'),
(4, 'My name is Vishal Bot', '2020-04-22 12:41:22', 'bot'),
(5, 'Where are you from', '2020-04-22 12:41:30', 'user'),
(6, 'I\'m from India', '2020-04-22 12:41:30', 'bot'),
(7, 'Go to hell', '2020-04-22 12:41:41', 'user'),
(8, 'Sorry, I am not able to understand you', '2020-04-22 12:41:41', 'bot'),
(9, 'bye', '2020-04-22 12:41:46', 'user'),
(10, 'Sad to see you are going. Have a nice day', '2020-04-22 12:41:46', 'bot');

COMMIT;
