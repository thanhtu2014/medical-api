<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Share;

if (!function_exists('get_current_action_view_type')) {
    /**
     * @param string $type
     * @return mixed
     */
    function get_current_action_view_type()
    {
        $type = '';

        $routeName = Route::currentRouteName();
        //get route root
        $routeRoot = explode('.', $routeName, 3)[0].'.'.explode('.', $routeName, 3)[1];
        switch($routeRoot) {
            case(DOCTOR_ROUTE_NAME_V1):
                $type = HOSPITAL_OR_DOCTOR_KEY_VALUE;//hospital or family
                break;
            
            case(FAMILY_ROUTE_NAME_V1):
                $type = FAMILY_KEY_VALUE; //family
                break;
            
            case(MEDICINE_ROUTE_NAME_V1):
                $type = FAMILY_KEY_VALUE; //medicine
                break;

            // case(FAMILY_ROUTE_NAME_V1):
            //     $type = FAMILY_KEY_VALUE; //keyword
            //     break;

            // case(FAMILY_ROUTE_NAME_V1):
            //     $type = FAMILY_KEY_VALUE; //medical condition
            //     break;

            
            
            // case(FAMILY_ROUTE_NAME_V1):
            //     $type = FAMILY_KEY_VALUE; //important word
            //     break;
            
            default:
                $type = HOSPITAL_OR_DOCTOR_KEY_VALUE;
        }

        return $type;
    }
}

if (!function_exists('generate_unique_code')) {
    /**
     * Write code on Method
     *
     * @return response()
     */
    function generate_unique_code()
    {
        do {
            $code = random_int(100000, 999999);
        } while (User::where("code", "=", $code)->first());
  
        return $code;
    }
}

if (!function_exists('generate_status')) {
    /**
     * Write code on Method
     *
     * @return response()
     */
    function generate_status() 
    {
        do {
            $text = 'テキストが入りますテキストが入りますテキストが入り
            ますテキストが入りますテキストが入りますテキストが
            入りますテキストが入りますテキストが入ります';
        } while (Share::where("text", "=", $text)->first());
  
        return $text;
    }
}