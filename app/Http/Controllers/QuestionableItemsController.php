<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\QuestionableItems;
use Maatwebsite\Excel\Facades\Excel;

class QuestionableItemsController extends Controller
{
    public function generateReport()
    {
        return request()->report == 'Excel' ? Excel::download(new QuestionableItems, 'Questionable Items' . '.xlsx') : null;
    }
}
