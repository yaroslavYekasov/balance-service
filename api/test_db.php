<?php
//start php server - go to folder -> php -S localhost:8000
// test db connection - http://localhost:8000/api/test_db.php

include '../config/database.php';

echo json_encode(["success" => "Database connected successfully"]);
?>
