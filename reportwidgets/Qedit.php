<?php namespace Indikator\Qedit\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use Flash;
use Lang;
use File;
use App;
use Cms\Classes\Theme;

class Qedit extends ReportWidgetBase
{
    public function render()
    {
        try {
            $this->loadData();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        if ($this->property('editor') == 'rich') {
            $this->addCss('/modules/backend/formwidgets/richeditor/assets/css/richeditor.css');
            $this->addJs('/modules/backend/formwidgets/richeditor/assets/js/build-min.js');
            $this->addJs('/modules/backend/formwidgets/codeeditor/assets/js/build-min.js');

            if ($lang = $this->getValidEditorLang()) {
                $this->addJs('/modules/backend/formwidgets/richeditor/assets/vendor/froala/js/languages/'.$lang.'.js');
            }
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => 'indikator.qedit::lang.plugin.name',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error'
            ],
            'type' => [
                'title'   => 'indikator.qedit::lang.widget.type',
                'default' => 'pages',
                'type'    => 'dropdown',
                'options' => [
                    'pages'        => Lang::get('indikator.qedit::lang.widget.type_page'),
                    'content'      => Lang::get('indikator.qedit::lang.widget.type_content'),
                    'static_pages' => Lang::get('indikator.qedit::lang.widget.type_static_pages'),
                    'partials'     => Lang::get('indikator.qedit::lang.widget.type_partials'),
                    'layouts'      => Lang::get('indikator.qedit::lang.widget.type_layouts')
                ]
            ],
            'editor' => [
                'title'   => 'indikator.qedit::lang.widget.editor',
                'default' => 'rich',
                'type'    => 'dropdown',
                'options' => [
                    'none' => Lang::get('indikator.qedit::lang.widget.editor_none'),
                    'rich' => Lang::get('indikator.qedit::lang.widget.editor_rich')
                ]
            ],
            'height' => [
                'title'             => 'indikator.qedit::lang.widget.height_title',
                'description'       => 'indikator.qedit::lang.widget.height_description',
                'default'           => '300',
                'type'              => 'string',
                'validationPattern' => '^[0-9]*$',
                'validationMessage' => 'indikator.qedit::lang.widget.error_number'
            ],
            'size' => [
                'title'       => 'indikator.qedit::lang.widget.size_title',
                'description' => 'indikator.qedit::lang.widget.size_description',
                'default'     => 'huge',
                'type'        => 'dropdown',
                'options'     => [
                    'large' => Lang::get('indikator.qedit::lang.widget.size_large'),
                    'huge'  => Lang::get('indikator.qedit::lang.widget.size_huge'),
                    'giant' => Lang::get('indikator.qedit::lang.widget.size_giant')
                ]
            ],
            'theme' => [
                'title'       => 'indikator.qedit::lang.widget.theme_title',
                'description' => 'indikator.qedit::lang.widget.theme_description',
                'default'     => true,
                'type'        => 'checkbox'
            ]
        ];
    }

    protected function loadData()
    {
        // Name of activated theme
        $this->vars['theme'] = Theme::getEditTheme()->getDirName();

        // Name of all themes
        $this->vars['themes'] = [];

        if ($themes = opendir('themes')) {
            while (false !== ($theme = readdir($themes))) {
                if ($theme != '.' && $theme != '..') {
                    $this->vars['themes'][] = $theme;
                }
            }

            closedir($themes);
        }

        // Name of root folder
        if (count($this->vars['themes']) == 1 || $this->property('theme')) {
            $rootFolder = '/themes/'.$this->vars['theme'];
            $prefix = false;
        }
        else {
            $rootFolder = '/themes';
            $prefix = true;
        }

        // All files from themes folder
        $this->vars['items'] = $prevFolder = '';
        $type = $this->property('type');

        $files = File::allFiles(base_path().$rootFolder);
        asort($files);

        foreach ($files as $file) {
            $file = str_replace(base_path().'/themes/', '', $file);
            $path = explode('/', $file);

            // Is not a current folder
            if (($type != 'static_pages' && ($type != $path[1] || $path[2] == 'static-pages')) || ($type == 'static_pages' && (!isset($path[2]) || $path[2] != 'static-pages'))) {
                continue;
            }

            // Make short file path
            $shortFile = str_replace([$path[0].'/', $path[1].'/'], '', $file);
            if ($type == 'static_pages') {
                $shortFile = str_replace($path[2].'/', '', $shortFile);
            }

            // Are there more themes?
            $prefix = $prefix ? $path[0].' | ' : '';

            // Add file to list
            $this->vars['items'] .= '<option value="'.$file.'">'.$prefix.substr($shortFile, 0, strrpos($shortFile, '.')).'</option>';
        }
    }

    public function onQeditSave()
    {
        $path = post('page');

        if (!empty($path)) {
            $theme = Theme::getEditTheme()->getDirName();
            $type = $this->property('type');

            // Complex content
            if ($type == 'layouts' || $type == 'pages' || $type == 'static_pages') {
                $original = File::get(base_path().'/themes/'.$path);

                if (substr_count($original, '<?php') == 0) {
                    $setting = substr($original, 0, strpos($original, '==') + 2)."\n";
                }
                else {
                    $setting = substr($original, 0, strpos($original, '?>') + 5)."\n";
                }

                $content = $setting.post('content');
            }

            // Simple content
            else {
                $content = post('content');
            }

            // Save changes
            File::put('themes/'.$path, $content);

            Flash::success(Lang::get('cms::lang.template.saved'));
        }

        else {
            Flash::warning(Lang::get('indikator.qedit::lang.widget.error_page'));
        }
    }

    public function getValidEditorLang()
    {
        $locale = App::getLocale();

        if ($locale == 'en') {
            return null;
        }

        $locale = str_replace('-', '_', strtolower($locale));
        $path = '/modules/backend/formwidgets/richeditor/assets/vendor/froala/js/languages/'.$locale.'.js';

        return File::exists(base_path().$path) ? $locale : false;
    }
}
