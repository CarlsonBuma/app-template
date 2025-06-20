# Function to log tasks
log_task() {
    echo "[INFO] $1"
}

# Backend: Put application in maintenance mode
log_task "Putting backend into maintenance mode..."
cd backend
php artisan down
log_task "Backend is now under maintenance."
cd ..

# Prompt user to decide on updating dependencies
read -p "Do you want to update dependencies (YES/NO)? " update_dependencies

if [[ "$update_dependencies" == "y" ]]; then
    cd backend
    log_task "Updating dependencies..."
    composer update
    log_task "Backend dependencies updated."

    cd ../quasar-web
    npm update
    log_task "Frontend dependencies updated."
    cd ..
else
    log_task "Skipping dependency updates..."
fi

# Prompt user to decide on migrations
read -p "Do you want to run database migrations (YES/NO)? " run_migrations

if [[ "$run_migrations" == "y" ]]; then
    cd backend
    log_task "Running database migrations..."
    php artisan migrate
    log_task "Database migrations executed successfully."
    cd ..
else
    log_task "Skipping database migrations..."
fi

# Backend deployment
cd backend
log_task "Deploying backend changes..."
php artisan optimize:clear
php artisan optimize
php artisan up
log_task "Backend updated and now live."
cd ..

# Frontend deployment
cd quasar-web
log_task "Deploying frontend changes..."
npm run build
log_task "Frontend updated."
cd ..

# Final message
log_task "Deployment process complete!"
