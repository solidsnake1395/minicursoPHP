<?php
$apiKey = 'b07e27c45d99002367aedb6d88980145';
$today = date('Y-m-d');

$url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&primary_release_date.gte=$today&primary_release_date.lte=$today&language=es-ES";

$response = file_get_contents($url);
$data = json_decode($response, true);

$moviesToday = $data['results'] ?? [];

$movies = [];
foreach ($moviesToday as $movie) {
    $movies[] = [
        'title' => $movie['title'],
        'overview' => $movie['overview'],
        'poster' => isset($movie['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : 'https://via.placeholder.com/300x450',
        'release_date' => $movie['release_date'],
        'language' => strtoupper($movie['original_language']),
        'vote' => $movie['vote_average']
    ];
}

$randomMovie = null;
if (!empty($movies)) {
    $randomMovie = $movies[array_rand($movies)];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cartelera de hoy</title>
<style>
body { font-family: Arial, sans-serif; background: #111; color: #eee; margin: 0; padding: 20px; }
h1, h2 { text-align: center; margin: 10px 0; }
.api-credit { text-align: center; margin-top: 20px; }
.api-credit img { width: 100px; vertical-align: middle; }
.recommendation { text-align: center; margin-bottom: 50px; }
.recommendation img { width: 300px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); transition: transform 0.3s; }
.recommendation img:hover { transform: scale(1.05); }
.recommendation .details { margin-top: 15px; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
.movie { background: #222; padding: 10px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.5); transition: transform 0.3s; }
.movie:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.7); }
.movie img { width: 100%; border-radius: 10px; margin-bottom: 10px; }
.movie h3 { margin: 5px 0; font-size: 1.1em; }
.movie p { font-size: 0.85em; color: #ccc; height: 60px; overflow: hidden; }
.movie .info { font-size: 0.8em; color: #aaa; margin-top: 5px; }
.section-title { margin-bottom: 20px; font-size: 1.5em; }
</style>
</head>
<body>

<?php if ($randomMovie): ?>
<div class="recommendation">
    <h1>🎬 Recomendación de hoy</h1>
    <p class="section-title">Estrenos del <?= date('d/m/Y') ?></p>
    <img src="<?= $randomMovie['poster'] ?>" alt="<?= htmlspecialchars($randomMovie['title']) ?>">
    <div class="details">
        <h2><?= htmlspecialchars($randomMovie['title']) ?></h2>
        <p><?= htmlspecialchars($randomMovie['overview']) ?></p>
        <div class="info">
            Idioma: <?= $randomMovie['language'] ?> | Estreno: <?= $randomMovie['release_date'] ?> | ⭐ <?= $randomMovie['vote'] ?>
        </div>
    </div>
</div>
<?php endif; ?>

<h2 class="section-title">Todos los estrenos de hoy</h2>
<div class="grid">
<?php foreach ($movies as $movie): ?>
    <div class="movie">
        <img src="<?= $movie['poster'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
        <h3><?= htmlspecialchars($movie['title']) ?></h3>
        <p><?= htmlspecialchars($movie['overview']) ?></p>
        <div class="info">
            Idioma: <?= $movie['language'] ?> | Estreno: <?= $movie['release_date'] ?> | ⭐ <?= $movie['vote'] ?>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="api-credit">
    <span>Datos proporcionados por </span>
    <a href="https://www.themoviedb.org/" target="_blank">
        <img src="https://www.themoviedb.org/assets/2/v4/logos/blue-square-6d4c2ee7df57f60a8f3b0d5c6c7e7b34d1ad97b3b3ef35a1ee7d7db08d6868f5.svg" alt="TMDb">
    </a>
</div>

</body>
</html>
