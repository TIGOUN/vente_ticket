# Déploiement sur Render

Ce document décrit les étapes pour déployer l'application **Vente Ticket** (Laravel 11 + Livewire 3) sur [Render](https://render.com/).

---

## 1. Prérequis

- Un compte Render.
- Le code poussé sur un dépôt GitHub / GitLab.
- Un provider SMTP (Brevo, Mailgun, Postmark, Gmail, etc.) pour l'envoi des tickets par email.

---

## 2. Architecture du déploiement

- **1 web service** (PHP natif, Apache) : l'application Laravel.
- **1 base de données PostgreSQL managée** : remplace SQLite.
- **1 disque persistant** (montage `/var/data`) : pour les PDF / QR codes générés (optionnel mais recommandé — sans disque, les fichiers générés disparaissent à chaque redéploiement).

> Le SQLite du repo (`database/database.sqlite`) n'est **pas** utilisable en production sur Render : le système de fichiers est éphémère, toute donnée écrite est perdue au redeploy / redémarrage.

---

## 3. Déploiement en une seule étape via `render.yaml` (Blueprint)

Un fichier `render.yaml` est déjà présent à la racine. Il décrit le web service et la base PostgreSQL.

1. Sur Render : **New → Blueprint**.
2. Sélectionner le dépôt.
3. Render détecte `render.yaml` et propose la création du service + de la base.
4. Remplir les variables marquées `sync: false` (voir section 5).
5. **Apply** → Render déclenche le build avec `render-build.sh`.

---

## 4. Déploiement manuel (sans Blueprint)

Si tu préfères créer les ressources à la main :

### 4.1. Créer la base de données
- **New → PostgreSQL**.
- Nom : `vente-ticket-db`, Plan : `basic-256mb` (ou supérieur), Region : Frankfurt.
- Garder l'onglet ouvert : tu auras besoin des valeurs `Internal Database URL` / `Host` / `Port` / `Database` / `User` / `Password`.

### 4.2. Créer le web service
- **New → Web Service** → sélectionner le dépôt.
- Runtime : **PHP**.
- Region : la même que la base (ex. Frankfurt).
- Branch : `main` (ou la branche à déployer).
- Build Command : `./render-build.sh`
- Start Command : `vendor/bin/heroku-php-apache2 public/`
- Health Check Path : `/up`

### 4.3. (Optionnel mais recommandé) Ajouter un disque persistant
- Dans le web service → **Disks** → Add Disk.
- Name : `ticket-storage`, Mount Path : `/var/data`, Size : 1 GB.
- Puis dans la config, définir `FILESYSTEM_DISK=local` et adapter si tu veux stocker les PDFs sur le disque (voir section 7).

---

## 5. Variables d'environnement à définir sur Render

### Obligatoires

| Variable | Valeur | Remarque |
|---|---|---|
| `APP_NAME` | `Vente Ticket` | |
| `APP_ENV` | `production` | |
| `APP_KEY` | *généré* | `php artisan key:generate --show` en local, ou laisser Render générer via `generateValue: true` |
| `APP_DEBUG` | `false` | **Ne jamais laisser `true` en prod** |
| `APP_URL` | `https://<nom-service>.onrender.com` | À ajuster après premier déploiement |
| `APP_TIMEZONE` | `Africa/Porto-Novo` | Ou le fuseau souhaité |
| `APP_LOCALE` | `fr` | |
| `LOG_CHANNEL` | `stack` | |
| `LOG_STACK` | `single` | |
| `LOG_LEVEL` | `error` | |
| `TRUSTED_PROXIES` | `*` | Nécessaire car Render est derrière un load balancer |

### Base de données (PostgreSQL Render)

| Variable | Valeur |
|---|---|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | *fourni par la base Render (Internal Host)* |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | *fourni par la base* |
| `DB_USERNAME` | *fourni par la base* |
| `DB_PASSWORD` | *fourni par la base* |

> Avec le `render.yaml` fourni, ces 5 variables sont automatiquement liées via `fromDatabase`.

### Sessions / Cache / Queue

| Variable | Valeur |
|---|---|
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | `120` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SESSION_SAME_SITE` | `lax` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `BROADCAST_CONNECTION` | `log` |
| `FILESYSTEM_DISK` | `local` |

### Mail (à remplir selon ton provider SMTP)

| Variable | Exemple (Brevo) |
|---|---|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp-relay.brevo.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | *login SMTP* |
| `MAIL_PASSWORD` | *clé SMTP* |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS` | `no-reply@tondomaine.com` |
| `MAIL_FROM_NAME` | `Vente Ticket` |

### Optionnelles

| Variable | Valeur | Quand |
|---|---|---|
| `BCRYPT_ROUNDS` | `12` | Par défaut 12, suffisant |
| `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_DEFAULT_REGION` | — | Seulement si tu passes le stockage sur S3 (recommandé pour les PDFs, voir §7) |

---

## 6. Ce qui se passe au build (`render-build.sh`)

1. `composer install --no-dev --optimize-autoloader`
2. `npm ci && npm run build` (Vite → `public/build/`)
3. `php artisan config:cache` / `route:cache` / `view:cache`
4. `php artisan storage:link` (symlink `public/storage` → `storage/app/public`)
5. `php artisan migrate --force` (applique les migrations)

---

## 7. Stockage des fichiers générés (PDFs, QR codes)

L'application écrit dans `storage/app/public/` (tickets, PDF, ZIP, QR codes). Sur Render, **le filesystem est éphémère** : tout est perdu à chaque redéploiement.

Deux options :

### Option A — Disque persistant (simple, déjà dans `render.yaml`)
- Monte un disque sur `/var/data` et utilise `FILESYSTEM_DISK=local`.
- Ajuste le `root` du disque `public` dans `config/filesystems.php` vers `/var/data/public` (non fait par défaut dans ce repo ; à ajouter si tu veux activer cette option).

### Option B — S3 (recommandé pour la montée en charge)
- Créer un bucket S3 (ou compatible : Backblaze B2, Cloudflare R2, Scaleway).
- Mettre `FILESYSTEM_DISK=s3` et remplir les variables `AWS_*`.
- Les QR codes et PDFs seront stockés sur le cloud, survivent aux redéploiements et scalent horizontalement.

---

## 8. Post-déploiement : créer le premier utilisateur admin

Se connecter via le Shell Render (onglet **Shell** du web service) :

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@tondomaine.com',
    'password' => bcrypt('un-mot-de-passe-solide'),
    'type_user' => 'admin',
    'email_verified_at' => now(),
]);
```

---

## 9. Points de vigilance

- **`APP_DEBUG=false`** : obligatoire en production (sinon stack traces exposées).
- **`APP_KEY`** : ne pas changer après déploiement (sinon sessions + données chiffrées cassées).
- **Migrations** : la commande `migrate --force` s'exécute à chaque build. Les migrations doivent donc rester idempotentes.
- **Plan `free`** : Render met en veille les services gratuits après 15 min d'inactivité → premier accès lent (~30 s). Pour éviter ça, passer sur `starter`.
- **Queue** : si tu actives des jobs lourds (envoi massif d'emails), ajoute un **Background Worker** Render qui lance `php artisan queue:work`.
- **Health check** : la route `/up` est exposée par Laravel automatiquement (voir `bootstrap/app.php`).

---

## 10. Compatibilité PostgreSQL

L'application était initialement conçue pour SQLite (dev). Voici ce qui a été fait / vérifié pour PostgreSQL sur Render :

- **Extension PHP `pdo_pgsql`** : ajoutée dans `composer.json` → installée automatiquement par le buildpack PHP de Render lors du `composer install --no-dev`.
- **`config.platform`** dans `composer.json` : déclare les extensions `pdo_pgsql` et `gd` comme présentes côté Composer, pour que `composer update` fonctionne localement (où ces extensions ne sont pas installées sur la machine du dev qui utilise SQLite). Les vraies extensions sont bien installées sur Render.
- **`Schema::defaultStringLength(191)`** : conditionné au driver `mysql` dans `AppServiceProvider` — inutile sur Postgres.
- **UUID** (`events.id`, `tickets.id`) : fonctionnent en mode natif Postgres (`uuid` réel, plus efficace que `CHAR(36)` MySQL).
- **`enum('type_user', ...)`** dans la table `users` : Laravel génère un `VARCHAR` + `CHECK` constraint sur Postgres. Fonctionne, mais toute modification future de cette colonne nécessitera `doctrine/dbal` ou une migration brute — à garder en tête.
- **`unsignedBigInteger`** : devient `BIGINT` sur Postgres (pas de notion `unsigned`). Les foreign keys fonctionnent normalement.
- **`LIKE` case-sensitive** (dans `app/Helpers/helper.php:42`) : Postgres est sensible à la casse, contrairement à MySQL/SQLite. Le pattern utilisé (`'TKT-FAST' . $anneeActuelle . '-%'`) et les codes générés en `strtoupper()` sont toujours en majuscules, donc compatible.
- **Aucun `DB::raw` / `whereRaw`** dans l'app → pas de SQL spécifique à un dialecte.

> Note dev local : si tu veux tester avec Postgres en local, installe `php8.2-pgsql` (ou version correspondant à ton PHP) et change `DB_CONNECTION=pgsql` dans `.env`.

---

## 11. Checklist finale avant de pousser

- [x] Conflits de merge résolus (`.gitignore`, `README.md`)
- [x] `APP_ENV=production` et `APP_DEBUG=false` dans les envs Render
- [x] Base PostgreSQL créée et liée via les vars `DB_*`
- [x] `APP_KEY` générée
- [x] `TRUSTED_PROXIES=*` (HTTPS derrière proxy Render)
- [x] `SESSION_SECURE_COOKIE=true`
- [x] SMTP configuré (sinon les emails de ticket ne partent pas)
- [ ] Bucket S3 ou disque persistant pour les fichiers générés (si l'app sert des PDFs régulièrement)
- [ ] Premier utilisateur admin créé via Tinker
