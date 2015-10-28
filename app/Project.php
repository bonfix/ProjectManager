<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $fillable = ['name','description','category_id','user_id','photo','video','files'];

}
