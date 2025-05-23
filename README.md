# Abunzi

A Laravel application for digitizing local dispute resolution.

---

**Abunzi** streamlines the process of filing, tracking, and resolving local disputes, with automated notifications and machine learning predictions for legal article classification and punishment suggestions.

---

## Features

- **Submit & Track Disputes:** File disputes, receive updates, and monitor progress.
- **Automated Email Notifications:** Stay informed at each resolution stage.
- **Machine Learning Integration:** Rubix ML predicts relevant legal articles and punishments.
- **Admin Panel:** Manage cases, users, and settings.
- **Secure Authentication:** Built with Laravel's security features.

## Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP)
- **Frontend:** Blade Templates, [Livewire](https://laravel-livewire.com/)
- **Machine Learning:** [Rubix ML](https://rubixml.com/)
- **Database:** MySQL/PostgreSQL

## Architecture

This project follows the MVC (Model-View-Controller) pattern to ensure a clean separation of concerns. Each view is paired with a corresponding controller and model, making the codebase organized and maintainable. For live and dynamic content, the application leverages Livewire, enabling real-time updates and interactive user experiences directly within Blade templates—without leaving the Laravel ecosystem.

## Getting Started

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL or PostgreSQL
- Node.js & NPM

### Installation

1. **Clone the repository**
    ```bash
    git clone https://github.com/MUGWANEZAMANZI/Abunzi.git
    cd Abunzi
    ```
2. **Install dependencies**
    ```bash
    composer install
    npm install
    npm run dev
    ```
3. **Configure environment**
    ```bash
    cp .env.example .env
    # Edit .env for your database and mail settings
    ```
4. **Generate application key & migrate database**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```
5. **Run the server**
    ```bash
    php artisan serve
    ```

---

## Local Network Access & Exposing the App

To allow other devices on your local network to access your Laravel app, you need to bind the PHP and npm servers to 0.0.0.0 and use your local machine's IP address.

### 1. Find Your Local IP Address

On Windows, open Command Prompt and run:

```cmd
ipconfig
```

Look for the line under your active network adapter that says `IPv4 Address`. It will look something like `192.168.1.10`.

### 2. Serve Laravel on All Network Interfaces

Instead of the default localhost, run:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Now, your app is accessible on your local network at:
```
http://<your-ip>:8000 usually hhtp://127.0.0.1:8000
```
Replace `<your-ip>` with the IPv4 address you found earlier (e.g., http://172.31.1.10:8000).

### 3. Serve Vite (npm) on All Network Interfaces

If your frontend uses Vite (default for Laravel 9+):

```bash
npm run dev -- --host 0.0.0.0
```
or if using older NPM scripts:
```bash
npm run dev -- --host=0.0.0.0
```

This ensures hot reloading and assets are also served on your network IP.

### 4. Access from Another Device

On another device connected to the same WiFi or network, open a browser and navigate to:

```
http://<your-ip>:8000
```

You should see your Laravel application!

**Note:**  
- Make sure your firewall allows inbound connections on port 8000.
- For production or public access, use a proper web server (like Nginx or Apache) and secure your app.

---

## Usage

- Register or log in.
- Submit a dispute with details.
- System predicts the article & punishment, and emails you.
- Track progress in your dashboard.

## Machine Learning

- Rubix ML is used for predictions.
- Ensure models are trained and saved in the correct storage path.
- Refer to [Rubix ML docs](https://rubixml.com/docs/) for training/updating models.

*For detailed guides, see the [Laravel Docs](https://laravel.com/docs/) and [Rubix ML Docs](https://rubixml.com/docs/).*
