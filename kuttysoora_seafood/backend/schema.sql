-- Create database
CREATE DATABASE IF NOT EXISTS kuttizgf_kuttysoora_seafood;
USE kuttizgf_kuttysoora_seafood;

-- Users table: stores comprehensive user information
CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	mobile VARCHAR(20) NOT NULL UNIQUE,
	name VARCHAR(100) NOT NULL,
	-- role: 'admin' or 'user' (default 'user')
	role VARCHAR(20) NOT NULL DEFAULT 'user',
	password_hash VARCHAR(255) DEFAULT NULL,
	address TEXT DEFAULT '',
	house VARCHAR(100) DEFAULT '',
	street VARCHAR(100) DEFAULT '',
	area VARCHAR(100) DEFAULT '',
	city VARCHAR(100) DEFAULT '',
	pin_code VARCHAR(10) DEFAULT '',
	landmark VARCHAR(255) DEFAULT '',
	referral VARCHAR(255) DEFAULT '',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	category VARCHAR(100) NOT NULL DEFAULT 'Seafood',
	description TEXT,
	price DECIMAL(10,2) NOT NULL,
	stock INT NOT NULL DEFAULT 0,
	image_url VARCHAR(255),
	brand VARCHAR(100) DEFAULT 'Kutty Soora',
	sku VARCHAR(50) UNIQUE,
	availability VARCHAR(50) DEFAULT 'in_stock',
	weight VARCHAR(100),
	dimensions VARCHAR(100),
	material VARCHAR(100),
	color VARCHAR(50),
	images TEXT,
	tags TEXT,
	created_date DATE,
	last_updated DATE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT NOT NULL DEFAULT 1,
	added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
	UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	total_amount DECIMAL(10,2) NOT NULL,
	status VARCHAR(50) DEFAULT 'pending',
	name VARCHAR(100) NOT NULL,
	address TEXT,
	phone VARCHAR(20),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items table
CREATE TABLE IF NOT EXISTS order_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
	order_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT NOT NULL,
	price DECIMAL(10,2) NOT NULL,
	FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, category, description, price, stock, image_url) VALUES
('Fresh King Prawns', 'Prawns', 'Large, fresh king prawns caught daily', 450.00, 25, 'assets/prawns.jpg'),
('Pomfret Fish', 'Fish', 'Fresh pomfret fish, cleaned and ready to cook', 380.00, 15, 'assets/pomfret.jpg'),
('Tiger Prawns', 'Prawns', 'Medium-sized tiger prawns, perfect for curries', 320.00, 30, 'assets/tiger_prawns.jpg'),
('Kingfish Steaks', 'Fish', 'Fresh kingfish cut into steaks', 420.00, 18, 'assets/kingfish.jpg'),
('Crab (Medium)', 'Crab', 'Fresh medium-sized crabs', 280.00, 12, 'assets/crab.jpg'),
('Squid Rings', 'Squid', 'Cleaned squid cut into rings', 250.00, 20, 'assets/squid.jpg'),
('Mackerel Fish', 'Fish', 'Fresh mackerel fish, rich in omega-3', 180.00, 40, 'assets/mackerel.jpg'),
('Lobster', 'Lobster', 'Fresh lobster, premium quality', 850.00, 8, 'assets/lobster.jpg');
