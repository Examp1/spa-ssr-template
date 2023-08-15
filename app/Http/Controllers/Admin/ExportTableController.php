<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ModelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportTableController extends Controller
{
    public function export(Request $request)
    {
        $model = DB::table($request->get('table'))
            ->select($request->get('fields'))
            ->get();

        return Excel::download(new ModelExport($model,$request->get('fields')), $request->get('table') . '.' . $request->get('aggregator'));
    }
}
