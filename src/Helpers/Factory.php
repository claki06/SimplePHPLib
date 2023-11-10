<?php

    namespace Framework\Helpers;

    use Framework\Helpers\ErrorHandler;

    class Factory{

        protected $modelName = "";

        protected $count = 1;

        protected $colValues = [];

        private $names = [
            'Johnathon',
            'Anthony',
            'Erasmo',
            'Raleigh',
            'Nancie',
            'Tama',
            'Camellia',
            'Augustine',
            'Christeen',
            'Luz',
            'Diego',
            'Lyndia',
            'Thomas',
            'Georgianna',
            'Leigha',
            'Alejandro',
            'Marquis',
            'Joan',
            'Stephania',
            'Elroy',
            'Zonia',
            'Buffy',
            'Sharie',
            'Blythe',
            'Gaylene',
            'Elida',
            'Randy',
            'Margarete',
            'Margarett',
            'Dion',
            'Tomi',
            'Arden',
            'Clora',
            'Laine',
            'Becki',
            'Margherita',
            'Bong',
            'Jeanice',
            'Qiana',
            'Lawanda',
            'Rebecka',
            'Maribel',
            'Tami',
            'Yuri',
            'Michele',
            'Rubi',
            'Larisa',
            'Lloyd',
            'Tyisha',
            'Samatha',
        ];

        private $usernames = [
            'Mischke',
            'Serna',
            'Pingree',
            'Mcnaught',
            'Pepper',
            'Schildgen',
            'Mongold',
            'Wrona',
            'Geddes',
            'Lanz',
            'Fetzer',
            'Schroeder',
            'Block',
            'Mayoral',
            'Fleishman',
            'Roberie',
            'Latson',
            'Lupo',
            'Motsinger',
            'Drews',
            'Coby',
            'Redner',
            'Culton',
            'Howe',
            'Stoval',
            'Michaud',
            'Mote',
            'Menjivar',
            'Wiers',
            'Paris',
            'Grisby',
            'Noren',
            'Damron',
            'Kazmierczak',
            'Haslett',
            'Guillemette',
            'Buresh',
            'Center',
            'Kucera',
            'Catt',
            'Badon',
            'Grumbles',
            'Antes',
            'Byron',
            'Volkman',
            'Klemp',
            'Pekar',
            'Pecora',
            'Schewe',
            'Ramage'
        ];

        private $emails = [
            "disturbedDesiree41@live.ca",
            "Maggiemushy@home.nl",
            "difficultJerome@yahoo.in",
            "toughCrystal@live.ca",
            "ugliestDenise12@yahoo.com.mx",
            "Amyimpossible@freenet.de",
            "Tabithaclear@freenet.de",
            "Calebgleaming@sfr.fr",
            "clearJake97@sympatico.ca",
            "Stephaniescary@skynet.be",
            "quaintJessica36@optusnet.com.au",
            "Dylanfriendly@hotmail.es",
            "depressedTammy@live.com.au",
            "ugliestPatrick@hotmail.co.uk",
            "mysteriousRose@freenet.de",
            "itchyNatalie78@bluewin.ch",
            "Marcusfunny@yahoo.com.ar",
            "gentleSusan74@gmail.com",
            "Kelseytalented@club-internet.fr",
            "Tonyashy@telenet.be",
            'Lydiauninterested@bigpond.com',
            "Joegleaming@telenet.be",
            "homelyDanny@aol.com",
            "adventurousMeredith@yahoo.it",
            "Jacquelinearrogant@yahoo.co.id",
            "glamorousArthur@hotmail.fr",
            "Andrenasty@t-online.de",
            "braveSeth60@live.fr",
            "shinyStefanie67@verizon.net",
            "thoughtfulJeremiah21@frontiernet.net",
            "mistyJamie9@yahoo.es",
            "Ashleighsore@yahoo.com.br",
            "hurtYolanda20@hotmail.es",
            "anxiousSheila87@yahoo.ca",
            "proudCole@blueyonder.co.uk",
            "faithfulSarah@yahoo.co.uk",
            "cleverWillie73@hotmail.es",
            "scaryAlyssa90@uol.com.br",
            "importantHenry@live.fr",
            "fragileBarbara40@tin.it",
            "stormyPamela60@live.it",
            "worrisomeEbony@wanadoo.fr",
            "blushingAdriana20@live.com",
            "nuttyJermaine@free.fr",
            "pleasantDustin@bigpond.com",
            "sparklingSeth@googlemail.com",
            "Neilobedient@ymail.com",
            "Daisycalm@juno.com",
            "panickyAudrey79@windstream.net",
            "crowdedEric@aim.com",
        ];

        public function convertFactoryKey($key){

            switch($key){
                case "name":
                    return $this->names[rand(0, count($this->names)- 1)];
                    break;
                case "username":
                    return $this->usernames[rand(0, count($this->usernames) - 1)];
                    break;
                case "password":
                    return "198dc79854a6ffffcde18b15f48e32d8";
                    break;
                case "email":
                    return $this->emails[rand(0, count($this->emails) - 1)];
                    break;
                case "rndNum":
                    return rand(0, 100000);
                default:
                    ErrorHandler::factoryKeyDoesntExists($key);
            }

        }

        public function create(){

            $modelLoc = "App\\Models\\" . $this->modelName;
            $model = new $modelLoc();

            for($i = 0; $i < $this->count; $i++){

                $colValues = [];
                foreach($this->colValues as $key => $value){

                    $colValues[$key] = $this->convertFactoryKey($value);

                }

                $model->create($colValues);
    
            }

        }
    
    }

?>