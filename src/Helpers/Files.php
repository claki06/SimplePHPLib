<?php

    namespace Framework\Helpers;
    
    class Files{


        /**
         * Resolves templates keywords with assoc array
         * @param string $tempContent: text content of template at /src/Templates/xTemplate.temp
         * @param assoc[string] $keys: assoc array with keys corresponding to template keywords
         *  and it values
         * @return string $tempContent: resolved $tempContent
         */
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


        /**
         * Returns base path (/) relative to project location
         * @return string
         */
        public function getBasePath(){
            return __DIR__ . '/../..';
        }


        /**
         * Removes extension from $filename
         * @param string $fileName: filename with extension
         * @return string
         */
        public function removeExtension($fileName){
            return explode('.', $fileName)[0];
        }


        /**
         * Adds php extension to filename
         * @param string $fileName: filename without extension
         * @return string
         */
        public function addExtension($fileName){
            return $fileName . ".php";
        }


        /**
         * Reads directory files without extensions
         * @param string $path: path to directory
         * @return string[]: filenames of directory without extensions
         */
        public function readDir($path){
            $fileNames = array_diff(scandir($this->getBasePath() . $path), array('..', '.'));
            $fileNamesWithoutExetensions = [];

            foreach($fileNames as $fileName){
                $fileNamesWithoutExetensions [] = $this->removeExtension($fileName);
            }

            return $fileNamesWithoutExetensions;
        }


        /**
         * Reads directory files with extensions
         * @param string $path: path to directory
         * @return string[]: filenames of directory
         */
        public function readDirWithExt($path){
            $fileNames = array_diff(scandir($this->getBasePath() . $path), array('..', '.'));
            return $fileNames;
        }


        /**
         * Makes path relative to project location
         * @param string $path: path INSIDE project directory
         * @return string
         */
        public function makePath($path){
            return $this->getBasePath() . $path;
        }


        /**
         * Makes table file in /app/Tables and writes table template to it
         * @param string $fileNameWE: file name without extension (table name)
         */
        public function writeTableTemplateFile($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath('/app/Database/Tables/'). $fileName;
            $tableFile = fopen($path, "w");

            $tableFileTemplate = file_get_contents($this->makePath('/src/Templates/TableTemplate.temp'));

            fwrite($tableFile, $tableFileTemplate);
            
            fclose($tableFile);
        }


        /**
         * Makes model file in /app/Models and write model template to it
         * @param string $fileNameWE: file name without extension (model name)
         */
        public function writeModelTemplateFile($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Models/") . $fileName;

            $modelFile = fopen($path, "w");

            $modelFileTemplate = file_get_contents($this->makePath("/src/Templates/ModelTemplate.temp"));

            fwrite($modelFile, $this->resolveTempKeys($modelFileTemplate, array("className" => $fileNameWE)));

            fclose($modelFile);
        }


        /**
         * Makes factory file in /app/Factories and write factory template to it
         * @param string $fileNameWE: file name without extension (factory name)
         */
        public function writeFactoryTemplate($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Database/Factories/") . $fileName;
            $modelFile = fopen($path, "w");

            $modelFileTemplate = file_get_contents($this->makePath("/src/Templates/FactoryTemplate.temp"));
            
            fwrite($modelFile, $this->resolveTempKeys($modelFileTemplate, array("className" => $fileNameWE , "modelName" => $this->breakOnBigLetters($fileNameWE)[0])));

            fclose($modelFile);
        }


        /**
         * Makes controller file in /app/Controllers and writes controller template to it
         * @param string $fileNameWE: file name without extension (controller name)
         */
        public function writeControllerTemplate($fileNameWE){
            $fileName = $this->addExtension($fileNameWE);
            $path = $this->makePath("/app/Controllers/" . $fileName);

            if(!file_exists($this->makePath("/app/Controllers"))){
                mkdir($this->makePath("/app/Controllers"));
            }

            $controllerFile = fopen($path, "w");

            $controllerFileTemplate = file_get_contents($this->makePath("/src/Templates/ControllerTemplate.temp"));

            fwrite($controllerFile, $this->resolveTempKeys($controllerFileTemplate, array("className" => $fileNameWE)));

            fclose($controllerFile);
        }


        /**
         * Breaks string into string array on bil letters
         * @param string $string: string to break
         * @return string[]: string array of broken string
         */
        public function breakOnBigLetters($string){
            return preg_split("/\B(?=[A-Z])/", $string);
        }


        /**
         * Converts html tag to components path
         * @param string $componentName: html tag (component tag)
         * @return string: path to component
         */
        public function convertComponentToPath($componentName){

            return str_replace(".", "/", $componentName);

        }


        /**
         * Writes page paths and last change time in /src/Static/List.stc of pages that are 
         * statically generated
         * @param string[] $pagePaths: paths to all pages (static generation)
         */
        public function addStaticFiles($pagePaths){

            $listPath = $this->makePath("/src/Static/List.stc");

            $listStream = fopen($listPath, "a+");

            $listData = file_get_contents($listPath);

            foreach($pagePaths as $path){
                if(str_contains($listData, $path)){
                    continue;
                }else{
                    $timem = filemtime($this->makePath("/app/Pages/$path.php"));
                    fwrite($listStream, "$path $timem\n");
                }
            }

            fclose($listStream);
        }


        /**
         * Writes page content to /src/Static/Pages/x-static.php
         * @param string $page: page name
         * @param string $data: page content
         */
        public function writeToStaticPages($page, $data){
            $path = $this->makePath("/src/Static/Pages/$page-static.php");

            $pageStream = fopen($path, "w");
            
            fwrite($pageStream, $data);

            fclose($pageStream);
        }


        /**
         * Reads all files inside folder structure
         * @param string $path: beginning folder
         * @return string[]: string array of all files inside folder strucutre
         */
        public function analyseFolderStructure($path){    
            
            $folderContent = $this->readDirWithExt($path);

            $phpFile = array();

            foreach($folderContent as $fileName){
                if(!str_contains($fileName, ".")){
                    $phpFile = array_merge($phpFile, $this->analyseFolderStructure("$path/$fileName"));
                }
                else{
                    array_push($phpFile, explode(".", $fileName)[0]);
                }
            }

            return $phpFile;
        }

    }



?>