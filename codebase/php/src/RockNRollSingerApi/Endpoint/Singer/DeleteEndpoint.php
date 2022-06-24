<?php

namespace RockNRollSingerApi\Endpoint\Singer;

use RockNRollSingerApi\RegExp;
use RockNRollSingerApi\Errors;

use RockNRollSingerApi\Endpoint\BasicEndpoint;

use EndpointerFramework\Exception\Db\NoRecordFoundException;
use EndpointerFramework\Exception\InternalServerErrorException;

class DeleteEndpoint extends BasicEndpoint
{
    public function run()
    {

        $t   = $this;

        $t->setDbConnection();

        $dbs = $t->getDbService();

        try {
            $dbs->begin();

            $srv = $t->getSingerService();

            $rec = $srv->findByName(

                $t->getField('name')

            );

            if (

                $rec === null

            ) {

                throw new NoRecordFoundException();
            }

            $srv->delete(

                $rec['id']

            );

            $dbs->commit();
        } catch (NoRecordFoundException $ex) {
            $dbs->rollBack();

            $t->addError(

                Errors::SINGER_NOTFOUND

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

(new DeleteEndpoint())->processRequest();

//  curl -v -X POST local-rocknrollsinger.endpointer.com/singer/delete -H "Content-Type: application/json" -d '{"name": "Bon Scot"}' | jq