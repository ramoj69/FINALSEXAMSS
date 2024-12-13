

CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	is_admin TINYINT(1) NOT NULL DEFAULT 0,
	is_suspended TINYINT(1) NOT NULL DEFAULT 0,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


# superadmin account

# Username: superadmin
# Password: $2y$10$Y4b1UT2wJyp8XNykJjX7

CREATE TABLE user_posts (
	user_post_id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR (50),
	body TEXT,
	user_id INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)


CREATE TABLE branches (
	branch_id INT AUTO_INCREMENT PRIMARY KEY,
	address VARCHAR(255),
	head_manager VARCHAR(255),
	contact_number VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
	added_by VARCHAR(255),
	last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	last_updated_by VARCHAR(255)
);

CREATE TABLE activity_logs (
	activity_log_id INT AUTO_INCREMENT PRIMARY KEY,
	operation VARCHAR(255),
	branch_id INT,
	address VARCHAR(255),
	head_manager VARCHAR(255),
	contact_number VARCHAR (255),
	username VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE photos (
	photo_id INT AUTO_INCREMENT PRIMARY KEY,
	photo_name TEXT,
	username VARCHAR(255),
	description VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE inquiries ( -- A typical user can only create inquiries
	inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
	description TEXT,
	user_id INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE replies ( -- An admin can only reply to inquiries
	reply_id INT AUTO_INCREMENT PRIMARY KEY,
	description TEXT,
	inquiry_id INT,
	user_id INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);