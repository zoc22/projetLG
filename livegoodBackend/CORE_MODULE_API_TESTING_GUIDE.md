# Guide d'Utilisation - Tests API Core Module via Postman

## 📋 Vue d'ensemble

Ce guide explique comment utiliser la collection Postman pour tester toutes les opérations du module Core de LiveGood.

## 🚀 Démarrage Rapide

### 1. Importer la Collection Postman

1. Ouvrez Postman
2. Cliquez sur **File** → **Import**
3. Sélectionnez le fichier `LiveGood_Core_Module_API_Tests.postman_collection.json`
4. La collection s'importe avec tous les tests pré-configurés

### 2. Configurer les Variables d'Environnement

Dans la collection, il y a deux variables principales à configurer:

```json
{
  "base_url": "http://localhost",  // URL de base de votre application
  "token": ""                        // Token d'authentification (laisser vide pour les tests sans auth)
}
```

**Pour obtenir un token d'authentification:**
- Appelez l'endpoint de login du module authentication
- Copiez le token reçu
- Collez-le dans la variable `token`

## 📁 Organisation de la Collection

La collection est organisée en **7 groupes de tests**:

### 1. 🏥 Santé & Configuration
Tests de la santé de l'application et de la configuration du module core.

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/health` | GET | Vérifie la santé de l'application |
| `/api/core/config` | GET | Configuration du module Core |
| `/api/core/permissions/available` | GET | Permissions et rôles disponibles |

### 2. 🧩 Gestion des Modules
Tests pour lister, détailler et vérifier l'état des modules.

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/modules` | GET | Lister tous les modules |
| `/api/core/modules/{module}` | GET | Détails d'un module |
| `/api/core/modules/{module}/status` | GET | Statut d'un module |

**Modules disponibles pour tester:**
- `core` - Module Core
- `authentication` - Module d'authentification
- `authorization` - Module d'autorisation
- `user-management` - Gestion des utilisateurs
- `affiliation` - Module d'affiliation
- `commission` - Module de commission
- `genealogy` - Module généalogique
- `ecommerce` - Module e-commerce
- `payment` - Module de paiement
- `notification` - Module de notification
- `support` - Module de support
- `training` - Module de formation
- `statistics` - Module de statistiques
- `rank` - Module de rangs
- `marketing` - Module de marketing

### 3. 🔧 Test des Helpers de Module
Tests des fonctions helper liées aux modules.

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/modules/test/helper-path` | POST | Teste `module_path()` |
| `/api/core/modules/test/helper-config` | POST | Teste `module_config()` |
| `/api/core/modules/test/helper-enabled` | POST | Teste `module_enabled()` |

**Exemple de payload pour helper-path:**
```json
{
  "module": "core",
  "path": "config"
}
```

### 4. 👥 Gestion des Rôles
Tests pour lister et récupérer les détails des rôles.

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/roles` | GET | Lister tous les rôles |
| `/api/core/roles/{role}` | GET | Détails d'un rôle spécifique |

**Rôles disponibles:**
- `super_admin` - Super Administrateur
- `admin` - Administrateur
- `support` - Support Client
- `affiliate` - Affilié
- `member` - Membre
- `guest` - Invité

### 5. 🔐 Gestion des Permissions
Tests des permissions utilisateur (**Authentification Requise**).

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/permissions/check` | POST | Vérifier une permission |
| `/api/core/permissions/check-all` | POST | Vérifier TOUTES les permissions |
| `/api/core/permissions/check-any` | POST | Vérifier AU MOINS UNE permission |
| `/api/core/permissions/check-role` | POST | Vérifier un rôle |
| `/api/core/permissions/check-super-admin` | GET | Vérifier si super admin |
| `/api/core/permissions/user` | GET | Récupérer les permissions de l'utilisateur |

**Exemple de payload pour check:**
```json
{
  "permission": "users.view"
}
```

**Exemple de payload pour check-all:**
```json
{
  "permissions": ["users.view", "users.create", "users.edit"]
}
```

### 6. 🧪 Test des Helpers de Permissions
Tests des fonctions helper de permissions (**Authentification Requise**).

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/permissions/test/helper-has-permission` | POST | Teste `has_permission()` |
| `/api/core/permissions/test/helper-is-admin` | GET | Teste `is_admin()` |

### 7. 🛠️ Helpers Généraux
Tests des fonctions helper générales du module core.

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/core/helpers/uuid/generate` | GET | Teste `generate_uuid()` |
| `/api/core/helpers/currency/format` | POST | Teste `format_currency()` |
| `/api/core/helpers/cache/remember-forever` | POST | Teste `cache_remember_forever()` |
| `/api/core/helpers/module-asset` | POST | Teste `module_asset()` |

**Exemple de payload pour format_currency:**
```json
{
  "amount": 1234.56,
  "currency": "USD",
  "locale": "en_US"
}
```

## 🔑 Permissions Disponibles

Les permissions testables sont divisées par fonctionnalité:

**Utilisateurs:**
- `users.view` - Voir la liste des utilisateurs
- `users.create` - Créer des utilisateurs
- `users.edit` - Modifier des utilisateurs
- `users.delete` - Supprimer des utilisateurs
- `users.impersonate` - Prendre l'identité d'un utilisateur

**Rôles & Permissions:**
- `roles.view` - Voir les rôles
- `roles.create` - Créer des rôles
- `roles.edit` - Modifier des rôles
- `roles.delete` - Supprimer des rôles
- `permissions.assign` - Attribuer des permissions

**Tableau de Bord:**
- `dashboard.view` - Voir le tableau de bord principal
- `dashboard.analytics` - Voir les analyses avancées

**Généalogie:**
- `genealogy.view` - Voir son arbre généalogique
- `genealogy.matrix` - Voir la matrice de parrainage
- `genealogy.export` - Exporter les données généalogiques

**Commissions:**
- `commissions.view` - Voir ses commissions
- `commissions.details` - Voir le détail des commissions
- `commissions.export` - Exporter les relevés

**Profil:**
- `profile.view` - Voir son profil
- `profile.edit` - Modifier son profil
- `profile.password` - Changer son mot de passe

## 📝 Exemples d'Utilisation

### Exemple 1: Vérifier les permissions d'un utilisateur

```bash
# 1. Récupérer toutes les permissions de l'utilisateur
GET /api/core/permissions/user
Header: Authorization: Bearer {token}

# Réponse:
{
  "success": true,
  "message": "User permissions retrieved successfully",
  "data": {
    "user_id": 1,
    "user_email": "user@example.com",
    "permissions": ["users.view", "users.create", "dashboard.view"],
    "total_permissions": 3
  }
}
```

### Exemple 2: Tester les helpers de module

```bash
# 1. Obtenir le chemin d'un module
POST /api/core/modules/test/helper-path
Content-Type: application/json

{
  "module": "authentication",
  "path": "models"
}

# Réponse:
{
  "success": true,
  "data": {
    "module": "authentication",
    "relative_path": "models",
    "full_path": "C:\\wamp64\\www\\livegood\\app-modules\\authentication\\models",
    "exists": true
  }
}
```

### Exemple 3: Vérifier un rôle

```bash
# POST /api/core/permissions/check-role
Header: Authorization: Bearer {token}
Content-Type: application/json

{
  "role": "admin"
}

# Réponse:
{
  "success": true,
  "data": {
    "user_id": 1,
    "user_email": "admin@example.com",
    "user_role": "admin",
    "checked_role": "admin",
    "has_role": true
  }
}
```

## ⚙️ Configuration Détaillée de l'Authentification

### Obtenir un Token via Postman

1. **Appelez l'endpoint de login** (du module authentication):
   ```
   POST /api/auth/login
   ```

2. **Copiez le token reçu** de la réponse

3. **Utilisez le script de pre-request** dans Postman:
   ```javascript
   pm.environment.set("token", pm.response.json().data.token);
   ```

4. **Ou définissez manuellement** dans la variable `token` de la collection

## 🐛 Troubleshooting

### Erreur 404 - Routes not found
**Solution:** Assurez-vous que le module Core est chargé et que le service provider est enregistré.

### Erreur 401 - Unauthenticated
**Solution:** Vérifiez que vous avez un token valide et qu'il est inclus dans le header `Authorization: Bearer {token}`

### Erreur 500 - Internal Server Error
**Solution:** Vérifiez les logs du serveur Laravel dans `storage/logs/laravel.log`

## 📊 Structure de Réponse Standard

Toutes les réponses de l'API suivent ce format:

```json
{
  "success": true/false,
  "message": "Description du résultat",
  "code": 200/401/404/500,
  "data": {
    // Les données spécifiques à l'endpoint
  }
}
```

## 🔄 Flux Recommandé de Tests

Pour tester complètement le module core:

1. **Santé** → Vérifier que l'application est accessible
2. **Configuration** → Récupérer la config et les permissions disponibles
3. **Modules** → Lister et détailler tous les modules
4. **Rôles** → Explorer les rôles et leurs permissions
5. **Helpers** → Tester les fonctions helper individuellement
6. **Authentification** → Obtenir un token
7. **Permissions** → Tester les vérifications de permissions avec le token

## 📌 Notes Importantes

- ✅ Les tests sans authentification peuvent être exécutés sans token
- ❌ Les tests avec authentification requièrent un token valide
- 🔄 Les tokens Sanctum expirent après un certain temps
- 💾 Les modifications de cache persistent selon la configuration
- 🌐 Les fonctions helper de devise requièrent l'extension PHP `intl`

## 📚 Documentation Additionnelle

- [Documentation Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Documentation des Traits Eloquent](https://laravel.com/docs/eloquent)
- [Guide des Helpers Laravel](https://laravel.com/docs/helpers)

---

**Version:** 1.0.0  
**Dernière mise à jour:** Mai 2026  
**Auteur:** LiveGood Development Team
