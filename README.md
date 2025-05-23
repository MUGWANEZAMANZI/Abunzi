# Abunzi

An Application for digitizing local dispute resolution.

---

**Abunzi** streamlines the process of filing, tracking, and resolving local disputes, with automated notifications and machine learning predictions for legal article classification and punishment information (awereness).

---

## Features

- **Submit & Track Disputes:** Citizens can easily file disputes, receive real-time updates, and monitor progress throughout the resolution process.
- **Automated Email Notifications:** The system sends timely email notifications to all stakeholders:
    - **Offender:** Notified about dispute progress and required actions.
    - **Victim:** Receives updates regarding case status and next steps.
    - **Justice Personnel:** Informed when cases are assigned and about upcoming venues or hearings.
- **Machine Learning Integration:** Powered by Rubix ML, the app predicts relevant legal articles and suggests punishment information for each case.
- **Admin Panel:** Manage and assign justice cases, add justice team members, and oversee sent cases.
- **Justice Panel:** View and resolve assigned cases, update case status, and manage team settings.
- **Citizen Panel:** Create an account, submit disputes, track ongoing cases, and download reports.
- **Secure Authentication:** Built on Laravel's robust authentication features to ensure data security.
- **Venue Management:** Justice personnel and citizens receive notifications about venue details and changes.
- **AI-Powered Assistant:** Interact with "Mbaza AI" for crime prompts and punishment guidance.

---

## Creating an Account & Email Notifications

### Creating an Account

1. **Register:** Visit the registration page and fill in your details to create a new account as a citizen, justice personnel, or admin.
2. **Verify Email:** You may be required to verify your email address to activate your account.
3. **Login:** Use your credentials to access your personalized dashboard based on your user role.

### Email Notifications

- **To Offender:** Automatically notified when a dispute is filed against them, as well as at each stage of the case.
- **To Victim:** Receives regular updates as the case progresses and when a resolution is reached.
- **To Justice Personnel:** Alerted when assigned to a new case, with details about the involved parties and venue information.
- **Venue Updates:** All relevant parties receive notifications if the venue or hearing date changes.

---

Let me know if you’d like this merged with your existing text or need further customization!

## Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP)
- **Frontend:** Blade Templates, [Livewire](https://laravel-livewire.com/)
- **Machine Learning:** [Rubix ML](https://rubixml.com/)
- **Database:** MySQL/PostgreSQL

**#Architecture**
This project follows the MVC (Model-View-Controller) pattern to ensure a clean separation of concerns. Each view is paired with a corresponding controller and model, making the codebase organized and maintainable.

The application uses Eloquent ORM for interacting with the database, providing an elegant and expressive syntax for database queries and relationships.

For live and dynamic content, the application leverages Livewire, enabling real-time updates and interactive user experiences directly within Blade templates—without leaving the Laravel ecosystem.

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

Look for the line under your active network adapter that says `IPv4 Address`. It will look something like `192.168.1.10 or 172.31.23.2`.

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

You should see our Abunzi application!

**Note:**  
- My firewall allows inbound connections on port 8000.
---

## Usage

- Register or log in.
- Submit a dispute with details.
- You can talk to Mbaza AI through crime prompt and punishment responses
- Track progress in your dashboard.
- Enjoy simplicity

## Machine Learning

- Rubix ML is used for predictions.
- Ensure models are trained and saved in the correct storage path.
- Refer to [Rubix ML docs](https://rubixml.com/docs/) for training/updating models.

*For detailed guides, see the [Laravel Docs](https://laravel.com/docs/) and [Rubix ML Docs](https://rubixml.com/docs/).*
