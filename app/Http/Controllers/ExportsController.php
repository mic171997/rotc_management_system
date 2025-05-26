<?php

namespace App\Http\Controllers;

use \PDF;
use Carbon\Carbon;
use App\Models\TblUnposted;
use App\Exports\VarianceNav;
use Illuminate\Http\Request;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;

class ExportsController extends Controller
{
    public function exportVariance()
    {
        // dd(request()->export);
        // session(['data' => $this->data()]);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        return Excel::download(new VarianceNav, 'export.csv');
    }

    // public function exportVariance()
    // {
    //     // Fetch your data from the database or any other source

    //     $data = json_decode(request()->export);
    //     // Define the CSV file name
    //     $fileName = 'export.csv';

    //     // Set the response headers
    //     $headers = array(
    //         'Content-Type' => 'csv',
    //         'Content-Disposition' => 'attachment; filename=' . $fileName,
    //         'Pragma' => 'no-cache',
    //         'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
    //         'Expires' => '0',
    //     );

    //     // Create a file stream
    //     $output = fopen('php://output', 'w');

    //     // Add CSV headers
    //     fputcsv($output, array_keys((array) $data[0]));

    //     // Add data rows
    //     foreach ($data as $row) {
    //         fputcsv($output, (array) $row);
    //     }

    //     // Close the file stream
    //     fclose($output);

    //     // Return the CSV as a response
    //     return new Response('', 200, $headers);
    // }
}
