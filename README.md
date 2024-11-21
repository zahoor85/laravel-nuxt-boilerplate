## About This Project

This project serves as a boilerplate for building a robust REST API with [Laravel 10](https://laravel.com/docs/10.x) and a frontend application with [Nuxt](https://nuxt.com/). It includes essential features for user management, such as listing users, searching user, updating profile details, and handling account actions for logged-in users. Additionally, it provides built-in email verification, password reset, and email update functionalities.


### Overall Requirements

- **PHP**: 8.1 or higher
- **Composer**: 2.0 or higher
- **Laravel**: 10
- **Database**: MySQL or other compatible database
- **Node**: v19 or higher

### Backend Features

- **Token-based Authentication**: Secure and seamless authentication using **Laravel Sanctum**.
- **Frontend URL Customization**: Easily configure frontend URLs for email verification and password resets.
- **Cross-Platform Support**: Optimized to work effortlessly across web and mobile applications.
- **Image Handling**: Efficient image processing with [Intervention Image](https://image.intervention.io/v3).
- **Email Delivery**: Reliable email sending using [Mailtrap](https://mailtrap.io/) as the SMTP server.
- **API Documenation**: Auto-generated API docs powered by [Scramble Laravel OpenAPI](https://scramble.dedoc.co/).

### Configuration

1. **Setup:**
   ```bash
      cd laravel-api
      composer install
      npm install
      cp .env.example  .env
      composer require intervention/image
      php artisan storage:link
      php artisan migrate
      php artisan db:seed --class=HobbiesSeeder
      pgp artisan serve # Start localhost:8000
   ```

2. **Environment Variables:**
    Add your frontend URL in the .env file:
   ```bash
      FRONTEND_URL=http://localhost:3000
   ```

3. **Mailtrap SMTP Environment Variables:**
    Add Mailtrap SMTP settings in the .env file:
   ```bash
      MAIL_MAILER=smtp
      MAIL_HOST=sandbox.smtp.mailtrap.io
      MAIL_PORT={PORT}
      MAIL_USERNAME={USERNAME}
      MAIL_PASSWORD={PASSWORD}
      MAIL_ENCRYPTION=null
      MAIL_FROM_ADDRESS="{EMAIL}"
      MAIL_FROM_NAME="${APP_NAME}"
   ```

4. **Headers Requirement**
   For REST clients, it is **mandatory** to pass the following headers to properly interact with the API and avoid hitting the default web routes (`web.php`):
   ```bash
      {
         "Content-Type": "application/json",
         "Accept": "application/json"
      }  
   ```
5. **For API Docs:**
    To view the API documentation used the following link:
    ```bash
      http://localhost:8000/docs/api      # HTML view
      http://localhost:8000/docs/api.json # JSON view
   ```

### Front-end Features

- **Nuxt JS**: Frontend development is done in [Nuxt](https://nuxt.com/), offering seamless integration with modern web technologies. 
- **Tailwind CSS**: [Tailwind](https://tailwindcss.com/) is used as UI/UX framework.
- **Nuxt - Laravel Sanctum Integration**: [Nuxt - Laravel Sanctum](https://manchenkoff.gitbook.io/nuxt-auth-sanctum) module provides a simple way to use Laravel Sanctum with Nuxt. SSR-ready!.
- **Pinia Store Management**: State management is handled with [Pinia](https://pinia.vuejs.org/), providing an intuitive and efficient solution for managing user state

### Configuration

**Setup:**
   Ensure [Node.js](https://nodejs.org/en) and npm are installed, then:
   Make sure to install dependencies:
   ```bash
      cd nuxt-client
      # npm
      npm install
      # pnpm
      pnpm install

      # yarn
      yarn install
      # bun
      bun install
   ```

**Add Environment:**
   Add .env file inside [nuxt-client] and add the following
   ```bash
   NUXT_PUBLIC_BACKEND_URL=http://localhost:8000
   ```
**Development Server:**
   Start the development server on `http://localhost:3000`:
   ```bash
      # npm
      npm run dev

      # pnpm
      pnpm dev

      # yarn
      yarn dev

      # bun
      bun run dev
   ```

**Production:**
   Build the application for production:
   ```bash
      # npm
      npm run build

      # pnpm
      pnpm build

      # yarn
      yarn build

      # bun
      bun run build
   ```
**Locally preview production build:**

   ```bash
      # npm
      npm run preview

      # pnpm
      pnpm preview

      # yarn
      yarn preview

      # bun
      bun run preview
   ```
   