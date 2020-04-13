
<?php
	$target = 1;
	$version = 0;	

	// ---------------------------------
	// Verbindung zur Datenbank aufbauen
	// ---------------------------------
	echo("verbinde zur Datenbank</br>");

	$pdo = new PDO('mysql:host=localhost;dbname=jobportal', 'REST', 'Paulchen_1');


	// ------------------------------------------------------
	// tblDatabaseBuild anlegen falls nicht bereits geschehen
	// ------------------------------------------------------
	echo("prüfe Tablle tblDatabaseBuild</br>");

	$sql = "CREATE TABLE IF NOT EXISTS tblDatabaseBuild (
		Build int(11) NOT NULL,
		PRIMARY KEY (Build)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;";


	$statement = $pdo->prepare($sql);
	$statement->execute();

	// ------------------------------------------------------------------
	// lese aktuelle Build Version oder setze auf 1 falls nicht vorhanden
	// ------------------------------------------------------------------
	echo("lese aktuelle Build Version</br>");

	$sql = "select if(isnull(max(BUILD))>0,0,max(BUILD)) BUILD from tblDatabaseBuild;";
	$statement = $pdo->prepare($sql);
	$statement->execute();
	$row = $statement->fetch();
	if ($row['BUILD'] == 0){
		echo("setze Build Version auf 1</br>");
		$sql = "insert into tblDatabaseBuild(BUILD) values(1);";
		$statement = $pdo->prepare($sql);
		$statement->execute();
	} else {
		echo("aktuelle Build Version: ".$row['BUILD']."</br>");
		$version = $row['BUILD'];
	}


	// ---------------
	// tblUser anlegen
	// ---------------		
	$target = $target + 1;
	if ($target > $version){
		echo("Build 2: lege tblUser an</br>");

		// Tabelle anlegen
		$sql = "CREATE TABLE tblUser (
  			ID INT NOT NULL AUTO_INCREMENT,
			Email VARCHAR(255) NOT NULL,
  			Token VARCHAR(255) NOT NULL,
  			PasswordMD5 VARCHAR(255) NOT NULL,
			EmailValidated INT NOT NULL,
  			Locked INT NOT NULL,
			Deleted INT NOT NULL,
  			PRIMARY KEY (ID));";

		$statement = $pdo->prepare($sql);
		$statement->execute();

		// Build Version anpassen
		$sql = "UPDATE tblDatabaseBuild Set BUILD = ".$target." WHERE 1=1";
		$statement = $pdo->prepare($sql);
		$statement->execute();

	}

?>

