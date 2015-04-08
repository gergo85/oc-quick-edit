<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function() {
        Route::any('indikator/qedit/content', function() {
            if (File::exists('themes/'.get('path')))
            {
                $content = file_get_contents('themes/'.get('path'));
                if (substr_count(get('path'), '/pages/') == 1) $content = substr($content, strrpos($content, '==') + 2);
                return trim($content);
            }

            else
            {
                return '';
            }
        });

        Route::any('indikator/qedit/date', function() {
            if (File::exists('themes/'.get('path')))
            {
                return date('Y-m-d H:i', filemtime('themes/'.get('path')));
            }

            else
            {
                return '';
            }
        });
    });
});
