# Barangay Automated Record and Information System (BARIS)

A comprehensive web-based management system for Barangay Daang Bakal, Mandaluyong City, designed to streamline barangay services and enhance accessibility for residents.

## 🚀 Features

### For Residents
- **Online Registration**: Register online or in-person with photo and ID verification
- **Document Requests**: Request 4 types of official documents:
  - Barangay Clearance
  - Barangay Certificate
  - Indigency Clearance
  - Resident Certificate
- **Complaint Management**: File and track complaints with real-time updates
- **Status Tracking**: Monitor request status 24/7
- **Announcements**: Stay updated with latest barangay news

### For Administrators
- **Centralized Dashboard**: Live data on registrations, requests, and complaints
- **Resident Management**: Approve/reject registrations, manage resident records
- **Document Processing**: Process requests within 1 day
- **Complaint Resolution**: Categorize and resolve complaints efficiently
- **Content Management**: Manage announcements, officials, and FAQs
- **Reports & Analytics**: Generate monthly summaries and export data

### For Super Administrators
- **Staff Management**: Create and manage admin accounts
- **Audit Logs**: Track all system activities with timestamps
- **Full System Access**: Complete control over all features

## 🛠 Tech Stack

- **Framework**: Laravel 12.x
- **Frontend**: Tailwind CSS 3.x + Alpine.js
- **Database**: SQLite (development) / MySQL (production)
- **Build Tool**: Vite
- **PHP**: 8.3+

## 📦 Installation

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & NPM
- Database (SQLite/MySQL)

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/meshamay/barangay_system.git
cd barangay_system
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database Setup**
```bash
# Update .env with your database credentials
php artisan migrate
```

6. **Build Frontend Assets**
```bash
npm run build
# For development with hot reload:
npm run dev
```

7. **Start Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 📁 Project Structure

```
barangay_system/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Admin controllers
│   │       ├── Auth/           # Authentication
│   │       ├── Resident/       # Resident controllers
│   │       └── SuperAdmin/     # Super admin controllers
│   └── Models/                 # Eloquent models
├── database/
│   └── migrations/             # Database migrations
├── resources/
│   ├── css/
│   │   └── app.css            # Tailwind CSS
│   ├── js/
│   │   └── app.js             # Alpine.js + Bootstrap
│   └── views/
│       ├── admin/             # Admin views
│       ├── auth/              # Authentication views
│       ├── layouts/           # Layout templates
│       └── resident/          # Resident views
└── routes/
    └── web.php                # Application routes
```

## 🎨 User Roles

### 1. Resident
- Register and create account
- Request documents online
- File complaints
- Track request status
- View announcements

### 2. Administrator
- Approve/reject registrations
- Process document requests
- Manage complaints
- Publish announcements
- View reports and analytics
- Manage barangay officials

### 3. Super Administrator
- All admin capabilities
- Create/manage admin accounts
- Access audit logs
- Full system control

## 📋 Document Types

1. **Barangay Clearance** - Proof of residency and good standing
2. **Barangay Certificate** - Official document for proof of residency/identity
3. **Indigency Clearance** - For residents with low/no income
4. **Resident Certificate** - Confirmation of residence and duration

## ⚙️ Configuration

### Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barangay_system
DB_USERNAME=root
DB_PASSWORD=
```

### Mail (for notifications)
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@barangay-daangbakal.gov.ph"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔒 Security Features

- Role-based access control (RBAC)
- Password-protected authentication
- Activity tracking
- Session management
- CSRF protection

## 📊 Key Features Details

### Document Processing
- **Processing Time**: 1 day
- **Pickup**: Physical pickup at barangay office
- **Notification**: Email alerts for status updates

### Registration
- **Online or In-Person**: Flexible registration options
- **ID Verification**: Valid photo and ID required
- **Student Support**: School ID accepted for students
- **Unique Resident ID**: Assigned upon approval

### Limitations
- Only for Barangay Daang Bakal residents
- No digital document copies (physical pickup required)
- Manual complaint resolution
- Profile editing restricted (requires re-registration)

## 🤝 Contributing

This project is part of the RTU Institute of Computer Studies capstone project for Barangay Daang Bakal, Mandaluyong City.

## 📝 License

This project is proprietary software developed for Barangay Daang Bakal.

## 📞 Support

For support and inquiries:
- Email: barangay@daangbakal.gov.ph
- Phone: (02) 1234-5678
- Office Hours: Mon-Fri, 8AM-5PM

---

**Developed by**: RTU Institute of Computer Studies
**For**: Barangay Daang Bakal, Mandaluyong City
**Year**: 2025
