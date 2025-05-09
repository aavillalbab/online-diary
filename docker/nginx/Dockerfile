FROM dsarhoya/nginx:1

# RUN apt-get update && \
#     apt-get install -y vim

COPY nginx.conf /etc/nginx/nginx.conf
ADD dsy.conf /etc/nginx/sites-enabled/

CMD ["nginx", "-g", "daemon off;"]
