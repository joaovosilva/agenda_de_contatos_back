<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $table = "tb_addresses";
    protected $primaryKey = "address_id";

    protected $fillable = [
        "zip_code",
        "street",
        "number",
        "neighborhood",
        "complement",
        "state",
        "contact_fk"        
    ];
}
