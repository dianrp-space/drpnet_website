#!/bin/bash

# Set tanggal
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="backups/$DATE"

# Buat direktori backup
mkdir -p $BACKUP_DIR

# Backup database
php artisan db:backup

# Backup file penting
cp .env "$BACKUP_DIR/.env"
cp -r storage/app/public "$BACKUP_DIR/storage_public"

# Compress backup
tar -czf "$BACKUP_DIR.tar.gz" $BACKUP_DIR

# Hapus direktori temporary
rm -rf $BACKUP_DIR

echo "Backup selesai: $BACKUP_DIR.tar.gz" 