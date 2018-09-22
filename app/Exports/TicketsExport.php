<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection , WithHeadings
{

	private $data;
	private $heading;

	function __construct() {
    	$this->data=\DB::Select("SELECT * from v_all ");
    	$this->heading=array_keys((array)$this->data[0]);
	    $this->data=collect(json_decode( json_encode($this->data), true));
    }

 	public function headings(): array
    {
        return $this->heading;
    }

    public function collection()
    {
        return $this->data;
    }
}
