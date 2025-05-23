Here’s a technical README section based on your requirements. This version focuses on setup and exposure, with instructions for getting XAMPP, Composer, starting the project, and exposing it on your local network.

---

# Abunzi

An application for digitizing local dispute resolution using Laravel, Blade, and Rubix ML.

---

## Technical Setup

### Prerequisites

- **PHP** >= 8.0
- **Composer** (Dependency manager for PHP)
- **XAMPP** (for Apache/MySQL, if you want an all-in-one local environment)
- **Node.js & NPM** (for frontend asset compilation)

---

### 1. Install XAMPP

Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).  
Start Apache and MySQL from the XAMPP Control Panel.

---

### 2. Install Composer

Download and install Composer from [https://getcomposer.org/download/](https://getcomposer.org/download/).  
Verify installation:

```bash
composer --version
```

---

### 3. Clone the Repository

```bash
git clone https://github.com/MUGWANEZAMANZI/Abunzi.git
cd Abunzi
```

---

### 4. Install PHP Dependencies

```bash
composer install
```

---

### 5. Install Node.js Dependencies

```bash
npm install
npm run dev
```

---

### 6. Environment Configuration

```bash
cp .env.example .env
```
- Edit `.env` to set up your database (MySQL/PostgreSQL) and mail credentials.
- Start MySQL via XAMPP and create a database for the project.

---

### 7. Generate App Key & Migrate Database

```bash
php artisan key:generate
php artisan migrate
```

---

### 8. Start the Laravel Development Server

```bash
php artisan serve
```
By default, the app runs at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Exposing the App on Local Network

To allow access from other devices on your WiFi/network:

1. **Find your local IP:**
   - On Windows, run:  
     ```cmd
     ipconfig
     ```
   - Look for `IPv4 Address`, e.g., `192.168.1.10`.

2. **Run Laravel on all interfaces:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **(If using Vite for assets) Run Vite on all interfaces:**
   ```bash
   npm run dev -- --host 0.0.0.0
   ```

4. **Access from another device:**  
   Go to `http://<your-ip>:8000` in a browser.

**Note:** Allow port 8000 through your firewall if needed.

---

## Project Start & Usage

- Register/login as a Citizen, Justice Personnel, or Admin.
- Submit and track disputes.
- Use the AI assistant for crime and punishment queries.
- Notifications are sent to all stakeholders.
- Admin and Justice panels for managing and resolving cases.

---

## Machine Learning

- Rubix ML powers legal article classification and punishment suggestion.
- Make sure models are trained and stored correctly.
- See [Rubix ML documentation](https://rubixml.com/docs/) for updating or training models.

---

## References

- [Laravel Documentation](https://laravel.com/docs/)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Rubix ML Documentation](https://rubixml.com/docs/)
- [XAMPP Documentation](https://www.apachefriends.org/faq.html)

---

Let me know if you want this merged with your original README or need additional customization!
