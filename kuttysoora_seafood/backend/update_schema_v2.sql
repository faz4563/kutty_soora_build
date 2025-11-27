-- Update schema to support all new requirements
USE kuttizgf_kuttysoora_seafood;

-- Add minimum_quantity field to products table for items like "Half Kg"
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS minimum_quantity VARCHAR(50) DEFAULT NULL AFTER availability,
ADD COLUMN IF NOT EXISTS is_special BOOLEAN DEFAULT 0 AFTER minimum_quantity,
ADD COLUMN IF NOT EXISTS is_dry BOOLEAN DEFAULT 0 AFTER is_special;

-- Update some products to have pre-order status
UPDATE products 
SET availability = 'pre_order' 
WHERE id IN (1, 4, 8) 
LIMIT 3;

-- Update some products to be out of stock
UPDATE products 
SET availability = 'out_of_stock' 
WHERE id IN (2, 6) 
LIMIT 2;

-- Add minimum quantities for shellfish
UPDATE products 
SET minimum_quantity = '500g' 
WHERE category IN ('Crab', 'Prawns', 'Lobster');

-- Mark some as special seafoods
UPDATE products 
SET is_special = 1 
WHERE id IN (1, 8);

-- Add sample dry seafood products if they don't exist
INSERT INTO products (name, category, description, price, stock, image_url, availability, is_dry, minimum_quantity) 
SELECT * FROM (
    SELECT 'Dried Prawns' as name, 'Dry Seafood' as category, 'Premium quality dried prawns' as description, 
           350.00 as price, 20 as stock, 'assets/dried_prawns.jpg' as image_url, 
           'in_stock' as availability, 1 as is_dry, '250g' as minimum_quantity
    UNION ALL
    SELECT 'Dried Fish (Karuvadu)', 'Dry Seafood', 'Traditional sun-dried fish', 
           280.00, 15, 'assets/dried_fish.jpg', 'in_stock', 1, '250g'
    UNION ALL
    SELECT 'Anchovy Dry', 'Dry Seafood', 'Small dried anchovies', 
           220.00, 25, 'assets/anchovy.jpg', 'in_stock', 1, '200g'
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM products WHERE category = 'Dry Seafood'
) LIMIT 3;

-- Ensure all products have proper stock status based on availability
UPDATE products SET stock = 0 WHERE availability = 'out_of_stock';
UPDATE products SET stock = GREATEST(stock, 5) WHERE availability = 'in_stock';
UPDATE products SET stock = GREATEST(stock, 3) WHERE availability = 'pre_order';
