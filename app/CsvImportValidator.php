<?php

namespace App;

use Illuminate\Validation\Factory as ValidationFactory;
use Exception;

class CsvImportValidator
{
    private $validator;

    private $rules = [
        'csv_extension'     => 'in:csv',
        'name_column'      => 'required',
        'timing_column' => 'required',
        'description_column' => 'required',
        'url_column' => 'required',
        'photo_url_column' => 'required',
        'price_column'  => 'required',
        'unique_id_column'  => 'required',
        'timing'         => 'required',
        'name'             => 'required',
        'description' => 'required',
        'url' => 'required',
        'photo_url' => 'required',
        'price'  => 'required',
        'unique_id'  => 'required',
    ];

    public function __construct(ValidationFactory $validator)
    {
        $this->validator = $validator;
    }

    public function validate($csv_file_path) 
    {
        // Line endings fix
        ini_set('auto_detect_line_endings', true);

        $csv_extendion = $csv_file_path->getClientOriginalExtension();

        // Open file into memory
        $opened_file = fopen($csv_file_path, 'r');

        // Get first row of the file as the header
        $header = fgetcsv($opened_file, 0, ',');
        // print_r($header);exit;

        // Find column headers
        $name_column = $this->getColumnNameByValue($header, 'name');
        $timing_column = $this->getColumnNameByValue($header, 'timing');
        $description_column = $this->getColumnNameByValue($header, 'description');
        $url_column = $this->getColumnNameByValue($header, 'url');
        $photo_url_column = $this->getColumnNameByValue($header, 'photo_url');
        $price_column = $this->getColumnNameByValue($header, 'price');
        $unique_id_column = $this->getColumnNameByValue($header, 'unique_id');

        // Get second row of the file as the first data row
        $data_row = fgetcsv($opened_file, 0, ',');

        // Combine header and first row data
        $first_row = array_combine($header, $data_row);

        // Find name in the name column
        $first_row_name = array_key_exists('name', $first_row)? $first_row['name'] : '';

        $first_row_timing = array_key_exists('timing', $first_row)? $first_row['timing'] : '';
        $first_row_description = array_key_exists('description', $first_row)? $first_row['description'] : '';
        $first_row_url = array_key_exists('url', $first_row)? $first_row['url'] : '';
        $first_row_photo_url = array_key_exists('photo_url', $first_row)? $first_row['photo_url'] : '';
        $first_row_price = array_key_exists('price', $first_row)? $first_row['price'] : '';
        $first_row_unique_id = array_key_exists('unique_id', $first_row)? $first_row['unique_id'] : '';

        // Close file and free up memory
        fclose($opened_file);

        // Build our validation array
        $validation_array = [
            'csv_extension'     => $csv_extendion,
            'name_column'      => $name_column,
            'timing_column' => $timing_column,
            'description_column' => $description_column,
            'url_column' => $url_column,
            'photo_url_column' => $photo_url_column,
            'price_column'  => $price_column,
            'unique_id_column'  => $unique_id_column,
            'timing'         => $first_row_timing,
            'name'             => $first_row_timing,
            'description' => $first_row_description,
            'url' => $first_row_url,
            'photo_url' => $first_row_photo_url,
            'price'  => $first_row_price,
            'unique_id'  => $first_row_unique_id
        ];

        // Return validator object
        return $this->validator->make($validation_array, $this->rules);
    }

    private function getColumnNameByValue($array, $value) 
    {
        return in_array($value, $array)? $value : '';
    }
}