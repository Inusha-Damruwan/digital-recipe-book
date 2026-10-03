<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/page-layout.php';
require_once __DIR__ . '/includes/db.php';

$featuredTitles = [
    'Creamy Garden Pasta',
    'Berry Morning Bowl',
    'Green Goddess Salad',
    'Sunday Lemon Cake',
    'Avocado Toast',
    'Roasted Veggie Bowl',
    'Tomato Basil Pasta',
    'Strawberry Shortcake',
];
$featuredDescriptions = [
    'Creamy Garden Pasta' => 'Comfort in a bowl, with a fresh little twist.',
    'Berry Morning Bowl' => 'A bright start with berries, oats and a little crunch.',
    'Green Goddess Salad' => 'Crisp, colorful and tossed in a herby dressing.',
    'Sunday Lemon Cake' => 'Soft, sunny and just sweet enough to share.',
    'Avocado Toast' => 'Creamy avocado, crisp toast and fresh herbs.',
    'Roasted Veggie Bowl' => 'A colorful bowl packed with fresh vegetables.',
    'Tomato Basil Pasta' => 'Simple tomato sauce with fragrant basil.',
    'Strawberry Shortcake' => 'Soft cake layered with cream and berries.',
];
$placeholders = implode(',', array_fill(0, count($featuredTitles), '?'));
$stmt = $pdo->prepare(
    "SELECT id, title, category, cooking_time, image_url FROM recipes
     WHERE title IN ($placeholders)
     ORDER BY FIELD(title, " . implode(',', array_fill(0, count($featuredTitles), '?')) . ')'
);
$stmt->execute(array_merge($featuredTitles, $featuredTitles));
$featuredRecipes = $stmt->fetchAll();

start_app_session();
require __DIR__ . '/index.html';
