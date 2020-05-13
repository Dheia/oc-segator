<?php namespace Waka\Segator\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Tags Back-end Controller
 */
class Tags extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Waka.Utils.Behaviors.DuplicateModel',
        'Backend.Behaviors.ReorderController',
        'Waka.Segator.Behaviors.CalculTags',

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';

    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Waka.Segator', 'segator', 'tags');
    }
}
