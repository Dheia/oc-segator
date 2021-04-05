<?php
return [
    'btns' => [
        'tagSelection' => [
            'label' => 'Calculer Tag de la sélection',
            'class' => 'btn-secondary',
            'ajaxCaller' => 'onCallTagCalculsSelected',
            'icon' => 'oc-icon-filter',
        ],
        'tagModel' => [
            'label' => 'Calculer tous les tags',
            'class' => 'btn-secondary',
            'ajaxCaller' => 'onCallTagCalculsModel',
            'icon' => 'oc-icon-filter',
        ],
        'tagAll' => [
            'label' => 'Calculer tous les tags',
            'class' => 'btn-secondary',
            'ajaxCaller' => 'onCallTagCalculsAll',
            'icon' => 'oc-icon-filter',
        ],
    ],
];
