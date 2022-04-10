FROM nginx:1.18-alpine
RUN apk update && apk add bash

RUN rm /etc/nginx/conf.d/default.conf
COPY ./dev.nginx.conf /etc/nginx/conf.d