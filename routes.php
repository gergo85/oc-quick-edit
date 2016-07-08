<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function()
    {
        Route::any('indikator/qedit/content', function()
        {
            if (File::exists('themes/'.get('path'))) {
                $content = File::get('themes/'.get('path'));

                if (substr_count(get('path'), '/layouts/') > 0 || substr_count(get('path'), '/pages/') > 0 || substr_count(get('path'), '/static-pages/') > 0) {
                    $content = substr($content, strpos($content, '==') + 2);
                }

                return trim($content);
            }

            else {
                return '';
            }
        });

        Route::any('indikator/qedit/date', function()
        {
            if (File::exists('themes/'.get('path'))) {
                $modified = File::lastModified('themes/'.get('path'));

                return date('Y-m-d G:i', $modified);
            }

            else {
                return '';
            }
        });
    });
});
