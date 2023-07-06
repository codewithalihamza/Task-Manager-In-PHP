-- Drop the existing tables if they exist
DROP TABLE IF EXISTS `todos`;
DROP TABLE IF EXISTS `users`;

-- Create the users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the todos table
CREATE TABLE IF NOT EXISTS `todos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `title` VARCHAR(250) DEFAULT NULL,
  `status` TINYINT(3) UNSIGNED DEFAULT '0',
  `due_date` DATE DEFAULT NULL,
  `attachment` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for testing
INSERT INTO `users` (`name`, `username`, `email`, `password`) VALUES
    ('John Doe', 'johndoe', 'johndoe@example.com', '123');

INSERT INTO `todos` (`user_id`, `title`, `status`, `due_date`, `attachment`) VALUES
    (1, 'Wake Up and Walk', 0, '2023-05-26', NULL),
    (1, 'Eat Breakfast', 0, '2023-05-27', NULL),
    (1, 'Do coding easily', 0, '2023-05-27', 'attachment1.pdf'),
    (1, 'Sleep', 1, '2023-05-28', 'attachment2.jpg');
