@echo off
REM Setup script for Golden Palace Hotel Management System on Windows

echo =====================================================
echo Golden Palace Hotel Management System - Setup Script
echo =====================================================
echo.
echo This script will help you set up the hotel management system for Windows with XAMPP.
echo.

REM Check if user wants to proceed
set /p proceed=Do you want to continue? (y/n): 

if not "%proceed%"=="y" (
    echo Setup cancelled.
    exit /b 0
)

REM Check for XAMPP
if not exist "C:\xampp\mysql\bin\mysql.exe" (
    echo XAMPP does not seem to be installed at the default location.
    echo Please install XAMPP first or update this script with your installation path.
    exit /b 1
)

REM Check if MySQL is running
netstat -ano | findstr "3306" > nul
if %errorlevel% neq 0 (
    echo MySQL does not appear to be running.
    echo Please start MySQL in your XAMPP control panel first.
    exit /b 1
)

echo Checking database connection...
echo.

REM Create database command
set db_create=CREATE DATABASE IF NOT EXISTS goldenpalacehotel;

REM Execute MySQL command to create database
"C:\xampp\mysql\bin\mysql.exe" -u root -e "%db_create%"

REM Check if successful
if %errorlevel% equ 0 (
    echo Database created/verified successfully.
) else (
    echo Error: Could not create database. Please check your MySQL credentials.
    exit /b 1
)

REM Import database schema
set /p import_schema=Do you want to import the database schema? (y/n): 

if "%import_schema%"=="y" (
    "C:\xampp\mysql\bin\mysql.exe" -u root goldenpalacehotel < goldenpalacehotel.sql
    
    if %errorlevel% equ 0 (
        echo Database schema imported successfully.
    ) else (
        echo Error: Could not import database schema.
        exit /b 1
    )
)

REM Update config file if needed
set /p update_config=Do you want to update the config.php file with your database credentials? (y/n): 

if "%update_config%"=="y" (
    set /p db_server=Enter database server (default is localhost): 
    if "%db_server%"=="" set db_server=localhost
    
    set /p db_username=Enter database username (default is root): 
    if "%db_username%"=="" set db_username=root
    
    set /p db_password=Enter database password (leave empty for no password): 
    
    set /p db_name=Enter database name (default is goldenpalacehotel): 
    if "%db_name%"=="" set db_name=goldenpalacehotel
    
    REM Create config file
    echo ^<?php > config.php
    echo. >> config.php
    echo $server = "%db_server%"; >> config.php
    echo $username = "%db_username%"; >> config.php
    echo $password = "%db_password%"; >> config.php
    echo $database = "%db_name%"; >> config.php
    echo. >> config.php
    echo $conn = mysqli_connect($server, $username, $password, $database); >> config.php
    echo. >> config.php
    echo if(!$conn){ >> config.php
    echo     die("^<script^>alert('Connection Failed.')^</script^>"); >> config.php
    echo } >> config.php
    echo ?^> >> config.php
    
    echo config.php updated successfully.
)

echo.
echo =====================================================
echo Setup complete! You can now access your hotel management system.
echo Go to: http://localhost/HMS/index.php
echo =====================================================
