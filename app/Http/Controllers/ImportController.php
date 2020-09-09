<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $file = $request->file('csv_file');

        DB::connection()->disableQueryLog();
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = addslashes($file->getRealPath());
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        //$path = Storage::putFile('/var/lib/mysql-files/'.$filename.".".$extension, $request->file('csv_file'));
        rename($tempPath, '/var/lib/mysql-files/'.$filename);
        $query = "LOAD DATA INFILE '$filename'
        INTO TABLE dawz_cdr
        FIELDS TERMINATED BY ','
        LINES TERMINATED BY '\n';";
        DB::connection()->getpdo()->exec($query);

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
