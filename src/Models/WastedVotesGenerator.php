<?php
/**
 * User: Junade Ali
 * Date: 06/04/15
 * Time: 04:44
 */

namespace WastedVotes\Models;

class WastedVotesGenerator {

    public function populateTable () {

        $constituencies =
            \Illuminate\Database\Capsule\Manager
                ::table('general_election_2010')
                ->lists('Constituency_Name');

        foreach ($constituencies as $constituency) {
            $this->getVoterPower($constituency);
        }

    }

    protected function getVoterPower ($constituency) {
        $averageConstituencySize = $this->getAverageConstituencyElectorate();
        $electorate = $this->getConstituencyElectorate($constituency);

        $relativeConstituencySize = $averageConstituencySize/$electorate;

        $marginalityResult = $this->getMarginality($constituency);
        $marginality = $marginalityResult['marginality'];

        $marginalityIndex = $marginality*$relativeConstituencySize;

        \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')->insert(
                array (
                    'constituency' => $constituency,
                    'wasted_votes' => $marginalityResult['wasted_votes'],
                    'marginality' => $marginality,
                    'marginality_with_size' => $marginalityIndex
                )
            );
    }

    private function getConstituencyElectorate ($constituency) {
        $electorate = \Illuminate\Database\Capsule\Manager
            ::table('general_election_2010')
            ->where('Constituency_Name', '=', $constituency)
            ->pluck('Electorate');

        $electorate = intval($electorate);

        if (is_int($electorate)) {
            return $electorate;
        } else {
            throw new \Exception ("getConstituencyElectorate got electorate which was not an integer.");
        }
    }

    private function getAverageConstituencyElectorate () {
        return 70149.94;
        //Value cached for speed, you can find this using the query below:
        /*var_dump(\Illuminate\Database\Capsule\Manager
            ::table('general_election_2010')
            ->avg('Electorate'));*/
    }

    private function getMarginality ($constituency) {

        $abbreviations = \Illuminate\Database\Capsule\Manager
            ::table('party_abbreviations_2010')
            ->select(array('Abbreviation'))
            ->get();

        $columns = array();

        foreach ($abbreviations as $column) {
            array_push($columns, $column['Abbreviation']);
        }

        $results = \Illuminate\Database\Capsule\Manager
            ::table('general_election_2010')
            ->where('Constituency_Name', '=', $constituency)
            ->select($columns)
            ->first();

        foreach ($results as $key => $result) {
            if (is_null($result)) {
                unset($results[$key]);
            }
        }

        arsort($results);
        $results = array_slice($results, 0, 3);

        reset($results);
        $best = key($results);
        next($results);
        $secondBest = key($results);
        next($results);
        $thirdBest = key($results);

        $wastedVotesQuery = \Illuminate\Database\Capsule\Manager
            ::table('general_election_2010')
            ->where('Constituency_Name', '=', $constituency)
            ->select(array('Votes', $secondBest))
            ->first();

        $wastedVotes = $wastedVotesQuery['Votes'] - $wastedVotesQuery[$secondBest];

        $return = array();
        $return['wasted_votes'] = $wastedVotes;

        reset($results);

        $marginality = (($results[$secondBest] + $results[$thirdBest])/2)/$results[$best];

        // This version would use harmonic means instead to generate marginality:
        //$marginality = (2/(1/($results[$secondBest]) + (1/$results[$thirdBest])))/$results[$best];

        $return['marginality'] = $marginality;

        return $return;

    }

}