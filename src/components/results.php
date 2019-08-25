<?php

function showResultsTable($skleague,$team_order, $team_info, $team_score, $min_max, $that, $year) {
    $html = "<div class='teamScoreTotal lbl'>Sum</div>";
    $html.= "</div>";

	$ol = 0;
	
    foreach ($team_order as $key => $value) {
        $team_name = $team_info[$key]["name"];
        $league = $team_info[$key]["league"];
		if ($league != 1) $ol++;
		
        $scoreTotal = number_format((float)$team_info[$key]["score"], 2, '.', '');

        if ($skleague==1 && $team_info[$key]["league"]==0) continue;
        $html.= "<div class='teamResults'>";
        $html.= "<div class='teamName'>". $team_name ."</div>";
        $html.= "<div class='teamScoreTotalxs'>". $scoreTotal ."</div>";

        $html.= "<div class='allScores'>";
		$count_cel = count($team_score[$key]);
        foreach ($team_score[$key] as $kkey => $vvalue) {
            $points = number_format((float)$vvalue[0], 2, '.', '');
            $best = $vvalue[1];

            $bestSolution = "";
            if ($best=="1" && $min_max == 1){
                if ($league==1) $bestSolution = "greenCell";
                else $bestSolution = "blueCell";
            }
            $html.= "<div class='score ".$bestSolution."'>";
            if ($vvalue[0]!="-")
                $html.= "<a href='?page=solution&id-assignment=".$kkey."&id-team=".$key."'>". $points ."</a>";
            else
                $html.= "-";
            $html.= "</div>";
        }
        $html.= "</div>";

        $html.= "<div class='teamScoreTotal'>". $scoreTotal ."</div>";
        $html.= "</div>";
    }

	// ak nie je Open liga tim vrati null;
	if ($ol == 0 && $skleague == 0)
		return null;
	
    if ($min_max == 1)
    {
      $league_name = $that->get("res_sk_liga_max"); 
      if ($skleague==0) $league_name = $that->get("res_open_liga_max"); 
    }
    else
    {
      $league_name = $that->get("res_sk_liga_min"); 
      if ($skleague==0) $league_name = $that->get("res_open_liga_min"); 
    }
    $league_name .= " " . $year;

    $head = "";
    $head .= "<div id='resultsTable'>";
    $head .= "<div id='resultsTableLabel' class='teamResults'>";
    $head .= "<div class='teamName lbl'>".$league_name."</div>";
    $head .= "<div class='allScores lbl'>";
    for ($i=1; $i<=$count_cel; $i++) {
        $head .= "<div class='score lbl'>". $i .".</div>";
    }
    $head .= "</div>";
	
    $html.= "</div>";
	
    return $head . $html;
}


$html = "";
$year = Security::get("year");
if (!$year) $year = Date("Y");

$team_info = array();
$team_score = array();
$team_order = array();

$get_teams = $this->database->get_teams($year);
if (mysqli_num_rows($get_teams) == 0) {
    $year--;
    $get_teams = $this->database->get_teams($year);
}

while($r = mysqli_fetch_assoc($get_teams)) {
    // key - id; values - name, league, total_score
    $team_info[$r["id_user"]] = array( "name" => $r["name"], "league" => $r["sk_league"], "score" => 0);
}

foreach ($team_info as $key => $value){
    $get_results = $this->database->get_team_results($year,$key);
    $sum = 0;
    while($r = mysqli_fetch_assoc($get_results)) {
        $points =  floatval($r["points"]);
        $sum += $points;
        if ($r["points"]==NULL) $points = "-";
        $team_score[$key][$r["id"]] = array($points,$r["best"]);
    }
    $team_info[$key]["score"] = $sum;
    $team_order[$key] = $sum;
}

arsort($team_order);


$html.= showResultsTable(1,$team_order,$team_info,$team_score, 1, $this, $year);
$html.= showResultsTable(0,$team_order,$team_info,$team_score, 1, $this, $year);


if ($year > 2016) {

	$get_teams = $this->database->get_teams($year);
	while($r = mysqli_fetch_assoc($get_teams)) {
		// key - id; values - name, league, total_score
		$team_info[$r["id_user"]] = array( "name" => $r["name"], "league" => $r["sk_league"], "score" => 0);
	}

	foreach ($team_info as $key => $value){
		$get_results = $this->database->get_team_results_worse($year,$key);
		$sum = 0;
		while($r = mysqli_fetch_assoc($get_results)) {
			$points =  floatval($r["points"]);
			$sum += $points;
			if ($r["points"]==NULL) $points = "-";
			$team_score[$key][$r["id"]] = array($points,$r["best"]);
		}
		$team_info[$key]["score"] = $sum;
		$team_order[$key] = $sum;
	}

	arsort($team_order);


	$html.= showResultsTable(1,$team_order,$team_info,$team_score, 0, $this, $year);
	$html.= showResultsTable(0,$team_order,$team_info,$team_score, 0, $this, $year);

	$html.= "</div>";
}

return $html;
?>



