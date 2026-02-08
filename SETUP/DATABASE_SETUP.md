# 🗄️ Database Setup Guide for SIM-PP

## Option 1: Using HeidiSQL (Recommended for Laragon)

1. **Open HeidiSQL** (included with Laragon)
    - Click on Laragon tray icon → Database → HeidiSQL

2. **Create Database**
    - Click "New" button or right-click on connection → "Create new" → "Database"
    - Database name: `sim_pp`
    - Collation: `utf8mb4_unicode_ci`
    - Click "OK"

3. **Verify Database**
    - You should see `sim_pp` in the left sidebar

---

## Option 2: Using phpMyAdmin

1. **Open phpMyAdmin**
    - Go to: http://localhost/phpmyadmin
    - Or click Laragon → Database → phpMyAdmin

2. **Create Database**
    - Click "New" in the left sidebar
    - Database name: `sim_pp`
    - Collation: `utf8mb4_unicode_ci`
    - Click "Create"

---

## Option 3: Using Laragon Terminal

1. **Open Laragon Terminal**
    - Right-click Laragon tray icon → Terminal

2. **Run MySQL Command**
    ```bash
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS sim_pp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    ```

---

## After Creating Database

### Run Laravel Migrations

```bash
# Navigate to project directory
cd c:\laragon\www\sim-pp

# Run migrations
php artisan migrate
```

Expected output:

```
INFO  Preparing database.

  Creating migration table ............................................. 12ms DONE

   INFO  Running migrations.

  0001_01_01_000000_create_users_table ................................. DONE
  2026_02_08_100001_create_tenants_table ............................... DONE
  2026_02_08_114406_add_two_factor_columns_to_users_table .............. DONE
  2026_02_08_400001_create_finance_tables .............................. DONE
```

---

## Verify Database Connection

```bash
php artisan tinker
```

Then in tinker:

```php
DB::connection()->getPdo();
// Should return PDO object without errors

DB::select('SELECT DATABASE()');
// Should return: [{"DATABASE()": "sim_pp"}]

exit
```

---

## Troubleshooting

### Error: "SQLSTATE[HY000] [1049] Unknown database 'sim_pp'"

**Solution:** Database not created yet. Follow Option 1 or 2 above.

### Error: "SQLSTATE[HY000] [2002] No connection could be made"

**Solution:**

1. Make sure MySQL is running in Laragon
2. Check Laragon → MySQL → Start

### Error: "Access denied for user 'root'@'localhost'"

**Solution:**

1. Check your `.env` file
2. Update `DB_PASSWORD` if Laragon MySQL has a password
3. Default Laragon MySQL: username=`root`, password=`` (empty)

---

## Next Steps After Database Setup

1. ✅ Create database `sim_pp`
2. ⬜ Run `php artisan migrate`
3. ⬜ Install multi-tenancy package
4. ⬜ Create tenant migrations
5. ⬜ Start building features!

---

**Quick Command Reference:**

```bash
# Check database connection
php artisan db:show

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drop all tables and re-run)
php artisan migrate:fresh

# Run migrations with seeding
php artisan migrate:fresh --seed
```
