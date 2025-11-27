-- Migration script to add missing columns to users table
USE kuttizgf_kuttysoora_seafood;

-- Add missing columns to users table if they don't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS address TEXT DEFAULT '',
ADD COLUMN IF NOT EXISTS house VARCHAR(100) DEFAULT '',
ADD COLUMN IF NOT EXISTS street VARCHAR(100) DEFAULT '',
ADD COLUMN IF NOT EXISTS area VARCHAR(100) DEFAULT '',
ADD COLUMN IF NOT EXISTS city VARCHAR(100) DEFAULT '',
ADD COLUMN IF NOT EXISTS pin_code VARCHAR(10) DEFAULT '',
ADD COLUMN IF NOT EXISTS landmark VARCHAR(255) DEFAULT '',
ADD COLUMN IF NOT EXISTS referral VARCHAR(255) DEFAULT '';

-- Add role column if missing (default to 'user')
ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) NOT NULL DEFAULT 'user';
-- Add password hash column if missing (nullable)
ALTER TABLE users ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) DEFAULT NULL;

-- Verify the table structure
DESCRIBE users;