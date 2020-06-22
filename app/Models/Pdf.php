<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $fillable = ['title', 'description', 'table_content', 'table_description', 'invoice_title', 'invoice_number', 'invoice_image', 'invoice_description', 'user_id', 'template', 'sign'];
}

