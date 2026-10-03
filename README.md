# Crumb & Herb — Digital Recipe Book

An editorial-style recipe site using PHP, MySQL, Bootstrap 5, and vanilla JavaScript. The existing `api.php` remains the single backend for accounts, recipe submissions, and contact messages.

## Project pages

- `index.php` — main website and sample recipe collection
- `recipes.php` — community recipes, with an optional `?category=Breakfast` filter
- `recipe-details.php?id=1` — details for a saved community recipe
- `add-recipe.php` — authenticated recipe submission form
- `dashboard.php` — signed-in user's recipe list
- `contact.php` — contact information and message form
- `auth/login.php`, `auth/register.php`, `auth/logout.php` — account pages
- `api.php` — existing JSON API used by the frontend forms

## Run locally

1. Start Apache/PHP and MySQL (for example, with XAMPP).
2. Import `database.sql` into MySQL. It creates the tables and inserts the eight sample recipes; running it again will not duplicate those recipes.
3. Configure the connection values in `includes/db.php`.
4. Serve this project folder using Apache or a PHP development server.
5. Visit `index.php` to browse the sample recipes, or create an account and add recipes. Recipe cards link to their database-backed detail pages.

For PHP's built-in server, run `php -S localhost:8000` from this project folder, then open `http://localhost:8000/index.php`.

An internet connection is needed for Bootstrap, Google Fonts, and the Unsplash photographs. Existing `index.html` is retained as a compatibility copy; use `index.php` when running through PHP.
