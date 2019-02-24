<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings {

	private $data;
	private $heading;

	function __construct() {
		$me = auth()->user();
		if ($me->isAdmin || $me->isResolver) {
			$where = "";
		} else {
			$uid = $me->uid;
			$empid = $me->employeeid;
			$relatedUsers = \App\User::whereRaw("employeeid>'' AND employeeid='$empid' and uid<>'$uid'")->get();
			$relatedUsers->push($me);
			if ($me->isViewer) {
				$relatedUsers = $relatedUsers->merge($me->viewerOfUsers());
			}
			$uw = '';
			foreach ($relatedUsers as $key => $ruser) {

				$usersWhere = ' ( ( requestedBy_uid= "' . $ruser->uid . '" )  or ( onBehalfOf_uid= "' . $ruser->uid . '")  )';

				if ($uw == '') {
					$uw = $usersWhere;
				} else {
					$uw = ' ' . $uw . ' OR ' . $usersWhere . '  ';
				}
			}
			$where = ' where ( ' . $uw . ' ) ';
		}
		$this->data = \DB::Select("SELECT * from v_all $where");
		$this->heading = array_keys((array) $this->data[0]);
		$this->data = collect(json_decode(json_encode($this->data), true));
	}

	public function headings(): array
	{
		return $this->heading;
	}

	public function collection() {
		return $this->data;
	}
}
