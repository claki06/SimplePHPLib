<?php

    use Framework\Helpers\Files;


    $fileController = new Files();

    $path = $fileController->makePath("/public/");

    $output = shell_exec("php -S localhost:" . ($argv[1] ?? '8000') ." -t " . $path);

    echo $output;

?>