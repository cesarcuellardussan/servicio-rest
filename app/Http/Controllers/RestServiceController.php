<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Artisaninweb\SoapWrapper\SoapWrapper;

class RestServiceController extends Controller
{

    /**
    * @var SoapWrapper
    */
    protected $soapWrapper;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapWrapper
     */
    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
        $this->soapWrapper->add('SoapService', function ($service) {
        $service
            ->wsdl(env('DOMAIN_SOAP').'/api/SoapService?wsdl')
            ->trace(true);
        });
    }

    public function RegisterClient(Request $request){
        $rules= [
            'documento' => 'required',
            'nombres'   => 'required',
            'email'     => 'required',
            'celular'   => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        try{
            //Errores de validacion
            if ($validator->fails()){
                return [
                    'success'       => 'false',
                    'cod_error'     => '400',
                    'message_error' => $validator->errors()->first(),
                ];
            }else{
                $results = $this->soapWrapper->call('SoapService.RegisterClient', [[
                    'documento' => $request->documento,
                    'nombres'   => $request->nombres,
                    'email'     => $request->email,
                    'celular'   => $request->celular
                ]]);
                return $results;
            }
        } catch (\Throwable $th) {
            //Errores de fallo de servidor
            return [
                'success'       => 'false',
                'cod_error'     => '500',
                'message_error' => $th->getMessage()
            ];
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
