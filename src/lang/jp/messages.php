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
        'clear' => 'クリア',
        'title' => [
            'label' => 'タイトルから探す',
            'placeholder' => '本のタイトルを検索する',
        ],
        'author' => [
            'label' => '著者から探す',
            'placeholder' => '本の著者を検索する',
        ],
    ],
    'action' => [
        'bulk' => [
            'placeholder' => '一括操作...',
            'delete' => [
                'select' => '選択範囲を削除',
                'info' => '選択した書籍を削除してもよろしいですか?もう後戻りはできなくなります。',
            ],
            'export' => [
                'select' => '選択内容をエクスポート',
                'button' => 'エクスポート',
                'info' => '選択した書籍のみをエクスポートしようとしています。',
            ],
        ],
        'create' => [
            'button' => '作成',
            'title' => [
                'label' => 'タイトル',
                'placeholder' => '本のタイトル',
                'error' => [
                    'required' => '本のタイトルは必須です。',
                    'min' => '本のタイトルが短すぎます。',
                ],
            ],
            'author' => [
                'label' => '著者',
                'placeholder' => '本の著者',
                'error' => [
                    'required' => '著者名は必須です。',
                    'min' => '著者名が短すぎます。',
                ],
            ],
        ],
        'edit' => [
            'button' => '編集',
        ],
        'delete' => [
            'button' => '削除',
            'button_confirm' => 'はい、削除',
            'info' => 'この本を削除してもよろしいですか?もう後戻りはできなくなります。',
        ],
        'export_all' => [
            'button' => 'すべてエクスポート',
            'info' => 'テーブル フィルターに関係なく、すべての書籍をエクスポートしようとしています。',
            'select' => [
                'fields' => [
                    'all' => 'タイトルと著者',
                    'title' => 'タイトルのみ',
                    'author' => '著者のみ',
                ],
                'filteype' => [
                    'csv' => 'エクスポート .csv',
                    'xml' => 'エクスポート .xml',
                ],
            ],
        ],
        'cancel' => 'キャンセル',
    ],
    'table' => [
        'header' => [
            'title' => 'タイトル',
            'author' => '著者',
            'last_modified' => '最終変更',
        ],
        'selection' => [
            'all' => '検索結果のすべての書籍が選択されます。',
            'page' => '冊すべてこのページ内が選択されています。',
            'select' => 'すべて選択',
            'deselect' => 'すべての選択を解除',
        ],
        'no_results' => '本が見つかりませんでした。',
        'pagination' => [
            'per_page' => '冊ずつ',
        ],
    ],
    'feedback' => [
        'success' => [
            'create' => '本は正常に作成されました。',
            'edit' => '本は正常に編集されました。',
            'delete' => '本は正常に削除されました。',
            'delete_bulk' => '選択したすべての書籍が正常に削除されました。',
            'export_all' => 'すべての書籍が正常にエクスポートされました。',
            'export_bulk' => '選択したすべての書籍が正常にエクスポートされました。',
        ],
        'warning' => [
            'no_selection' => 'この操作を実行するには、少なくとも 1 冊の選択した本が必要です。',
        ],
    ],
    'locale' => [
        'english' => '英語',
        'japanese' => '日本語',
        'french' => 'フランス語',
        'spanish' => 'スペイン語',
    ],
    'top' => 'トップ',
];
