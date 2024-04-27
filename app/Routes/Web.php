<?php

use App\Models\Knjiga;
use Framework\Routing\Route;

    Route::get("/", function(){
        view("home");
    });

?>