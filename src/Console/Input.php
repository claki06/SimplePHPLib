<?php

    namespace Framework\Console;
    
    use Framework\Helpers\Files;

    class Input{
        
        /**
         * Array of arguments
         */
        private $input = [];

        private $fileController;
        
        public function __construct($argv){

            array_shift($argv);

            $this->input = $argv;

            $this->fileController = new Files();

            if($command = $this->validateInput()){
                require($this->fileController->getBasePath() . "/src/Executes/" . $command . ".php");
            }
            else{
                echo "This command doesn't exist! \n";
            }
            
        }

        private function validateInput(){

            $path = "/src/Executes";

            $files = $this->fileController->readDir($path);

            $commandParts = explode(':', $this->input[0]);

            $command = ucwords($commandParts[0]) . ucwords($commandParts[1]);

            foreach($files as $file){

                if($file == $command){

                    return $command;
                }
            }

            return null;

        }
    }

?>