<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
start_app_session();

$action = $_GET['action'] ?? '';
$data = request_data();

if ($action === 'recipes') {
    require_once __DIR__ . '/includes/db.php';
    $stmt = $pdo->query('SELECT r.id, r.title, r.category, r.ingredients, r.instructions, r.cooking_time, r.image_url, u.username FROM recipes r JOIN users u ON u.id = r.user_id ORDER BY r.created_at DESC, r.id DESC');
    json_response(['success' => true, 'recipes' => $stmt->fetchAll()]);
}

require_once __DIR__ . '/includes/db.php';

if ($action === 'me') {
    json_response(['success' => true, 'user' => current_user($pdo)]);
}
if ($action === 'register') {
    $username = clean_text($data['username'] ?? '');
    $email = strtolower(clean_text($data['email'] ?? ''));
    $password = (string)($data['password'] ?? '');
    if (mb_strlen($username) < 3 || mb_strlen($username) > 50) json_response(['success'=>false,'message'=>'Username must be 3–50 characters.'], 422);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_response(['success'=>false,'message'=>'Enter a valid email address.'], 422);
    if (strlen($email) > 190) json_response(['success'=>false,'message'=>'Email address must be 190 characters or fewer.'], 422);
    if (strlen($password) < 8) json_response(['success'=>false,'message'=>'Password must contain at least 8 characters.'], 422);
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) json_response(['success'=>false,'message'=>'That email or username is already registered.'], 409);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username,email,password) VALUES (?,?,?)');
    $stmt->execute([$username,$email,$hash]);
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$pdo->lastInsertId();
    json_response(['success'=>true,'user'=>current_user($pdo)]);
}
if ($action === 'login') {
    $email = strtolower(clean_text($data['email'] ?? ''));
    $password = (string)($data['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) json_response(['success'=>false,'message'=>'Incorrect email or password.'], 401);
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    json_response(['success'=>true,'user'=>current_user($pdo)]);
}
if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    json_response(['success'=>true]);
}
if ($action === 'add_recipe') {
    $user = current_user($pdo);
    if (!$user) json_response(['success'=>false,'message'=>'Please log in before adding a recipe.'], 401);
    $title = clean_text($data['title'] ?? '');
    $category = clean_text($data['category'] ?? '');
    $time = filter_var($data['cooking_time'] ?? null, FILTER_VALIDATE_INT);
    $ingredients = clean_text($data['ingredients'] ?? '');
    $instructions = clean_text($data['instructions'] ?? '');
    $image = clean_text($data['image_url'] ?? '');
    $allowed = ['Breakfast','Lunch','Dinner','Dessert','Snack','Drinks'];
    if ($title === '' || mb_strlen($title)>150) json_response(['success'=>false,'message'=>'Enter a recipe title (up to 150 characters).'],422);
    if (!in_array($category,$allowed,true)) json_response(['success'=>false,'message'=>'Choose a valid category.'],422);
    if (!$time || $time<1 || $time>1440) json_response(['success'=>false,'message'=>'Cooking time must be between 1 and 1440 minutes.'],422);
    if ($ingredients === '' || $instructions === '') json_response(['success'=>false,'message'=>'Ingredients and instructions are required.'],422);
    if ($image !== '' && (strlen($image) > 2048 || !filter_var($image,FILTER_VALIDATE_URL) || !in_array(parse_url($image,PHP_URL_SCHEME),['http','https'],true))) json_response(['success'=>false,'message'=>'Image URL must be a valid http or https URL up to 2048 characters.'],422);
    $stmt=$pdo->prepare('INSERT INTO recipes (user_id,title,category,ingredients,instructions,cooking_time,image_url) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([$user['id'],$title,$category,$ingredients,$instructions,$time,$image ?: null]);
    json_response(['success'=>true,'message'=>'Recipe added.']);
}
if ($action === 'contact') {
    $name=clean_text($data['name'] ?? '');
    $email=strtolower(clean_text($data['email'] ?? ''));
    $message=clean_text($data['message'] ?? '');
    if ($name==='' || mb_strlen($name)>100) json_response(['success'=>false,'message'=>'Enter your name (up to 100 characters).'],422);
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) json_response(['success'=>false,'message'=>'Enter a valid email address.'],422);
    if (strlen($email) > 190) json_response(['success'=>false,'message'=>'Email address must be 190 characters or fewer.'],422);
    if (mb_strlen($message)<10 || mb_strlen($message)>3000) json_response(['success'=>false,'message'=>'Message must be 10–3000 characters.'],422);
    $stmt=$pdo->prepare('INSERT INTO messages (name,email,message) VALUES (?,?,?)');
    $stmt->execute([$name,$email,$message]);
    json_response(['success'=>true]);
}
json_response(['success'=>false,'message'=>'Unknown action.'],404);
