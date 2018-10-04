<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'tickets';

    protected $fillable = ['name', 'timing', 'description', 'url', 'photo_url', 'price', 'unique_id'];
}