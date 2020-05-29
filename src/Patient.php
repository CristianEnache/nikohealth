<?php

namespace Nikohealth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Nikohealth\Auth;
use Exception;

class Patient{

    /**
     * @param Request $request
     * https://sandbox-painfreestore.nikohealth.com/api/external/v1/patients
     */
	public function get(){

        $client = Auth::getHttpClient();
	    $response = $client->get( config('app.niko_health_api_url') . '/external/v1/patients');

        try{
            return json_decode($response->getBody()->getContents());
        }catch(Exception $exception){
            return false;
        }

    }


    public static function find($id){

        $client = Auth::getHttpClient();
        $response = $client->get( config('app.niko_health_api_url') . '/external/v1/patients/' . $id);

        try{
            return json_decode($response->getBody()->getContents());
        }catch(Exception $exception){
            return false;
        }

    }


    public static function store($patient){

        $client = Auth::getHttpClient();

        $response = $client->post( config('app.niko_health_api_url') . '/external/v1/patients', [
            'json'  => $patient
        ]);

        try{
            return json_decode($response->getBody()->getContents());
        }catch(Exception $exception){
            return $exception->getMessage();
        }

    }

    /**
     * @param $patient
     * @return bool
     */
    public static function update($patient){

        $client = Auth::getHttpClient();

        $patient_id = $patient->Id;

        unset($patient->Id);
        unset($patient->DisplayId);

        // Weird
        $patient->LocationId = (isset($patient->Location->Id)) ? $patient->Location->Id : '';

        unset($patient->Location);

        $patient = (array) $patient;

        $client->put( config('app.niko_health_api_url') . '/external/v1/patients/' . $patient_id, [
            'json' => $patient
        ]);

	    return true;

    }


    /**
     * @param $patient
     * @return bool
     */
    public static function delete($id){

        $client = Auth::getHttpClient();
        $response = $client->delete( config('app.niko_health_api_url') . '/external/v1/patients/' . $id);

        $contents = $response->getBody()->getContents();

        return $contents;

    }




}
