<?php
/**
 * User: Junade Ali
 * Date: 08/04/15
 * Time: 22:04
 */

class ResultsTest extends PHPUnit_Framework_TestCase {

        public function testSearchSuccess () {
            $base = new \WastedVotes\Models\Base();
            $base->connectDB();
            $search = new \WastedVotes\Models\Search();
            $constituency = $search->getConstituency("Rugby");


            $this->assertEquals($constituency, 'Rugby');
        }

        public function testPostcodeSearch () {
            $base = new \WastedVotes\Models\Base();
            $base->connectDB();
            $search = new \WastedVotes\Models\Search();
            $constituency = $search->getConstituency("CV21 1RQ");

            $this->assertEquals($constituency, 'Rugby');
        }

    public function testFailSearch () {
        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $search = new \WastedVotes\Models\Search();
        $constituency = $search->getConstituency("thisshouldfail");


        $this->assertEquals($constituency, FALSE);
    }
}