<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrmIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Інтеграція адмінів в срм в яких є дозвіл на інтеграцію';

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
        Log::info('Сrm - integration');
        /* @var Admin[] $admins */
        $admins = Admin::query()
            ->where('crm_access',1)
            ->select(['id','name','email','phone','password'])
            ->get();

        $auth = $this->login();

        if($auth['success']){
            $access_token = $auth['data']['access_token'];
            $adminsData = [];

            foreach ($admins as $admin){
                $adminsData[] = [
                    'site_id'  => $admin->id,
                    'name'     => $admin->name,
                    'email'    => $admin->email,
                    'phone'    => $admin->phone,
                    'password' => $admin->getOriginal('password'),
                ];
            }

            $post = [
                'accounts' => $adminsData
            ];

            $report = $this->sync($post,$access_token);

            if(isset($report['success']) && $report['success'] && isset($report['data']['report']['sync']['count']) && $report['data']['report']['sync']['count']){
                foreach ($report['data']['report']['sync']['ids'] as $id){
                    foreach ($admins as $admin){
                        if($admin->id == $id){
                            $admin->crm_sync = 1;
                            $admin->save();
                        }
                    }
                }
            }
            Log::info('Сrm - integration: Report',$report);
        }
    }

    private function login(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('CRM_URL').'/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
    "email": "'.env('CRM_ADMIN_EMAIL').'",
    "password":"'.env('CRM_ADMIN_PASSWORD').'"
}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);
    }

    private function sync($data,$access_token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::CRM_URL.'/api/account/sync',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);
    }
}
