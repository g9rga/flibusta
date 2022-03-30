<style>
.c {
	background: #eee;
	border-radius: 50%;
	border-color: #eee;
}
</style>

<?php
$stmt = $dbh->prepare("SELECT g.GenreMeta
	FROM libgenrelist g
	GROUP BY g.GenreMeta
	ORDER BY (SELECT COUNT(*) FROM libgenrelist a WHERE g.GenreMeta=a.GenreMeta) DESC");
$stmt->execute();
$cn = 10;


echo "<div class='container'><div class='row'>";

while ($bg = $stmt->fetch()) {
	$cn++;
	if ($cn > 0) {
		$cn = 0;
	}

	echo "<div class='col-sm-6 mb-3'>";
	echo "<div class='card'>";

	echo "<div class='card-header'><h3>$bg->genremeta</h3></div>";

	echo "<div class='card-body'><table class='table'><tbody>";

	$st2 = $dbh->prepare("SELECT libgenrelist.genreid, libgenrelist.genremeta, libgenrelist.genredesc,
		(SELECT COUNT(*) FROM libgenre WHERE libgenre.GenreId=libgenrelist.GenreId) cnt
		FROM libgenrelist
		WHERE GenreMeta=:meta
		ORDER BY genredesc");
	$st2->bindParam(":meta", $bg->genremeta);
	$st2->execute();
	while ($g = $st2->fetch()) {	
		echo "<tr>";
		echo "<td><a href='/?gid=$g->genreid/'>$g->genredesc</a></td>";
	        echo "<td>$g->cnt</td>";
		echo "</tr>";
	}
	
	echo "</tbody></table></div>";
	echo "</div>";
	echo "</div>";

}
echo "</div></div>";
