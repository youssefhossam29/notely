# Notely Repository

Welcome to the official repository for **Notely**! This repository contains the source code and assets for our powerful and intuitive note-taking platform.

## Description

**Notely** is a sleek and modern online space designed to help users effortlessly create, update, view, and manage their notes. Users can also move notes to the trash, restore them, and permanently delete them. Additionally, users can create and update their profiles, pin notes, upload multiple images per note, and log in using their Google account.

## Key Features

- **Create Notes:** Users can effortlessly create and manage notes in an intuitive interface.
- **Update Notes:** Modify existing notes as needed.
- **View Notes:** Easily access and review created notes.
- **Pin/Unpin Notes:** Highlight important notes by pinning them.
- **Upload Multiple Images:** Attach multiple images to a single note.
- **Move Notes to Trash:** Temporarily remove notes without permanently deleting them.
- **Restore Notes:** Recover notes from the trash.
- **Delete Notes Forever:** Permanently remove notes from the system.
- **Create Profile:** Users can register and login to their personal profile.
- **Update Profile:** Modify profile information as needed.
- **Google Sign-In:** Authenticate using Google via OAuth.
- **Google reCAPTCHA:** Secure authentication forms with Google reCAPTCHA.

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
   git clone https://github.com/youssefhossam29/notely.git
   cd notely
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

   * Duplicate the `.env.example` file:

     ```bash
     cp .env.example .env
     ```
   * Generate the application key:

     ```bash
     php artisan key:generate
     ```

5. **Configure the Database:**

   * Create a new database and update the `.env` file with the database credentials.

6. **Run Database Migrations:**

   ```bash
   php artisan migrate --seed
   ```

7. **Run Sample Seeder (optional):**

   This command will create 5 users and 5 notes for each user, each note having 2 images:

   ```bash
   php artisan db:seed --class=DataSeeder
   ```

8. **Serve the Application:**

   ```bash
   php artisan serve
   ```

## ✅ Google Sign-In Setup

To enable Google Sign-In:

1. Go to [Google Cloud Console](https://console.cloud.google.com/)

2. Create a new project and enable the **OAuth 2.0 Client IDs**

3. Add your credentials to the `.env` file:

   ```env
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT=/auth/google/callback
   ```

   ⚠️ Replace `your-client-id` and `your-client-secret` with the actual credentials provided by Google.

4. Make sure to add the `GOOGLE_REDIRECT` URL to your OAuth client in Google Console.

## ✅ Google reCAPTCHA Setup

To enable reCAPTCHA:

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Create a new site and choose reCAPTCHA v2 or v3.
3. Add your credentials to the `.env` file:

   ```env
   NOCAPTCHA_SECRET=your-secret-key
   NOCAPTCHA_SITEKEY=your-site-key
   ```

   ⚠️ Replace `your-site-key` and `your-secret-key` with your actual reCAPTCHA credentials.

## 🚀 Built With

This project was built using the following technologies:

[![Laravel](https://img.shields.io/badge/Laravel-F72C1F?style=flat\&logo=laravel\&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat\&logo=mysql\&logoColor=white)](https://www.mysql.com)
[![Composer](https://img.shields.io/badge/Composer-885630?style=flat\&logo=composer\&logoColor=white)](https://getcomposer.org)
[![NPM](https://img.shields.io/badge/NPM-CB3837?style=flat\&logo=npm\&logoColor=white)](https://www.npmjs.com)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat\&logo=vite\&logoColor=white)](https://vitejs.dev)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=flat\&logo=bootstrap\&logoColor=white)](https://getbootstrap.com)
[![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=flat\&logo=jquery\&logoColor=white)](https://jquery.com)
[![Font Awesome](https://img.shields.io/badge/Font%20Awesome-339AF0?style=flat\&logo=fontawesome\&logoColor=white)](https://fontawesome.com)
[![Google Sign-In](https://img.shields.io/badge/Google%20Sign--In-4285F4?style=flat\&logo=google\&logoColor=white)](https://developers.google.com/identity)
[![reCAPTCHA](https://img.shields.io/badge/Google%20reCAPTCHA-4285F4?style=flat\&logo=google\&logoColor=white)](https://www.google.com/recaptcha)

---

## Explore the Codebase

Feel free to explore the codebase to gain a deeper understanding of how **Notely** is built and configured. Contributions and feedback are always welcome.

Thank you for your interest in **Notely**! We look forward to improving note-taking together.

**Happy Noting!**

