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

    /**
     * Metodo para registrar un cliente
     *
     * @param Request $request
     * @return array
     */
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
     * Metodo para recargar la billetera
     *
     * @param Request $request
     * @return array
     */
    public function RechargeWallet(Request $request){
        $rules= [
            'documento' => 'required',
            'celular'   => 'required',
            'valor'     => 'required|numeric|gt:0',
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
                $results = $this->soapWrapper->call('SoapService.RechargeWallet', [[
                    'documento' => $request->documento,
                    'celular'   => $request->celular,
                    'valor'     => $request->valor
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
     * Metodo para pagar compras
     *
     * @param Request $request
     * @return array
     */
    public function PayPurchase(Request $request){
        $rules= [
            'documento' => 'required',
            'valor'     => 'required|numeric|gt:0',
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
                $results = $this->soapWrapper->call('SoapService.PayPurchase', [[
                    'documento' => $request->documento,
                    'valor'     => $request->valor
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
     * Metodo para confirmar pago
     *
     * @param Request $request
     * @return array
     */
    public function ConfirmPayment(Request $request){
        $rules= [
            'id'    => 'required|numeric|gt:0',
            'token' => 'required',
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
                $results = $this->soapWrapper->call('SoapService.ConfirmPayment', [[
                    'id'    => $request->id,
                    'token' => $request->token
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
}
