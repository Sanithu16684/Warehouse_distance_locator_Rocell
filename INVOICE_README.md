# Invoice Management System

## Overview
This is a comprehensive invoice management system integrated with the Warehouse Distance Locator application. It provides full CRUD (Create, Read, Update, Delete) functionality for managing invoices.

## Features

### 1. **View All Invoices** (`invoices.html`)
- Display all invoices in a clean, organized table
- View invoice number, customer name, dates, total amount, and status
- Color-coded status badges (Pending, Paid, Overdue, Cancelled)
- Quick action buttons for viewing, editing, and deleting invoices
- Empty state display when no invoices exist

### 2. **Create New Invoice** (`create_invoice.html`)
- Intuitive form for creating new invoices
- Customer information section (name, email, phone)
- Dynamic invoice items table
  - Add multiple items
  - Remove items
  - Automatic total calculation per item
- Automatic calculation of:
  - Subtotal
  - Tax (15%)
  - Total amount
- Invoice status selection
- Notes field for additional information
- Form validation

### 3. **Edit Invoice** (`edit_invoice.html`)
- Pre-populated form with existing invoice data
- Same features as create invoice
- Update all invoice details
- Maintain invoice history through updated_at timestamp

### 4. **View Invoice Details** (`invoice_details.html`)
- Complete invoice information display
- Professional invoice layout
- Customer information
- Itemized list of products/services
- Financial summary
- Status display
- Print functionality
- Quick access to edit or delete

## Database Schema

The system uses a MySQL database table `invoices` with the following structure:

```sql
- id (Primary Key)
- invoice_number (Unique)
- customer_name
- customer_email
- customer_phone
- invoice_date
- due_date
- items (JSON format)
- subtotal
- tax
- total
- status (pending/paid/overdue/cancelled)
- notes
- created_at
- updated_at
```

## Backend API Endpoints

### PHP Files:
1. **get_invoices.php** - Retrieve all invoices
2. **get_invoice.php** - Retrieve single invoice by ID
3. **create_invoice.php** - Create new invoice
4. **update_invoice.php** - Update existing invoice
5. **delete_invoice.php** - Delete invoice

## Frontend Technologies

- **HTML5**: Structure and layout
- **CSS3**: Styling with modern gradients and animations
- **JavaScript (Vanilla)**: Dynamic functionality
- **SweetAlert2**: Beautiful alert dialogs
- **Responsive Design**: Works on desktop and mobile devices

## Installation

1. Import the database schema:
   ```sql
   mysql -u root -p location_map < invoices.sql
   ```

2. Ensure your web server (Apache/Nginx) has PHP enabled and MySQL configured

3. Access the application through your web browser:
   - Main map interface: `index.php`
   - Invoice management: `invoices.html`

## Usage

### Creating an Invoice:
1. Click "Create New Invoice" button
2. Fill in customer information
3. Add invoice items (description, quantity, unit price)
4. Review the automatically calculated totals
5. Add any additional notes
6. Click "Save Invoice"

### Viewing Invoices:
1. Access `invoices.html` to see all invoices
2. Click "View" button to see detailed invoice information
3. Use the print button to generate a printable version

### Editing an Invoice:
1. From the invoices list or detail view, click "Edit"
2. Modify the required fields
3. Click "Update Invoice"

### Deleting an Invoice:
1. Click "Delete" button on any invoice
2. Confirm the deletion in the popup dialog

## Design Features

- **Modern UI**: Gradient backgrounds and smooth transitions
- **Intuitive Navigation**: Clear buttons and links between pages
- **Responsive Layout**: Adapts to different screen sizes
- **Status Indicators**: Color-coded badges for quick status identification
- **Real-time Calculations**: Automatic totals and tax calculations
- **User Feedback**: Success/error messages using SweetAlert2
- **Print-friendly**: Special CSS for printing invoice details

## Security Considerations

- SQL injection protection using prepared statements
- Input validation on both frontend and backend
- Proper error handling
- Escaped output to prevent XSS attacks

## Navigation

- From the main map view (`index.php`), access Invoice Management via the sidebar button
- From any invoice page, return to the main map or navigate between invoice pages
- Breadcrumb-style navigation with back buttons

## Future Enhancements

Potential improvements could include:
- PDF export functionality
- Email invoice to customers
- Payment tracking
- Invoice templates
- Multi-currency support
- Advanced filtering and search
- Invoice numbering automation
- Recurring invoices
