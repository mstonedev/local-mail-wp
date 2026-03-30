# Local Mail — Mailpit (MU Plugin)

A zero-config must-use plugin that routes all WordPress mail through [Mailpit](https://mailpit.axllent.org/) for local development. No settings page, no database writes, no activation required.

## What It Does

Intercepts WordPress's PHPMailer instance on every request and redirects outgoing mail to a local Mailpit SMTP server on `127.0.0.1:1025`. All emails — WooCommerce orders, password resets, contact forms, user registration — are captured in the Mailpit web UI instead of being delivered.

## Requirements

- WordPress 5.0+
- PHP 8.0+
- [Mailpit](https://mailpit.axllent.org/) running locally (SMTP on port `1025`)
- [Laravel Herd](https://herd.laravel.com/) or equivalent local environment


## Installation

1. Copy `local-mail.php` to `wp-content/mu-plugins/`
2. No activation needed — must-use plugins load automatically

Verify it loaded: **WP Admin → Plugins → Must-Use**. The plugin will appear listed without activate/deactivate controls.

## Usage

Start Mailpit before your dev session:

```bash
mailpit
```

Open the inbox at **http://localhost:8025**. Any email WordPress sends will appear there within seconds.

Test with WP-CLI:

```bash
wp eval 'wp_mail("test@example.com", "Test", "Mailpit is working.");'
```


## Configuration

Default settings route to `127.0.0.1:1025` with no authentication. To change the host or port, edit the constants at the top of `local-mail.php`:

```php
define( 'LOCAL_MAIL_HOST', '127.0.0.1' );
define( 'LOCAL_MAIL_PORT', 1025 );
```


## ⚠️ Local Development Only

This plugin is intended exclusively for local development environments. **Do not deploy to staging or production.** It disables SMTP authentication and encryption, meaning all mail will silently fail to reach real recipients.

Add `local-mail.php` to your `.gitignore` if your `mu-plugins` directory is tracked, or use a deployment script that excludes it.

## How It Works

The plugin hooks into `phpmailer_init`, which fires after WordPress initializes PHPMailer but before any message is sent. This is the correct placement — it runs inside the WordPress lifecycle unlike `wp-config.php` hooks, which fire before the hook system is available.

```php
add_action( 'phpmailer_init', function( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = LOCAL_MAIL_HOST;
    $phpmailer->Port       = LOCAL_MAIL_PORT;
    $phpmailer->SMTPAuth   = false;
    $phpmailer->SMTPSecure = '';
});
```


## Stack Context

Built for use with [Laravel Herd](https://herd.laravel.com/) + [DBngin](https://dbngin.com/) + [Mailpit](https://mailpit.axllent.org/) on macOS. Compatible with any local WordPress environment that can reach `127.0.0.1:1025`.

---
