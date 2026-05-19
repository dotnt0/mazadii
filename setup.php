<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          
define('DB_NAME', 'mazadi');

$log = []; 
$errors = []; 
function ok($m)  { global $log;    $log[]    = $m; }
function fail($m){ global $errors; $errors[] = $m; }

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';charset=utf8mb4', DB_USER, DB_PASS,
                   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `".DB_NAME."`");
    ok("Database '".DB_NAME."' created / verified.");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        username      VARCHAR(50)  UNIQUE NOT NULL,
        email         VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        is_admin      TINYINT(1)   DEFAULT 0,
        created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    ok("Table 'users' created.");

    $pdo->exec("CREATE TABLE IF NOT EXISTS auctions (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        title        VARCHAR(255)  NOT NULL,
        category     VARCHAR(100)  NOT NULL DEFAULT 'Other',
        description  TEXT          NOT NULL,
        location     VARCHAR(100)  NOT NULL,
        starting_bid DECIMAL(12,2) NOT NULL,
        current_bid  DECIMAL(12,2) NOT NULL,
        bid_count    INT           DEFAULT 0,
        seller_id    INT           NOT NULL,
        seller_name  VARCHAR(100)  NOT NULL,
        image_url    VARCHAR(500)  DEFAULT '',
        ends_at      DATETIME      NOT NULL,
        created_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
        status       ENUM('active','ended','removed') DEFAULT 'active',
        FOREIGN KEY (seller_id) REFERENCES users(id)
    ) ENGINE=InnoDB");
    ok("Table 'auctions' created.");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bids (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        auction_id INT           NOT NULL,
        user_id    INT           NOT NULL,
        username   VARCHAR(50)   NOT NULL,
        amount     DECIMAL(12,2) NOT NULL,
        created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (auction_id) REFERENCES auctions(id),
        FOREIGN KEY (user_id)    REFERENCES users(id)
    ) ENGINE=InnoDB");
    ok("Table 'bids' created.");

    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        auction_id    INT          NOT NULL,
        user_id       INT          DEFAULT NULL,
        reviewer_name VARCHAR(100) NOT NULL,
        rating        TINYINT      NOT NULL,
        body          TEXT         NOT NULL,
        created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (auction_id) REFERENCES auctions(id)
    ) ENGINE=InnoDB");
    ok("Table 'reviews' created.");

    $chk = $pdo->prepare('SELECT id FROM users WHERE username=?');
    $chk->execute(['admin']);
    if (!$chk->fetch()) {
        $ins = $pdo->prepare('INSERT INTO users (username,email,password_hash,is_admin) VALUES (?,?,?,1)');
        $ins->execute(['admin','admin@mazadi.sa', password_hash('admin123', PASSWORD_DEFAULT)]);
        ok("Admin user created. Username: <b>admin</b> | Password: <b>admin123</b>");
    } else { ok("Admin user already exists — skipped."); }

    $chk->execute(['mazadi_seller']);
    $row = $chk->fetch();
    if (!$row) {
        $ins = $pdo->prepare('INSERT INTO users (username,email,password_hash) VALUES (?,?,?)');
        $ins->execute(['mazadi_seller','seller@mazadi.sa', password_hash('seller123', PASSWORD_DEFAULT)]);
        $sellerId = $pdo->lastInsertId();
        ok("Demo seller created. Username: <b>mazadi_seller</b> | Password: <b>seller123</b>");
    } else { $sellerId = $row['id'];
    ok("Demo seller already exists — skipped."); }

    if ($pdo->query('SELECT COUNT(*) FROM auctions')->fetchColumn() == 0) {
        $ins = $pdo->prepare(
            'INSERT INTO auctions (title,category,description,location,starting_bid,current_bid,
             bid_count,seller_id,seller_name,image_url,ends_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)'
        );
        $samples = [
            ['1972 Ford Mustang – Classic Muscle Car','Vehicles',
             'A rare 1972 Ford Mustang in excellent condition. Original engine, restored interior, and a beautiful deep red exterior. Comes with full service history and a clean title. A genuine collector\'s piece.',
             'Riyadh, Saudi Arabia',30000,45000,12,
             'https://images.unsplash.com/photo-1672173625722-04fd626667e1?w=900&q=80',
             date('Y-m-d H:i:s', strtotime('+2 days'))],
            ['Rolex Submariner – Vintage 1989','Watches & Jewelry',
             'An authentic Rolex Submariner from 1989. Comes with original box, papers, and service records. The watch is in near-mint condition with original bracelet.',
             'Jeddah, Saudi Arabia',12000,18500,27,
             'https://images.unsplash.com/photo-1763189851330-23f36450bbde?w=900&q=80',
             date('Y-m-d H:i:s', strtotime('+5 hours'))],
            ['Apple MacBook Pro 16" M3 Max – Barely Used','Electronics',
             'Apple MacBook Pro 16-inch with the M3 Max chip, 64GB RAM, and 2TB SSD. Purchased 3 months ago, barely used. Still under Apple warranty. Comes with original charger and box.',
             'Dammam, Saudi Arabia',5500,7200,8,
             'https://images.unsplash.com/photo-1607603289612-71ae134aa577?w=900&q=80',
             date('Y-m-d H:i:s', strtotime('+1 day'))],
        ];
        foreach ($samples as $s) {
            $ins->execute([$s[0],$s[1],$s[2],$s[3],$s[4],$s[5],$s[6],$sellerId,'mazadi_seller',$s[7],$s[8]]);
        }
  
        ok("3 sample auctions inserted.");

        $ids = $pdo->query('SELECT id FROM auctions ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
        $rv  = $pdo->prepare('INSERT INTO reviews (auction_id,reviewer_name,rating,body) VALUES (?,?,?,?)');
        if (isset($ids[0])) {
            $rv->execute([$ids[0],'Faisal M.',5,'Incredible vehicle, the photos match perfectly. The seller is very responsive and honest about the condition.']);
            $rv->execute([$ids[0],'Omar K.',4,'Great classic car. Bidding process was smooth and fair. Highly recommend this auction.']);
        }
        if (isset($ids[1])) $rv->execute([$ids[1],'Sara Al-Otaibi',5,'Absolutely authentic, I had it verified by a certified watchmaker. Fantastic deal!']);
        if (isset($ids[2])) {
            $rv->execute([$ids[2],'Mohammed Al-Harbi',4,'Great condition, exactly as described. Shipping was fast too.']);
            $rv->execute([$ids[2],'Noura F.',5,'Unbelievable price for such a high-spec machine. Very happy with the purchase.']);
        }
        ok("Sample reviews inserted.");
    } else { ok("Auctions already exist — sample data skipped.");
    }

    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    if (!is_dir($dir)) { mkdir($dir, 0755, true); file_put_contents($dir.'.htaccess',"Options -Indexes\n"); ok("uploads/ directory created."); }
    else ok("uploads/ directory already exists.");
} catch (PDOException $e) { fail("Database error: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mazadi — Setup</title>
<style>
  body { font-family:Georgia,serif; max-width:700px; margin:60px auto; padding:0 20px; background:#f5f5f5; color:#333; }
  h1   { color:#1a6b8a; }
  .ok  { background:#d4edda; border:1px solid #c3e6cb; padding:10px 16px; border-radius:4px; margin:5px 0; color:#155724; }
  .err { background:#f8d7da; border:1px solid #f5c6cb; padding:10px 16px; border-radius:4px; margin:5px 0; color:#721c24; }
  .box { background:#fff3cd; border:1px solid #ffc107; padding:18px 22px; border-radius:4px; margin-top:24px; }
  code { background:#e8f4f8; padding:2px 6px; border-radius:3px; font-size:0.9rem; }
  a    { color:#1a6b8a; }
</style>
</head>
<body>
<h1>🛠️ Mazadi — Database Setup</h1>
<?php foreach ($log    as $m): ?><div class="ok">✅ <?= $m ?></div><?php endforeach; ?>
<?php foreach ($errors as $m): ?><div class="err">❌ <?= htmlspecialchars($m) ?></div><?php endforeach; ?>
<?php if (empty($errors)): ?>
<div class="box">
    <strong>⚠️ Setup complete! Next steps:</strong><br><br>
    1. <strong>Delete <code>setup.php</code></strong> — it is a security risk.<br>
    2. <a href="index.php"><strong>Open Mazadi →</strong></a><br><br>
    <strong>Test accounts:</strong><br>
    • Regular user: <code>mazadi_seller</code> / <code>seller123</code><br>
    • Admin: <code>admin</code> / <code>admin123</code>
</div>
<?php else: ?>
<div class="box">
    Setup had errors. Make sure:<br>
    • XAMPP MySQL is <strong>running</strong><br>
    • Credentials in <code>api/db.php</code> match your MySQL setup (default: root / no password)
</div>
<?php endif; ?>
</body>
</html>
