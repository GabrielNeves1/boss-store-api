<?php

declare(strict_types=1);

return [
    'admin_skipped' => 'SEED_ADMIN_NAME, SEED_ADMIN_EMAIL e SEED_ADMIN_PASSWORD vazios no .env: nenhum usuário do painel foi criado.',
    'logo_missing' => 'Logo não encontrado em :path (visto de dentro do container). Configure SEED_ASSETS_HOST_PATH e SEED_LOGO_*_PATH no .env.',
    'catalog_media_skipped' => 'Pasta de originais do catálogo não montada: configure CATALOG_MEDIA_HOST_PATH e CATALOG_MEDIA_SOURCE_PATH no .env e recrie o container. Mídia não gerada.',
];
