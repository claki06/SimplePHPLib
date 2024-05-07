<?php

    namespace Framework\Helpers;

    use Framework\Helpers\Files;

    class Download{


        /**
         * Path to Downloads folder
         * @var string
         */
        public $path = "/app/Downloads/";


        /**
         * Starts to download given file
         * @param string $filename: name of file to download
         */
        public function download($filename){

            $fileController = new Files();

            $filePath = $fileController->makePath($this->path) . $filename;
    
            if(file_exists($filePath)){
    
                $chunkSize = 5242880;
    
                $fileSize = intval(filesize($filePath));
    
                header('Content-Type: application/octet-stream');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.$fileSize);
                header('Content-Disposition: attachment;filename="'.basename($filename).'"');        
    
                if($fileSize > $chunkSize){
    
                    $handle = fopen($filePath, "rb");
    
                    while(!feof($handle)){
    
                        echo fread($handle, $chunkSize);
                        ob_flush();
                        flush();
                    }
    
                    fclose($handle);
    
                }
                else{
                    
                    echo readfile($filePath);
    
                    exit();
                }
    
            }else{
    
                echo "File .$filename doesn't exist";
    
                exit();
    
            }
        }

    }

?>