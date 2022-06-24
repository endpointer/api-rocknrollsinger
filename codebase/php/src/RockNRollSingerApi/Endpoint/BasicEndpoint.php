<?php

namespace RockNRollSingerApi\Endpoint;

use RockNRollSingerApi\Constants;

use EndpointerFramework\Endpoint\BasicEndpoint as FMKBE;

use EndpointerFramework\Service\Db\DbConnection;
use EndpointerFramework\Service\Db\DbService;

use EndpointerFramework\Exception\InternalServerErrorException;
use EndpointerFramework\Exception\Db\TransactionException;

use RockNRollSingerApi\Service\Singer\SingerService;

abstract class BasicEndpoint extends FMKBE
{

    private $singerService = null;

    function setDbConnection()
    {

        $t = $this;

        $t

            ->setDbService(

                (new DBService())->setEndpoint($t)

            );

        try {

            $t

                ->getDbService()
                ->setDbConnection(

                    (new DbConnection())

                        ->connect(

                            $t->getDbConfig()

                        )->getConnection()

                );
        } catch (TransactionException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    function getEnv(

        $k // config key

    ) {

        return $_ENV[Constants::CONFIG_ID][$k];
    }

    function getField(

        $fieldName

    ) {

        $t = $this;

        return $t

            ->getRequest()
            ->getField(

                $fieldName

            );
    }

    function getSingerService()
    {

        $t = $this;

        if (

            $t->singerService === null

        ) {

            $t->singerService = (new SingerService())->setEndpoint($t);
        }

        return $t->singerService;
    }

    function setBody(

        $body

    ) {

        $t = $this;

        $t

            ->getResponse()
            ->setBody(

                $body

            );

        return $t;
    }

    function addError(

        $msg // error message

    ) {

        $t = $this;

        $t

            ->getErrors()
            ->addError(

                $msg

            );

        return $t;
    }

    function findSingerByName(

        $name // singer name

    ) {

        $t = $this;

        return

            $t

            ->getSingerService()
            ->findByName(

                $name

            );
    }

    function findSingerById(

        $id // singer id

    ) {

        $t = $this;

        return

            $t

            ->getSingerService()
            ->findById(

                $id

            );
    }

    function getDbConfig()
    {
        $t = $this;

        return [

            'dataSource' => $t->getEnv('dataSource'),

            'userName' => $t->getEnv('userName'),

            'password' => $t->getEnv('password'),

            'options' => Constants::DB_CONFIG_OPTIONS['options']

        ];
    }

    function getSingerId(

        $name

    ) {

        $t = $this;

        return (new SingerService())

            ->setEndpoint($t)
            ->findByName(

                $name

            )['id'];
    }
}
