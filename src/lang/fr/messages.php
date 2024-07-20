<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Project Specific Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for the current project.
    |
    */

    'search' => [
        'title' => [
            'label' => 'Recherche par Titre',
            'placeholder' => 'Rechercher un titre de livre',
        ],
        'author' => [
            'label' => 'Recherche par Auteur',
            'placeholder' => 'Rechercher un auteur',
        ],
    ],
    'action' => [
        'bulk' => [
            'placeholder' => 'Action groupée...',
            'delete' => [
                'select' => 'Supprimer la sélection',
                'info' => 'Êtes-vous sûr de vouloir supprimer les livres sélectionnés ? Vous ne pourrez pas revenir en arrière.',
            ],
            'export' => [
                'select' => 'Exporter la sélection',
                'button' => 'Exporter',
                'info' => 'Vous êtes sur le point d\'exporter uniquement les livres sélectionnés.',
            ],
        ],
        'create' => [
            'button' => 'Créer',
            'title' => [
                'label' => 'Titre',
                'placeholder' => 'Titre du livre',
                'error' => [
                    'required' => 'Le titre du livre est obligatoire.',
                    'min' => 'Le titre du livre est trop court.',
                ],
            ],
            'author' => [
                'label' => 'Auteur',
                'placeholder' => 'Auteur du livre',
                'error' => [
                    'required' => 'L\'auteur est obligatoire.',
                    'min' => 'Le nom de l\'auteur est trop court.',
                ],
            ],
        ],
        'edit' => [
            'button' => 'Modifier',
        ],
        'delete' => [
            'button' => 'Supprimer',
            'button_confirm' => 'Oui, supprimer',
            'info' => 'Etes-vous sûr de vouloir supprimer ce livre? Vous ne pourrez pas revenir en arrière.',
        ],
        'export_all' => [
            'button' => 'Tout exporter',
            'info' => 'Vous êtes sur le point d\'exporter tous les livres quels que soient les filtres du tableau.',
            'select' => [
                'fields' => [
                    'all' => 'Titre et auteur',
                    'title' => 'Titre uniquement',
                    'author' => 'Auteur uniquement',
                ],
                'filteype' => [
                    'csv' => 'Exporter .csv',
                    'xml' => 'Exporter .xml',
                ],
            ],
        ],
        'cancel' => 'Annuler',
    ],
    'table' => [
        'header' => [
            'title' => 'Titre',
            'author' => 'Auteur',
            'last_modified' => 'Dernière modification',
        ],
        'selection' => [
            'all' => 'Tous les livres de la recherche sont sélectionnés.',
            'page' => 'livre(s) sur cette page sont sélectionnés.',
            'select' => 'Tout sélectionner',
            'deselect' => 'Tout déselectionner',
        ],
        'no_results' => 'Aucun livre trouvé.',
        'pagination' => [
            'per_page' => 'par page',
        ],
    ],
    'feedback' => [
        'success' => [
            'create' => 'Le livre a été créé avec succès.',
            'edit' => 'Le livre a été modifié avec succès.',
            'delete' => 'Le livre a été supprimé avec succès.',
            'delete_bulk' => 'Tous les livres sélectionnés ont été supprimés avec succès.',
            'export_all' => 'Tous les livres ont été exportés avec succès.',
            'export_bulk' => 'Tous les livres sélectionnés ont été exportés avec succès.',
        ],
        'warning' => [
            'no_selection' => 'Vous avez besoin d\'au moins un livre sélectionné pour effectuer cette action.',
        ],
    ],
    'locale' => [
        'english' => 'Anglais',
        'japanese' => 'Japonais',
        'french' => 'Français',
        'spanish' => 'Espagnol',
    ],
];
