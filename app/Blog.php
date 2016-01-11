<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model {

	protected $fillable = ['subject','content','edition','link','user','created_at','updated_at'];

}
