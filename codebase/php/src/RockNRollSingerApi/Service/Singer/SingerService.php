<?php

namespace RockNRollSingerApi\Service\Singer;

use EndpointerFramework\Service\BasicService;
use EndpointerFramework\Exception\Db\NoRecordFoundException;
use EndpointerFramework\Exception\Db\AlreadyCreatedException;

class SingerService extends BasicService
{

    public function findById(

        $id // singer id

    ) {
        $t = $this;

        $ep = $t->getEndpoint();
        $dbs = $ep->getDbService();

        try {

            $dbs->queryRow(

                \dirname(__FILE__) . '/findby_id.sql',

                [

                    'id'     => $id

                ]

            );

            return $dbs->getRow();
        } catch (NoRecordFoundException $ex) {

            return null;
        }
    }

    public function findByName(

        $name // singer name

    ) {
        $t = $this;

        $ep = $t->getEndpoint();
        $dbs = $ep->getDbService();

        try {

            $dbs->queryRow(

                \dirname(__FILE__) . '/findby_name.sql',

                [

                    'name'           => $name

                ]

            );

            return $dbs->getRow();
        } catch (NoRecordFoundException $ex) {

            return null;
        }
    }

    public function updateName(

        $name,   // singer name
        $id      // singer id

    ) {
        $t    = $this;

        $ep    = $t->getEndpoint();
        $dbs    = $ep->getDbService();

        $dbs->update(

            \dirname(__FILE__) . '/update_name.sql',

            [

                'name' => $name,

                'id'   => $id

            ]

        );

        return $dbs->getRowCount();
    }

    public function delete(

        $id      // singer id

    ) {
        $t    = $this;

        $ep    = $t->getEndpoint();
        $dbs    = $ep->getDbService();

        $dbs->update(

            \dirname(__FILE__) . '/delete.sql',

            [

                'id'   => $id

            ]

        );

        return $dbs->getRowCount();
    }

    public function list(

        $name // singer name or ''

    ) {
        $t = $this;

        $ep = $t->getEndpoint();
        $dbs = $ep->getDbService();

        if (

            preg_match('/.+/', $name)

        ) {

            $sql = "

            select * from singer
            
             where (upper(name) like upper(concat('%', :name, '%')))
            
             order by name
             
            ";

            $dbs->queryRowset(

                $sql,

                [

                    'name'    => $name

                ],

                false

            );
        } else {

            $sql = "

            select * from singer
            
             order by name
             
            ";

            $dbs->queryRowset(

                $sql,

                [],

                false

            );
        }

        return $dbs->getRowset();
    }

    public function create(

        $name          // singer name

    ) {
        $t    = $this;

        $ep    = $t->getEndpoint();
        $dbs    = $ep->getDbService();

        if (

            $t->isAlreadyCreated(

                $name

            )

        ) {

            throw new AlreadyCreatedException();
        }

        $dbs->insert(

            \dirname(__FILE__) . '/create.sql',

            [

                'name'     => $name

            ]

        );

        return $dbs->getLastInsertId();
    }

    public function isAlreadyCreated(

        $name           // singer name

    ) {

        $t = $this;

        return

            $t->findByName(

                $name

            ) ?
            true :
            false;
    }
}
