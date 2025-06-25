# Notely Repository

Welcome to the official repository for **Notely**! This repository contains the source code and assets for our powerful and intuitive note-taking platform.

## Description

**Notely** is a sleek and modern online space designed to help users effortlessly create, update, view, and manage their notes. Users can also move notes to the trash, restore them, and permanently delete them. Additionally, users can create and update their profiles.

## Key Features

* **Create Notes:** Users can effortlessly create and manage notes in an intuitive interface.
* **Update Notes:** Modify existing notes as needed.
* **View Notes:** Easily access and review created notes.
* **Pin/Unpin Notes:** Highlight important notes by pinning them for quick access.
* **Upload Multiple Images:** Attach multiple images to a single note.
* **Move Notes to Trash:** Temporarily remove notes without permanently deleting them.
* **Restore Notes:** Recover notes from the trash.
* **Delete Notes Forever:** Permanently remove notes from the system.
* **Create Profile:** Users can register and login to their personal profile.
* **Login via Google:** Authenticate using Google accounts via Google Console integration.
* **Update Profile:** Modify profile information as needed.
* **Google reCAPTCHA:** Protect forms from bots using Google's reCAPTCHA v2.

## Requirements

To install and run **Notely**, ensure you have the following:

* PHP 8.1 or later
* Composer
* MySQL Database
* Laravel 10
* Node.js and NPM (for frontend assets)

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

7. **Optional: Seed Sample Data**

   To generate test users and notes with images, run:

   ```bash
   php artisan db:seed --class=DataSeeder
   ```

   This will create:

   * 5 users
   * 5 notes for each user
   * 2 images attached to each note

8. **Serve the Application:**

   ```bash
   php artisan serve
   ```

## Google Login Configuration

To enable **Login with Google**, follow these steps:

1. Go to [Google Cloud Console](https://console.cloud.google.com/).

2. Create a new project or use an existing one.

3. Enable the **Google+ API** and **OAuth 2.0 Client ID**.

4. Set the **Authorized redirect URI** to:

   ```
   http://your-app-domain.com/auth/google/callback
   ```

5. In your `.env` file, add:

   ```env
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT=/auth/google/callback
   ```

> ⚠️ Replace `your-client-id` and `your-client-secret` with the actual credentials provided by Google.

## Google reCAPTCHA Configuration

To use **Google reCAPTCHA**, follow these steps:

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin).
2. Register a new site (choose reCAPTCHA v2).
3. In your `.env` file, add:

   ```env
   NOCAPTCHA_SITEKEY=your-site-key
   NOCAPTCHA_SECRET=your-secret-key
   ```

> ⚠️ Replace `your-site-key` and `your-secret-key` with your actual reCAPTCHA credentials.

## Explore the Codebase

Feel free to explore the codebase to gain a deeper understanding of how **Notely** is built and configured. Contributions and feedback are always welcome.

Thank you for your interest in **Notely**! We look forward to improving note-taking together.

**Happy Noting!**

