<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phones extends Model
{
    protected $table = "tb_phones";
    protected $primaryKey = "phone_id";

    protected $fillable = [
        "phone",
        "type",
        "contact_fk"
    ];
}
