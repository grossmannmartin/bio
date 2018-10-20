FROM dockette/nginx

COPY ./sites/api.vyvoj.net.conf /etc/nginx/sites.d/api.vyvoj.net.conf
COPY ./cert/fullchain.pem /etc/nginx/certs/api.vyvoj.net/fullchain.pem
COPY ./cert/privkey.pem /etc/nginx/certs/api.vyvoj.net/privkey.pem

EXPOSE 80 443

WORKDIR /srv

CMD ["nginx", "-g", "daemon off;"]
