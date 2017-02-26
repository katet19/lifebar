<?php
class Game
{
	public $_id;
	public $_gbid;
	public $_title;
	public $_rated;
	public $_released;
	public $_genre;
	public $_platforms;
	public $_year;
	public $_image;
	public $_imagesmall;
	public $_highlight;
	public $_publisher;
	public $_developer;
	public $_alias;
	public $_theme;
	public $_franchise;
	public $_similar;
	public $_t1;
	public $_t2;
	public $_t3;
	public $_t4;
	public $_t5;
	
	function __construct($id, $gbid, $title, $rated, $released, $genre, $platforms, $year, $image, $imagesmall, $highlight, $publisher, $developer, $alias, $theme, $franchise, $similar, $t1=0, $t2=0, $t3=0, $t4=0, $t5=0) {
		$this->_id = $id;
		$this->_gbid = $gbid;
		$this->_title = $title;
		$this->_rated = $rated;
		$this->_released = $released;
		$this->_genre = $genre;
		$this->_platforms = $platforms;
		$this->_year = $year;
		if($imagesmall != '')
			$this->_imagesmall = $imagesmall;
		else
			$this->_imagesmall = $image;
		if($image != '')
			$this->_image = $image;
		else
			$this->_image = $imagesmall;
		$this->_highlight = $highlight;
		$this->_publisher = $publisher;
		$this->_developer = $developer;
		$this->_alias = $alias;
		$this->_theme = $theme;
		$this->_franchise = $franchise;
		$this->_similar = $similar;
		$this->_t1 = $t1;
		$this->_t2 = $t2;
		$this->_t3 = $t3;
		$this->_t4 = $t4;
		$this->_t5 = $t5;
	}	
}

function GameObject($row){
	return new Game($row["ID"], 
				$row["GBID"],
				$row["Title"],
				$row["Rated"],
				$row["Released"],
				$row["Genre"],
				$row["Platforms"],
				$row["Year"],
				$row["ImageLarge"],
				$row["ImageSmall"],
				$row["Highlight"],
				$row["Publisher"],
				$row["Developer"],
				$row["Alias"],
				$row["Theme"],
				$row["Franchise"],
				$row["Similar"],
				$row["Tier1"],
				$row["Tier2"],
				$row["Tier3"],
				$row["Tier4"],
				$row["Tier5"]
				);
}


class GameRank
{
	public $_id;
	public $_gbid;
	public $_title;
	public $_rated;
	public $_released;
	public $_genre;
	public $_platforms;
	public $_year;
	public $_image;
	public $_imagesmall;
	public $_publisher;
	public $_developer;
	public $_alias;
	public $_theme;
	public $_franchise;
	public $_similar;
	public $_tier;
	public $_rank;
	
	function __construct($id, $gbid, $title, $rated, $released, $genre, $platforms, $year, $image, $imagesmall, $publisher, $developer, $alias, $theme, $franchise, $similar, $tier, $rank) {
		$this->_id = $id;
		$this->_gbid = $gbid;
		$this->_title = $title;
		$this->_rated = $rated;
		$this->_released = $released;
		$this->_genre = $genre;
		$this->_platforms = $platforms;
		$this->_year = $year;
		if($imagesmall != '')
			$this->_imagesmall = $imagesmall;
		else
			$this->_imagesmall = $image;
		if($image != '')
			$this->_image = $image;
		else
			$this->_image = $imagesmall;
		$this->_publisher = $publisher;
		$this->_developer = $developer;
		$this->_alias = $alias;
		$this->_theme = $theme;
		$this->_franchise = $franchise;
		$this->_similar = $similar;
		$this->_tier = $tier;
		$this->_rank = $rank;
	}	
}

function GameRankObject($row){
	return new GameRank($row["ID"], 
				$row["GBID"],
				$row["Title"],
				$row["Rated"],
				$row["Released"],
				$row["Genre"],
				$row["Platforms"],
				$row["Year"],
				$row["ImageLarge"],
				$row["ImageSmall"],
				$row["Publisher"],
				$row["Developer"],
				$row["Alias"],
				$row["Theme"],
				$row["Franchise"],
				$row["Similar"],
				$row["Tier"],
				$row["Rank"]
				);
}

class GameMeta
{
	public $_id;
	public $_franchises;
	public $_genres;
	public $_developers;
	public $_publishers;
	public $_concepts;
	public $_locations;
	public $_people;
	public $_platforms;
	public $_themes;
	
	function __construct($id, $franchises, $genres, $developers, $publishers, $concepts, $locations, $people, $platforms, $themes){
		$this->_id = $id;
		$this->_franchises = $franchises;
		$this->_genres = $genres;
		$this->_developers = $developers;
		$this->_publishers = $publishers;
		$this->_concepts = $concepts;
		$this->_locations = $locations;
		$this->_people = $people;
		$this->_platforms = $platforms;
		$this->_themes = $themes;
	}
}

class GameVideo{
	public $_id;
	public $_gameid;
	public $_url;
	public $_source;
	public $_desc;
	public $_sourceid;
	public $_channel;
	public $_image;
	public $_views;
	public $_length;
	
	function __construct($id, $gameid, $source, $url, $desc, $sourceid, $channel, $image, $views, $length) {
		$this->_id = $id;
		$this->_gameid = $gameid;
		$this->_url = $url;
		$this->_source = $source;
		$this->_desc = $desc;
		$this->_sourceid = $sourceid;
		$this->_channel = $channel;
		$this->_image = $image;
		$this->_views = $views;
		$this->_length = $length;
	}
}


?>
