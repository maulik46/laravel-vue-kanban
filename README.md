# Kanban Board

A modern task management application built with Laravel 12, Vue 3, and Inertia.js.

## Overview

This application provides a drag-and-drop Kanban board interface for managing tasks across different categories (e.g., To Do, In Progress, Done). Tasks can be created, updated, reordered, and moved between columns with persistent storage.

## Tech Stack

- **Backend:** Laravel 12
- **Frontend:** Vue 3, Typescript
- **SPA Integration:** Inertia.js
- **Database:** SqlLite
- **Styling:** Tailwind CSS 4

## Features

- Drag-and-drop task management
- Create, update, and delete tasks
- Color-coded tasks
- Seamless persistence of board state
- Clean, service-based architecture

## Architecture

The application follows a clean, layered architecture:

1. **Controllers:** Handle HTTP requests and responses
2. **Services:** Contain business logic
3. **Validators:** Handle input validation
4. **Models:** Manage database interactions

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 20.0+ & NPM
- SqlLite

### Setup

1. **Clone the repository**

```bash
git clone https://github.com/maulik46/laravel-vue-kanban.git
cd laravel-vue-kanban
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install JavaScript dependencies**

```bash
npm install
```

4. **Set up environment variables**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Run migrations**

```bash
php artisan migrate
```

6. **Run seeder**

```bash
php artisan db:seed --class=TaskTypeSeeder
```

7. **Build frontend assets**

```bash
npm run dev
```

8. **Start the development server**

```bash
php artisan serve
```

## Usage

1. Visit `http://127.0.0.1:8000/` in your browser
2. Create new task types (columns) as needed in TaskTypeSeeder
3. Add tasks to your columns
4. Drag and drop tasks to reorder or move between columns

## API Endpoints

- `GET /tasks` - Get all task types with their tasks
- `POST /tasks` - Create a new task
- `PUT /tasks/{task}` - Update a task
- `POST /tasks/reorder` - Reorder a task
- `DELETE /tasks/{task}` - Delete a task