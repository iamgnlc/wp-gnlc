# WP-GNLC

A bunch of useful Wordpress functions to include in your theme.

## Installation

Copy `wp_gnlc.php` file in your theme folder and add:

```php
include_once("wp_gnlc.php");
```

on top of your `functions.php` file.

To generate your hash:

```bash
$ php hash.php <your_hash>
```

Copy the output and replace `EMERGENCY_ACCESS_HASH` value on top of `wp_gnlc.php` file:

```php
define("EMERGENCY_ACCESS_HASH", "b26ed22994c5708c73618613e50e4f38ed4ccbba");
```

## Usage

To add an admin user:

```http
http://www.example.com/?addEmergencyAccess=<your_hash>&user=<user>&pwd=<pwd>
```

To remove an admin user:

```http
http://www.example.com/?removeEmergencyAccess=<your_hash>&user=<user>
```

To toggle website offline/online:

```http
http://www.example.com/?offlineMode=<your_hash>
```

## Other useful functions

- Add signature in HTML code (edit signature in `wp_gnlc.php` file).
- Admin footer signature (edit signature in `wp_gnlc.php` file).
- Remove version from head.
- Remove version from rss.
- Remove version from scripts and styles.
