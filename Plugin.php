<?php namespace Indikator\Qedit;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'indikator.qedit::lang.plugin.name',
            'description' => 'indikator.qedit::lang.plugin.description',
            'author'      => 'indikator.qedit::lang.plugin.author'
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Indikator\Qedit\ReportWidgets\Qedit' => [
                'label'   => 'indikator.qedit::lang.plugin.name',
                'context' => 'dashboard'
            ]
        ];
    }
}
