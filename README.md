# Jobb Házasság Akadémia

A comprehensive Laravel-based educational platform focused on relationship and marriage improvement through interactive articles, quizzes, and self-reflection exercises.

## Features

- **Interactive Articles**: Rich content with multiple block types including text, images, and interactive elements
- **Self-Reflection Exercises**: Guided reflection questions to help users develop self-awareness
- **Interactive Quizzes**: Knowledge assessment and learning reinforcement
- **Google Authentication**: Secure login with Google OAuth integration
- **Subscription Management**: Stripe-powered payment processing and subscription handling
- **Admin Dashboard**: Content management system for articles, quizzes, and reflections
- **User Progress Tracking**: Save and track reflection notes and quiz responses
- **Responsive Design**: Mobile-friendly interface with modern UI/UX

## Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL with SQLite for development
- **Authentication**: Laravel Socialite (Google OAuth)
- **Payments**: Stripe integration
- **Session Management**: Database-driven sessions
- **Logging**: Custom Stripe webhook logging

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or SQLite
- Google OAuth credentials
- Stripe account

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd jobbhazassag-laravel
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   
   **These values need to be substituted in the created .env file:**
   
   - `APP_KEY`: Generate with `php artisan key:generate`
   - `DB_DATABASE`: Your database name
   - `DB_USERNAME`: Your database username
   - `DB_PASSWORD`: Your database password
   - `STRIPE_SECRET_KEY`: Your Stripe secret key
   - `STRIPE_PUBLISHABLE_KEY`: Your Stripe publishable key
   - `STRIPE_WEBHOOK_SECRET`: Your Stripe webhook secret
   - `GOOGLE_CLIENT_ID`: Your Google OAuth client ID
   - `GOOGLE_CLIENT_SECRET`: Your Google OAuth client secret
   - `GOOGLE_CLIENT_REDIRECT`: Your Google OAuth redirect URI
   - `MAKE_PARTNER_INVITE_URL`: Your Make.com webhook URL (optional)

5. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Configuration

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (development)
   - `https://yourdomain.com/auth/google/callback` (production)

### Stripe Setup

1. Create a Stripe account at [stripe.com](https://stripe.com)
2. Get your API keys from the dashboard
3. Set up webhooks pointing to `/stripe/webhook`
4. Configure webhook events:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`

### Database Configuration

The application uses database sessions by default. Make sure to run migrations to create the sessions table:

```bash
php artisan session:table
php artisan migrate
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/GoogleController.php      # Google OAuth handling
│   ├── ArticleController.php          # Article display and interaction
│   ├── OnboardingController.php       # User onboarding flow
│   ├── StripeWebhookController.php    # Payment processing
│   └── Dashboard/                     # Admin panel controllers
├── Models/
│   ├── Article.php                    # Article model
│   ├── GoogleUser.php                 # Google user authentication
│   ├── Subscriber.php                 # Subscription management
│   ├── Quiz.php                       # Quiz functionality
│   └── Reflection.php                 # Self-reflection exercises
└── Middleware/
    └── CheckSubscriberAccess.php      # Subscription verification

resources/
├── views/                             # Blade templates
├── css/                              # Styling files
└── js/                               # JavaScript functionality

database/
├── migrations/                        # Database schema
└── seeders/                          # Sample data
```

## Key Features Explained

### Article System
- Dynamic content blocks with different types (text, image, quiz, reflection)
- SEO-friendly URLs with slugs
- Progressive content delivery

### Authentication Flow
- Google OAuth integration
- Automatic user creation
- Session persistence (1 year lifetime)
- Intended URL redirect after login

### Subscription Management
- Stripe Checkout integration
- Webhook-based payment processing
- Automatic subscriber status updates
- Access control middleware

### Admin Dashboard
- Article management
- Quiz and reflection creation
- User subscription monitoring
- Content analytics

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Development Mode
```bash
composer run dev
```
This command runs:
- Laravel development server
- Queue worker
- Log monitoring
- Asset compilation

## Logging

The application includes custom logging for Stripe webhooks:
- Stripe logs are stored in `storage/logs/stripe-YYYY-MM-DD.log`
- Regular application logs in `storage/logs/laravel-YYYY-MM-DD.log`
- Logs are rotated daily and kept for 14 days

## Security

- CSRF protection enabled
- SQL injection prevention through Eloquent ORM
- XSS protection with Blade templating
- Secure session handling
- OAuth 2.0 authentication
- Stripe webhook signature verification

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team.