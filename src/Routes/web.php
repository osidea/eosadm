<?php
$routeOption = [
    'prefix' => config('admin.perfix'),
    'as' => 'EOSFM::',
    'namespace' => 'EOSFM\Framework\Controllers',
    'middleware' => ['web', 'EOSCheck']
];
Route::group($routeOption, function() {
    Route::any('/login', ['as' => 'login', 'uses' => 'Common@login']);
    Route::any('/logout', ['as' => 'logout', 'uses' => 'Common@logout']);
    Route::any('/{class?}/{function?}', function($class='Index', $function='index'){
        $c = '\\EOSFM\Framework\Controllers\\'. $class ;
        if(class_exists($c)){
            $p = new $c;
            try{
                return $p -> $function($class, $function);
            } catch (Exception $e){
                return apiReturn(-2, $e->getMessage());
            }
        } else {
            return V('public.404');
        }
    });
});