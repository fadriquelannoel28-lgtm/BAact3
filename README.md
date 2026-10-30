# EcoCoins - Automated Recycling Reward Points System

A Web-Based Automated Recycling Reward Points System for Barangay Residents. Built with PHP, Bootstrap 5, JavaScript, and MySQL.

## Features & Modules

- **Resident Registration & QR Management**: Self-registration with automatic personal QR code generation.
- **Automated Collection Station Simulator**: Simulates scanning a resident's QR code, detecting deposited recyclables (plastic bottles, cans, etc.), and calculating reward points.
- **EcoPoints Dashboard & Breakdown**: Resident portal showing total EcoPoints balance, monthly stats, recycling activity history, and interactive points breakdown.
- **Reward Redemption Catalog**: Residents can browse rewards from local community partners (SM Lipa, Robinsons, Coffee Project) and redeem vouchers.
- **Recycling History**: Real-time log of deposits with category filters (Plastic, Can, Others).
- **Barangay Admin Dashboard**: Comprehensive administrator portal to manage residents, monitor live deposits, configure accepted materials and point values, manage rewards inventory, and generate community recycling reports.

## Default Credentials

### Resident Account
- **Email**: `user@example.com`
- **Password**: `user123`
- **Personal QR Code**: `ECO-RES-88219`

### Barangay Admin Account
- **Email**: `admin@ecocoins.ph`
- **Password**: `admin123`

## Setup & Running

1. Open XAMPP Control Panel and start **Apache** (and optionally **MySQL**).
2. Clone or place this folder in `C:\xampp\htdocs\Ecocoins`.
3. Open your browser and navigate to `http://localhost/Ecocoins`.
4. If using MySQL in phpMyAdmin, you can optionally import `database/ecocoins.sql`. The system also includes an automatic fallback data layer so it works out of the box.
