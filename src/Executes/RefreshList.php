<?php

    use Framework\Helpers\Files;

    array_shift($argv);

    $fileContrller = new Files();

    $listPath = $fileContrller->makePath("/src/Static/List.stc");

    $allFiles = $fileContrller->analyseFolderStructure("/app/Pages");

    $stream = fopen($listPath, "r");

    $dataToWrite = "";
  
    while(!feof($stream)){

        $deleteFile = true;

        $line = fgets($stream);

        $lineParts = explode(" ", $line);

        foreach($allFiles as $file){

            if(str_contains($line, $file) || $line == ""){
                
                if($deleteFile) $deleteFile = false;
                $dataToWrite .= $line;
            }
        }

        if($deleteFile){
            if(str_contains($lineParts[0],"components")) continue;
            unlink($fileContrller->makePath("/src/Static/Pages/$lineParts[0]-static.php"));
        } 
        

    }

    fclose($stream);

    $stream = fopen($listPath, "w");

    fwrite($stream, $dataToWrite);

    fclose($stream);
?>