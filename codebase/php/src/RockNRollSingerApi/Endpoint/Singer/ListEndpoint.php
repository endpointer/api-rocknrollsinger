<?php

namespace RockNRollSingerApi\Endpoint\Singer;

use RockNRollSingerApi\Endpoint\BasicEndpoint;

class ListEndpoint extends BasicEndpoint
{
    public function run()
    {

        $t      = $this;

        $t->setDbConnection();

        $list =

            $t

            ->getSingerService()
            ->list(

                $t->getField('name')

            );

        foreach ($list as &$rec) {

            unset($rec['id']);
        }

        $t

            ->getResponse()
            ->setBody(

                $list

            );
    }
}

(new ListEndpoint())->processRequest();

//  curl -G -v local-rocknrollsinger.endpointer.com/singer/list?name=Bon+Scott | jq