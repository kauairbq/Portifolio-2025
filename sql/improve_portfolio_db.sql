USE portfolio_db;

-- Add timestamps to categories
ALTER TABLE categories
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Add timestamps and index to projects
ALTER TABLE projects
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD INDEX idx_category_id (category_id);

-- Modify FK constraint to add ON UPDATE CASCADE
ALTER TABLE projects DROP FOREIGN KEY projects_ibfk_1;

ALTER TABLE projects
ADD CONSTRAINT fk_projects_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Optional: Add unique constraint on categories.name
ALTER TABLE categories
ADD CONSTRAINT unique_category_name UNIQUE (name);