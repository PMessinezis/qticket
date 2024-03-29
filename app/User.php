<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
	use Notifiable;

	use CrudTrait;

	protected $table = 'users';
	public $timestamps = true;
	public $incrementing = false;
	public $primaryKey = 'uid';
	protected $appends = array('isResolver', 'isAdmin', 'isViewer', 'name', 'listname', 'fullname', 'firstlastname');
	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $hidden = [
		'password', 'remember_token',
	];

	public function memberOf() {
		return $this->belongsToMany('App\ADGroup', 'memberofadgroup', 'user_uid', 'adgroup_gid');
	}

	public function viewerOf() {
		return $this->belongsToMany('App\ADGroup', 'ticketsviewerofadgroup', 'user_uid', 'adgroup_gid');
	}

	public function viewerOfUsers() {
		$users = collect([]);
		$groups = $this->viewerOf;
		foreach ($groups as $g) {
			$users = $users->merge($g->members);
		}
		return $users;
	}

	public function viewerOfUids() {
		$users = $this->viewerOfUsers();
		return $users ? $users->pluck('uid')->toArray() : [];
	}

	public function getFullnameAttribute() {
		if ($this->lastname && $this->firstname) {
			$n = $this->lastname . ', ' . $this->firstname;
		} else {
			$n = $this->lastname . $this->firstname;
		}
		return $n;
	}

	public function getFirstlastnameAttribute() {
		$n = $this->firstname . ' ' . $this->lastname;
		return $n;
	}

	public function getNameAttribute() {
		return $this->fullname;
	}

	public function getListnameAttribute() {
		return $this->fullname . " ($this->email)";
	}

	public function resolver() {
		return $this->hasOne('App\Resolver', 'user_uid', 'uid');
	}

	public function isAdmin() {
		if ($this->resolver) {
			return $this->resolver()->first()->isAdmin();
		} else {
			return $this->id == 1 || User::all()->count() == 0;
		}
	}

	public function isReviewer() {
		if ($this->resolver) {
			return $this->resolver->isReviewer();
		} else {
			return false;
		}
	}

	public function getIsResolverAttribute() {
		if ($this->resolver) {
			return true; //$this->resolver->isActive ; //&& $this->id!=1;
		} else {
			return false;
		}
	}

	public function getIsAdminAttribute() {
		return $this->isAdmin();
	}

	public function getIsReviewerAttribute() {
		return $this->isReviewer();
	}

	public function getIsViewerAttribute() {
		return $this->viewerOf->count() > 0;
	}

	public function ldapinfo() {
		if (isset($this->lastUserDomain)) {
			setCurrentUserDomain($this->lastUserDomain);
		}
		return ldapinfo($this->uid);
	}

	public function refreshFromLDAP() {
		$ld = $this->ldapinfo();
		if ($ld->count()) {
			$this->firstname = ($ld['givenname'] ?? '');
			$this->lastname = ($ld['sn'] ?? '');
			$this->employeeid = ($this->employeeid ?? ($ld['employeeid'] ?? ''));
			$this->title = ($ld['title'] ?? '');
			$this->description = ($ld['description'] ?? '');
			if ($this->description == '') {
				$this->description = ($ld['displayname'] ?? '');
			}
			$this->email = ($ld['mail'] ?? '');
			$this->phone1 = ($ld['telephonenumber'] ?? '');
			$this->phone2 = ($ld['mobile'] ?? '');
			$this->tmhma = ($ld['department'] ?? '');
			$adr = $ld['streetaddress'] ?? '';
			$city = $ld['l'] ?? '';
			if ($adr != '' && $city != '') {
				$adr = $adr . ', ';
			}

			$this->topothesia = $adr . $city;
			// dd($this);
			echo " $this->description   $this->phone1    $this->phone2  " . PHP_EOL;
			// if ($this->firstname == '' || $this->lastname == '') {
			// 	echo $this->description . " OOPS ! " . PHP_EOL;
			// 	return null;
			// }
			$this->save();
		} else {
			return null;
		}
		return $this;
	}

	public static function fromLDAP($auser) {
		$user = self::where('uid', $auser)->first();
		if (!isset($user)) {
			$user = new User;
			$user->uid = $auser;
			$user->isTempEntry = true;
		}
		return $user->refreshFromLDAP();
	}

	public static function refreshAllfromLDAP() {
		$Users = self::all();
		foreach ($Users as $u) {
			$u->refreshFromLDAP();
		}
	}

}
