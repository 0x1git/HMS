<?php include 'config.php'; echo 'Database status: ' . (mysqli_ping() ? 'Connected' : 'Disconnected') . PHP_EOL; echo 'MySQL Version: ' . mysqli_get_server_info() . PHP_EOL; ?>
