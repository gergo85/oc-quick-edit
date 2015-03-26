<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function() {
        Route::any('indikator/qedit/content', function() {
            if (File::exists('themes/'.get('theme').'/pages/'.get('page').'.htm'))
            {
                $content = file_get_contents('themes/'.get('theme').'/pages/'.get('page').'.htm');
                return trim(substr($content, strrpos($content, '==') + 2));
            }
            else
            {
                return '';
            }
        });

        Route::any('indikator/qedit/date', function() {
            if (File::exists('themes/'.get('theme').'/pages/'.get('page').'.htm'))
            {
                return date('Y-m-d H:i', filemtime('themes/'.get('theme').'/pages/'.get('page').'.htm'));
            }
            else
            {
                return '';
            }
        });
    });
});
