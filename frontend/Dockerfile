# Étape 1 : Build de l'application Angular
FROM node:20 as build

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build --configuration=production

# Étape 2 : Nginx Server (Sert le frontend et proxy l'API)
FROM nginx:alpine
COPY --from=build /app/dist/banque-profils/browser /usr/share/nginx/html
# On copiera la config nginx via un volume dans docker-compose
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
