<?php

return [
    /* 要件通りの文言設定 */
    'required' => ':attributeを入力してください',
    'email'    => ':attributeはメール形式で入力してください',
    'min'      => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'confirmed' => 'パスワードと一致しません',

    /* 項目名の日本語化 */
    'attributes' => [
        'name'     => 'お名前',
        'email'    => 'メールアドレス',
        'password' => 'パスワード',
    ],

    /* その他（必要に応じて） */
    'unique' => 'この:attributeはすでに使用されています',
];
