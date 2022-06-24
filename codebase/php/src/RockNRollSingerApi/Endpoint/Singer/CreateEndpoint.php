<?php

namespace RockNRollSingerApi\Endpoint\Singer;

use EndpointerFramework\Exception\Db\AlreadyCreatedException;
use EndpointerFramework\Exception\InternalServerErrorException;
use RockNRollSingerApi\Errors;
use RockNRollSingerApi\RegExp;

use RockNRollSingerApi\Endpoint\BasicEndpoint;

class CreateEndpoint extends BasicEndpoint
{
    public function run()
    {

        $t   = $this;

        $t->setDbConnection();

        $dbs = $t->getDbService();

        try {
            $dbs->begin();

            $srv = $t->getSingerService();

            if (

                $srv->isAlreadyCreated(

                    $t->getField('name')

                )

            ) {

                throw new AlreadyCreatedException();
            }

            $srv->create(

                $t->getField('name')

            );

            $dbs->commit();
        } catch (AlreadyCreatedException $ex) {
            $dbs->rollBack();

            $t->addError(

                Errors::SINGER_ALREADYCREATED

            );

            $t->throwTransactionException();
        } catch (InternalServerErrorException $ex) {
            $dbs->rollBack();

            throw $ex;
        }
    }

    public function validateInput()
    {

        $t = $this;

        if (

            !preg_match(

                RegExp::SINGER_NAME,

                $t->getField('name') // singer name

            )

        ) {

            $t->addError(

                Errors::SINGER_INVALIDNAME

            );
        }

        $t

            ->getErrors()
            ->throwInvalidInputException();
    }
}

(new CreateEndpoint())->processRequest();

//  curl -X POST local-rocknrollsinger.endpointer.com/singer/create -H "Content-Type: application/json" -d '{"name": "Bon Scott"}'