<?php namespace Indikator\Qedit\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;
use Cms\Classes\Page;
use Flash;
use Lang;
use Cms\Classes\Theme;
use Cms\Widgets\TemplateList;
use Cms\Classes\Content;

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
            'type' => [
                'title'             => 'indikator.qedit::lang.widget.type',
                'default'           => 'pages',
                'type'              => 'dropdown',
                'options'           => ['pages' => Lang::get('indikator.qedit::lang.widget.type_page'), 'content' => Lang::get('indikator.qedit::lang.widget.type_content')]
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
        $this->vars['items'] = '';
        $this->vars['theme'] = Theme::getEditTheme()->getDirName();

        if ($this->property('type') == 'pages')
        {
            $items = Page::getNameList();

            foreach ($items as $path => $name)
            {
                 $this->vars['items'] .= '<option value="'.$path.'.htm">'.$name.'</option>';
            }
        }
    }

    public function onQeditSave()
    {
        $page = post('page');

        if (!empty($page))
        {
            $theme = Theme::getEditTheme()->getDirName();
            $type = $this->property('type');

            if ($type == 'pages')
            {
                $original = file_get_contents('themes/'.$theme.'/pages/'.$page);
                $setting = substr($original, 0, strrpos($original, '==') + 2)."\n";
                $content = $setting.post('content');
            }
            else
            {
                $content = post('content');
            }

            file_put_contents('themes/'.$theme.'/'.$type.'/'.$page, $content);
            Flash::success(Lang::get('cms::lang.template.saved'));
        }

        else
        {
            Flash::warning(Lang::get('indikator.qedit::lang.widget.error_page'));
        }
    }
}
