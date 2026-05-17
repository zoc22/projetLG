# Guide Complet: Tests Postman du Module Commission

## 📋 Table des Matières
1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Exécution des Tests](#exécution-des-tests)
4. [Cas de Test Détaillés](#cas-de-test-détaillés)
5. [Débogage](#débogage)

---

## Installation

### Prérequis
- Postman (Desktop ou Web)
- Laravel en cours d'exécution (`php artisan serve`)
- Token JWT/Sanctum valide

### Importer la Collection
1. Ouvrir Postman
2. Menu **File > Import**
3. Sélectionner: `Commission_Module_API_Tests.postman_collection.json`
4. Cliquer **Import**

---

## Configuration

### 1️⃣ Configurer l'Environnement

**Variables à configurer:**

```
base_url:      http://localhost:8000
auth_token:    [vide - sera rempli automatiquement]
user_id:       [vide - sera rempli automatiquement]
commission_id: [vide - sera rempli automatiquement]
period:        2024-W01
```

#### Créer un Environment Postman
1. Click **Environments** en haut à gauche
2. Click **Create New**
3. Entrer ces valeurs initiales:
   - `base_url`: `http://localhost:8000`
   - `period`: `2024-W01`
4. Click **Save**
5. Sélectionner cet environment

---

## Exécution des Tests

### Option 1: Exécution Manuelle (Recommandée pour le débogage)

#### Étape 1: Authentification
```
POST {{base_url}}/api/auth/login
```
**Body (JSON):**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

✅ **Résultat attendu:**
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "email": "user@example.com"
    }
}
```

> Le token et user_id sont **automatiquement** sauvegardés dans l'environment Postman

#### Étape 2: Récupérer la Liste des Commissions
```
GET {{base_url}}/api/commissions?page=1&per_page=15
Headers:
  Authorization: Bearer {{auth_token}}
  Accept: application/json
```

✅ **Résultat attendu (200 OK):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": "commission-uuid-1",
                "user_id": "user-uuid",
                "amount": 150.50,
                "status": "pending",
                "type": "weekly",
                "source": "order-123",
                "period_string": "2024-W01",
                "created_at": "2024-05-07T10:30:00Z"
            }
        ],
        "total": 42,
        "per_page": 15
    }
}
```

> L'ID de la première commission est **automatiquement** sauvegardé dans `commission_id`

#### Étape 3: Consulter les Détails d'une Commission
```
GET {{base_url}}/api/commissions/{{commission_id}}
```

✅ **Résultat attendu:**
```json
{
    "success": true,
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440001",
        "user_id": "550e8400-e29b-41d4-a716-446655440000",
        "amount": 150.50,
        "status": "pending",
        "type": "weekly",
        "source": "order-id",
        "period_string": "2024-W01",
        "fastStart": null,
        "matrix": { "id": "...", "commission_id": "...", "active_members_count": 45 },
        "matching": null,
        "retail": null,
        "influencer": null,
        "diamond": null
    }
}
```

#### Étape 4: Résumé par Période
```
GET {{base_url}}/api/commissions/summary/{{period}}
```

✅ **Résultat attendu:**
```json
{
    "success": true,
    "period": "2024-W01",
    "summary": [
        {
            "status": "pending",
            "type": "weekly",
            "count": 5,
            "total": 1250.50
        },
        {
            "status": "paid",
            "type": "monthly",
            "count": 3,
            "total": 3500.00
        }
    ],
    "total_amount": 4750.50
}
```

#### Étape 5: Résumé par Statut
```
GET {{base_url}}/api/commissions/status/summary
```

✅ **Résultat attendu:**
```json
{
    "success": true,
    "data": [
        {
            "status": "pending",
            "count": 12,
            "total": 2150.75
        },
        {
            "status": "validated",
            "count": 8,
            "total": 1900.00
        },
        {
            "status": "paid",
            "count": 22,
            "total": 5430.25
        }
    ]
}
```

#### Étape 6: Résumé par Type
```
GET {{base_url}}/api/commissions/type/summary
```

✅ **Résultat attendu:**
```json
{
    "success": true,
    "data": [
        {
            "type": "weekly",
            "count": 15,
            "total": 2150.50
        },
        {
            "type": "monthly",
            "count": 18,
            "total": 4200.00
        },
        {
            "type": "special",
            "count": 9,
            "total": 1130.50
        }
    ]
}
```

#### Étape 7: Rapport des Bonus
```
GET {{base_url}}/api/commissions/report/bonus
```

✅ **Résultat attendu:**
```json
{
    "success": true,
    "data": [
        {
            "source": "faststart",
            "type": "weekly",
            "count": 5,
            "total": 250.50
        },
        {
            "source": "matrix",
            "type": "weekly",
            "count": 42,
            "total": 10.50
        },
        {
            "source": "retail",
            "type": "monthly",
            "count": 3,
            "total": 85.00
        }
    ],
    "total": 346.00
}
```

---

### Option 2: Exécution Automatisée (Collection Runner)

#### Pour exécuter tous les tests en séquence:

1. Click **Collection > Commission_Module_API_Tests**
2. Click **Run** (triangle play icon)
3. Configuration du runner:
   - **Environment**: Sélectionner l'environment créé
   - **Iterations**: 1
   - **Delay**: 100 ms (entre chaque requête)
   - **Keep variable values**: ✓ (pour conserver les variables entre tests)

4. Click **Run Commission_Module_API_Tests**

#### Visualiser les Résultats
- ✅ **Green**: Test réussi
- ❌ **Red**: Test échoué
- **Summary**: Affiche le nombre de tests réussis/échoués

---

## Cas de Test Détaillés

### 📊 Section 1: Commission Management

| N° | Nom du Test | Endpoint | Attendu |
|-----|------------|----------|---------|
| 1.1 | List All Commissions | GET `/commissions` | 200 + pagination |
| 1.2 | Get Commission Details | GET `/commissions/{id}` | 200 + relations |
| 1.3 | Summary by Period | GET `/commissions/summary/{period}` | 200 + agrégations |
| 1.4 | Summary by Status | GET `/commissions/status/summary` | 200 + groupe par statut |
| 1.5 | Summary by Type | GET `/commissions/type/summary` | 200 + groupe par type |

### 📈 Section 2: Bonus Reports

| N° | Nom du Test | Endpoint | Attendu |
|-----|------------|----------|---------|
| 2.1 | Bonus Report by Type | GET `/commissions/report/bonus` | 200 + détail bonus |

### ⚠️ Section 3: Error Handling

| N° | Nom du Test | Cas | Attendu |
|-----|------------|------|---------|
| 3.1 | Unauthorized (No Token) | GET `/commissions` sans auth | 401 |
| 3.2 | Invalid Period | GET `/commissions/summary/invalid` | 400/422 |
| 3.3 | Commission Not Found | GET `/commissions/00000000-...` | 404 |

### 🚀 Section 4: Performance

| N° | Nom du Test | Cas | Attendu |
|-----|------------|------|---------|
| 4.1 | Large Dataset | GET `/commissions?per_page=100` | 200 + <1000ms |
| 4.2 | Concurrent Requests | Runner avec 5+ itérations | Tous 200 |

### ✔️ Section 5: Data Validation

| N° | Nom du Test | Cas | Attendu |
|-----|------------|------|---------|
| 5.1 | Amount Format | Tous les montants sont numbers | ✓ |
| 5.2 | Status Values | Status in [pending, validated, paid, cancelled] | ✓ |
| 5.3 | Type Values | Type in [weekly, monthly, special] | ✓ |

### 🔒 Section 6: Business Logic

| N° | Nom du Test | Cas | Attendu |
|-----|------------|------|---------|
| 6.1 | User Isolation | Données user_id == current user | ✓ |
| 6.2 | Period Filtering | Period dans résumé | ✓ |
| 6.3 | Total Calculations | Total calculé correctement | ✓ |

---

## Débogage

### 🔍 Problème: 401 Unauthorized

**Cause 1**: Token expiré ou invalide
```
Solution: Relancer le login et copier le nouveau token
```

**Cause 2**: Header Authorization mal formaté
```
✓ Correct: Authorization: Bearer eyJ0eXAi...
✗ Incorrect: Authorization: eyJ0eXAi...
```

---

### 🔍 Problème: 404 Not Found

**Cause**: Commission ID n'existe pas pour l'utilisateur
```
Solution: 
1. Relancer la liste: GET /commissions
2. Copier le ID de la première ligne
3. Utiliser dans GET /commissions/{id}
```

---

### 🔍 Problème: 422 Validation Error

**Cause**: Paramètre de période invalide
```
✓ Correct: 2024-W01, 2024-M01
✗ Incorrect: 2024-01-01, 01-01-2024
```

---

### 🔍 Problème: Response Time > 1000ms

**Cause**: Performance lente du serveur
```
Solution:
1. Vérifier: php artisan optimize
2. Vérifier les index de base de données
3. Utiliser Database Profiler: php artisan tinker
```

---

### 📝 Script de Test Complet (Console Postman)

```javascript
// Exécuter dans: Postman > Console (Ctrl+Alt+C)

// 1. Vérifier authentification
pm.test('Auth Token exists', function() {
    pm.expect(pm.environment.get('auth_token')).to.exist;
});

// 2. Vérifier user_id
pm.test('User ID exists', function() {
    pm.expect(pm.environment.get('user_id')).to.exist;
});

// 3. Résumé des tests
console.log('✓ Setup complet');
console.log('Base URL:', pm.environment.get('base_url'));
console.log('User ID:', pm.environment.get('user_id'));
console.log('Token:', pm.environment.get('auth_token').substring(0, 20) + '...');
```

---

## Statut des Tests par Environnement

### Local Development
```
✓ Tous les tests doivent passer
```

### Staging
```
✓ Tous les tests doivent passer
⚠️ Vérifier la performance avec données réelles
```

### Production
```
✓ Subset des tests (lecture seule)
❌ PAS de tests de création/modification
```

---

## Optimisation des Variables d'Environment

Pour faciliter les tests sur plusieurs utilisateurs:

```javascript
// Pre-request Script (avant chaque requête)
if (!pm.environment.get('auth_token')) {
    console.warn('⚠️ Token manquant - Exécuter login en premier');
}
```

---

## Export des Résultats

### Générer un rapport
1. Runner > Results > **Export Results**
2. Format: JSON ou HTML
3. Partager pour analyse

---

## Checklist de Validation

- [ ] Token obtenu et stocké
- [ ] 5 endpoints listés accessibles
- [ ] Tous les résumés retournent des données
- [ ] Pas de 401/403/404 sur données valides
- [ ] Temps réponse < 1s
- [ ] Totaux calculés correctement
- [ ] Isolation utilisateur confirmée

---

## Support & Troubleshooting

### Logs Laravel
```bash
tail -f storage/logs/laravel.log
```

### Tester manuellement avec curl
```bash
curl -X GET \
  'http://localhost:8000/api/commissions' \
  -H 'Authorization: Bearer YOUR_TOKEN' \
  -H 'Accept: application/json'
```

### CLI Artisan
```bash
php artisan route:list | grep commissions
php artisan tinker
> App\Models\Commission::count()
```
