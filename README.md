# Client Portal

A small site where customers can follow the progress of their project.

## Features

- Project overview with a progress bar
- Milestones that can be ticked off
- Status and "waiting on customer" messages
- Updates posted to the customer
- A private link per project, no login needed

## Tech

- PHP 8.3 + Apache (Docker)
- MariaDB
- HTML, CSS, and JavaScript

## Getting started

1. Copy `api/config.example.php` to `api/config.php` and add your database password.
2. Start the containers with `docker compose up`.
3. Open `http://localhost:8080/admin/new-project.php` and create a project.
