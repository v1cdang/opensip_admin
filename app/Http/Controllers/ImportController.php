<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CsvImportRequest;
use App\CsvData;
use App\DawzCDRs;

class ImportController extends Controller
{
    private function getAllCarriers()
    {
        $carriers = DB::table('o2b_carriers')->select('carrierid')
                    ->orderBy('carrierid','asc')
                    ->get();
        return $carriers;
    }
    public function getImport()
    {
        $carriers = $this->getAllCarriers();
        $db_fields = config('app.db_fields');

        return view('import', ['carriers' => $carriers]);
    }

    public function parseImport(CsvImportRequest $request)
    {
        $file = $request->file('csv_file');
        $carrierid = $request->input('carrierSelect');
        $db_fields = config('app.db_fields');
        $columns = $db_fields[$carrierid];

        DB::connection()->disableQueryLog();
        $pdo = DB::connection()->getPdo();
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = addslashes($file->getRealPath());
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

            //echo $tempPath;

        //$data = array_map('str_getcsv', file($path));
        $query = "TRUNCATE ".$carrierid."_cdr";
        $pdo->exec($query);

        $query = "LOAD DATA LOCAL INFILE '$tempPath'
        INTO TABLE ".$carrierid."_cdr FIELDS TERMINATED BY ','
        LINES TERMINATED BY '\r\n'
        ($columns)
        ";




        $recordsCount = $pdo->exec($query);
        echo $recordsCount;

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
