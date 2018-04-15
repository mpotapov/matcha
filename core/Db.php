<?php

class Db
{

    public function __construct()
    {
        $db = self::getConnection();

        $sql = "
    CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT UNIQUE,
    `username`varchar(10) NOT NULL UNIQUE,
	`first_name` varchar(30) NOT NULL,
    `last_name` varchar(30) NOT NULL,
    `email` varchar(30) NOT NULL UNIQUE,
    `password` varchar(300) NOT NULL,
    `country` varchar(300),
    `city` varchar(300),
    `gender` enum('none', 'male', 'female', 'hermaphrodite', 'agender', 'travesti', 'third_gender') DEFAULT 'none',
    `age` int(5),
    `sexual_prefer` enum('none', 'asexual', 'bisexual', 'heterosexual', 'homosexual') DEFAULT 'bisexual',
    `biography` varchar(300),
    `interest_list` SET('Geek', 'IT', 'VapeNation', 'Sport', 'Traveller', 'Bummer', 'Anime', 'Games', 'Social', 'Serials', 'Hokage', 'Music', 'Family', 'Alcohol'),
    `fame_rating` int(11) NOT NULL,
    `last_activity` datetime,
    `activation_key` varchar(30) NOT NULL,
    `status` enum('1', '0') DEFAULT '0',
    `location` enum('1', '0') DEFAULT '1',
    PRIMARY KEY (`id`));
    
    CREATE TABLE IF NOT EXISTS photos (
	id int(11) NOT NULL UNIQUE,
    profile_photo varchar(300),
    photo1 varchar(300),
    photo2 varchar(300),
    photo3 varchar(300),
    photo4 varchar(300),
    PRIMARY KEY (id));
    
    CREATE TABLE IF NOT EXISTS `markers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` varchar(300),
  `lat` FLOAT( 10, 6 ) NOT NULL ,
  `lng` FLOAT( 10, 6 ) NOT NULL ,
	`user_id` INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    PRIMARY KEY (id));
    
    CREATE TABLE IF NOT EXISTS matches(
    id int NOT NULL AUTO_INCREMENT,
    who_id int NOT NULL,
    whom_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (who_id) REFERENCES users(id),
    FOREIGN KEY (whom_id) REFERENCES users(id));
    
        CREATE TABLE IF NOT EXISTS block(
    id int NOT NULL AUTO_INCREMENT,
    who_id int NOT NULL,
    whom_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (who_id) REFERENCES users(id),
    FOREIGN KEY (whom_id) REFERENCES users(id));
    
            CREATE TABLE IF NOT EXISTS fake(
    id int NOT NULL AUTO_INCREMENT,
    who_id int NOT NULL,
    whom_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (who_id) REFERENCES users(id),
    FOREIGN KEY (whom_id) REFERENCES users(id));
    
                CREATE TABLE IF NOT EXISTS connected(
    id int NOT NULL AUTO_INCREMENT,
    who_id1 int NOT NULL,
    who_id2 int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (who_id1) REFERENCES users(id),
    FOREIGN KEY (who_id2) REFERENCES users(id));
    
                    CREATE TABLE IF NOT EXISTS messages(
    id int NOT NULL AUTO_INCREMENT,
    from_id int NOT NULL,
    to_id int NOT NULL,
    message text NOT NULL,
    time datetime,
    PRIMARY KEY (id),
    FOREIGN KEY (from_id) REFERENCES users(id),
    FOREIGN KEY (to_id) REFERENCES users(id));
    
                        CREATE TABLE IF NOT EXISTS notifications(
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    notification varchar(300) NOT NULL,
    `time` datetime,
    `status` ENUM('1', '0') DEFAULT '0',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id));
";
        $result = $db->prepare($sql);
        $result->execute();
    }

    public static function getConnection()
    {
        $params = include (ROOT . '/config/db_params.php');

        try {
            $db = new PDO("mysql:host={$params['host']};dbname={$params['db_name']}",
                $params['user'], $params['password']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return $db;
    }
}