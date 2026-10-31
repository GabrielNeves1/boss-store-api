<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Unidades de exemplo
|--------------------------------------------------------------------------
|
| Aparelhos reais das fotos e reels em fotos-originais. Preços, saúde da
| bateria, garantia e acessórios são EXEMPLOS a confirmar com a loja. O
| modelo da quarta unidade (só aparece em reel) também precisa ser confirmado.
| Em "media", "source" é o nome do arquivo original; "focal" é o ponto focal
| (x, y de 0 a 1) do recorte 4:5; "at" é o segundo do vídeo usado no quadro ou
| no poster.
|
*/

return [
    [
        'code' => 'BAS-0101',
        'category' => 'iphone',
        'model' => 'iPhone 15 Pro',
        'color' => 'Titânio azul',
        'storage_gb' => 256,
        'battery_health' => 91,
        'condition' => 'excellent',
        'warranty_months' => 3,
        'accessories' => ['Caixa original', 'Cabo USB-C'],
        'price_pix_cents' => 459_900,
        'price_card_cents' => 489_900,
        'max_installments' => 12,
        'status' => 'available',
        'featured' => true,
        'media' => [
            ['kind' => 'photo', 'source' => 'snapgram.io_ibzmkwl0urhm.jpg', 'focal' => [0.5, 0.45], 'alt' => 'iPhone 15 Pro titânio azul de costas no suporte, ao lado da caixa original, numa bancada ao sol'],
            ['kind' => 'frame', 'source' => 'snapgram.io_8ytcb1qo5qtj.mp4', 'at' => 4.1, 'focal' => [0.5, 0.5], 'alt' => 'Traseira do iPhone 15 Pro titânio azul na mão, com as três câmeras em primeiro plano'],
            ['kind' => 'frame', 'source' => 'snapgram.io_b7g10s3ixas7.mp4', 'at' => 7.7, 'focal' => [0.5, 0.5], 'alt' => 'Lateral do iPhone 15 Pro titânio azul mostrando o módulo de câmeras e a borda de titânio'],
            ['kind' => 'frame', 'source' => 'snapgram.io_b7g10s3ixas7.mp4', 'at' => 17.0, 'focal' => [0.5, 0.5], 'alt' => 'Tela do iPhone 15 Pro ligada na tela de bloqueio'],
            ['kind' => 'video', 'source' => 'snapgram.io_8ytcb1qo5qtj.mp4', 'at' => 4.1, 'alt' => 'O iPhone 15 Pro titânio azul girando na mão, mostrando cada lado à luz do dia'],
        ],
    ],
    [
        'code' => 'BAS-0102',
        'category' => 'iphone',
        'model' => 'iPhone 13',
        'color' => 'Rosa',
        'storage_gb' => 128,
        'battery_health' => 86,
        'condition' => 'excellent',
        'warranty_months' => 3,
        'accessories' => ['Caixa original', 'Cabo USB-C para Lightning'],
        'price_pix_cents' => 279_900,
        'price_card_cents' => 299_900,
        'max_installments' => 12,
        'status' => 'reserved',
        'featured' => true,
        'media' => [
            ['kind' => 'photo', 'source' => 'snapgram.io_rtcrkuj8vk3q.jpg', 'focal' => [0.55, 0.45], 'alt' => 'iPhone 13 rosa de costas no suporte, em frente à caixa original, numa bancada ao sol'],
            ['kind' => 'frame', 'source' => 'snapgram.io_4l5g5go1x5hx.mp4', 'at' => 5.5, 'focal' => [0.5, 0.5], 'alt' => 'Traseira do iPhone 13 rosa na mão'],
            ['kind' => 'frame', 'source' => 'snapgram.io_4l5g5go1x5hx.mp4', 'at' => 9.9, 'focal' => [0.5, 0.5], 'alt' => 'Tela do iPhone 13 rosa, desligada, na mão'],
            ['kind' => 'video', 'source' => 'snapgram.io_4l5g5go1x5hx.mp4', 'at' => 5.5, 'alt' => 'O iPhone 13 rosa girando na mão, mostrando cada lado à luz do dia'],
        ],
    ],
    [
        'code' => 'BAS-0103',
        'category' => 'iphone',
        'model' => 'iPhone 12',
        'color' => 'Preto',
        'storage_gb' => 64,
        'battery_health' => 82,
        'condition' => 'good',
        'warranty_months' => 3,
        'accessories' => ['Cabo USB-C para Lightning'],
        'price_pix_cents' => 189_900,
        'price_card_cents' => 204_900,
        'max_installments' => 10,
        'status' => 'available',
        'featured' => true,
        'media' => [
            ['kind' => 'photo', 'source' => 'snapgram.io_mjafn4j4ozds.jpg', 'focal' => [0.55, 0.45], 'alt' => 'iPhone 12 preto de costas no suporte, numa bancada ao sol com jardim ao fundo'],
            ['kind' => 'frame', 'source' => 'snapgram.io_iq9lejjkd1nv.mp4', 'at' => 6.3, 'focal' => [0.5, 0.5], 'alt' => 'Traseira do iPhone 12 preto na mão, contra o céu'],
            ['kind' => 'frame', 'source' => 'snapgram.io_iq9lejjkd1nv.mp4', 'at' => 13.8, 'focal' => [0.5, 0.5], 'alt' => 'Tela do iPhone 12 preto, desligada, na mão'],
            ['kind' => 'video', 'source' => 'snapgram.io_iq9lejjkd1nv.mp4', 'at' => 6.3, 'alt' => 'O iPhone 12 preto girando na mão, mostrando cada lado à luz do dia'],
        ],
    ],
    [
        'code' => 'BAS-0104',
        'category' => 'iphone',
        'model' => 'iPhone 16 Pro',
        'color' => 'Titânio deserto',
        'storage_gb' => 256,
        'battery_health' => 98,
        'condition' => 'like_new',
        'warranty_months' => 3,
        'accessories' => ['Caixa original', 'Cabo USB-C'],
        'price_pix_cents' => 629_900,
        'price_card_cents' => 669_900,
        'max_installments' => 12,
        'status' => 'sold',
        'featured' => false,
        'media' => [
            ['kind' => 'frame', 'source' => 'snapgram.io_n2k0vlq0i0cn.mp4', 'at' => 6.0, 'focal' => [0.5, 0.5], 'alt' => 'Traseira do iPhone 16 Pro titânio deserto na mão, contra o céu'],
            ['kind' => 'frame', 'source' => 'snapgram.io_n2k0vlq0i0cn.mp4', 'at' => 1.2, 'focal' => [0.5, 0.5], 'alt' => 'Lateral do iPhone 16 Pro titânio deserto mostrando a borda e os botões'],
            ['kind' => 'frame', 'source' => 'snapgram.io_n2k0vlq0i0cn.mp4', 'at' => 10.8, 'focal' => [0.5, 0.5], 'alt' => 'Tela do iPhone 16 Pro na mão, refletindo o céu'],
            ['kind' => 'video', 'source' => 'snapgram.io_n2k0vlq0i0cn.mp4', 'at' => 6.0, 'alt' => 'O iPhone 16 Pro titânio deserto girando na mão, mostrando cada lado à luz do dia'],
        ],
    ],
];
