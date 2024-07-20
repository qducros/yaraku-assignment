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
            'label' => 'Buscar por título',
            'placeholder' => 'Buscar el título de un libro',
        ],
        'author' => [
            'label' => 'Buscar por autor',
            'placeholder' => 'Buscar un autor de libro',
        ],
    ],
    'action' => [
        'bulk' => [
            'placeholder' => 'Acción masiva...',
            'delete' => [
                'select' => 'Borrar la selección',
                'info' => '¿Estás seguro de que deseas borrar los libros seleccionados? No podrás volver atrás.',
            ],
            'export' => [
                'select' => 'Exportar la selección',
                'button' => 'Exportar',
                'info' => 'Estás a punto de exportar solo los libros seleccionados.',
            ],
        ],
        'create' => [
            'button' => 'Crear',
            'title' => [
                'label' => 'Título',
                'placeholder' => 'Titulo del libro',
                'error' => [
                    'required' => 'Se requiere el título del libro.',
                    'min' => 'El título del libro es demasiado corto.',
                ],
            ],
            'author' => [
                'label' => 'Autor',
                'placeholder' => 'Autor del libro',
                'error' => [
                    'required' => 'Se requiere el autor del libro.',
                    'min' => 'El nombre del autor es demasiado corto.',
                ],
            ]
        ],
        'edit' => [
            'button' => 'Editar'
        ],
        'delete' => [
            'button' => 'Borrar',
            'button_confirm' => 'Sí, borrar',
            'info' => '¿Estás seguro de que deseas borrar este libro? No podrás volver atrás.',
        ],
        'export_all' => [
            'button' => 'Exportar todo',
            'info' => 'Está a punto de exportar todos los libros independientemente de los filtros de la tabla.',
            'select' => [
                'fields' => [
                    'all' => 'Título y autor',
                    'title' => 'Sólo titulo',
                    'author' => 'Solo autor',
                ],
                'filteype' => [
                    'csv' => 'Exportar .csv',
                    'xml' => 'Exportar .xml',
                ],
            ],
        ],
        'cancel' => 'Cancelar',
    ],
    'table' => [
        'header' => [
            'title' => 'Título',
            'author' => 'Autor',
            'last_modified' => 'Última modificación',
        ],
        'selection' => [
            'all' => 'Se seleccionan todos los libros de la búsqueda.',
            'page' => 'libro(s) en esta página estan seleccionado.',
            'select' => 'Seleccionar todo',
            'deselect' => 'Deseleccionar todo'
        ],
        'no_results' => 'No se encontraron libros.',
        'pagination' => [
            'per_page' => 'por página',
        ],
    ],
    'feedback' => [
        'success' => [
            'create' => 'El libro fue creado correctamente.',
            'edit' => 'El libro fue editado correctamente.',
            'delete' => 'El libro fue eliminado correctamente.',
            'delete_bulk' => 'Todos los libros seleccionados fue eliminados correctamente.',
            'export_all' => 'Todos los libros fue exportados correctamente.',
            'export_bulk' => 'Todos los libros seleccionados fue exportados correctamente.',
        ],
        'warning' => [
            'no_selection' => 'Necesita al menos un libro seleccionado para realizar esta acción.',
        ],
    ],
    'locale' => [
        'english' => 'Inglés',
        'japanese' => 'Japonés',
        'french' => 'Francés',
        'spanish' => 'Español',
    ],
];
