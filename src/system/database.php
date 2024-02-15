<?php

class Database {
	
	private $conn;
	
	function __construct() {
		$config = parse_ini_file("config/db_conn.ini");
		$this->conn = mysqli_connect(
			$config["server"],
			$config["name"],
			$config["password"],
			$config["database"]);

		if (!$this->conn) {
			echo mysqli_connect_error();
		}
		@mysqli_query($this->conn, "SET sql_mode = ''");
		@mysqli_query($this->conn, "SET CHARACTER SET 'utf8'");
	}
	

	public function login($mail, $password) {
		$sql = "SELECT * ";
		$sql.= "FROM users as U ";
		$sql.= "LEFT JOIN banned as B ";
		$sql.= "ON U.id = B.id_user ";
		$sql.= "LEFT JOIN teams as T ";
		$sql.= "ON U.id = T.id_user ";
		$sql.= "WHERE U.mail = '" . $mail . "' AND ";
		$sql.= "U.password = '" . md5($password) . "' AND ";
		$sql.= "B.id_user IS NULL";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result);
	}
	
	
	public function last_logged($id) {
		$sql = "UPDATE users ";
		$sql.= "SET last_logged = NOW() ";
		$sql.= "WHERE id = '" . $id . "'";
		mysqli_query($this->conn, $sql);
	}

	public function reset_password($email, $passwd)
        {
		$sql = "UPDATE users set password='" . md5($passwd) . "' WHERE mail='" . $email . "'";
                mysqli_query($this->conn, $sql);
                if (mysqli_affected_rows($this->conn) > 0) return 1;
                return 0;
        }


	public function menu_assignments() {
		$sql = "SELECT id ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE year = ";
		$sql.= "(SELECT year ";
		$sql.= "FROM assignments_group ";
		$sql.= "ORDER BY year DESC ";
		$sql.= "LIMIT 0,1) AND begin < NOW()";
		$sql.= "ORDER BY end ASC";
		return mysqli_query($this->conn, $sql);
	}


	public function menu_archive_year() {
		$sql = "SELECT year ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE year != ";
		$sql.= "(SELECT year ";
		$sql.= "FROM assignments_group ";
		$sql.= "ORDER BY year DESC ";
		$sql.= "LIMIT 0,1) ";
		$sql.= "GROUP BY year ";
		$sql.= "ORDER BY year ";
		return mysqli_query($this->conn, $sql);
	}


	public function menu_archive_assignments($year) {
		$sql = "SELECT id ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE year = '" . $year . "' " ;
		$sql.= "ORDER BY id ASC";
		return mysqli_query($this->conn, $sql);
	}


	public function assignment_group($id) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE id = '" . $id . "'";
		$result_assignment_group = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result_assignment_group);
	}

	
	public function assignment($id) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments ";
		$sql.= "WHERE id = '" . $id . "'";
		$result_assignment = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result_assignment);
	}
	

	public function assignments($id) {
		$sql = "SELECT A.id, G.begin, G.end, A.sk_title, IF(A.en_title!='', A.en_title, A.sk_title) AS en_title, IF(A.de_title!='', A.de_title, A.sk_title) AS de_title, ";
		$sql.= "A.sk_description, IF(A.en_description!='', A.en_description, A.sk_description) AS en_description, ";
                $sql.= "IF(A.de_description!='', A.de_description, A.sk_description) AS de_description ";
		$sql.= "FROM assignments_group AS G ";
		$sql.= "LEFT JOIN assignments AS A ";
		$sql.= "ON G.id = A.id_group ";
		$sql.= "WHERE G.id = '" . $id . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function assignment_solutions($id_group) {
		$sql = "SELECT S.id, IF(S.best = 1, 'best', '') AS best, T.name AS team, T.id_user AS id_team, S.id_assignment AS id_assignment, C.category, S.best_assignment_number ";
		$sql.= "FROM assignments AS A ";
		$sql.= "LEFT JOIN solutions AS S ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN teams AS T ";
		$sql.= "ON S.id_user = T.id_user ";
		$sql.= "LEFT JOIN comments AS C ";
		$sql.= "ON S.id = C.id_solution ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' ";
		$sql.= "GROUP BY T.name ";
		$sql.= "ORDER BY S.best DESC, T.name ASC";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	public function assignment_solutions_each_solution($id_group,$id_user) {
		$sql = "SELECT S.id, IF(S.best = 1, 'best', '') AS best, T.name AS team, T.id_user AS id_team, C.points, C.internal_comment, C.category, S.text, C.id_user AS id_jury ";
		$sql.= "FROM solutions AS S ";
		$sql.= "LEFT JOIN assignments AS A ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN teams AS T ";
		$sql.= "ON S.id_user = T.id_user ";
		$sql.= "LEFT JOIN comments AS C ";
		
		$sql.= "ON S.id = C.id_solution AND C.id_user = '".$id_user."' ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' ";
		$sql.= "ORDER BY S.best DESC, T.name ASC";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	public function count_assignments($id_group) {
		$sql = "SELECT count(A.id) ";
		$sql.= "FROM assignments AS A ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' ";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
	public function assignment_solutions_comment($id_user, $id_solution) {
		$sql = "SELECT * ";
		$sql.= "FROM comments ";
		$sql.= "WHERE id_solution = '" . $id_solution . "' AND id_user = '" . $id_user . "'";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_num_rows($result);
	}
	

	public function solution_content($id_group, $id_user) {
		$sql = "SELECT T.name AS team, A.sk_title, IF(A.en_title!='', A.en_title, A.sk_title) AS en_title, ";
                $sql.= "IF(A.de_title!='', A.de_title, A.sk_title) AS de_title, T.sk_league, ";
		$sql.= "T.description as team_info, ";
		$sql.= "S.text, S.id AS id_solution ";
		$sql.= "FROM assignments as A ";
		$sql.= "LEFT JOIN solutions as S ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN teams as T ";
		$sql.= "ON S.id_user = T.id_user ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' AND S.id_user = '" . $id_user . "'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function get_solution_photos($id) {
		$sql = "SELECT * ";
		$sql.= "FROM images_solution ";
		$sql.= "WHERE id_solution = '" . $id . "'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function get_solution_video($id) {
		$sql = "SELECT * ";
		$sql.= "FROM videos ";
		$sql.= "WHERE context = '0' AND id_context = '" . $id . "'";
		return mysqli_query($this->conn, $sql);
	}


	public function get_coment($id) {
		$sql = "SELECT * ";
		$sql.= "FROM comments ";
		$sql.= "WHERE id_solution = '" . $id . "' AND id_user = '1'";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result);	
	}
	
	
	public function show_coment($id_group) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments as A ";
		$sql.= "LEFT JOIN solutions as S ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN comments AS C ";
		$sql.= "ON C.id_solution = S.id AND C.id_user = '1'";
		$sql.= "WHERE A.id_group = '" . $id_group . "' AND C.text IS NULL";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_num_rows($result);
	}


	public function get_solution_program($id) {
		$sql = "SELECT * ";
		$sql.= "FROM programs ";
		$sql.= "WHERE id_solution = '" . $id . "'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function new_assignment_content($id) {
		$sql = "SELECT id, sk_title, en_title, de_title, sk_description, en_description, de_description ";
		$sql.= "FROM assignments ";
		$sql.= "WHERE id = '" . $id . "'";
		$result_assignment = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result_assignment);
	}
	
	
	public function create_new_assignment($id_user) {
		$sql = "INSERT INTO assignments ";
		$sql.= "(id_user, sk_title, en_title, de_title, sk_description, en_description, de_description) VALUES ";
		$sql.= "('" . $id_user . "', 'Nové zadanie', 'New assignment', 'Neue Aufgabe', '', '', '')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}
	
	
	public function edit_assignment($id, $sk_title, $en_title, $de_title, $sk_description, $en_description, $de_description) {
		$sql = "UPDATE assignments ";
		$sql.= "SET sk_title = '" . $sk_title . "', en_title = '" . $en_title . "', de_title = '" . $de_title ."', ";
		$sql.= "sk_description = '" . $sk_description . "', en_description = '" . $en_description . "', ";
                $sql.= "de_description = '" . $de_description . "' ";
		$sql.= "WHERE id = '" . $id . "'";
		mysqli_query($this->conn, $sql);
	}


	public function unpublished_assignment_jury($id_user) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments ";
		$sql.= "WHERE id_group = '0' AND id_user = '" . $id_user . "'";
		return mysqli_query($this->conn, $sql);
	}


	public function unpublished_assignment() {
		$sql = "SELECT * ";
		$sql.= "FROM assignments ";
		$sql.= "WHERE id_group = '0'";
		return mysqli_query($this->conn, $sql);
	}


	public function published_group() {
		$sql = "SELECT * ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE end > NOW() ";
		$sql.= "GROUP BY id";
		return mysqli_query($this->conn, $sql);
	}
	
	public function after_deadline_group() {
		$sql = "SELECT * ";
		$sql.= "FROM assignments_group ";
		$sql.= "WHERE end <= NOW() ";
		$sql.= "GROUP BY id";
		return mysqli_query($this->conn, $sql);
	}
	
	public function published_assignment($id_group) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments ";
		$sql.= "WHERE id_group = '" . $id_group . "'";
		return mysqli_query($this->conn, $sql);
	}


	public function remove_assignment($id) {
		$sql = "DELETE FROM assignments ";
		$sql.= "WHERE id='" . $id . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function new_assignment_image($id_assignment, $extension) {
		$sql = "INSERT INTO images_assignment ";
		$sql.= "(id_assignment, extension) VALUES ";
		$sql.= "('" . $id_assignment . "', '" . $extension . "')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}
	

	public function solution_image($id_solution, $extension, $token) {
		$sql = "INSERT INTO images_solution ";
		$sql.= "(id_solution, extension, token) VALUES ";
		$sql.= "('" . $id_solution . "', '" . $extension . "', '" . $token . "')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}
	

	public function solution_program($id_solution, $extension, $original_name, $token) {
		$sql = "INSERT INTO programs ";
		$sql.= "(id_solution, extension, original_name, token) VALUES ";
		$sql.= "('" . $id_solution . "', '" . $extension . "', '" . $original_name . "', '" . $token . "')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}

	
	public function get_image_assignment($id_assignment) {
		$sql = "SELECT * ";
		$sql.= "FROM images_assignment ";
		$sql.= "WHERE id_assignment = '" . $id_assignment . "'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function get_image_solution($id_solution) {
		$sql = "SELECT * ";
		$sql.= "FROM images_solution ";
		$sql.= "WHERE id_solution = '" . $id_solution . "'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function get_programs_solution($id_solution) {
		$sql = "SELECT * ";
		$sql.= "FROM programs ";
		$sql.= "WHERE id_solution = '" . $id_solution . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function new_assignment_remove_image($id) {
		$sql = "SELECT * ";
		$sql.= "FROM images_assignment ";
		$sql.= "WHERE id = '" . $id . "'";
		$result_image = mysqli_query($this->conn, $sql);
		$row_image = mysqli_fetch_assoc($result_image);
		$sql = "DELETE FROM images_assignment ";
		$sql.= "WHERE id='" . $id . "'";
		mysqli_query($this->conn, $sql);
		return $row_image;
	}
	
	
	public function new_solution_remove_program($id) {
		$sql = "SELECT * ";
		$sql.= "FROM programs ";
		$sql.= "WHERE token = '" . $id . "'";
		$result_image = mysqli_query($this->conn, $sql);
		$row_image = mysqli_fetch_assoc($result_image);
		$sql = "DELETE FROM programs ";
		$sql.= "WHERE token='" . $id . "'";
		mysqli_query($this->conn, $sql);
		return $row_image["id"] . "." . $row_image["extension"];
	}
	
	
	public function new_solution_remove_image($id) {
		$sql = "SELECT * ";
		$sql.= "FROM images_solution ";
		$sql.= "WHERE token = '" . $id . "'";
		$result_image = mysqli_query($this->conn, $sql);
		$row_image = mysqli_fetch_assoc($result_image);
		$sql = "DELETE FROM images_solution ";
		$sql.= "WHERE token='" . $id . "'";
		mysqli_query($this->conn, $sql);
		return $row_image;
	}
	
	
	public function add_youtube_link($context, $id_context, $link) {
		$sql = "INSERT INTO videos ";
		$sql.= "(context, id_context, link) VALUES ";
		$sql.= "('" . $context . "', '" . $id_context . "', '" . $link . "')";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function get_all_youtube_link($context, $id_context) {
		$sql = "SELECT * ";
		$sql.= "FROM videos ";
		$sql.= "WHERE context = '" . $context . "' AND id_context = '" . $id_context . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function remove_video($id, $id_context) {
		$sql = "DELETE FROM videos ";
		$sql.= "WHERE id='" . $id . "' AND id_context = '" . $id_context . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function create_assignemnt_group($begin, $end) {
		$sql = "INSERT INTO assignments_group ";
		$sql.= "(begin, end, year) VALUES ";
		$sql.= "('" . $begin . "', '" . $end . "', '" . date("Y") . "')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}
	
	
	public function publish_assignemnts($id_group, $id) {
		$sql = "UPDATE assignments ";
		$sql.= "SET id_group = '" . $id_group . "' ";
		$sql.= "WHERE id = '" . $id . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function find_email($mail) {
		$sql = "SELECT * ";
		$sql.= "FROM users ";
		$sql.= "WHERE mail = '" . $mail . "'";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_num_rows($result);
	}


	public function registration_user($mail, $password, $team, $about, $league, $city, $street_name, $zip_code, $category) {
		$sql = "INSERT INTO users (mail, password, admin, jury) VALUES (?, ?, '0', '0')";
		$stmt = mysqli_prepare($this->conn, $sql);
		$hashed_password = md5($password);
		mysqli_stmt_bind_param($stmt, "ss", $mail, $hashed_password);
		mysqli_stmt_execute($stmt);
		$id_user = mysqli_insert_id($this->conn);

		$sql = "INSERT INTO teams (id_user, name, description, sk_league, city, street_name, zip, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($this->conn, $sql);
		mysqli_stmt_bind_param($stmt, "isssssss", $id_user, $team, $about, $league, $city, $street_name, $zip_code, $category);
		mysqli_stmt_execute($stmt);
	}


	public function exist_solution($id_assignment, $id_user) {
		$sql = "SELECT * ";
		$sql.= "FROM solutions ";
		$sql.= "WHERE id_assignment = '" . $id_assignment . "' AND id_user = '" . $id_user . "'";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_fetch_assoc($result);
	}
	
	
	public function create_solution($id_assignment, $id_user, $text) {
		$sql = "INSERT INTO solutions ";
		$sql.= "(id_assignment, id_user, text) VALUES ";
		$sql.= "('" . $id_assignment . "', '" . $id_user . "', '" . $text . "')";
		mysqli_query($this->conn, $sql);
		return mysqli_insert_id($this->conn);
	}
	
	
	public function update_solution($id_assignment, $id_user, $text) {
		$sql = "UPDATE solutions ";
		$sql.= "SET text = '" . $text . "' ";
		$sql.= "WHERE id_assignment = '" . $id_assignment . "' AND id_user = '" . $id_user . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function expired_assignment($id_assignment) {
		$sql = "SELECT * ";
		$sql.= "FROM assignments_group AS AG ";
		$sql.= "WHERE AG.id = '" . $id_assignment . "' AND AG.end < NOW()";
		$result = mysqli_query($this->conn, $sql);
		return mysqli_num_rows($result);
	}
	
	
	public function get_comment($solution, $id_user) {
		$sql = "SELECT * ";
		$sql.= "FROM comments ";
		$sql.= "WHERE id_solution = '" . $solution . "' AND id_user = '" . $id_user . "'";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
public function set_comment($solution, $id_user, $text, $points, $internal_comment=NULL) {
		$sql = "INSERT INTO comments ";
		$sql.= "(id_solution, id_user, text, points, internal_comment) VALUES ";
		$sql.= "('" . $solution . "', '" . $id_user . "', '" . $text . "', '" . $points . "', '" . $internal_comment . "')";
		mysqli_query($this->conn, $sql);
	}
	
	public function set_category($id_team, $category) {		
		$sql.= "UPDATE solutions AS S ";
		$sql.= "LEFT JOIN assignments AS A ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN teams AS T ";
		$sql.= "ON S.id_user = T.id_user ";
		$sql.= "LEFT JOIN comments AS C ";
		$sql.= "ON S.id = C.id_solution ";
		$sql.= "SET C.category = " . $category . " ";
		$sql.= "WHERE T.id_user = '" . $id_team . "'";
		mysqli_query($this->conn, $sql);
	}	
	
	public function set_best_assignment_number($id_team, $number) {		
		$sql.= "UPDATE solutions ";
		$sql.= "SET best_assignment_number = '" . $number . "', best = 1 ";
		$sql.= "WHERE id_user = '" . $id_team . "'";
		mysqli_query($this->conn, $sql);
	}
	
		public function unset_best($id_team) {		
		$sql.= "UPDATE solutions ";
		$sql.= "SET best_assignment_number = 0, best = 0 ";
		$sql.= "WHERE id_user = '" . $id_team . "'";
		mysqli_query($this->conn, $sql);
	}	
	
	
	public function update_comment($solution, $id_user, $text, $points, $internal_comment=NULL) {
		$sql = "UPDATE comments ";
		$sql.= "SET text = '" . $text . "', points = '" . $points . "', internal_comment = '" . $internal_comment . "' ";
		$sql.= "WHERE id_solution = '" . $solution . "' AND id_user = '" . $id_user . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function get_jury_comments($solution) {
		$sql = "SELECT * ";
		$sql.= "FROM comments as C ";
		$sql.= "LEFT JOIN users as U ";
		$sql.= "ON C.id_user = U.id ";
		$sql.= "WHERE C.id_solution = '" . $solution . "' AND C.id_user != '1'";
		return mysqli_query($this->conn, $sql);
	}

	
	public function find_team($team) {
		$sql = "SELECT * ";
		$sql.= "FROM teams ";
		$sql.= "WHERE name = '" . $team . "'";
		return mysqli_num_rows(mysqli_query($this->conn, $sql));
	}
	
	public function get_team_category($id_team) {
		$sql = "SELECT category ";
		$sql.= "FROM teams ";
		$sql.= "WHERE id_user = '" . $id_team . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function back_solution($id_group) {
		$sql = "SELECT S.id, IF(S.best = 1, 'best', '') AS best, T.name AS team, T.id_user AS id_team ";
		$sql.= "FROM assignments AS A ";
		$sql.= "LEFT JOIN solutions AS S ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "LEFT JOIN teams AS T ";
		$sql.= "ON S.id_user = T.id_user ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' AND S.id IS NOT NULL ";
		$sql.= "GROUP BY T.name ";
		$sql.= "ORDER BY S.best DESC, T.name ASC";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
	public function get_all_teams() {
		$sql = "SELECT * ";
		$sql.= "FROM teams ";
		$sql.= "ORDER BY name";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
	public function get_all_jury() {
		$sql = "SELECT * ";
		$sql.= "FROM users ";
		$sql.= "WHERE jury = 1";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
	public function get_all_admins() {
		$sql = "SELECT * ";
		$sql.= "FROM users ";
		$sql.= "WHERE admin = 1";
		$result = mysqli_query($this->conn, $sql);
		return $result;
	}
	
	
	public function remove_team($id_user) {
		$sql = "DELETE FROM teams ";
		$sql.= "WHERE id_user='" . $id_user . "'";
		mysqli_query($this->conn, $sql);

		$sql = "DELETE FROM users ";
		$sql.= "WHERE id='" . $id_user . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function remove_jury($id) {
		$sql = "DELETE FROM users ";
		$sql.= "WHERE id='" . $id . "'";
		mysqli_query($this->conn, $sql);
	}
	
	
	public function create_jury($mail, $password) {
		$sql = "INSERT INTO users ";
		$sql.= "(mail, password, admin, jury) VALUES ";
		$sql.= "('" . $mail . "', '" . md5($password) . "', '0', '1')";
		mysqli_query($this->conn, $sql);
		$id_user = mysqli_insert_id($this->conn);
	}
	
	
	
	public function best_solution($id_group, $id_team) {
		$sql = "SELECT S.id, S.best ";
		$sql.= "FROM assignments AS A ";
		$sql.= "LEFT JOIN solutions as S ";
		$sql.= "ON A.id = S.id_assignment ";
		$sql.= "WHERE A.id_group = '" . $id_group . "' AND S.id_user = '" . $id_team . "'";
		return mysqli_query($this->conn, $sql);
	}
	
	
	public function change_best_solution($id_solution, $best) {
		$sql = "UPDATE solutions ";
		$sql.= "SET best = '" . $best . "' ";
		$sql.= "WHERE id = '" . $id_solution . "'";
		mysqli_query($this->conn, $sql);
	}


    public function get_team_results($year, $team_id, $cat) {
        $sql = "SELECT G.id, ( ";
        $sql.= "SELECT C.points ";
        $sql.= "FROM assignments AS A  ";
        $sql.= "LEFT JOIN solutions AS S ";
        $sql.= "ON A.id = S.id_assignment  ";
        $sql.= "LEFT JOIN comments AS C ";
        $sql.= "ON S.id = C.id_solution ";
        $sql.= "WHERE A.id_group = G.id AND S.id_user = '" . $team_id . "' AND C.id_user = 1 AND C.category = '" . $cat . "' ";
        $sql.= "ORDER BY points desc  ";
        $sql.= "LIMIT 1 ";
        $sql.= ") AS points,( ";
        $sql.= "SELECT S.best ";
        $sql.= "FROM assignments AS A  ";
        $sql.= "LEFT JOIN solutions AS S  ";
        $sql.= "ON A.id = S.id_assignment  ";
        $sql.= "LEFT JOIN comments AS C ";
        $sql.= "ON S.id = C.id_solution ";
        $sql.= "WHERE A.id_group = G.id AND S.id_user = '" . $team_id . "' AND C.id_user = 1 AND C.category = '" . $cat . "' ";
        $sql.= "ORDER BY points desc, best desc  ";
        $sql.= "LIMIT 1 ";
        $sql.= ") AS best ";
        $sql.= "FROM assignments_group as G ";
        $sql.= "WHERE G.year = '" . $year . "'";

        return mysqli_query($this->conn, $sql) ;
    }

    public function get_team_results_worse($year, $team_id, $cat) {
        $sql = "SELECT G.id, ( ";
        $sql.= "SELECT C.points ";
        $sql.= "FROM assignments AS A  ";
        $sql.= "LEFT JOIN solutions AS S ";
        $sql.= "ON A.id = S.id_assignment  ";
        $sql.= "LEFT JOIN comments AS C ";
        $sql.= "ON S.id = C.id_solution ";
        $sql.= "WHERE A.id_group = G.id AND S.id_user = '" . $team_id . "' AND C.id_user = 1 AND C.category = '" . $cat . "' ";
        $sql.= "ORDER BY points desc  ";
        $sql.= "LIMIT 1 OFFSET 1";
        $sql.= ") AS points,( ";
        $sql.= "SELECT S.best ";
        $sql.= "FROM assignments AS A  ";
        $sql.= "LEFT JOIN solutions AS S  ";
        $sql.= "ON A.id = S.id_assignment  ";
        $sql.= "LEFT JOIN comments AS C ";
        $sql.= "ON S.id = C.id_solution ";
        $sql.= "WHERE A.id_group = G.id AND S.id_user = '" . $team_id . "' AND C.id_user = 1 AND C.category = '" . $cat . "' ";
        $sql.= "ORDER BY points asc  ";
        $sql.= "LIMIT 1 ";
        $sql.= ") AS best ";
        $sql.= "FROM assignments_group as G ";
        $sql.= "WHERE G.year = '" . $year . "'";

        return mysqli_query($this->conn, $sql) ;
    }

    public function get_teams($year) {
        $sql = "SELECT DISTINCT T.id_user, T.name, T.sk_league ";
        $sql.= "FROM assignments AS A ";
        $sql.= "RIGHT JOIN assignments_group AS G ";
        $sql.= "ON G.id = A.id_group ";
        $sql.= "RIGHT JOIN solutions AS S ";
        $sql.= "ON S.id_assignment = A.id ";
        $sql.= "RIGHT JOIN comments AS C ";
        $sql.= "ON C.id_solution = S.id ";
        $sql.= "RIGHT JOIN teams AS T ";
        $sql.= "ON T.id_user = S.id_user ";
        $sql.= "WHERE G.year = '" . $year . "' AND C.id_user = 1";
        return mysqli_query($this->conn, $sql);
    }

	public function getUserAndTeamInfo($userId) {
		$sql = "SELECT 
                    u.mail, 
                    t.name, 
                    t.description, 
                    t.city, 
                    t.street_name, 
                    t.zip, 
                    t.category,
					t.sk_league 
                FROM 
                    users as u 
                JOIN 
                    teams as t 
                ON 
                    u.id = t.id_user 
                WHERE 
                    u.id = ?";

		if ($stmt = $this->conn->prepare($sql)) {
			$stmt->bind_param("i", $userId);
			$stmt->execute();
			$stmt->bind_result($mail, $name, $description, $city, $streetName, $zip, $category, $sk_league);

			if ($stmt->fetch()) {
				$userInfo = [
					'mail' => $mail,
					'name' => $name,
					'description' => $description,
					'city' => $city,
					'street_name' => $streetName,
					'zip' => $zip,
					'category' => $category,
					'sk_league' => $sk_league
				];
			} else {
				$userInfo = null;
			}

			$stmt->close();

			return $userInfo;
		} else {
			die("Statement could not be prepared: " . $this->conn->error);
		}
	}

	public function update_user($mail, $team, $about, $city, $street_name, $zip_code, $category, $userid) {
		// Update the user table
		$sql = "UPDATE users SET mail = ? WHERE id = ?";
		$stmt = mysqli_prepare($this->conn, $sql);
		mysqli_stmt_bind_param($stmt, "si", $mail, $userid);
		mysqli_stmt_execute($stmt);

		// Update the teams table
		$sql = "UPDATE teams SET name = ?, description = ?, city = ?, street_name = ?, zip = ?, category = ? WHERE id_user = ?";
		$stmt = mysqli_prepare($this->conn, $sql);
		mysqli_stmt_bind_param($stmt, "ssssssi", $team, $about, $city, $street_name, $zip_code, $category, $userid);
		mysqli_stmt_execute($stmt);
	}

    public function check_for_this_years_solution($userID, $currentYear){
        $sql = "SELECT a.id 
                FROM users u
                LEFT JOIN solutions s ON u.id = s.id_user
                LEFT JOIN assignments a ON s.id_assignment = a.id
                LEFT JOIN assignments_group ag ON a.id_group = ag.id AND ag.year = ?
                WHERE u.id = ? AND ag.id IS NOT NULL
                LIMIT 1";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $currentYear, $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);


        return mysqli_num_rows($result) > 0;

    }


	function __destruct() {
		mysqli_close($this->conn);
	}

}

?>
