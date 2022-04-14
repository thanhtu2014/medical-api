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
        switch($routeName) {
            case(DOCTOR_ROUTE_NAME_V1):
                $type = 'doctor';
                break;
            
            case(FAMILY_ROUTE_NAME_V1):
                $type = 'family';
                break;
            
            default:
                $type = 'doctor';
        }

        return $type;
    }
}
