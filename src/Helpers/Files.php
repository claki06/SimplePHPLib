<?php

    namespace Framework\Helpers;
    
    class Files{

        private function resolveTempKeys($tempContent, $keys){

            foreach($keys as $key => $value){
                if($key == "className"){
                    $tempContent = str_replace("{{className}}", $value, $tempContent);
                }
                if($key == "modelName"){
                    $tempContent = str_replace("{{modelName}}", $value, $tempContent);
                }
            }

            return $tempContent;

        }

        public function getBasePath(){
            return __DIR__ . '/../..';
        }

        public function removeExtension($fileName){
            return explode('.', $fileName)[0];
        }

        public function addExtension($fileName){
            return $fileName . ".php";
        }

        public function readDir($path){
            $fileNames = array_diff(scandir($this->getBasePath() . $path), array('..', '.'));
            $fileNamesWithoutExetensions = [];
            foreach($fileNames as $fileName){
                $fileNamesWithoutExetensions [] = $this->removeExtension($fileName);
            }

            return $fileNamesWithoutExetensions;
        }

        public function readDirWithExt($path){
            $fileNames = array_diff(scandir($this->getBasePath() . $path), array('..', '.'));
            return $fileNames;
        }



        public function makePath($path){
            return $this->getBasePath() . $path;
        }

        public function writeTableTemplateFile($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath('/app/Database/Tables/'). $fileName;
            $tableFile = fopen($path, "w");

            $tableFileTemplate = file_get_contents($this->makePath('/src/Templates/TableTemplate.temp'));

            fwrite($tableFile, $tableFileTemplate);
            
            fclose($tableFile);
        }

        public function writeModelTemplateFile($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Models/") . $fileName;

            $modelFile = fopen($path, "w");

            $modelFileTemplate = file_get_contents($this->makePath("/src/Templates/ModelTemplate.temp"));

            fwrite($modelFile, $this->resolveTempKeys($modelFileTemplate, array("className" => $fileNameWE)));

            fclose($modelFile);
        }

        public function writeFactoryTemplate($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Database/Factories/") . $fileName;
            $modelFile = fopen($path, "w");

            $modelFileTemplate = file_get_contents($this->makePath("/src/Templates/FactoryTemplate.temp"));
            
            fwrite($modelFile, $this->resolveTempKeys($modelFileTemplate, array("className" => $fileNameWE , "modelName" => $this->breakOnBigLetters($fileNameWE)[0])));

            fclose($modelFile);
        }

        public function writeControllerTemplate($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Controllers/" . $fileName);

            $controllerFile = fopen($path, "w");

            $controllerFileTemplate = file_get_contents($this->makePath("/src/Templates/ControllerTemplate.temp"));

            fwrite($controllerFile, $this->resolveTempKeys($controllerFileTemplate, array("className" => $fileNameWE)));

            fclose($controllerFile);
        }

        public function breakOnBigLetters($string){
            return preg_split("/\B(?=[A-Z])/", $string);
        }

        public function convertComponentToPath($componentName){

            return str_replace(".", "/", $componentName);

        }

    }

?>