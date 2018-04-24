#
# NOTE: THIS DOCKERFILE IS GENERATED VIA "update.sh"
#
# PLEASE DO NOT EDIT IT DIRECTLY.
#

FROM php:7-fpm

RUN apt-get update && apt-get install -y \
		python-pip \
		libav-tools \
	--no-install-recommends && rm -r /var/lib/apt/lists/*

# Install youtube-dl library
RUN pip install --upgrade youtube-dl
