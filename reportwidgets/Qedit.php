<?php namespace Indikator\Qedit\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use Cms\Classes\Page;
use Flash;
use Lang;
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

        if ($this->property('editor') == 'rich')
        {
            $this->addCss('/modules/backend/formwidgets/richeditor/assets/css/richeditor.css');
            $this->addJs('/modules/backend/formwidgets/richeditor/assets/js/build-min.js');
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
            'editor' => [
                'title'             => 'indikator.qedit::lang.widget.editor',
                'default'           => 'rich',
                'type'              => 'dropdown',
                'options'           => ['none' => Lang::get('indikator.qedit::lang.widget.editor_none'), 'rich' => Lang::get('indikator.qedit::lang.widget.editor_rich')]
            ],
            'height' => [
                'title'             => 'indikator.qedit::lang.widget.height',
                'default'           => '300',
                'type'              => 'string',
                'validationPattern' => '^[0-9]*$',
                'validationMessage' => 'indikator.qedit::lang.widget.error_number'
            ]
        ];
    }

    protected function loadData()
    {
        $pages = Page::getNameList();
        $this->vars['pages'] = '';

        foreach ($pages as $path => $name)
        {
             $this->vars['pages'] .= '<option value="'.$path.'">'.$name.'</option>';
        }

        $this->vars['theme'] = Theme::getEditTheme()->getDirName();
    }

    public function onQeditSave()
    {
        $page = post('page');

        if (!empty($page))
        {
            $theme = Theme::getEditTheme()->getDirName();

            $original = file_get_contents('themes/'.$theme.'/pages/'.$page.'.htm');
            $setting = substr($original, 0, strrpos($original, '==') + 2)."\n";

            file_put_contents('themes/'.$theme.'/pages/'.$page.'.htm', $setting.post('content'));
            Flash::success(Lang::get('cms::lang.template.saved'));
        }

        else
        {
            Flash::warning(Lang::get('indikator.qedit::lang.widget.error_page'));
        }
    }
}
