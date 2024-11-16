/* Setup for the passwords database*/

DROP DATABASE IF EXISTS passwords;

CREATE DATABASE passwords;


/* Create a new user for this database. Not required, but included anyway.*/

DROP USER IF EXISTS 'passwords_db_user'@'localhost';

CREATE USER 'passwords_db_user'@'localhost' IDENTIFIED BY 'HVGyt789uIOJknbhvgytf&^89uionjk';
GRANT ALL PRIVILEGES ON passwords.*  TO 'passwords_db_user'@'localhost';

use passwords;


/* Set up encryption */

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('my secret passphrase', 256));
SET @init_vector = RANDOM_BYTES(16);


/* Create the tables for the database */

CREATE TABLE IF NOT EXISTS users (
  first_name VARCHAR(128) NOT NULL,
  last_name VARCHAR(128) NOT NULL,
  user_id SMALLINT NOT NULL AUTO_INCREMENT,

  PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS websites (
  site_name VARCHAR(128) NOT NULL,
  url VARCHAR(256) UNIQUE NOT NULL,   -- UNIQUE because no two websites can have the same URL
  site_id SMALLINT NOT NULL AUTO_INCREMENT,

  PRIMARY KEY (site_id)
);

/* Accounts table holds information for a user's account at a website, including their username and password. */
CREATE TABLE IF NOT EXISTS accounts (
  username VARCHAR(128) NOT NULL,
  password VARBINARY(512) NOT NULL,
  email_address VARCHAR(128) NOT NULL,
  user_id SMALLINT NOT NULL,  -- Relation to users table. Way to know which user this username and password is associated with.
  site_id SMALLINT NOT NULL,  -- Relation to websites table. Way to know what website this is associated with.
  comment VARCHAR(512),
  time_stamp DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Automatically sets the timestamp to current time and updates it if the account is updated.

  -- The same user cannot have a second account at the same website with the same username.
  PRIMARY KEY (user_id, site_id, username),

  /*
    Ensures that deletion of a user results in deleting their accounts, and updating the user's id is reflected in
    their accounts. Prevents "dangling" accounts with no user who "owns" them.
  */
  FOREIGN KEY (user_id)
    REFERENCES users(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

  /*
    Similar to the foreign key for user_id, so that if a website is deleted, accounts that are connected to it are
    deleted with it.
  */
  FOREIGN KEY (site_id)
    REFERENCES websites(site_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);



/* Insert values for the database */
INSERT INTO users (first_name, last_name) VALUES
  ('John', 'Smith'),
  ('Peter', 'Parker'),
  ('Satoru', 'Gojo'),
  ('Roy', 'Vanegas'),
  ('Carolyn', 'Rosiene');



INSERT INTO websites (site_name, url) VALUES
  ('Youtube', 'https://www.youtube.com/'),
  ('GitHub', 'https://github.com/'),
  ('Crunchyroll', 'https://www.crunchyroll.com/'),
  ('Uhart blackboard', 'http://blackboard.hartford.edu/'), -- pretend blackboard only has http to demonstrate getting only accounts associated with https URLs
  ('Netflix', 'https://www.netflix.com/browse');


INSERT INTO accounts (username, password, email_address, user_id, site_id, comment) VALUES
  ('jsmith', AES_ENCRYPT('5678JohnSmithIsTheBest62374uerfjncb', @key_str, @init_vector), 'jsmith@gmail.com', 1, 1, 'This will be the most epic youtube account ever.'),
  ('jsmith95', AES_ENCRYPT('4521485bestpassword*290', @key_str, @init_vector), 'jsmith@outlook.com', 1, 2, 'Going to make the best projects on github.'),
  ('johsmith', AES_ENCRYPT('F5q`s2K4@[N4=I+G9', @key_str, @init_vector), 'johsmith@hartford.edu', 1, 4, 'UHART blackboard account'),
  ('jsmith2', AES_ENCRYPT('£e7R92AZDb68@V]JM', @key_str, @init_vector), 'jsmith2@gmail.com', 1, 1, 'Second youtube account.'),
  ('not_spiderman2002', AES_ENCRYPT('ThisIsATotallySecurePassword', @key_str, @init_vector), 'pparker@live.com', 2, 1, NULL),
  ('peter_parker32', AES_ENCRYPT('2"dG1Y6v,TdRz4-5D|bzWR~U?=][_', @key_str, @init_vector), 'pparker@live.com', 2, 5, 'You can watch spiderman on netflix.'),
  ('the_honored_one', AES_ENCRYPT('8TurnedSidewaysisInfinity3729075', @key_str, @init_vector), 'honored_one@gmail.com', 3, 3, 'It might be a little difficult, but would I lose? Nah, I\'d win.'),
  ('sgojo', AES_ENCRYPT('8TurnedSidewaysisInfinity3729075', @key_str, @init_vector), 'sgojo@hartford.edu', 3, 4, 'It might be gojover for his grades.'),
  ('vanegas', AES_ENCRYPT('qK13*M29 S0HL&aMGFQ{MsN6<^T{o_', @key_str, @init_vector), 'vanegas@hartford.edu', 4, 4, 'Professor Vanegas\' UHART account.'),
  ('codewarrior', AES_ENCRYPT('ipo-6CG6}zom`1M2=::}_b£"\+?+r', @key_str, @init_vector), 'rvanegas@outlook.com', 4, 2, NULL),
  ('rosiene', AES_ENCRYPT('{d].9E5b420N1ja[}@99z+a;Zru)V', @key_str, @init_vector), 'rosiene@hartford.edu', 5, 4, 'Professor Rosiene\'s UHART account.');
