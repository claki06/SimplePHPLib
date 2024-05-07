<?php

    namespace Framework\Console;
    
    use Framework\Helpers\Files;

    class Input{
        

        /**
         * @var string[] array of args given in console
         */
        private $input = [];


        /**
         * @var Files
         */
        private $fileController;
        

        public function __construct($argv){

            array_shift($argv);

            $this->input = $argv;

            $this->fileController = new Files();

            $this->executeCommand($argv);
 
        }


        /**
         * Executes command with $input
         */
        public function executeCommand($argv){
            if($command = $this->validateInput()){
                require($this->fileController->getBasePath() . "/src/Executes/" . $command . ".php");
            }
            else{
                echo "This command doesn't exist! \n";
            }
        }


        /**
         * Checks if given commands exists
         * @return string $command if command exists 
         * @return null
         */
        private function validateInput(){

            $path = "/src/Executes";

            $command = "";

            $files = $this->fileController->readDir($path);

            $commandParts = explode(':', $this->input[0]);

            if(count($commandParts) > 1){
                $command = ucwords($commandParts[0]) . ucwords($commandParts[1]);
            }
            else{
                $command = ucwords($commandParts[0]);
            }

            foreach($files as $file){

                if($file == $command){

                    return $command;
                }
            }

            return null;

        }
    }

?>