<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ADGroup extends Model {
	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $table = 'adgroups';
	public $incrementing = false;
	public $primaryKey = 'gid';
	public $timestamps = true;

	public function members() {
		return $this->belongsToMany('App\User', 'memberofadgroup', 'adgroup_gid', 'user_uid');
	}

	public function viewers() {
		return $this->belongsToMany('App\User', 'ticketsviewerofadgroup', 'adgroup_gid', 'user_uid');
	}

}
