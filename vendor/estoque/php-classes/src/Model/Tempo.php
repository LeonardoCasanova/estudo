<?php

namespace Estoque\Model;
use \Estoque\Model;
use function GuzzleHttp\json_encode;

class Tempo extends Model {


    public static function getTime($nrtime){

        $nrtime = str_replace("-", "", $nrtime);

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,"https://api.hgbrasil.com/weather?woeid=$nrtime");

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        die(json_encode($data));
        
    }

    public function setTime($ntime) {

        $data = Tempo::getTime($ntime);

    }




}
