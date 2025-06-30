# Mood Tracker Application

This is a web application built with Laravel and Bootstrap that allows users to log their daily moods, view a history of their entries, and filter them by date.

## ✨ Key Features

- **Mood Logging:** Users can log their daily mood with a specific type (e.g., Happy, Sad, Calm) and an optional note.
- **History View:** A table displays a chronological history of all logged moods.
- **Date Range Filtering:** Users can filter their mood history by a specific date range.
- **Soft Delete & Restore:** Mood entries are soft-deleted, meaning they can be restored later from a dedicated "Trashed" view.
- **Export to PDF:** The filtered mood history can be exported as a PDF document.
- **Mood Streak:** A streak counter is displayed on the dashboard to encourage consistent logging.
- **Modern UI:** A clean, modern user interface designed with Bootstrap 5.

## ⚙️ Setup Instructions

Follow these steps to set up and run the project locally.

1.  **Clone the repository:**
    ```bash
    git clone [Your GitHub Repository Link Here]
    cd [your-project-folder]
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Copy the environment file:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Configure your database** in the `.env` file (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.).

6.  **Run database migrations** to create the necessary tables:
    ```bash
    php artisan migrate
    ```

7.  **Serve the application:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.

## 🔒 Demo Credentials

You can use the following credentials to log in and demo the application:

-   **Email:** `demo@example.com`
-   **Password:** `password`

## 📸 Screenshots (Optional)

[You can add links to your screenshots here, e.g., using GitHub's image hosting.]

## 🚀 Live Demo (Optional)

[If you have hosted the application, provide the link here.]
