<?php

namespace App\Http\Controllers;

use App\Models\TblJustification;
use App\Models\TblVarianceReport;
use App\Models\TblVarianceReportLine;
use Illuminate\Http\Request;

class JustificationController extends Controller
{
	public function getResults()
	{
		$result = TblVarianceReport::selectRaw('
			tbl_variance_report.id,
			tbl_variance_report.company,
		  	tbl_variance_report.business_unit,
		   	tbl_variance_report.department,
		    tbl_variance_report.section,
			adv_start_date,
			adv_end_date,
			actual_start_date,
			actual_end_date,
			nav_date,
			name,
			tbl_variance_report.created_at
			')
			->where([
				['tbl_variance_report.company', 'LIKE', request()->comp ],
				['tbl_variance_report.business_unit', 'LIKE', request()->b_unit ],
				['tbl_variance_report.department', 'LIKE', request()->dept ],
				['tbl_variance_report.section', 'LIKE', request()->sect ]
			])
			->join('users', 'users.id', '=', 'tbl_variance_report.user_id')->cursor();
		$data['data'] = $result;
		return $data;
	}

	public function getLines()
	{
		$result = TblVarianceReportLine::SelectRaw('
		tbl_variance_report_line.id, 
		itemcode, 
		description, 
		uom, 
		app_qty,
		nav_qty,
		variance,
		batch_id,
		reason
		')
			->where([
				['batch_id', request()->id],
				// ['variance', '<', 0]
			])
			->leftjoin('tbl_justification', 'tbl_justification.id', 'tbl_variance_report_line.justification_id')
			->orderBy('itemcode')
			// ->limit(1000)
			->get();

		$data['data'] = $result;
		return $data;
	}

	public function getReasons()
	{
		return TblJustification::cursor();
	}

	public function postReason()
	{
		TblVarianceReportLine::findorFail(request()->id)->update(
			['justification_id' => request()->reason]
		);
		$result = TblVarianceReportLine::SelectRaw('
		tbl_variance_report_line.id, 
		itemcode, 
		description, 
		uom, 
		app_qty,
		nav_qty,
		variance,
		batch_id,
		reason
		')
			->leftjoin('tbl_justification', 'tbl_justification.id', 'tbl_variance_report_line.justification_id')
			->find(request()->id);

		return response()->json(['message' => 'Success', 'result' => $result], 200);
	}
}
