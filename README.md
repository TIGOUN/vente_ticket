# Vente Ticket

Application Laravel 11 + Livewire 3 pour la vente et la gestion de tickets avec QR codes (génération, scan, distribution par email / PDF).

## Stack

- PHP 8.2+
- Laravel 11
- Livewire 3
- Tailwind CSS + Vite
- Base de données : PostgreSQL (prod) / SQLite (dev local)
- PDF : `barryvdh/laravel-dompdf`
- QR code : `endroid/qr-code`, `simplesoftwareio/simple-qrcode`
- PWA : `silviolleite/laravelpwa`

## Développement local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
composer run dev
```

## Déploiement sur Render

Voir [`DEPLOY_RENDER.md`](DEPLOY_RENDER.md) pour les étapes et la liste des variables d'environnement.

## Licence

MIT
