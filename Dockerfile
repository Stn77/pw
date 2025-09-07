FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies including ImageMagick
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    pkg-config \
    imagemagick \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install imagick with autoyes and proper configuration
RUN yes '' | pecl install imagick && docker-php-ext-enable imagick

# Verify imagick installation
RUN php -m | grep imagick || (echo "Imagick installation failed" && exit 1)

# Fix ImageMagick policy for PDF/SVG (if needed)
# RUN sed -i 's/rights="none" pattern="PDF"/rights="read|write" pattern="PDF"/' /etc/ImageMagick-6/policy.xml \
#     && sed -i 's/rights="none" pattern="SVG"/rights="read|write" pattern="SVG"/' /etc/ImageMagick-6/policy.xml

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
