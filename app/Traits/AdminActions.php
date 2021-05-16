<?php

namespace App\Traits;

trait AdminActions{
        //beforeメソッドは以下に記載されているすべてのメソッドに先んじて実行されるメソッド
        public function before($user, $ability)
        {
            if ($user->isAdmin()) {
                return true;
            }
        }
}
