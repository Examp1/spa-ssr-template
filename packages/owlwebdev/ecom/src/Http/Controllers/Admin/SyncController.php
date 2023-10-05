<?php

namespace Owlwebdev\Ecom\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Owlwebdev\Ecom\Exports\ProductExport;
use Owlwebdev\Ecom\Exports\ProductShortExport;
use Owlwebdev\Ecom\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class SyncController extends Controller
{
    public function export(Request $request)
    {
        parse_str($request->input('filter'), $filter_array);

        if ($request->input('type') && $request->input('type') == 'short') {
            return new ProductShortExport($filter_array);
        }

        return new ProductExport($filter_array);
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('import_file')) {
            return redirect()->back()->with('error', __('No file'));
        }

        $import = (new ProductImport)->import($request->file('import_file'));
        // dd($import);
        // $problems = [];

        // foreach ($import->failures() as $failure) {

        // $failure->row(); // row that went wrong
        // $failure->attribute(); // either heading key (if using heading row concern) or column index
        // $failure->errors(); // Actual error messages from Laravel validator
        // $failure->values(); // The values of the row that has failed.

        //     $problems[] = $failure->row() . '-' . $failure->errors();
        // }

        // // dd($import->errors());

        // foreach ($import->errors() as $error) {
        // // dd($error);

        //     $problems[] = $error->row() . '-' . $error->errors();
        // }

        // if (!empty($problems)) {
        //     return redirect()->back()->with('error', __('Problems') . ':' . PHP_EOL . implode(PHP_EOL, $problems));
        // }
        return redirect()->back()->with('success', __('Imported successfully!'));
    }
}
