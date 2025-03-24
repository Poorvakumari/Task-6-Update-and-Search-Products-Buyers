-- Create database if not exists
CREATE DATABASE IF NOT EXISTS product_buyer_db;
USE product_buyer_db;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create buyers table
CREATE TABLE IF NOT EXISTS buyers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'seller') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample products
INSERT INTO products (name, category, price, description) VALUES
('iPhone 13 Pro', 'Electronics', 999.99, 'Latest iPhone model with advanced camera system'),
('Samsung Galaxy S21', 'Electronics', 799.99, 'Android flagship with 5G capability'),
('MacBook Pro M1', 'Electronics', 1299.99, 'Apple Silicon powered laptop'),
('Nike Air Max', 'Footwear', 129.99, 'Classic running shoes with air cushioning'),
('Adidas Ultra Boost', 'Footwear', 159.99, 'Premium running shoes with boost technology'),
('Leather Wallet', 'Accessories', 49.99, 'Genuine leather wallet with multiple card slots'),
('Smart Watch', 'Electronics', 199.99, 'Fitness tracking smartwatch with heart rate monitor'),
('Gaming Mouse', 'Electronics', 79.99, 'High-precision gaming mouse with RGB lighting'),
('Coffee Maker', 'Home Appliances', 89.99, '12-cup programmable coffee maker'),
('Blender', 'Home Appliances', 69.99, 'High-speed blender for smoothies and shakes'),
('Yoga Mat', 'Sports', 29.99, 'Non-slip yoga mat with carrying strap'),
('Dumbbell Set', 'Sports', 149.99, 'Adjustable dumbbell set with storage rack'),
('Desk Chair', 'Furniture', 129.99, 'Ergonomic office chair with lumbar support'),
('Standing Desk', 'Furniture', 299.99, 'Electric adjustable standing desk'),
('Wireless Headphones', 'Electronics', 159.99, 'Noise-cancelling wireless headphones');

-- Insert sample buyers
INSERT INTO buyers (name, email, phone, location) VALUES
('John Smith', 'john.smith@email.com', '555-0101', 'New York'),
('Sarah Johnson', 'sarah.j@email.com', '555-0102', 'Los Angeles'),
('Michael Brown', 'michael.b@email.com', '555-0103', 'Chicago'),
('Emma Wilson', 'emma.w@email.com', '555-0104', 'Houston'),
('David Lee', 'david.l@email.com', '555-0105', 'Phoenix'),
('Lisa Anderson', 'lisa.a@email.com', '555-0106', 'Philadelphia'),
('Robert Taylor', 'robert.t@email.com', '555-0107', 'San Antonio'),
('Jennifer White', 'jennifer.w@email.com', '555-0108', 'San Diego'),
('William Martinez', 'william.m@email.com', '555-0109', 'Dallas'),
('Elizabeth Davis', 'elizabeth.d@email.com', '555-0110', 'San Jose'),
('James Wilson', 'james.w@email.com', '555-0111', 'Austin'),
('Mary Johnson', 'mary.j@email.com', '555-0112', 'Jacksonville'),
('Thomas Anderson', 'thomas.a@email.com', '555-0113', 'Fort Worth'),
('Patricia Brown', 'patricia.b@email.com', '555-0114', 'Columbus'),
('Daniel Taylor', 'daniel.t@email.com', '555-0115', 'San Francisco');

-- Insert sample users (admin and seller)
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: password
('seller1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'seller'), -- password: password
('seller2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'seller'); -- password: password
