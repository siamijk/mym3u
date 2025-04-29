<?php
$playlistUrl = "https://raw.githubusercontent.com/siamijk/mym3u/refs/heads/main/bdix.m3u8";
$playlist = @file_get_contents($playlistUrl);

if (!$playlist) {
    die("Failed to load playlist.");
}

preg_match_all('/#EXTINF:-1.*?tvg-name="(.*?)".*?tvg-logo="(.*?)".*?group-title="(.*?)".*?,(.*?)\n(.*?)\n/', $playlist, $matches);

$channels = [];
for ($i = 0; $i < count($matches[0]); $i++) {
    $channels[] = [
        'name' => $matches[1][$i],
        'logo' => $matches[2][$i],
        'group' => $matches[3][$i],
        'title' => $matches[4][$i],
        'url' => $matches[5][$i],
    ];
}

$grouped = [];
$groups = [];
foreach ($channels as $ch) {
    $grouped[$ch['group']][] = $ch;
    $groups[] = $ch['group'];
}
$groups = array_unique($groups);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OPENFLIX</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      background-color: #000;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      transition: background-color 0.3s, color 0.3s;
    }

    /* Dark Mode by Default */
    body.light-theme {
      background-color: #f4f4f4;
      color: #111;
    }

    /* Top Navigation */
    header {
      background: #000;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .logo {
      color: white;
      font-weight: bold;
      font-size: 1.5em;
      cursor: pointer;
    }
    .theme-toggle, .menu-toggle {
      background: none;
      border: none;
      color: white;
      font-size: 20px;
      cursor: pointer;
    }

    /* Category Navigation */
    nav {
      display: none;
      flex-direction: column;
      gap: 10px;
      padding: 10px 20px;
      background: #111;
      margin: 10px 0;
    }

    /* Top Nav background based on theme */
    body.light-theme nav {
      background: #f4f4f4;
    }

    nav.show {
      display: flex;
    }

    /* Navigation Links */
    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
    }

    /* Adjust link color based on theme */
    body.light-theme nav a {
      color: #111;  /* Dark text color on light theme */
    }

    /* Desktop Version of Category Navigation */
    @media (min-width: 600px) {
      nav {
        flex-direction: row;
        display: flex !important;
        background: none;
        padding: 0;
        justify-content: center;
      }

      body.light-theme nav {
        background: #f4f4f4;
      }
    }

 .search-bar {
  width: 100%;
  max-width: 600px;
  margin: 10px auto;
  padding: 0 10px;
  box-sizing: border-box;
  position: relative;
}



   .search-bar input {
  width: 100%;
  padding: 12px 40px 12px 20px;
  border-radius: 50px;
  border: none;
  outline: none;
  background: #222;
  color: white;
  font-size: 16px;
  box-sizing: border-box;
}


    .search-bar i {
      position: absolute;
      right: 30px;
      top: 14px;
      color: red;
      font-size: 18px;
    }

    /* Section Title */
    .section-title {
      font-size: 1.5em;
      margin: 30px 20px 10px;
      border-left: 5px solid red;
      padding-left: 10px;
      display: flex;
      align-items: center;
      font-weight: bold;
    }

    .section-title::after {
      content: "";
      flex-grow: 1;
      height: 2px;
      background: red;
      margin-left: 10px;
    }

    /* Channel Card */
    .channel-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
      gap: 15px;
      padding: 10px 20px;
    }

    .channel-card {
      background: #111;
      border-radius: 12px;
      text-align: center;
      padding: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .channel-card img {
      width: 60px;
      height: 60px;
      background: white;
      border-radius: 50%;
      padding: 5px;
      object-fit: cover;
    }

.channel-card a {
  text-decoration: none;
  color: inherit; /* Ensures it uses the parent color */
}


    .channel-card p {
      margin-top: 8px;
      color: #ddd;
      font-weight: bold;
      text-decoration: none;
    }

    /* See More Button */
    .see-more-btn {
      display: block;
      margin: 10px auto;
      padding: 8px 16px;
      background-color: red;
      color: white;
      border: none;
      border-radius: 20px;
      font-weight: bold;
      cursor: pointer;
    }

    .hidden-channel {
      display: none;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 20px;
      font-size: 0.9em;
      background: #111;
      color: #aaa;
    }

    /* Back to Top Button */
    #backToTop {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: red;
      color: white;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      font-size: 18px;
      font-weight: bold;
      display: none;
      justify-content: center;
      align-items: center;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-left">
      <div class="logo" onclick="location.href='index.php'">OPENFLIX</div>
    </div>
    <div>
      <button class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
      <button class="theme-toggle" onclick="toggleTheme()"><i class="fas fa-moon"></i></button>
    </div>
  </header>
  <nav id="topNav">
    <?php foreach ($groups as $grp): ?>
      <a href="#<?= rawurlencode($grp) ?>"><?= htmlspecialchars($grp) ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="search-bar">
    <input type="text" id="searchInput" onkeyup="filterChannels()" placeholder="Search Channels">
    <i class="fas fa-search"></i>
  </div>
  <main>
    <?php foreach ($grouped as $group => $chans): ?>
      <div class="section-title" id="<?= htmlspecialchars($group) ?>"><?= strtoupper($group) ?></div>
      <div class="channel-grid">
        <?php foreach ($chans as $i => $ch): ?>
          <div class="channel-card <?= $i >= 15 ? 'hidden-channel' : '' ?>" data-name="<?= strtolower($ch['name']) ?>">
            <a href="player.php?url=<?= urlencode($ch['url']) ?>&group=<?= urlencode($ch['group']) ?>">
              <img src="<?= $ch['logo'] ?>" alt="<?= $ch['name'] ?>">
              <p><?= htmlspecialchars($ch['name']) ?></p>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if (count($chans) > 15): ?>
        <button class="see-more-btn" onclick="this.previousElementSibling.querySelectorAll('.hidden-channel').forEach(el => el.style.display='block'); this.style.display='none';">See More</button>
      <?php endif; ?>
    <?php endforeach; ?>
  </main>
 <footer style="text-align: center; padding: 30px 20px; font-size: 1em; background: linear-gradient(45deg, #2b2d42, #8d99ae); color: #fff; border-top: 2px solid #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); font-family: 'Segoe UI', sans-serif;">
  <p style="font-weight: 600; font-size: 1.2em; text-transform: uppercase; letter-spacing: 2px;">BY <a href="https://t.me/etcvai" target="_blank" style="color: #f1c40f; text-decoration: none; font-weight: bold; transition: color 0.3s ease;">ETCVAI</a></p>
</footer>

  <button id="backToTop" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">↑</button>
  <script>
    const topNav = document.getElementById('topNav');
    function toggleMenu() {
      topNav.classList.toggle('show');
    }
    function toggleTheme() {
      document.body.classList.toggle('light-theme');
    }
    function filterChannels() {
      const value = document.getElementById('searchInput').value.toLowerCase();
      document.querySelectorAll('.channel-card').forEach(card => {
        const name = card.getAttribute('data-name');
        card.style.display = name.includes(value) ? 'flex' : 'none';
      });
    }
    window.addEventListener('scroll', () => {
      document.getElementById('backToTop').style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
  </script>
</body>
</html>
