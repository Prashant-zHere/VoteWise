# VoteWise - Online Voting System

A secure, web-based voting platform designed for educational institutions and organizations to conduct transparent elections with role-based access control.

## Features

### Multi-Role Authentication
- **Admin**: Complete system management and oversight
- **Candidates**: Registration, profile management, and campaign information
- **Voters**: Secure voting interface with ballot casting

### Core Functionality
- **Candidate Registration**: Self-registration with approval workflow
- **Position Management**: Create and manage electoral positions
- **Secure Voting**: One-vote-per-position system with voter verification
- **Real-time Results**: Live vote counting and analytics
- **PDF Reports**: Generate printable election results
- **Dashboard Analytics**: Visual charts and statistics for administrators

### Security Features
- Session-based authentication
- Role-based access control
- Unique voter identification
- Vote integrity protection

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Charts**: Chart.js for data visualization
- **PDF Generation**: TCPDF library
- **Server**: Apache/Nginx with PHP support

## Installation

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- phpMyAdmin (recommended for database management)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd online-voting-system
   ```

2. **Database Setup**
   - Create a new MySQL database named `online_voting`
   - Import the database schema:
     ```sql
     mysql -u root -p online_voting < db/online_voting_db.sql
     ```

3. **Configure Database Connection**
   - Edit `include/conn/conn.php`
   - Update database credentials:
     ```php
     $conn = mysqli_connect("localhost", "username", "password", "online_voting");
     ```

4. **Set File Permissions**
   ```bash
   chmod 755 include/photo/
   chmod 644 include/css/*
   chmod 644 include/js/*
   ```

5. **Web Server Configuration**
   - Point document root to project directory
   - Ensure PHP modules are enabled: `mysqli`, `session`, `gd`

## Default Credentials

### Admin Access
- **ID**: `admin`
- **Password**: `admin`
- **Email**: `abc@gmail.com`

### Test Accounts
The system includes sample voters and candidates for testing purposes. Check the database for complete test data.

## Project Structure

```
├── admin/                  # Admin panel and management
│   ├── pages/             # Admin page components
│   └── *.php             # Admin functionality files
├── user/                  # User interfaces
│   ├── candidate/        # Candidate dashboard and features
│   └── voter/           # Voter interface and voting system
├── include/              # Shared resources
│   ├── conn/            # Database connection and session management
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   ├── img/             # System images
│   └── photo/           # User profile photos
├── db/                   # Database schema and setup
├── tcpdf/               # PDF generation library
```

## Usage Guide

### For Administrators
1. Login with admin credentials
2. Manage positions, candidates, and voters
3. Approve/reject candidate registrations
4. Monitor voting progress and results
5. Generate PDF reports

### For Candidates
1. Register through the candidate registration page
2. Wait for admin approval
3. Access candidate dashboard after approval
4. Update profile and campaign information

### For Voters
1. Login with voter credentials
2. View available positions and candidates
3. Cast votes (one per position)
4. View confirmation of submitted votes

## Database Schema

### Core Tables
- **admin**: System administrators
- **candidates**: Registered candidates with approval status
- **voters**: Registered voters
- **positions**: Electoral positions/offices
- **votes**: Cast votes linking voters to candidates

### Key Relationships
- Candidates belong to specific positions
- Votes link voters to candidates for specific positions
- One vote per voter per position constraint

## Security Considerations

- Change default admin credentials immediately
- Use HTTPS in production environments
- Implement proper input validation and sanitization
- Regular database backups recommended
- Monitor for suspicious voting patterns

## Customization

### Styling
- Modify CSS files in `include/css/` directory
- Update logos and images in `include/img/`

### Functionality
- Extend user roles by modifying authentication logic
- Add new fields to candidate/voter profiles
- Customize voting rules and restrictions

## Troubleshooting

### Common Issues
1. **Database Connection Failed**
   - Verify database credentials in `conn.php`
   - Ensure MySQL service is running

2. **Photo Upload Issues**
   - Check file permissions on `include/photo/` directory
   - Verify PHP file upload settings

3. **Session Problems**
   - Ensure PHP sessions are properly configured
   - Check session file permissions

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request
---

