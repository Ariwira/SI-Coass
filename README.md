# 🦷 Sistem Informasi Coass RSPGM Universitas Udayana

![UNUD Logo](https://upload.wikimedia.org/wikipedia/id/2/2d/Logo-unud-baru.png)

## 🏥 Overview

Welcome to the Sistem Informasi Coass for Rumah Sakit Pendidikan Gigi & Mulut (RSPGM) Universitas Udayana! This web-based platform streamlines the management of clinical rotations for dental co-assistant students at Udayana University's Dental Teaching Hospital.

### Key Features

- **📝 Student Registration & Management**: Streamlined onboarding of new clinical students
- **🗓️ Rotation Scheduling**: Automated scheduling system for clinical departments
- **📊 Case Tracking**: Digital recording and verification of clinical cases
- **📋 Evaluation System**: Comprehensive assessment tools for supervisors
- **📱 Mobile-Responsive Interface**: Access from any device, anywhere
- **📈 Performance Analytics**: Real-time insights into student progress
- **🔔 Notification System**: Automated alerts for upcoming rotations and evaluations

## 💻 Technology Stack

This project is built with CodeIgniter 4, a powerful PHP framework that provides a solid foundation for rapid development of secure, high-performance applications.

- **Backend**: CodeIgniter 4
- **Database**: MySQL
- **Frontend**: Bootstrap 5, jQuery, Chart.js
- **Authentication**: CI Shield
- **API**: RESTful architecture
- **Deployment**: Docker support

## 🚀 Getting Started

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Git

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/unud-rspgm/coass-management-system.git
   cd coass-management-system
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Configure your environment:

   ```bash
   cp env .env
   ```

   Then edit the `.env` file with your database credentials and other configuration options.

4. Initialize the database:

   ```bash
   php spark migrate
   php spark db:seed InitialSetup
   ```

5. Start the development server:

   ```bash
   php spark serve
   ```

6. Visit `http://localhost:8080` in your browser!

### Docker Installation (Alternative)

```bash
docker-compose up -d
```

## 📋 Project Structure

```
coass-management-system/
├── app/                    # Application code
│   ├── Config/             # Configuration files
│   ├── Controllers/        # Controller classes
│   ├── Models/             # Database models
│   ├── Views/              # View templates
│   └── Helpers/            # Helper functions
├── public/                 # Publicly accessible files
│   ├── assets/             # CSS, JS, and images
│   └── index.php           # Application entry point
├── writable/               # Logs, cache, and other writable data
├── system/                 # CodeIgniter system files
├── tests/                  # Test files
├── .env                    # Environment configuration
└── composer.json           # Composer dependencies
```

## 👥 User Roles

1. **Administrator**: Full system access, user management, configuration
2. **Supervisor**: Department heads who evaluate and approve student cases
3. **Co-Assistant**: Dental students tracking their clinical experience
4. **Academic Staff**: Monitoring student progress and generating reports

## 📱 Interactive Features

- **Interactive Dashboard**: Real-time visualizations of student progress
- **Digital Case Forms**: Interactive forms with dynamic validation
- **Discussion Forum**: Collaborative learning space for case discussions
- **Knowledge Base**: Searchable repository of clinical guidelines
- **Achievement System**: Gamified experience to motivate students

## 🔒 Security Features

- CSRF protection
- XSS filtering
- SQL injection prevention
- Rate limiting
- Role-based access control
- Data encryption for sensitive information

## 🌐 API Documentation

The system includes a comprehensive API for integration with other university systems:

```
GET /api/students                 # List all students
GET /api/students/{id}            # Get student details
POST /api/students                # Create new student
PUT /api/students/{id}            # Update student
GET /api/rotations                # List rotations
POST /api/cases                   # Submit new case
GET /api/evaluations/{student_id} # Get evaluations
```

## 📊 Database Schema

The core database includes the following main tables:

- `users` - User authentication and profile information
- `students` - Student-specific data
- `departments` - Clinical departments
- `rotations` - Scheduling data for student rotations
- `cases` - Clinical cases recorded by students
- `evaluations` - Assessment records from supervisors
- `notifications` - System alerts and messages

## 🤝 Contributing

We welcome contributions from the university community! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For technical support, please contact:

- Email: tuaji.bangau@gmail.com
- Phone: +62 (361) 8736173
- Help Desk: Room 301, RSPGM Building

## 📜 License

This project is proprietary and owned by Universitas Udayana. All rights reserved.

## 🙏 Acknowledgements

- Faculty of Dentistry, Universitas Udayana
- RSPGM Administration Team
- IT Development Team, Universitas Udayana
- CodeIgniter Development Team
