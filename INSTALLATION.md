# Tisane Lontan - Guide d'installation et de déploiement

## Prérequis

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL (production) ou SQLite (dev)
- Serveur SMTP pour les emails

---

## Installation locale (WampServer)

```bash
# 1. Dépendances PHP
composer install

# 2. Dépendances JS + build CSS
npm install && npm run build

# 3. Créer le .env (déjà fait) ou copier
cp .env.example .env
php artisan key:generate

# 4. Base de données + données de démonstration
php artisan migrate:fresh --seed

# 5. Lien symbolique pour les images
php artisan storage:link
```

L'application est accessible à : **http://localhost/Tisane-Serge/public**

---

## Compte administrateur (créé par le seeder)

| Champ | Valeur |
|-------|--------|
| Email | `admin@tisane-lontan.re` |
| Mot de passe | `password` |

**Changer le mot de passe en production** via l'interface admin ou tinker.

---

## Configuration email (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-fournisseur.fr
MAIL_PORT=587
MAIL_USERNAME=votre@email.fr
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tisane-lontan.re"
MAIL_FROM_NAME="Tisane Lontan"

# Email du vendeur pour les notifications de nouvelles commandes
MAIL_SELLER_EMAIL="vendeur@tisane-lontan.re"
```

En développement, les emails sont loggés dans `storage/logs/laravel.log` (MAIL_MAILER=log).

---

## Rappels automatiques (Cron)

Les rappels 24h et 1h sont envoyés par la commande :

```bash
php artisan orders:send-reminders
```

### Configurer le Cron (Linux/production)

```bash
crontab -e
```

Ajouter :
```
* * * * * cd /chemin/vers/tisane-lontan && php artisan schedule:run >> /dev/null 2>&1
```

### Configurer le planificateur sous Windows (WampServer)

Utiliser le **Planificateur de tâches Windows** :
- Action : `php C:\wamp64\www\Tisane-Serge\artisan schedule:run`
- Déclencheur : toutes les minutes

---

## Structure du site

### Pages publiques
| URL | Page |
|-----|------|
| `/` | Accueil |
| `/boutique` | Catalogue produits |
| `/boutique/{slug}` | Fiche produit |
| `/panier` | Panier |
| `/commander` | Checkout + choix point de retrait |
| `/commander/confirmation/{ref}` | Confirmation de commande |

### Administration
| URL | Page |
|-----|------|
| `/connexion` | Connexion admin |
| `/admin` | Tableau de bord |
| `/admin/produits` | Gestion des produits |
| `/admin/categories` | Gestion des catégories |
| `/admin/points-retrait` | Gestion des points de retrait + créneaux |
| `/admin/commandes` | Liste des commandes |
| `/admin/commandes/{id}` | Détail commande + changement statut |

---

## Flux de commande

1. Client parcourt la boutique et ajoute au panier (session)
2. Checkout : saisit ses coordonnées, choisit point + date + créneau
3. Commande enregistrée en BDD
4. Email de confirmation envoyé au client
5. Notification email envoyée au vendeur
6. Rappel automatique 24h avant (via cron)
7. Rappel automatique 1h avant (via cron)
8. Le vendeur met à jour le statut dans l'admin

---

## Statuts de commande

| Statut | Signification |
|--------|---------------|
| `en_attente` | Commande reçue, non traitée |
| `confirmee` | Commande confirmée par le vendeur |
| `prete` | Commande préparée, prête à récupérer |
| `recuperee` | Client a récupéré sa commande |
| `annulee` | Commande annulée |
