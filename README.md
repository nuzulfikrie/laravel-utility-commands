### Laravel utility command

Command commonly used to manage database seeders inside a laravel project, intended for v11 Laravel project 

- **Options:**
  - `--s3`: Upload the backup to S3
  - `--s3-bucket`: Specify S3 bucket (optional)
  - `--s3-region`: Specify S3 region (optional)
- **Features:**
  - Creates SQL dump
  - Compresses to ZIP
  - Optional S3 upload
  - Progress indicators
- **Output:**
  - Local ZIP file (if not uploading to S3)
  - S3 upload confirmation (if using S3)

## Common Options

Most commands support these common options:
- `--env`: Specify the environment
- `--verbose` or `-v`: Show detailed output
- `--quiet` or `-q`: Suppress output
- `--help`: Show command help

## Best Practices

1. **Environment Safety:**
   - Always use appropriate environment flags
   - Test commands in development first
   - Use force flags cautiously

2. **Database Operations:**
   - Backup data before destructive operations
   - Use `--force` flags carefully
   - Consider impact on related tables

3. **Testing:**
   - Use `app:test-setup` before running tests
   - Ensure test database is configured properly
   - Clear cache when testing environment changes

## Troubleshooting

### Common Issues

1. **Database Connection Issues:**
   ```bash
   php artisan db:create database_name
   # If fails, check database credentials in .env
   ```

2. **Permission Issues:**
   ```bash
   # Ensure proper file permissions
   chmod -R 775 storage bootstrap/cache
   ```

3. **Passport Installation:**
   ```bash
   # If Passport installation fails
   php artisan passport:install --force
   ```

### Error Resolution

If you encounter errors:
1. Check environment configuration
2. Ensure database connections are correct
3. Clear application cache
4. Verify file permissions
5. Check logs in `storage/logs`

## Contributing

When adding new commands:
1. Follow Laravel's command naming conventions
2. Add appropriate documentation
3. Include progress indicators for long-running operations
4. Implement proper error handling
5. Add command to this README

## License

This command collection is part of the main application and follows its licensing terms.
