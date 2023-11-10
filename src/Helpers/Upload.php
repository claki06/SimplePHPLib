<?php

    namespace Framework\Helpers;

    class Upload{

        public $extensions = null;
        public $path = "/app/Uploads/";
        public $maxFileSize = 6055360;

        public function upload($name){
        
    
            if(isset($_FILES[$name])){

                $fileController = new Files();
    
                $fileName = $_FILES[$name]["name"];
                $extension = pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
                $fileSize = $_FILES[$name]["size"];
                $tmpName = $_FILES[$name]["tmp_name"];

                if($this->extensions != null){
                    
                    if(!in_array($extension, $this->extensions)){
                        exit();
                    }

                }

                if($fileSize > $this->maxFileSize){
                    exit();
                }

                move_uploaded_file($tmpName, $fileController->makePath($this->path) . $fileName);
                echo "SUCCESS";
    
            }
    
            
    
    
    
        }

    }

?>