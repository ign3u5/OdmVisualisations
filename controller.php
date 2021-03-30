<?php 
	require_once "connect.php";
	require_once "linq.php";	
	class DataController
	{
		private $database;
		private $connection;
		
		public function __construct()
		{
			$this->database = new Database();
			$this->connection = $this->database->getConnection();
		}

		function GetTotalShowType()
		{
			$sqlQuery = "
			Select 
				`tblShowTypes`.`description` as 'ShowType', 
				COUNT(`show_id`) as 'Total' 
			from `tblShows` 
			inner join tblShowTypes 
				on `tblShowTypes`.`type_id` = `tblShows`.`type_id` 
			GROUP BY `tblShows`.`type_id`";

			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				$results = array();
				
				$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($rows as $row)
				{
					$results[$row['ShowType']] = $row['Total'];
				}
				return $results;
			}
			else
			{
				echo "Failed to get show type totals";
			}
		}
		
		function GetTotalRatings()
		{
			$sqlQuery = "
				Select 
					description as 'Rating', 
					COUNT(`show_id`) as 'Total' 
				from `tblShowRatings`
				inner join `tblRatings`
					on `tblShowRatings`.`rating_id` = tblRatings.rating_id
				group by tblShowRatings.rating_id";

			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				$result = $statement->fetchAll(PDO::FETCH_OBJ);
				return $result;
			}
			else
			{
				echo "Failed to get rating totals";
			}
		}
		
		function GetMoviesByRating() {}
		function GetTvShowsByRating() {}
		
		function GetTotalTvShowsForReleaseYears()
		{
			$sqlQuery = "
			SELECT 
				tblShows.year_of_release as 'Year', 
				COUNT(tblShows.show_id) as 'Total' 
			FROM tblShows 
			INNER JOIN tblShowTypes
				ON tblShows.type_id = tblShowTypes.type_id
			WHERE tblShowTypes.description = 'TV Show' 
			GROUP BY tblShows.year_of_release";
				
			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_OBJ);
			}
			else
			{
				echo "Failed to get total TV shows for release years";
			}
		}
		
		function GetTotalMoviesForReleaseYears()
		{
			$sqlQuery = "
			SELECT 
				tblShows.year_of_release as 'Year', 
				COUNT(tblShows.show_id) as 'Total' 
			FROM tblShows 
			INNER JOIN tblShowTypes
				ON tblShows.type_id = tblShowTypes.type_id
			WHERE tblShowTypes.description = 'Movie' 
			GROUP BY tblShows.year_of_release";
				
			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_OBJ);
			}
			else
			{
				echo "Failed to get total movies for release years";
			}
		}
		
		function GetAverageDuration()
		{
			$sqlQuery = "SELECT
							tblShowTypes.description as 'ShowType',
							AVG(tblShows.duration) as 'AverageDuration'
						FROM tblShows
						INNER JOIN tblShowTypes
							ON tblShows.type_id = tblShowTypes.type_id
						GROUP BY tblShows.type_id";
			
			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				$results = array();
				
				$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($rows as $row)
				{
					$results[$row['ShowType']] = $row['AverageDuration'];
				}
				return $results;
			}
			else
			{
				echo "Failed to get Average Duration for release years";
			}
		}
		
		function GetTotalNumberOfSeasons()
		{
			$sqlQuery = "SELECT
							SUM(tblShows.duration) as 'Total'
						FROM tblShows
						INNER JOIN tblShowTypes
							ON tblShows.type_id = tblShowTypes.type_id
						WHERE tblShowTypes.description = 'TV Show'";
			
			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				$result = $statement->fetch(PDO::FETCH_OBJ)->Total;
				if ($result != null)
					return $result;
				echo "Failed to get param from Total Number of Seasons";
			}
			else
			{
				echo "Failed to get Total Number of Seasons";
			}
		}

		function GetAvailableSeasons() {
			$sqlQuery = "SELECT 
							count(tblShows.show_id) as 'Total', 
							tblShows.duration as 'Seasons' 
						FROM tblShows 
						INNER JOIN tblShowTypes 
							on tblShows.type_id = tblShowTypes.type_id 
						WHERE tblShowTypes.description = 'TV Show' 
						GROUP BY tblShows.duration";

			$statement = $this->connection->prepare($sqlQuery);
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_OBJ);
			}
			else {
				echo "Failed to get Available Seasons";
			}
		}
		
		function GetTopActors($numberOfEntries)
		{
			$sqlQuery = "SELECT 
							tblCast.name as 'Name',
						COUNT(tblShowCast.show_id) as 'Total'
						FROM tblCast
						INNER JOIN tblShowCast
							ON tblShowCast.cast_id = tblCast.cast_id
						GROUP BY tblShowCast.cast_id
						ORDER BY Total DESC
						LIMIT :numberOfEntries";
			
			$statement = $this->connection->prepare($sqlQuery);
			
			$statement->bindParam(":numberOfEntries", $numberOfEntries, PDO::PARAM_INT);
			
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_OBJ);
			}
			else
			{
				echo "Failed to get list of the top ".$numberOfEntries."Actors";
			}
		}
		
		function GetTopCountries($numberOfEntries)
		{
			$sqlQuery = "SELECT 
							tblCountries.name as 'Name',
						COUNT(tblShowCountries.show_id) as 'Total'
						FROM tblCountries
						INNER JOIN tblShowCountries
							ON tblShowCountries.country_id = tblCountries.country_id
						GROUP BY tblCountries.country_id
						ORDER BY Total DESC
						LIMIT :numberOfEntries";
			
			$statement = $this->connection->prepare($sqlQuery);
			
			$statement->bindParam(":numberOfEntries", $numberOfEntries, PDO::PARAM_INT);
			
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_OBJ);
			}
			else
			{
				echo "Failed to get list of the top ".$numberOfEntries."Countries";
			}
		}
		
		function GetAllCountries()
		{
			$sqlQuery = "SELECT 
							tblCountries.name as 'Name'
						FROM tblCountries";
			
			$statement = $this->connection->prepare($sqlQuery);
			
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
			}
			else
			{
				echo "Failed to get all countries";
			}
		}
		
		function GetAllCountryMovies($country)
		{
			$sqlQuery = "SELECT 
							tblShows.title as 'Title'
						FROM tblShows
						INNER JOIN tblShowCountries
							ON tblShows.show_id = tblShowCountries.show_id
						INNER JOIN tblCountries
							ON tblShowCountries.country_id = tblCountries.country_id
						WHERE tblCountries.name = :countryName";
			
			$statement = $this->connection->prepare($sqlQuery);
			
			$statement->bindParam(":countryName", $country);
			
			if ($statement->execute())
			{
				return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
			}
			else
			{
				echo "Failed to get all country movies";
			}
		}
	}
?>