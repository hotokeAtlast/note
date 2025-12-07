<!-- Index -->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>Index</title>
</head>
<body>
	<?php
		include 'header.php';
	?>


<div class="notes-prev">
	<?php
	require_once 'db_conn.php';
	if($user_id){
		$stmt = $pdo->prepare("SELECT * FROM notes WHERE uid = :uid ORDER BY date DESC, time DESC");
		$stmt->execute([':uid' => $uid]);

		$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($notes as $note){
			echo '
			<div class="note-carousel">
		<div class="prev-title">
			<h6 class="char">'. $note['nof_char'] .' words.</h6>
			<h3>'. htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8') .'</h3>
		</div>
		<p class="content">'. htmlspecialchars(mb_strimwidth($note['content'], 0,120, "..."), ENT_QUOTES, 'UTF-8') .'</p>
		<div class="prev-footer">
		<div class="link"><a href="">read more...</a></div>
		
		<h6 class="prev-footer-right">created on: '. htmlspecialchars($note['date'], ENT_QUOTES, 'UTF-8') .' at '. htmlspecialchars($note['time'], ENT_QUOTES, 'UTF-8') .'</h6>
		</div>
	</div>';
		}
	}	else{
		echo "loggin to see your notes";
	}
	?>
	
</div>
<div id="toast" class="toast"></div>
<script src="script.js"></script>
</body>
</html>

