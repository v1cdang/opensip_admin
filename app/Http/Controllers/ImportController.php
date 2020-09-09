<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CsvImportRequest;
use App\CsvData;
use App\DawzCDRs;

class ImportController extends Controller
{
    public function getImport()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $query = "LOAD DATA LOCAL INFILE '$path'
        INTO TABLE dawz_cdr FIELDS TERMINATED BY ','
        LINES TERMINATED BY '\r\n'
        IGNORE 1 LINES;";
    }

    public function processImport(Request $request)
    {
        DB::connection()->disableQueryLog();
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        $db_fields = config('app.db_fields');
        foreach ($csv_data as $row) {
            $contact = new Dawzcdrs();
           // print_r($row);
            foreach($request->fields as $index => $value) {
                if (!is_null($value)) {
                    $field = $db_fields[$value];
                    $contact->$field = $row[$index];
                }
            }

            $contact->save();
        }

        return view('import_success');
    }
}
