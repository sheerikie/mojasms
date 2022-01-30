<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreSMSRequest;
use App\Http\Requests\UpdateSMSRequest;
use App\Models\SMS;
use Illuminate\Support\Str;
use App\Http\Controllers\API\BaseController as BaseController;

class SMSController extends BaseController
{
    protected $bearer_token;
    protected $base_url='https://api.mojasms.dev';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $client = new \GuzzleHttp\Client();
        $url= $this->base_url.'/login';
        $response = $client->post(
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'email' => 'interviewtest@mojagate.com',
                    'password' => '6648f8c$1P1084',
                ],
            ]
        );
        $body = $response->getBody()->getContents();
        $token=json_decode((string) $body, true);
        $this->bearer_token = $token['data']['token'];
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getHttpHeaders()
    {
        $bearerToken = $this->bearer_token;
        $headers    =   [
             'headers' => [
                'Accept' => 'application/json',
               'Authorization' => 'Bearer ' .$bearerToken,
             ],
             'http_errors' => false,
         ];
        return $headers;
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function unit_balance()
    {
        $access_token= $this->bearer_token;
        $client = new \GuzzleHttp\Client(self::getHttpHeaders());

        $url= $this->base_url.'/balance';
        $response = $client->get($url, ['verify' => false]);
      
        // $body = $response->getBody();
        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        return $resp;
        // print_r(json_decode((string) $body));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_sms()
    {
        $access_token= $this->bearer_token;
        $url= $this->base_url.'/sendsms';


        $message_id =  Str::random(40);
        $message='Welcome to MojaGate';
        $recipient='254703153668';



        $client = new \GuzzleHttp\Client(self::getHttpHeaders());
        $response = $client->post(
            $url,
            [
                'json' => [
                    'from' => 'MOJAGATE',
                    'phone' => $recipient,
                    'message' => $message,
                    'message_id' =>$message_id,
                    'webhook_url' => 'https://mojagate.com/sms-webhook',
                ],
            ]
        );

        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        
        $delivery_status = $resp['statusCode'];
        $content=json_decode((string) $resp['bodyContents'], true);
       
        $delivery_status=="200"?$delivery_time = $content['data']['recipients'][0]['created_at']:$delivery_time= null;
       
       
        $sms= new SMS();
        $sms->content=$message;
        $sms->message_id=$message_id;
        $sms->recipient=$recipient;
        $sms->delivery_status=$delivery_status=="200"?1:0;
        $sms->delivery_time=$delivery_time;
        $sms->save();

        return (json_decode((string) $sms));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function batch_sms()
    {
        $access_token= $this->bearer_token;
        $url= $this->base_url.'/batch-sms';
        $client = new \GuzzleHttp\Client(self::getHttpHeaders());

        $message_id =  Str::random(40);
        $message1='just another test message';
        $message2='Welcome to MojaGate';
        $recipient1='254703153668';
        $recipient2='254700053678';

        $messages = [
            [
                'message' => $message1,
                'phone' => $recipient1,
                'message_id' => $message_id
            ],
            [
                'message' => $message2,
                'phone' => $recipient2,
                'message_id' => $message_id,
            ],
        ];



        $response = $client->post(
            $url,
            [
            'json' => [
            'from' => 'MOJAGATE',
            'webhook_url' => 'https://mojagate.com/sms-webhook',
            'messages'=>$messages
            ],
            ],
            true
        );

        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();

        $delivery_status = $resp['statusCode'];
        $content=json_decode((string) $resp['bodyContents'], true);
       
        $delivery_time = date("Y/m/d h:i:sa");
        foreach ($messages as $message) {
            $sms= new SMS();
            $sms->content=$message['message'];
            $sms->message_id=$message['message_id'];
            $sms->recipient=$message['phone'];
            $sms->delivery_status=$delivery_status=="200"?1:0;
            $sms->delivery_time=$delivery_time;
            $sms->save();
        }
        // $resp['statusCode'] = $response->getStatusCode();
        // $resp['bodyContents'] = $response->getBody()->getContents();
        $body = $resp['bodyContents'];
        return (json_decode((string) $body));
    }
}