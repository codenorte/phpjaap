<?php

namespace App\Models;

use \Tavo\ValidadorEc;

class ValidarCedula
{
    /**
     * validar cedula
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function validar($cedula)
    {
        // Crear nuevo objeto
    	$validador = new ValidadorEc;

        $type_data='';
        $message_cedula='';
        $message_RucPersonaNatural='';
        $message_RucSociedadPrivada='';
        $message_RucSociedadPublica='';
        //return strlen($cedula);

        if(strlen($cedula)==10){
            $type_data = 'Cédula';
        }
        else if(strlen($cedula)>10&&strlen($cedula)<14){
            $type_data = 'RUC';
        }
        else{
            $type_data = 'Digitos incorrectos';
        }
		// validar CI - '0926687856'
    	if ($validador->validarCedula($cedula)) {
    		$message_cedula= 'Cédula válida';
    	} else {
    		$message_cedula= 'Cédula incorrecta: '.$validador->getError();
    	}
		// validar RUC persona natural - '0926687856001'
    	if ($validador->validarRucPersonaNatural($cedula)) {
    		$message_RucPersonaNatural = 'RUC válido';
    	} else {
    		$message_RucPersonaNatural = 'RUC incorrecto: '.$validador->getError();
    	}

		// validar RUC sociedad privada - 0992397535001
    	if ($validador->validarRucSociedadPrivada($cedula)) {
    		$message_RucSociedadPrivada = 'RUC válido';
    	} else {
    		$message_RucSociedadPrivada = 'RUC incorrecto: '.$validador->getError();
    	}

		// validar RUC sociedad pública - 1760001550001
    	if ($validador->validarRucSociedadPublica($cedula)) {
    		$message_RucSociedadPublica = 'RUC válido';
    	} else {
    		$message_RucSociedadPublica = 'RUC incorrecto: '.$validador->getError();
    	}

        return response()->json(array(
            'type_data'=>$type_data,
            'cedula'=>$message_cedula,
            'RucPersonaNatural'=>$message_RucPersonaNatural,
            'RucSociedadPrivada'=>$message_RucSociedadPrivada,
            'RucSociedadPublica'=>$message_RucSociedadPublica
        ),200);
    }
}
