<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function() {
        Route::any('indikator/qedit/content', function() {
            if (File::exists('themes/'.get('path')))
            {
                $content = File::get('themes/'.get('path'));
                if (substr_count(get('path'), '/content/') == 0) $content = substr($content, strrpos($content, '==') + 2);
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
                return date('Y-m-d G:i', File::lastModified('themes/'.get('path')));
            }

            else
            {
                return '';
            }
        });
    });
});
