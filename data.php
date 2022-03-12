<?php
// user => password
$users = array('sss' => 'singul@rity');

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Digest realm="'.$realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Canceled!');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
	!isset($users[$data['username']])) {
	echo "auth_digest: {$_SERVER["PHP_AUTH_DIGEST"]}<br>";
	$_SERVER["PHP_AUTH_DIGEST"] = "";
	echo "wrong credentials";
	return;
}


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response) die('Wrong Credentials!');

// function to parse the http auth header
function http_digest_parse($txt)
{
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
                $data[$m[1]] = $m[3] ? $m[3] : $m[4];
                unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
}

$key = rtrim(file_get_contents("/usr/local/www/sss/key.txt"), "\n");
setcookie("key", $key);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>sss database</title>
		<style>body{width:60%;margin:auto;}</style>
	</head>
	<body>
		<h1>query the database</h1>
		<p>documentation <a href="https://wiki.5066.team/books/signin-system/page/dataphp" target="_blank">here</a></p>

		<p>
			today's key: <b><?php echo $key;?></b><br>
			<form action="/form.php" method="post">
				<input type="hidden" name="key" value="<?php echo $key;?>">
				<input type="submit" value="go to sign-in form">
			</form>

		</p>

		<h2>filter by name</h2>
		<form>
		name:<br>
		<input type="text" name="name"><br>
		date (YYYY-MM-DD or remove any part):<br>
		<input type="text" name="date"><br>
		find all names not signed in on date:<br>
		<input type="checkbox" name="inv" value="search inverse"><br>
		<input type="submit">
		</form>
		<a href="/data.php"><button>clear</button></a>

		<hr>
		<ul>
		<?php
		$db = new SQlite3('/usr/local/www/sss/a.db');

		# inverse mode
		if (isset($_GET["inv"])) {
			if (!isset($_GET["date"])) {
				echo "ENTER A DATE.";
				return;
			}
			$q = "select name from names where (not id in (select id from (select n.* from names n, entries e where n.name like '%' || e.name || '%' and d like '%{$_GET["date"]}%')));";
			$a = $db->query($q);
			while ($n = $a->fetchArray()) {
				echo "<li>{$n["name"]}</li>";
			}
		} else {
			$q = "1";
			if (isset($_GET["name"])) {
				$q .= " and (name like '%{$_GET["name"]}%')";
			}
			if (isset($_GET["date"])) {
				$q .= " and (d like '%{$_GET["date"]}%')";
			}

			$a = $db->query("select * from entries where ($q)");

			while ($r = $a->fetchArray()) {
				if ($r["out"] == 1) {
					$i = "out";
				} else {
					$i = "in";
				}
				echo "<li>{$r["name"]} signed {$i} at {$r["d"]}</li>";
			}
		}
		?>
		</ul>
		<hr>

		<p><a href="/">go home</a></p>
	</body>
</html>
