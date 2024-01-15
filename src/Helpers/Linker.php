<?php

    namespace Framework\Helpers;

    use Framework\Helpers\Files;
    use Framework\Helpers\RegexHelper;

    class Linker{

        private $fileController;
        private $static = false;
        private $allFiles = array();

        public function __construct()
        {
            $this->fileController = new Files();

        }

        public function link($page, $data, $pageName){
    
            $componentsPath = $this->fileController->makePath("/app/Pages/components");


            if(str_contains($page, "+=STATIC=+")){

                $page = str_replace("+=STATIC=+", "", $page);

                $this->static = true;

                if(!in_array($pageName, $this->allFiles)){
                    array_push($this->allFiles, $pageName);
                }

            }

            if(!$this->shouldCompile($pageName)){

                $preCompiledPagePath = $this->fileController->makePath("/src/Static/Pages/$pageName-static.php");

                return file_get_contents($preCompiledPagePath);
            }

            if(file_exists($componentsPath)){
                $page = $this->resolveComponents($page);
            }

            $page = $this->resolvePageParams($page, $data);

            $page = $this->resolvePageConditions($page, $data);

            $this->ifIsStatic($pageName, $page);

            return $page;
        }

        private function shouldCompile($pageName){
            
            $compile = false;

            $listPath = $this->fileController->makePath("/src/Static/List.stc");

            if(!$this->static) return true;

            if(!str_contains(file_get_contents($listPath), $pageName)){
                return true;
            };

            $listStream = fopen($listPath, "r+");

            $lastPos = 0;

            while(!feof($listStream)){

                $parts = explode(" ",fgets($listStream));

                if($parts[0] == ""){
                    break;
                }

                $path = $this->fileController->makePath("/app/Pages/$parts[0].php");
                
                if(file_exists($path)){
                    
                    if(filemtime($path) > intval($parts[1])){

                        if(!$compile) $compile = true;
    
                        fseek($listStream, $lastPos);
                        
                        fwrite($listStream, "$parts[0] " . filemtime($path) . "\n");
                    }

                }
                

                $lastPos = ftell($listStream);
            }

            fclose($listStream);

            return $compile;

        }

        private function resolvePageConditions($page, $data){

            if($data != null){
                extract($data);
            }

            $ifMatches = [];
            $foreachMatches = [];
            $errorMatches = [];

            preg_match_all("/@if([\p{Any}]+?)\)[\n\r\t]/", $page, $ifMatches);
            preg_match_all("/@foreach([\p{Any}]+?)\)[\n\r\t]/", $page, $foreachMatches);
            preg_match_all("/(@error\(\"([^*]+?)\"\)([^*]+?)@enderror)/", $page, $errorMatches);


            for($i = 0; $i < count($ifMatches[1]); $i++){
                $ifMatches[1][$i] .= ")";

                $page = str_replace("@if" . $ifMatches[1][$i], "<?php if" . $ifMatches[1][$i] . ": ?>", $page);
            }

            for($i = 0; $i < count($foreachMatches[1]); $i++){

                $foreachMatches[1][$i] .= ")";


                $page = str_replace("@foreach" . $foreachMatches[1][$i], "<?php foreach" . $foreachMatches[1][$i] . ": ?>", $page);
            }

            for($i = 0; $i < count($errorMatches[1]); $i++){

                if(isset($_SESSION['VALIDATION_FAILS'][$errorMatches[2][$i]])){
                    $errorMatches[3][$i] = str_replace("{{\$message}}", "<?php echo \$_SESSION['VALIDATION_FAILS']['" . $errorMatches[2][$i] . "'] ?>", $errorMatches[3][$i]);
                    $page = str_replace($errorMatches[0][$i], $errorMatches[3][$i], $page);
                }
                else{
                    $page = str_replace($errorMatches[0][$i], "", $page);
                }


            }

            $page = str_replace("@else", "<?php else: ?>", $page);

            $page = str_replace("@endif", "<?php endif; ?>", $page);

            $page = str_replace("@endforeach", "<?php endforeach; ?>", $page);   
            
            return $page;


        }

        private function resolveComponents($page){

            $matches = array();

            preg_match_all("/<x-([^<>]+)>/", $page, $matches);
            
            for($i = 0; $i < count($matches[1]); $i++){

                $match = $matches[1][$i];


                $componentName = $this->extractComponentName($match);


                $componentArgs = $this->extractComponentAttributes($match, $componentName, $page);


                $page = $this->resolveComponentString($match, $componentArgs, $componentName, $page);

            }

            return $page;
            
        }


        private function resolveComponentParams($componentString, $params){

            foreach($params as $key => $match){
                
                $stringToChange = "";

                if(str_contains($match, "$") || $key != "children"){
                
                    $stringToChange = "$" . $key;
                    $componentString = str_replace($stringToChange, $match , $componentString);
                
                }else{

                    $stringToChange = "{{\$$key}}";
                    $componentString = str_replace($stringToChange, $match , $componentString);
                
                }

            }

            return $componentString;

        }

        private function resolvePageParams($page, $data){

            if($data != null){

                extract($data);

            }

            $matches = array();

            preg_match_all("/{{([^{}]+)}}/", $page, $matches);

            for($i = 0; $i < count($matches[1]); $i++){

                $match = $matches[1][$i];

                $stringToSearch = "{{" . $match . "}}";

                $page = str_replace($stringToSearch, "<?php echo $match; ?>", $page);
                
            }

            return $page;
        }

        private function extractComponentAttributes($match, $componentName, $page){

            $searchResult = array();

            preg_match_all("/\b(\w+)=['\"]([\w ]+)['\"]/", $match, $searchResult);
            
            $componentArgs = array_combine($searchResult[1], $searchResult[2]);

            preg_match_all("/:([\w]+)={{([$][\w]+)}}/", $match, $searchResult);

            $componentArgs = array_merge($componentArgs, array_combine($searchResult[1], $searchResult[2]));

            $temp = str_replace("/", '.', $componentName);

            $pattern = RegexHelper::preparePattern($match);

            if(preg_match("/<x-$pattern>([\p{Any}]*?)<\/x-$temp>/", $page, $searchResult)){
                $componentArgs["children"] = $searchResult[1];
            }
            else{
                $componentArgs["children"] = null;
            }

            return $componentArgs;
        }

        private function extractComponentName($string){

            preg_match("/\b([\w.-]+)/", $string, $searchResult);


            
            return $searchResult[1];

        }

        private function resolveComponentString($match, $componentArgs, $componentName, $page){


            $componentPath = $this->fileController->convertComponentToPath($componentName);

            $componentString = file_get_contents($this->fileController->makePath("/app/Pages/components/$componentPath.php"));         

            $componentString = $this->resolveComponents($componentString);

            if($this->static){
                if(!in_array($componentName, $this->allFiles)){
                    array_push($this->allFiles, "components/$componentPath");
                }
            }

            if($componentArgs['children'] != null){
                return str_replace("<x-$match>" . $componentArgs['children'] . "</x-$componentName>", $this->resolveComponentParams($componentString, $componentArgs) ,$page);
            }
            else{
                return str_replace("<x-$match></x-$match>", $this->resolveComponentParams($componentString, $componentArgs) ,$page);
            }
        }

        private function ifIsStatic($pageName, $page){
            if($this->static){
                $this->fileController->addStaticFiles($this->allFiles);
                $this->fileController->writeToStaticPages($pageName, $page);
            }

        }
    }

?>