<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tickets;

class UploadJsonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:jsonData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron To Upload Json Data into Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->curlGet('http://127.0.0.1:8000/json-data-upload');
        $data = json_decode($data);
        $upload_data = $data->value;

        foreach ($upload_data as $value) {
            $create_data = ['name' => $value->name,
            'timing' => $value->timing,
            'description' => $value->description,
            'url' => $value->url,
            'photo_url' => $value->photo_url,
            'price' => $value->price,
            'unique_id' => $value->unique_id];

            Tickets::create($create_data);
            
        }     
      
    }

    public function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
