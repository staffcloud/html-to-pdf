ARG BASE=ubuntu:24.04
FROM ${BASE}
ARG SSL_PACKAGE=libssl3t64
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    php-cli php-mbstring ca-certificates fontconfig fonts-dejavu-core fonts-liberation \
    libjpeg62 libjpeg-turbo8 libpng16-16 libxrender1 libxext6 ${SSL_PACKAGE} \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /package
USER 1000:1000
