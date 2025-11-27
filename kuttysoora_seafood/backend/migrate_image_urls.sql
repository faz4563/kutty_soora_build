-- Update existing product image URLs to match the images column data
-- This script updates image_url based on the images column

UPDATE products 
SET image_url = SUBSTRING_INDEX(images, ',', 1)
WHERE images IS NOT NULL AND images != '';

-- For products that don't have images column data but have the old assets/ paths,
-- you can manually update them here
-- Example:
-- UPDATE products SET image_url = 'images/product_image_001.png' WHERE id = 1;
-- UPDATE products SET image_url = 'images/product_image_002.png' WHERE id = 2;
