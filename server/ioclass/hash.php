<?php

class Meal {

    private static $ing1 = '$2a';
    private static $ing2 = '$10';

    public function __construct(){
        #code...
    }

    public static function pepper() {
        return substr(sha1(mt_rand()), 0, 22);
    }

    //cook a meal
    public static function cook($password) {
        return crypt($password, self::$ing1 . self::$ing2 . '$' . self::pepper());
    }

    //compare meals
    public static function compare_meals($old, $new) {
        $full_salt = substr($old, 0, 29);
        $new_meal = crypt($new, $full_salt);
        return ($old == $new_meal);
    }

}

?>
