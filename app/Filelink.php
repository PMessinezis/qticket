<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Filelink extends Model {
	use CrudTrait;

	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $table = 'filelinks';
	public $timestamps = true;
	protected $appends = ['link'];

	public function getLinkAttribute() {
		return '/howtoguide/' . $this->id . '/' . $this->originalName;
	}

	public function setFileNameAttribute($value) {
		$attribute_name = "fileName";
		$disk = "public";
		$destination_path = "";
		$this->originalName = $value->getClientOriginalName();
		$this->fileExt = $value->guessExtension();
		$this->mimeType = $value->getMimeType();
		$this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
	}

	// $file = $request->file('file');
	// 	$a = new Attachment;
	// 	$a->originalName = $file->getClientOriginalName();
	// 	$a->fileExt = $file->guessExtension();
	// 	$a->mimeType = $file->getMimeType();
	// 	$a->size = $file->getClientSize();
	// 	\Log::info('file', ['fname' => $a->originalName, 'mimetype' => $a->mimeType]);
	// 	if ($a->mimeType == 'application/CDFV2-unknown' || $a->mimeType == 'application/vnd.ms-outlook' || $a->mimeType == 'application/vnd.ms-office') {
	// 		$a->mimeType = $file->getClientMimeType();
	// 		// if (a->fileExt=='') a-1>fileExt=$file->getExtension();
	// 		if ($a->fileExt == '') {
	// 			$a->fileExt = $file->getClientOriginalExtension();
	// 		}

	// 		$postfix = Auth::user()->uid . date("YmdHisB") . $ticket->refid;
	// 		$newPath = 'attachments/' . $postfix . '.' . $a->fileExt;
	// 		copy($file->getPathname(), storage_path('app/' . $newPath));
	// 		$a->filename = $newPath;
	// 	} else {
	// 		$a->fileName = $file->store('attachments');
	// 	}
	// 	$a->ticket_id = $ticket->id;
	// 	$a->uploadedByUser_uid = Auth::user()->uid;
	// 	$a->save();
	// 	return $a->id;

	public function linkhtml() {
		return '<a href="' . $this->link . '" target="_blank" >' . $this->originalName . '</a>';
	}

	public function download() {

		$headers = [
			'Content-Type' => $this->mimeType,
			'Content-Disposition' => ' inline; filename="' . $this->originalName . '"',
		];
		return response()->file(storage_path('app/public/' . $this->fileName), $headers);
	}

}
