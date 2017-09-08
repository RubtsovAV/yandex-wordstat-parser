FROM php:7.1-cli

RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		bzip2 \
		libfontconfig \
		git \
		curl \
		libevent-dev \
		libssl-dev \
		nodejs \

	# Install official PhantomJS release
	&& mkdir /tmp/phantomjs \
	&& curl -L https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2 \
	        | tar -xj --strip-components=1 -C /tmp/phantomjs \
	&& mv /tmp/phantomjs/bin/phantomjs /usr/local/bin \

	# Clean up
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
	sysvsem \
	sysvshm \
	pcntl \
	sockets

# Install PHP event for ReactPHP
RUN pecl install event \
	&& sh -c "echo 'extension=event.so' > /usr/local/etc/php/conf.d/20-event.ini"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer