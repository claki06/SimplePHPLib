<?php

    namespace Framework\Helpers;

    class Upload{


        /**
         * @var string[]: Allowed extensions to be uploaded
         */
        public $extensions = null;


        /**
         * @var string: path to /app/Uploads
         */
        public $path = "/app/Uploads/";


        /**
         * @var int: max file size that can be uploaded to server
         */
        public $maxFileSize = 6055360;


        /**
         * Starts to upload file
         * @param string $name: form input name
         */
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