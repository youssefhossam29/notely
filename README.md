# Notely Repository

Welcome to the official repository for **Notely**! This repository contains the source code and assets for our powerful and intuitive note-taking platform.

## Description

**Notely** is a sleek and modern online space designed to help users effortlessly create, update, view, and manage their notes. Users can also move notes to the trash, restore them, and permanently delete them. Additionally, users can create and update their profiles.

## Key Features

- **Create Notes:** Users can effortlessly create and manage notes in an intuitive interface.
- **Update Notes:** Modify existing notes as needed.
- **View Notes:** Easily access and review created notes.
- **Move Notes to Trash:** Temporarily remove notes without permanently deleting them.
- **Restore Notes:** Recover notes from the trash.
- **Delete Notes Forever:** Permanently remove notes from the system.
- **Create Profile:** Users can register and login to thier personal profile.
- **Update Profile:** Modify profile information as needed.

## Requirements

To install and run **Notely**, ensure you have the following:

- PHP 8.1 or later
- Composer
- MySQL Database
- Laravel 10

## Getting Started

Follow these steps to set up and run **Notely**:

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/youssefhossam29/notes-app.git
   cd notes-app
   ```

2. **Install Composer Dependencies:**

   ```bash
   composer install
   ```

3. **Install NPM Dependencies (Optional for Frontend Assets):**

   ```bash
   npm install
   npm run build
   ```

4. **Set Up the Environment File:**

   - Duplicate the `.env.example` file:
     ```bash
     cp .env.example .env
     ```
   - Generate the application key:
     ```bash
     php artisan key:generate
     ```

5. **Configure the Database:**

   - Create a new database and update the `.env` file with the database credentials.

6. **Run Database Migrations:**

   ```bash
   php artisan migrate --seed
   ```

7. **Serve the Application:**

   ```bash
   php artisan serve
   ```

## Explore the Codebase

Feel free to explore the codebase to gain a deeper understanding of how **Notely** is built and configured. Contributions and feedback are always welcome.

Thank you for your interest in **Notely**! We look forward to improving note-taking together.

**Happy Noting!**

