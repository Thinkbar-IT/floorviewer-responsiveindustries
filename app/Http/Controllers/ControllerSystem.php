<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ControllerSystem extends BaseController
{

    public function storageLink() {
        exec('cd .. && php artisan storage:link');
        return redirect('/admin');
    }

}
