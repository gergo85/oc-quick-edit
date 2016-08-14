<?php

App::before(function($request)
{
    Route::group(['prefix' => Config::get('cms.backendUri', 'backend')], function()
    {
        Route::any('indikator/qedit/content', function()
        {
            if (File::exists(base_path().'/themes/'.get('path'))) {
                $content = File::get(base_path().'/themes/'.get('path'));

                if (substr_count(get('path'), '/layouts/') > 0 || substr_count(get('path'), '/pages/') > 0 || substr_count(get('path'), '/static-pages/') > 0) {
                    if (substr_count($content, '<?php') == 0) {
                        $content = substr($content, strpos($content, '==') + 2);
                    }
                    else {
                        $content = substr($content, strpos($content, '?>') + 5);
                    }
                }

                return trim($content);
            }

            else {
                return '';
            }
        });

        Route::any('indikator/qedit/date', function()
        {
            if (File::exists(base_path().'/themes/'.get('path'))) {
                $modified = File::lastModified(base_path().'/themes/'.get('path'));

                return date('Y-m-d G:i', $modified);
            }

            else {
                return '';
            }
        });
    });
});
