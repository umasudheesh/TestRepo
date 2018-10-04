<?php

namespace App\Http\Controllers;

use DB;

class CsvFileImporter extends Controller
{
    public function import($csv_import)
    {
        // Save file to temp directory
        $moved_file = $this->moveFile($csv_import);

        // Normalize line endings
        $normalized_file = $this->normalize($moved_file);

        // Import contents of the file into database
        return $this->importFileContents($normalized_file);
    }

    /**
     * Move File to a temporary storage directory for processing
     * temporary directory must have 0755 permissions in order to be processed
     */
    private function moveFile($csv_import)
    {
        // Check if directory exists make sure it has correct permissions, if not make it
        if (is_dir($destination_directory = storage_path('imports/tmp'))) {
            chmod($destination_directory, 0755);
        } else {
            mkdir($destination_directory, 0755, true);
        }

        // Get file's original name
        $original_file_name = $csv_import->getClientOriginalName();

        // Return moved file as File object
        return $csv_import->move($destination_directory, $original_file_name);
    }

    /**
     * Convert file line endings to uniform "\r\n" to solve for EOL issues
     * Files that are created on different platforms use different EOL characters
     * This method will convert all line endings to Unix uniform
     *
     */
    protected function normalize($file_path)
    {
        //Load the file into a string
        $string = file_get_contents($file_path);

        if (!$string) {
            return $file_path;
        }

        //Convert all line-endings using regular expression
        $string = preg_replace('~\r\n?~', "\n", $string);

        file_put_contents($file_path, $string);


        return $file_path;
    }

    /**
     * Import CSV file into Database using LOAD DATA INFILE function
     *
     */
    private function importFileContents($file_path)
    {
        $query = "LOAD DATA INFILE '$file_path' INTO TABLE tickets
        FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
            IGNORE 1 LINES";

        return DB::connection()->getpdo()->exec($query);
    }
}