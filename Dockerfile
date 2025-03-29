# Use the official PHP image with FPM (PHP 8.2)
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

# Install Node.js (using NodeSource for Node 16)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
	&& apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy key files needed for composer install (including artisan)
COPY composer.json composer.lock artisan ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Now copy the rest of your application code
COPY . .

# Generate Ziggy routes (if needed)
RUN php artisan ziggy:generate

# Install Node dependencies and build assets
RUN npm install
RUN npm run build

# Expose port 8000
EXPOSE 8000

# Start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
