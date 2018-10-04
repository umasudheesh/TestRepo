<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CsvFileImporter;
use Illuminate\Support\Facades\Input;
use App;
use DB;

class CsvUploadController extends Controller
{
    public function getCsvUpload()
    {
        return view('csv_upload');
    }

    public function postCsvUpload()
    {
        $message = '';
        // Check if form submitted a file
        if (Input::hasFile('csv_import')) {
            $csv_import = Input::file('csv_import');

            // Validate that the file is present
            if($csv_import->isValid())
            {
                // Create validator object
                $validator = App::make('App\CsvImportValidator');

                // Run validation with input file
                $validator = $validator->validate($csv_import);
                // print_r($validator);
                // exit;

                if ($validator->fails()) {

                    // return Redirect::back()->withErrors($validator);
                    $messages = $validator->messages();
                    $msg='';
                    foreach ($messages->all() as $value) {
                        $msg.= $value.'<br>';
                    }
                    
                    $Response['meta'] = array('status' => false,
                                        'statusCode' => 400, 'message' => $msg);
                }
                else
                {
                    // Lets construct our importer
                    $csv_importer = new CsvFileImporter();

                    // Import our csv file
                    if ($csv_importer->import($csv_import)) {
                        // Provide success message to the user
                        $stausCode = 200;
                        $message = 'Your file has been successfully imported!';
                    } else {
                        $stausCode = 401;
                        $message = 'Your file did not import';
                    }   

                    $Response['meta'] = array('status' => false,
                                        'statusCode' => $stausCode, 'message' => $message);
                }

            }
            else
            {
                $Response['meta'] = array('status' => false,
                                        'statusCode' => 401, 'message' => 'You must provide a CSV file for import.');
            }

            // return Redirect::back()->with('message', $message);
            
        }
        else
        {
            $Response['meta'] = array('status' => false,
                                        'statusCode' => 401, 'message' => 'Please Select Csv File');
        }

        return response(json_encode($Response))->header('Content-Type', 'application/json');
    }

    public function getJsonData()
    {
        $data = DB::table('test_tickets')->select('*')->get();

        $Response['meta'] = array('status' => false,
                                        'statusCode' => 200, 'message' => 'Success');
        $Response['value'] = $data;

        return response(json_encode($Response))->header('Content-Type', 'application/json');

    }
}
