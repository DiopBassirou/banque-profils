Cahier des charges — Banque de Profils (Laravel + Angular + MySQL + Sanctum)
Tu es un développeur full-stack senior. Construis une application web de production, sécurisée et maintenable, selon les spécifications ci-dessous. Ne saute aucune section liée à la sécurité — elles sont obligatoires, pas optionnelles.

1. Objectif du produit
   Une plateforme publique où des personnes en recherche de stage/CDD/CDI/alternance publient un profil, et où des employeurs/recruteurs parcourent et filtrent ces profils. Doit supporter une croissance à moyen terme (comptes utilisateurs, modération, futures offres d'emploi côté employeur).

2. Stack technique
   Backend : Laravel (dernière version stable), API REST uniquement (pas de Blade pour le front).
   Auth : Laravel Sanctum, mode SPA (cookies HttpOnly + protection CSRF), pas de tokens exposés au JS.
   Base de données : MySQL 8.
   Frontend : Angular (dernière version stable), TypeScript strict activé.
   Déploiement : VPS Linux (Ubuntu), Nginx en reverse proxy, PHP-FPM, PM2 ou systemd pour les processus si besoin, Certbot pour HTTPS.
3. Modèle de données (migrations)
   users : id, name, email (unique), password (hashed, bcrypt/argon2), role (enum: admin uniquement — pas de compte candidat/employeur), email_verified_at, timestamps, soft deletes.
   profils : id, nom_affiche, niveau, type_recherche (enum), domaine, region, description, email_contact (nullable, affiché publiquement), email_gestion (nullable, JAMAIS affiché — sert uniquement à recevoir le lien de connexion), whatsapp (nullable, format international), status (enum: en_attente, publie, rejete, expire), expires_at, timestamps, soft deletes. Au moins un de email_contact/whatsapp doit être renseigné pour que le profil soit visible ; email_gestion est indépendant et optionnel.
   magic_links : id, email (index), token_hash (hash du token, jamais le token brut), expires_at, used_at (nullable), created_at.
   moderation_logs : id, profil_id, admin_id, action, raison, created_at.
   password_reset_tokens, personal_access_tokens (pour l'admin uniquement).
   Index sur domaine, type_recherche, region, status pour les filtres.
4. Authentification & autorisation
   Publier un profil et parcourir/rechercher restent des actions publiques, sans mot de passe. Pour gérer un profil après publication (modifier/supprimer), on utilise un système de lien de connexion signé, à durée limitée ("magic link") — pas de compte permanent, pas de token qui ne périme jamais.

Espace admin : Sanctum SPA classique avec mot de passe (cookie de session, withCredentials), réservé au rôle admin.
Gestion de profil candidat (sans mot de passe) :
Sur /mon-profil, la personne saisit l'email qu'elle a fourni comme email_gestion (ou email_contact si pas d'email de gestion distinct).
Le backend génère un token aléatoire (32+ caractères), stocke uniquement son hash (token_hash) en base avec une expiration (30-60 min), et envoie le lien signé par email (URL::temporarySignedRoute côté Laravel).
Toute nouvelle demande de lien invalide les liens précédents non utilisés pour cet email (un seul lien valide à la fois).
Le lien est à usage unique (used_at renseigné dès le premier clic) et déclenche une session courte (24-48h, cookie Sanctum) limitée aux profils associés à cet email — jamais un accès permanent.
Rate limiting strict sur la demande de lien : max 3/heure par email, pour éviter le spam de boîtes mail.
Cas sans email du tout (uniquement whatsapp) : en dernier recours, générer un lien de gestion affiché une seule fois à l'écran juste après publication, avec avertissement explicite ("notez ce lien, il ne sera plus jamais affiché"). Ce cas doit rester l'exception, pas la norme — encourager activement la saisie d'un email_gestion, même privé, à la publication.
Toute perte de lien/accès sans email de gestion → passage obligatoire par l'admin, qui peut retrouver le profil par recherche (nom, email, whatsapp) dans l'espace de modération.
Policies Laravel côté admin (ProfilPolicy) : seul admin modère (valide/rejette/supprime n'importe quel profil) ; une session "magic link" ne peut agir que sur les profils liés à son email.
Middleware role:admin sur toutes les routes d'administration.
Limitation de connexion admin : verrouillage temporaire après N tentatives échouées.
Option à prévoir : 2FA pour le(s) compte(s) admin. 5. Sécurité — obligatoire
Validation : Form Requests Laravel sur chaque endpoint d'écriture, whitelist des champs acceptés (jamais de $request->all() brut vers le modèle).
Autorisations : chaque contrôleur vérifie la policy correspondante, jamais de confiance aveugle dans un user_id envoyé par le client.
Rate limiting : throttle middleware sur POST /api/profils (ex: 3-5/heure par IP), sur /admin/login (ex: 5/min), et sur toute route publique d'écriture. C'est la ligne de défense principale puisqu'il n'y a pas de compte candidat — à ne pas alléger.
Anti-spam : captcha obligatoire (Google reCAPTCHA v3 ou hCaptcha) sur le formulaire de publication de profil, en plus du rate limiting. Aussi : validation stricte du format email/whatsapp, filtrage anti-liens/spam basique dans le champ description (ex: rejet si trop de liens externes).
CORS : configuration stricte (config/cors.php) limitée au(x) domaine(s) du frontend, pas de wildcard \* en production.
CSRF : activé par défaut avec Sanctum SPA, vérifier que le cookie XSRF-TOKEN est bien géré côté Angular (HttpClientXsrfModule).
XSS : Angular échappe par défaut (DomSanitizer si du HTML dynamique est affiché) ; ne jamais utiliser innerHTML avec du contenu utilisateur non nettoyé.
SQL injection : uniquement Eloquent/Query Builder, aucune requête SQL brute avec concaténation de variables.
Secrets : tout dans .env, jamais commité (.gitignore), variables différentes en dev/staging/prod, rotation des clés (APP_KEY, clés API tierces).
HTTPS forcé : redirection HTTP→HTTPS au niveau Nginx, APP_URL en https, cookies avec Secure + SameSite=Lax ou Strict.
Headers de sécurité : CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy (via un middleware ou Nginx).
Uploads (si CV/photo ajoutés plus tard) : validation stricte du type MIME réel (pas juste l'extension), taille max, stockage hors public/ avec accès contrôlé, scan antivirus si possible.
Logs & audit : chaque action de modération tracée dans moderation_logs ; logs applicatifs (Laravel log channels) sans données sensibles en clair.
Sauvegardes : dump MySQL automatisé quotidien (cron + rétention), testé en restauration au moins une fois.
Dépendances : composer audit et npm audit intégrés au pipeline CI, mise à jour régulière. 6. RGPD / vie privée
Consentement explicite à la publication du contact (checkbox, pas pré-cochée).
Page de politique de confidentialité claire (données collectées, durée de conservation, usage).
Fonction "supprimer mon profil" accessible sans devoir contacter un admin.
Expiration automatique des profils après une durée définie (ex: 3 mois), avec relance email avant suppression.
Export/suppression de compte sur demande (droit à l'oubli). 7. API — endpoints principaux
GET /api/profils (public, paginé, filtrable: domaine, type, region — n'expose jamais email_gestion)
POST /api/profils (public, throttled + captcha)

POST /api/auth/magic-link (public, throttled 3/h/email : envoie le lien de connexion)
GET /api/auth/magic-link/{token} (public, signé + expirant : établit la session courte)
GET /api/mes-profils (session magic-link requise)
PUT /api/profils/{id} (session magic-link requise + policy : profil lié à l'email connecté)
DELETE /api/profils/{id} (session magic-link requise + policy, ou admin)

POST /api/admin/login
POST /api/admin/logout
GET /api/admin/profils (admin uniquement, tous statuts, recherche par nom/email/whatsapp)
PATCH /api/admin/profils/{id}/status (admin uniquement: valider/rejeter)
DELETE /api/admin/profils/{id} (admin uniquement)
Toutes les réponses en JSON, codes HTTP corrects (422 validation, 401/403 auth, 404 générique et identique que le token soit invalide, expiré ou déjà utilisé — ne jamais préciser lequel, pour ne pas faciliter l'énumération, 429 rate limit).

8. Frontend Angular — structure attendue
   Modules : ProfilsModule (public — parcourir, publier), CompteModule (gestion via magic link : demander un lien, voir/modifier/supprimer ses profils), AdminModule (lazy-loaded, guardé, mot de passe).
   AuthGuard/RoleGuard sur les routes /admin/\* ET sur /mes-profils (session magic-link requise pour cette dernière).
   HttpInterceptor pour gérer les cookies Sanctum (admin et session magic-link) et les erreurs 401 (redirection appropriée).
   Formulaires réactifs (ReactiveFormsModule) avec validation côté client ET serveur (jamais l'un sans l'autre).
   Boutons de contact sur chaque profil : afficher un bouton par moyen de contact renseigné, chacun ouvrant directement l'app correspondante (pas de formulaire intermédiaire) :
   email_contact renseigné → bouton "Envoyer un email" avec href="mailto:{{email_contact}}?subject=...".
   whatsapp renseigné → bouton "Envoyer sur WhatsApp" avec href="https://wa.me/{{numero_sans_espaces_ni_plus}}?text=..." (le numéro doit être stocké et validé au format international, ex: 221771234567, pour que le lien wa.me fonctionne).
   Les deux boutons peuvent coexister si les deux contacts sont renseignés. email_gestion n'est jamais affiché ni utilisé pour ces boutons.
   Ouvrir dans un nouvel onglet (target="\_blank" rel="noopener").
   Design : reprendre l'identité définie précédemment — vert profond #0B4A34, or #C89B3C, rouge terracotta #A23B2E en accent, typographie serif (Fraunces) pour les titres + sans-serif (Work Sans) pour le corps, motif étoile discret en élément de marque. Layout sobre, pas de composants génériques type "carte SaaS" non justifiée.
   Accessibilité : focus visible au clavier, contrastes suffisants, formulaires avec labels associés.
9. Déploiement — VPS partagé existant (Docker uniquement)
   Ce projet est déployé sur un VPS qui héberge déjà plusieurs autres applications (Node.js via PM2, un projet Docker). Contrainte absolue : ne jamais interférer avec l'existant. Tout ce projet doit tourner en Docker Compose, dans son propre dossier, isolé du reste.

Emplacement : /opt/banque-profils/ (dossier dédié, propre au projet, ne rien mettre ailleurs dans /opt/).
Ports déjà occupés sur ce serveur — ne jamais les réutiliser : 22, 53, 80, 443 (système/nginx), 3000 (btp-api), 3001 (gestockpro-backend), 4000 (scolarité), 8085 (dit_biblio-frontend), 8000 et 5432 (internes au réseau Docker de dit_biblio, à éviter aussi pour ne pas créer de confusion). Avant de choisir un port, vérifier sa disponibilité réelle avec ss -tlnp | grep :LEPORT (aucune ligne affichée = libre). Ports libres recommandés : 3002, 3003, 5000, 8080 — le projet doit en choisir un pour le frontend/API exposé et le documenter dans son propre README.
Stack Docker Compose du projet (3 services, réseau Docker interne dédié au projet) :
api (PHP-FPM + Laravel).
web (Nginx interne au projet, sert le build Angular + reverse-proxy vers api) — c'est CE service qui expose le port choisi (ex: 3002) vers l'hôte ; ne jamais publier 80/443 depuis ce compose, ils appartiennent déjà au Nginx système.
db (MySQL 8).
Persistance des données MySQL — obligatoire : monter le volume de données MySQL sur un chemin du disque du VPS (bind mount), pas un volume Docker anonyme, pour survivre à un docker compose down/recreate/mise à jour d'image :
services:
db:
image: mysql:8
volumes: - /opt/banque-profils/data/mysql:/var/lib/mysql # pas de "ports:" exposé vers l'hôte — MySQL ne doit être joignable # que par le service "api" à l'intérieur du réseau Docker du projet
Faire aussi des dumps MySQL réguliers (mysqldump via cron, stocké hors du conteneur) en plus du bind mount — le bind mount protège contre la perte au recreate, pas contre une corruption ou une erreur humaine.
Exposition publique : ne PAS publier de nouveau port 80/443 côté Docker. Une fois le service web du projet exposé sur son port choisi (ex: 3002:80 dans le compose), créer un nouveau fichier de config dans /etc/nginx/sites-available/banque-profils (ne jamais toucher aux configs existantes btp-app, creche-app, scolarite), avec un proxy_pass http://localhost:3002;, puis sudo nginx -t avant tout sudo systemctl reload nginx (reload, jamais restart).
php artisan migrate --force + config:cache + route:cache à l'intérieur du conteneur api au déploiement (docker compose exec api php artisan ...), jamais APP_DEBUG=true en prod.
Avant de valider le déploiement : vérifier que docker ps montre les nouveaux conteneurs actifs et que les autres apps du serveur (btp, crèche, biblio, scolarité) répondent toujours normalement. 10. Tests
Backend : tests Feature Laravel sur chaque endpoint (cas valides, invalides, non autorisés).
Frontend : au moins des tests unitaires sur les composants de formulaire et les guards.
Test manuel de charge basique avant lancement public (ex: k6 ou artillery sur POST /api/profils). 11. Livrable attendu
Fournis le code organisé par dossiers (backend/, frontend/), un README.md avec les commandes d'installation et de déploiement, les fichiers .env.example (sans secrets réels), et un résumé des choix de sécurité effectués. Signale explicitement toute limitation connue ou tout point qui nécessiterait une revue de sécurité supplémentaire avant un vrai lancement public.
