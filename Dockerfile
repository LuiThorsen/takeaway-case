# Use the official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
	build-essential \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	libonig-dev \
	libzip-dev \
	zip \
	unzip \
	git \
	curl \
	gnupg \
	&& docker-php-ext-configure zip \
	&& docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Node.js (using NodeSource, here using Node 16)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
	&& apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy package files and install Node dependencies
COPY package.json package-lock.json* ./
RUN npm install

# Copy the rest of your application code
COPY . .

# Generate Ziggy routes if you're using it
RUN php artisan ziggy:generate

# Build frontend assets (for example with Vite)
RUN npm run build

# Run database migrations (optional, or you can run these on startup)
# RUN php artisan migrate --force

# Expose port 8000 (or whichever port you prefer)
EXPOSE 8000

# Start the PHP server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
