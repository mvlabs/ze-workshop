FROM prooph/nginx:www

# Remove default configuration
RUN rm /etc/nginx/sites-enabled/www.conf

# Copy local apps configuration
COPY docker/nginx/config/ /etc/nginx/sites-available/

# Copy local nginx configuration
COPY docker/nginx/conf.d/ /etc/nginx/conf.d/custom/

# Copy local source contents
COPY . /app/

# Change data ownership
RUN chown -R root:root /app/*

# Change data permissions
RUN chmod -R +r /app/*
