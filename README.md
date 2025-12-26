# Project Management System

A modern, feature-rich project management system built with Laravel, Livewire, and Tailwind CSS.

![test Dashboard](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![Livewire](https://img.shields.io/badge/Livewire-3.x-pink?style=flat-square)

## ✨ Features

### 🎨 Modern UI/UX
- **Dark Mode Support**: Fully functional dark/light theme toggle
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile
- **Glassmorphism Effects**: Modern, premium design aesthetics
- **Smooth Animations**: Enhanced user experience with micro-interactions

### 📊 Project Management
- **Kanban Board**: Drag-and-drop task management with visual columns
- **List View**: Alternative table view for tasks
- **Project Dashboard**: Overview of all projects with progress tracking
- **Task Assignment**: Assign tasks to team members
- **Time Tracking**: Manual and automatic time logging per task
- **Subtasks**: Break down tasks into smaller actionable items
- **Comments**: Team collaboration through task comments

### 🔐 Authorization & Access Control
- **Role-Based Access**: Super Admin and Regular User roles
- **Project-Based Permissions**: Users only see projects they're assigned to
- **Member Management**: Add/remove team members from projects

### 🤖 AI & API Integrations (Mock)
- **Gemini AI**: Auto-generate task descriptions
- **Google Maps**: Geocoding for task locations
- **Carrier API**: Shipping and logistics tracking (in development)

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Setup

1. **Clone the repository**
```bash
git clone https://github.com/mehmetsindi/project-management.git
cd project-management
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run dev
```

7. **Start the server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Default Credentials

**Super Admin:**
- Email: `admin@test.com`
- Password: `password`

**Regular User:**
- Email: `user@test.com`
- Password: `password`

## 📁 Project Structure

```
test/
├── app/
│   ├── Livewire/          # Livewire components
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic & API services
│   └── Http/
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   └── css/               # Tailwind CSS
└── public/                # Public assets
```

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x
- **Frontend**: Livewire 3.x, Alpine.js
- **Styling**: Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Jetstream

## 📸 Screenshots

### Dashboard
Modern, clean dashboard with quick access to projects and time tracking.

### Kanban Board
Drag-and-drop task management with visual status columns.

### Task Detail Modal
Comprehensive task view with comments, subtasks, and time tracking.

### Dark Mode
Fully supported dark theme across the entire application.

## 🔄 Development Status

### ✅ Completed
- [x] User authentication & authorization
- [x] Project management (CRUD)
- [x] Kanban board with drag-and-drop
- [x] Task management with details modal
- [x] Time tracking
- [x] Comments & subtasks
- [x] Dark mode
- [x] Responsive design
- [x] Mock AI integration (Gemini)
- [x] Mock Maps integration (Google Maps)

### 🚧 In Progress
- [ ] Carrier API integration
- [ ] Real-time notifications
- [ ] File attachments
- [ ] Advanced reporting

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 👨‍💻 Author

**Mehmet Sindi**
- GitHub: [@mehmetsindi](https://github.com/mehmetsindi)

## 🙏 Acknowledgments

- Laravel Team for the amazing framework
- Livewire for reactive components
- Tailwind CSS for utility-first styling
