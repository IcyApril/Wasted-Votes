<?php

class ResultsTest extends PHPUnit_Framework_TestCase {

	public function testTopFives () {
        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $results = new WastedVotes\Models\Results();

        $top = $results->getTopFiveAreas();
        $bottom = $results->getBottomFiveAreas();

        $areArrays = $top && $bottom;

        $this->assertEquals($areArrays, true);
    }

    public function testGetWastedVotes () {

        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $results = new WastedVotes\Models\Results();

        $wastedvotes = $results->getWastedVotes("Rugby");

        $this->assertGreaterThan(0, $wastedvotes);
    }

    public function testGetMarginality () {

        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $results = new WastedVotes\Models\Results();

        $marginality = $results->getMarginality("Rugby");

        $this->assertGreaterThan(0, $marginality);
    }

    public function testGetMarginalityWithSize () {

        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $results = new WastedVotes\Models\Results();

        $marginality = $results->getMarginalityWithSize("Rugby");

        $this->assertGreaterThan(0, $marginality);
    }

    public function testRank () {

        $base = new \WastedVotes\Models\Base();
        $base->connectDB();
        $results = new WastedVotes\Models\Results();

        $rank = $results->getRank("Na h-Eileanan an Iar (Western Isles)");
        $this->assertEquals($rank, 1);

        $rank = $results->getRank("Arfon");
        $this->assertEquals(2, $rank);

        $rank = $results->getRank("Kirkcaldy & Cowdenbeath");
        $this->assertEquals(646, $rank);

        $rank = $results->getRank("Knowsley");
        $this->assertEquals(650, $rank);
    }

}