<?php

namespace RockNRollSingerApi;

class Constants
{

    public const CONFIG_ID     = 'ENDPOINTER_CONFIG';

    public const DB_CONFIG_OPTIONS = [

        'options'      =>  [

            \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,

            \PDO::ATTR_DEFAULT_FETCH_MODE   => \PDO::FETCH_ASSOC,

            \PDO::ATTR_EMULATE_PREPARES     => false

        ]

    ];
}
