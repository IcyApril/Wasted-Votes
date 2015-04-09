<?php
/**
 * User: Junade Ali
 * Date: 06/04/15
 * Time: 04:40
 */

namespace WastedVotes\Models;

class Base {

    public $capsule;

    function connectDB () {

        $capsule = new \Illuminate\Database\Capsule\Manager;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'wastedvotes',
            'username'  => 'wastedvotes',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        // Set the event dispatcher used by Eloquent models... (optional)

        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        $this->capsule = $capsule;

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }

    public function listAllConstituencies () {
        $constituencies =
            \Illuminate\Database\Capsule\Manager
                ::table('general_election_2010')
                ->lists('Constituency_Name');

        return $constituencies;
    }
}