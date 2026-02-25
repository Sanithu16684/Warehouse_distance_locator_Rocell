-- Invoice Management System Database Schema
-- Add this to the existing location_map database

USE `location_map`;

-- Create invoices table
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL UNIQUE,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `items` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','overdue','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample data for testing
INSERT INTO `invoices` (`invoice_number`, `customer_name`, `customer_email`, `customer_phone`, `invoice_date`, `due_date`, `items`, `subtotal`, `tax`, `total`, `status`, `notes`) VALUES
('INV-2025-001', 'ABC Company Ltd', 'contact@abccompany.lk', '+94 11 234 5678', '2025-01-01', '2025-01-31', '[{"description":"Product A","quantity":10,"unit_price":1000}]', 10000.00, 1500.00, 11500.00, 'pending', 'First invoice of the year'),
('INV-2025-002', 'XYZ Traders', 'info@xyztraders.lk', '+94 77 123 4567', '2025-01-05', '2025-02-05', '[{"description":"Product B","quantity":5,"unit_price":2500}]', 12500.00, 1875.00, 14375.00, 'paid', NULL);
