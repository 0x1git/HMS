# Golden Palace Hotel Management System

## Database Setup

1. Start XAMPP and make sure MySQL and Apache are running
2. Open phpMyAdmin (http://localhost/phpmyadmin/)
3. Create a new database named `goldenpalacehotel`
4. Import the `goldenpalacehotel.sql` file to set up the database structure

## Configuration

The system uses the following default credentials:
- Database server: localhost
- Database username: root
- Database password: (empty by default, modify in config.php if needed)
- Database name: goldenpalacehotel

## Important Note About Database Changes

If upgrading from a previous version that used the name "bluebirdhotel", you may need to:
1. Export your data from the old database
2. Import it into the new "goldenpalacehotel" database
3. Or run the db_rename.sql script to rename the database

## Recent Fixes

- Fixed duplicate HTML tags in admin.php
- Corrected path issues for redirection
- Updated database configuration
- Fixed iframe class issues
- Updated logo references
- Standardized currency symbols
- Removed hardcoded paths and credentials
