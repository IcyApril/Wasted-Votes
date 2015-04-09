<?php
/**
 * User: Junade Ali
 * Date: 07/04/15
 * Time: 21:49
 */

namespace WastedVotes\Models;

class Results {

    public function getTopFiveAreas () {

        $topAreas = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->orderBy('marginality_with_size', 'DESC')
            ->limit(5)
            ->lists('constituency');

        return $topAreas;
    }

    public function getBottomFiveAreas () {

        $bottomAreas = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->orderBy('marginality_with_size', 'ASC')
            ->limit(5)
            ->lists('constituency');

        $bottomAreas = array_reverse($bottomAreas);

        return $bottomAreas;
    }

    public function getWastedVotes ($constituency) {

        $wasted = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->where('constituency', $constituency)
            ->pluck('wasted_votes');

        return $wasted;

    }

    public function getMarginality ($constituency) {

        $marginality = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->where('constituency', $constituency)
            ->pluck('marginality');

        $marginality = round($marginality, 2);

        return $marginality;

    }

    public function getMarginalityWithSize ($constituency) {

        $marginality = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->where('constituency', $constituency)
            ->pluck('marginality_with_size');

        $marginality = round($marginality, 2);

        return $marginality;

    }

    public function getRank ($constituency) {

        $rank = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->where('marginality_with_size', '>', $this->getMarginalityWithSize($constituency))
            ->count();

        $rank++;

        return $rank;

    }

    public function getTotalVotes ($constituency) {

        $size = \Illuminate\Database\Capsule\Manager
            ::table('general_election_2010')
            ->where('Constituency_Name', $constituency)
            ->pluck('Votes');

        return $size;

    }

}