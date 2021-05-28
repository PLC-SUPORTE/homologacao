<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'remote-sftp',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
            
        ],
        'correspondente-contratacao' => [
            'driver' => 'local',
            'root' => storage_path('app/public/correspondente/contratacao'),
            'visibility' => 'public',
        ],
        'pesquisapatrimonial-imovel' => [
            'driver' => 'local',
            'root' => storage_path('pesquisapatrimonial/imovel'),
            'visibility' => 'public',
            
        ],
        'pesquisapatrimonial-veiculo' => [
            'driver' => 'local',
            'root' => storage_path('pesquisapatrimonial/veiculo'),
            'visibility' => 'public',
        ],
        'pesquisapatrimonial-boleto' => [
            'driver' => 'local',
            'root' => storage_path('app/public/boletos'),
            'visibility' => 'public',
        ],
        'pesquisapatrimonial-notadebito' => [
            'driver' => 'local',
            'root' => storage_path('app/public/boletos/notadebito'),
            'visibility' => 'public',
        ],
        'pesquisapatrimonial-empresa' => [
            'driver' => 'local',
            'root' => storage_path('pesquisapatrimonial/empresa'),
            'visibility' => 'public',
        ],
        'reembolso-local' => [
            'driver' => 'local',
            'root'   => public_path() . '/imgs/reembolso',
            'url' => env('APP_URL').'/public/imgs',
            'visibility' => 'public',
        ],
        'pesquisapatrimonial-local' => [
            'driver' => 'local',
            'root'   => public_path() . '/imgs/pesquisapatrimonial',
            'url' => env('APP_URL').'/public/pesquisapatrimonial',
            'visibility' => 'public',
        ],
        'gestao-contrato' => [
            'driver' => 'local',
            'root' => storage_path('gestao/contrato'),
            'visibility' => 'public',
            
        ],
        'public' => [
            'driver' => 'teste',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'remote-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal',
        ],
    'solicitacaopagamento-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/solicitacaopagamento',
    ],
       'marketing-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/marketing',
    ],
       'treinamento-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/treinamentos',
    ],
      'software-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/softwares',
    ],
    'comprovantepagamento-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/comprovantepagamento',
    ],
    'pesquisapatrimonial-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/pesquisapatrimonial',
    ],
    'pesquisapatrimonial-boletos-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/pesquisapatrimonial/boletos',
    ],
    'guias-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv02',
        'password' => 'Plc@2020++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/',
    ],
    'propostas-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/propostas',
    ],
    'gestao-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/gestao',
    ],
    'associacao-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/associacaonf',
    ],
    'reembolso-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/reembolso',
    ],
    'compras-solicitante-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/compras/solicitante',
    ],
    'compras-comite-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/compras/comitecompras',
    ],
    'aprovacao-comite-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/compras/comiteaprovacao',
    ],
    'contratacao-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/contratacao',
    ],
    'contratacao-local' => [
        'driver' => 'local',
        'root' => storage_path('movimentacaopessoas/contratacao'),
        'visibility' => 'public',
    ],
    'ferias-sftp' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/ferias',
    ],
    'ferias-local' => [
        'driver' => 'local',
        'root' => storage_path('movimentacaopessoas/ferias'),
        'visibility' => 'public',
    ],
    'solicitacoes-local' => [
        'driver' => 'local',
        'root' => storage_path('escritorio/solicitacoes'),
        'visibility' => 'public',
    ],
    'solicitacoescompra' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/solicitacoescompra',
    ],
    'guiascusta' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/guiascustas',
    ],
    'guiascusta-sftp2' => [
        'driver' => 'sftp',
        'host' => 'advwin.plcadvogados.com.br',
        'username' => 'ftp.plcadv',
        'password' => 'Plc4V@kL10++',
        'visibility' => 'public',
        'port' => 42222,
        'permPublic' => 0766,
        'root' => '/Vault$/portal/guiascusta',
    ],

],

];
