<?php
/**
 * User: Junade Ali
 * Date: 07/04/15
 * Time: 23:51
 */

namespace WastedVotes\Models;

class Search {

    public function getConstituency ($search) {

        if ($this->isPostcode($search) == true) {
            $search = $this->postcodeToConstituency($search);
            if ($search == false) {
                return false;
            }
        }

        $constituency = \Illuminate\Database\Capsule\Manager
            ::table('wasted_votes_2010')
            ->orderBy('marginality_with_size', 'DESC')
            ->where('constituency', 'LIKE', '%'.$search.'%')
            ->pluck('constituency');


        if (!empty($constituency) && (strlen($constituency)>0)) {
            return $constituency;
        } else {
            return false;
        }

    }

    public function postcodeToConstituency ($postcode) {

        // Get cURL resource
        $curl = \curl_init();
        // Set some options - we are passing in a useragent too here
        \curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://www.theyworkforyou.com/api/getConstituency',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'postcode' => $postcode,
                'key' => 'Ft2qHtB4SQVSEUUbjCF33Auh'
            )
        ));
        // Send the request & save response to $resp
        $resp = \curl_exec($curl);
        // Close request to clear up some resources
        \curl_close($curl);

        $data = json_decode($resp);
        return $data->guardian_name;
    }

    public function isPostcode($postcode) {
        $postcode = strtoupper(str_replace(' ','',$postcode));
        if(preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/",$postcode) || preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/",$postcode) || preg_match("/^GIR0[A-Z]{2}$/",$postcode))
            return true;
        else
            return false;
    }
}