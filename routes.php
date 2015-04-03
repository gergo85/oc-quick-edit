<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function() {
        Route::any('indikator/qedit/content', function() {
            if (File::exists('themes/'.get('theme').'/'.get('type').'/'.get('page')))
            {
                $content = file_get_contents('themes/'.get('theme').'/'.get('type').'/'.get('page'));
                return trim(substr($content, strrpos($content, '==') + 2));
            }
            else
            {
                return '';
            }
        });

        Route::any('indikator/qedit/date', function() {
            if (File::exists('themes/'.get('theme').'/'.get('type').'/'.get('page')))
            {
                return date('Y-m-d H:i', filemtime('themes/'.get('theme').'/'.get('type').'/'.get('page')));
            }
            else
            {
                return '';
            }
        });
    });
});
