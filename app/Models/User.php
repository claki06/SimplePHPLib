<?php

    namespace App\Models;

    use Framework\Models\Model;
    use App\Models\Post;

    class User extends Model{

        public function posts(){

            return $this->hasMany(Post::class, "id", "userId");

        }

        protected static $table = "users";
        
    }
    
?>