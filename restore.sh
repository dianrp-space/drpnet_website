#!/bin/bash

if [ -z "$1" ]; then
    echo "Usage: ./restore.sh backup_file.tar.gz"
    exit 1
fi

BACKUP_FILE=$1
TEMP_DIR="restore_temp"

# Extract backup
mkdir -p $TEMP_DIR
tar -xzf $BACKUP_FILE -C $TEMP_DIR

# Restore files
cp $TEMP_DIR/*/.env .env
cp -r $TEMP_DIR/*/storage_public/* storage/app/public/

# Restore database
php artisan migrate:fresh
php artisan db:restore

# Cleanup
rm -rf $TEMP_DIR

echo "Restore selesai" 