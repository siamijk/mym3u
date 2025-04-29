<?php
$url = $_GET['url'] ?? '';
$group = $_GET['group'] ?? '';
$name = $_GET['name'] ?? 'Now Playing';

// Load playlist
$playlistUrl = "https://raw.githubusercontent.com/siamijk/mym3u/refs/heads/main/bdix.m3u8";
$playlist = @file_get_contents($playlistUrl);

preg_match_all('/#EXTINF:-1.*?tvg-name="(.*?)".*?tvg-logo="(.*?)".*?group-title="(.*?)".*?,(.*?)\n(.*?)\n/', $playlist, $matches);

$channels = [];
foreach ($matches[1] as $i => $match) {
    $channels[] = [
        'name' => $matches[1][$i],
        'logo' => $matches[2][$i],
        'group' => $matches[3][$i],
        'title' => $matches[4][$i],
        'url' => $matches[5][$i],
    ];
}

$suggested = array_filter($channels, fn($ch) => $ch['group'] === $group && $ch['url'] !== $url);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($name) ?>OPENFLIX</title>
  <link href="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.min.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #000;
      color: #fff;
    }
    header {
      background: #000;
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo {
      font-size: 1.5em;
      font-weight: bold;
      cursor: pointer;
    }
    .video-wrapper {
      display: flex;
      justify-content: center;
      padding: 0 10px;
      margin-top: 20px;
    }
    .video-container {
      width: 100%;
      max-width: 900px;
    }
    .plyr__video-embed iframe {
      width: 100% !important;
      height: auto !important;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.6);
    }
    .suggested-section {
      margin: 20px;
    }
    .suggested-title {
      font-size: 1.2em;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .suggested-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
      gap: 15px;
    }
    .suggested-card {
      background: #111;
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      text-decoration: none;
    }
    .suggested-card img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 8px;
      background: white;
    }
    .suggested-card p {
      margin: 0;
      font-size: 0.9em;
      font-weight: bold;
      color: #ddd;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo" onclick="location.href='index.php'">^BackTo~OPENFLIX</div>
  </header>

  <div class="video-wrapper">
    <div class="video-container">
      <video id="player" controls autoplay>
        <source src="<?= htmlspecialchars($url) ?>" type="application/x-mpegURL" />
        Your browser does not support the video tag.
      </video>
    </div>
  </div>

  <?php if (count($suggested)): ?>
  <div class="suggested-section">
    <div class="suggested-title">More in <?= htmlspecialchars($group) ?></div>
    <div class="suggested-grid">
      <?php foreach ($suggested as $ch): ?>
        <a class="suggested-card" href="player.php?url=<?= urlencode($ch['url']) ?>&group=<?= urlencode($ch['group']) ?>&name=<?= urlencode($ch['name']) ?>">
          <img src="<?= htmlspecialchars($ch['logo']) ?>" alt="<?= htmlspecialchars($ch['name']) ?>">
          <p><?= htmlspecialchars($ch['name']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <script>
    const player = new Plyr('#player', {
      captions: { active: true },
      quality: { default: 576, options: [144, 240, 360, 480, 576, 720, 1080] },
    });

    // Handle errors gracefully
    player.on('error', event => {
      console.error('An error occurred while playing the video:', event.detail);
      alert('An error occurred. Please try again later.');
    });
  </script>
</body>
</html>