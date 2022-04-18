<?php

use Illuminate\Support\Facades\Route;

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
