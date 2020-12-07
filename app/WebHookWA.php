<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebHookWA extends Model
{
    protected $table = "webhook_wa";
    protected $connection = 'pgsql';
}
