<?php

echo "getenv: " . getenv('DB_PASSWORD') . PHP_EOL;
echo "_ENV: " . ($_ENV['DB_PASSWORD'] ?? 'NOT FOUND') . PHP_EOL;
echo "_SERVER: " . ($_SERVER['DB_PASSWORD'] ?? 'NOT FOUND') . PHP_EOL;
