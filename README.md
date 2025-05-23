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
- **Frontend:** Blade Templates
- **Machine Learning:** [Rubix ML](https://rubixml.com/)
- **Database:** MySQL/PostgreSQL

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

## Usage

- Register or log in.
- Submit a dispute with details.
- System predicts the article & punishment, and emails you.
- Track progress in your dashboard.

## Machine Learning

- Rubix ML is used for predictions.
- Ensure models are trained and saved in the correct storage path.
- Refer to [Rubix ML docs](https://rubixml.com/docs/) for training/updating models.

## Contributing

Contributions welcome!  
Fork, create a branch, commit, and open a pull request.

## License

MIT License.

## Contact

Open an issue or reach out [on GitHub](https://github.com/MUGWANEZAMANZI).

---

*For detailed guides, see the [Laravel Docs](https://laravel.com/docs/) and [Rubix ML Docs](https://rubixml.com/docs/).*
