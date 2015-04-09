<?php
/**
 * User: Junade Ali
 * Date: 02/04/15
 * Time: 09:25
 */

namespace WastedVotes\Controllers;

use WastedVotes\Models\Search;

class Base {

    private $capsule;

    public function __construct () {

        if($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
            header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        }

        $base = new \WastedVotes\Models\Base();

        $this->capsule = $base->connectDB();

        if (isset($_GET['q'])) {
            switch ($_GET['q']) {
                case 'generate':
                    $this->generate();
                    break;
                case 'search':
                    $this->searchForConstituency($_POST['area']);
                    break;
                case 'sitemapgenerate':
                    $this->getSitemapXML();
                    break;
                default:
                    header("HTTP/1.0 404 Not Found");
                    break;

            }
        } elseif (isset($_GET['constituency'])) {
            $search = new Search();
            if ($search->getConstituency($_GET['constituency']) !== false) {
                $this->showResultsPage($_GET['constituency']);
            } else {
                header("HTTP/1.0 404 Not Found");
                die();
            }
        } else {
            $results = new \WastedVotes\Models\Results();
            $topAreas = $results->getTopFiveAreas();
            $bottomAreas = $results->getBottomFiveAreas();

            if (isset($_GET['err'])) {
                $err = $_GET['err'];
            } else {
                $err = '';
            }

            $this->loadView('index', 'Home', array('topAreas' => $topAreas, 'bottomAreas' => $bottomAreas, 'err' => $err));
        }

    }

    public function generate () {
        //var_dump(\Illuminate\Database\Capsule\Manager::table('general_election_2010')->get());
        $votes = new \WastedVotes\Models\WastedVotesGenerator();
        $votes->populateTable();
    }

    public function loadView ($view, $title, $data) {

        $loader = new \Twig_Loader_Filesystem('src/views/');
        $twig = new \Twig_Environment($loader);
        echo $twig->render(
            $view.'.html',
            array(
                'title' => $title,
                'data' => $data
            )
        );
    }

    public function searchForConstituency ($area) {
        $search = new \WastedVotes\Models\Search();

        $constituency = $search->getConstituency($area);

        if ($constituency === false) {
            header('Location: /?err=areanotfound');
        } else {
            header('Location: /?constituency='.urlencode($constituency));
            die();
        }

    }

    public function showResultsPage ($constituency) {

        $results = new \WastedVotes\Models\Results();

        $marginality = $results->getMarginality($constituency);
        $marginalityWithSize = $results->getMarginalityWithSize($constituency);
        $rank = $results->getRank($constituency);

        $topAreas = $results->getTopFiveAreas();
        $bottomAreas = $results->getBottomFiveAreas();

        $totalVotes = $results->getTotalVotes($constituency);

        $wastedVotes = $results->getWastedVotes($constituency);
        $wastedVotesPercentage = round(($wastedVotes/$totalVotes)*100);

        $this->loadView('result', $constituency.' Voter Power',
            array(  'marginality' => $marginality,
                    'marginalityWithSize' => $marginalityWithSize,
                    'rank' => $rank,
                    'constituency' => $constituency,
                    'topAreas' => $topAreas,
                    'bottomAreas' => $bottomAreas,
                    'totalVotes' => $totalVotes,
                    'wastedVotes' => $wastedVotes,
                     'wastedVotesPercentage' => $wastedVotesPercentage
            ));

    }

    public function getSitemapXML () {
        echo '
<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $base = new \WastedVotes\Models\Base();
        $constituencies = $base->listAllConstituencies();

        echo "<url>
        <loc>https://wastedvotes.reformfoundation.org/</loc>
        <changefreq>yearly</changefreq>
        <priority>1</priority>
    </url>";

        foreach ($constituencies as $constituency) {

            echo "<url>
        <loc>https://wastedvotes.reformfoundation.org/?constituency=".urlencode($constituency)."</loc>
        <changefreq>yearly</changefreq>
        <priority>0.9</priority>
    </url>";

        }
    echo '</urlset>';
    }

}