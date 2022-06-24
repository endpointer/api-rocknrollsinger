<?php

namespace RockNRollSingerApi\Endpoint\Singer;

use RockNRollSingerApi\Endpoint\BasicEndpoint;
use RockNRollSingerApi\Errors;
use RockNRollSingerApi\RegExp;

class FindByNameEndpoint extends BasicEndpoint
{
    public function run()
    {

        $t      = $this;

        $t->setDbConnection();

        $rec =

            $t

            ->getSingerService()
            ->findByName(

                $t->getField('name')

            );

        if ($rec === null) {

            $t

                ->addError(Errors::SINGER_NOTFOUND)
                ->throwTransactionException();
        }

        unset($rec['id']);

        $t->setBody($rec);
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

(new FindByNameEndpoint())->processRequest();

//  curl -v local-rocknrollsinger.endpointer.com/singer/findByName/Bon+Scott | jq