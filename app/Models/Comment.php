<?php

    namespace App\Models;

    use Framework\Models\Model;
    use App\Models\User;
    use App\Models\Post;

    class Comment extends Model{

        protected static $table = "comments";

        public function user(){
            return $this->belongsTo(User::class, "userId", 'id');
        }

        public function post(){
            return $this->belongsTo(Post::class, "postId", 'id');
        }


    }

    
?>