-- Migration script to add minimum_quantity field to products table
-- Run this script to add the new dropdown fields

USE kuttizgf_kuttysoora_seafood;

-- Add minimum_quantity column if it doesn't exist
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS minimum_quantity VARCHAR(50) DEFAULT 'per_kg';

-- Update existing products to have default value
UPDATE products 
SET minimum_quantity = 'per_kg' 
WHERE minimum_quantity IS NULL OR minimum_quantity = '';

-- Show the updated table structure
DESCRIBE products;
