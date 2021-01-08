<?php

return [
    'menu' => [
        'label' => 'Segmentation',
        'description' => "Gestion des segmentations et des calculs",
        'settings' => "Optiosn segmentations",
        'settings_description' => "Réglage des valeurs par defaut",
        'settings_category' => 'Wakaari Modèle',
        'settings_category_options' => 'Wakaari Options',
        "calcul_btn" => "Calculer le Tag",
    ],
    'tag' => [
        'name' => 'Nom de la segmentation',
        'slug' => 'Slug',
        'auto_create' => 'Création automatique',
        'data_source' => 'Source des données',
        'options' => 'Options',
        'data_source_placeholder' => '--Choisissez une source--',
        'is_hidden' => "Caché ? ",
        'is_active' => "Actif ?",
        'auto_class_calculs' => "Ajouter automatiquement la class de calculs du tag",
        'class_calculs' => "Class de calculs",
        'calculs' => "Ajouter des calculs",
        "only_tag" => "Limité la verification au cible avec le(s) tag(s)",

    ],
    'popup' => [
        'title' => 'Segmentation',
    ],
];
