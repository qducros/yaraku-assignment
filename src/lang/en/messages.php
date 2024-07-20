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
            'label' => 'Search by Title',
            'placeholder' => 'Search a book title',
        ],
        'author' => [
            'label' => 'Search by Author',
            'placeholder' => 'Search a book author',
        ],
    ],
    'action' => [
        'bulk' => [
            'placeholder' => 'Bulk action...',
            'delete' => [
                'select' => 'Delete selection',
                'info' => 'Are you sure you want to delete the selected books? You won\'t be able to go back.',
            ],
            'export' => [
                'select' => 'Export selection',
                'button' => 'Export',
                'info' => 'You are about to export only the selected books.',
            ],
        ],
        'create' => [
            'button' => 'Create',
            'title' => [
                'label' => 'Title',
                'placeholder' => 'Book title',
                'error' => [
                    'required' => 'The book title is required.',
                    'min' => 'The book title is too short.',
                ],
            ],
            'author' => [
                'label' => 'Author',
                'placeholder' => 'Book author',
                'error' => [
                    'required' => 'The book author is required.',
                    'min' => 'The book author is too short.',
                ],
            ]
        ],
        'edit' => [
            'button' => 'Edit'
        ],
        'delete' => [
            'button' => 'Delete',
            'button_confirm' => 'Yes, delete',
            'info' => 'Are you sure you want to delete this book? You won\'t be able to go back.',
        ],
        'export_all' => [
            'button' => 'Export all',
            'info' => 'You are about to export all books regardless of the table filters.',
            'select' => [
                'fields' => [
                    'all' => 'Title and author',
                    'title' => 'Title only',
                    'author' => 'Author only',
                ],
                'filteype' => [
                    'csv' => 'Export .csv',
                    'xml' => 'Export .xml',
                ],
            ],
        ],
        'cancel' => 'Cancel',
    ],
    'table' => [
        'header' => [
            'title' => 'Title',
            'author' => 'Author',
            'last_modified' => 'Last modified',
        ],
        'selection' => [
            'all' => 'All books from the search are selected.',
            'page' => 'book(s) on this page are selected.',
            'select' => 'Select all',
            'deselect' => 'Deselect all'
        ],
        'no_results' => 'No books found.',
        'pagination' => [
            'per_page' => 'per page',
        ],
    ],
    'feedback' => [
        'success' => [
            'create' => 'The book was created successfully.',
            'edit' => 'The book was edited successfully.',
            'delete' => 'The book was deleted successfully.',
            'delete_bulk' => 'All selected books were deleted successfully.',
            'export_all' => 'All books were exported successfully.',
            'export_bulk' => 'All selected books were exported successfully.',
        ],
        'warning' => [
            'no_selection' => 'You need at least one selected book to perform this action.',
        ],
    ],
    'locale' => [
        'english' => 'English',
        'japanese' => 'Japanese',
        'french' => 'French',
        'spanish' => 'Spanish',
    ],
];
