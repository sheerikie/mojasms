<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSMSRequest;
use App\Http\Requests\UpdateSMSRequest;
use App\Models\SMS;
use Illuminate\Support\Str;

class SMSController extends Controller
{
    protected $bearer_token;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            'https://api.mojasms.dev/login',
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
        //dd($token['data']['token']);
        $this->bearer_token = $token['data']['token'];
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unit_balance()
    {
        $access_token= $this->bearer_token;
        $client = new \GuzzleHttp\Client();
        $response = $client->get(
            'https://api.mojasms.dev/balance',
            [
                'headers' => [
                    'Authorization' => "Bearer " . $access_token,
                    'Accept' => 'application/json',
                ],
            ]
        );
        $body = $response->getBody();
        print_r(json_decode((string) $body));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_sms()
    {
        $access_token= $this->bearer_token;


        $message_id =  Str::random(40);
        $content='Welcome to MojaGate';
        $recipient='254703153668';



        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            'https://api.mojasms.dev/sendsms',
            [
                'headers' => [
                    'Authorization' => "Bearer " . $access_token,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'from' => 'MOJAGATE',
                    'phone' => $recipient,
                    'message' => $content,
                    'message_id' =>$message_id,
                    'webhook_url' => 'https://mojagate.com/sms-webhook',
                ],
            ]
        );


        $body = $response->getBody();

        $data=json_decode((string) $body, true);
        //dd($token['data']['token']);
        $delivery_status = $data['status'];
        $delivery_time = $data['data']['recipients'][0]['created_at'];


        // dd($delivery_time);
        $sms= new SMS();
        $sms->content=$content;
        // $sms->user_id=auth()->id();
        $sms->message_id=$message_id;
        $sms->recipient=$recipient;
        $sms->delivery_status=$delivery_status=='success'?1:0;
        $sms->delivery_time=$delivery_time;
        $sms->save();

        print_r(json_decode((string) $sms));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function batch_sms()
    {
        $access_token= $this->bearer_token;
        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            'https://api.mojasms.dev/batch-sms',
            [
        'headers' => [
            'Authorization' => "Bearer " . $access_token,
            'Accept' => 'application/json',
        ],
        'json' => [
            'from' => 'MOJAGATE',
            'messages' => [
                [
                    'message' => 'just another test message',
                    'phone' => '254712345678',
                ],
                [
                    'message' => 'test message',
                    'phone' => '254712345679',
                    'message_id' => 'custom_messageID',
                ],
            ],
            'webhook_url' => 'https://mojagate.com/sms-webhook',
        ],
    ]
        );
        $body = $response->getBody();
        print_r(json_decode((string) $body));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSMSRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSMSRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function show(SMS $sMS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SMS $sMS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSMSRequest  $request
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSMSRequest $request, SMS $sMS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function destroy(SMS $sMS)
    {
        //
    }
}