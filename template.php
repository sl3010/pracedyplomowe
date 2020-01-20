<?php
// USUNAC dbo.SlowaKluczowe opisy do ID_Pracy 27 i 32 (testowe)
// USUNAC dbo.Praca Dyplomowa tematy testowe
//
require_once('co.php');
session_start();
if (!isset($_SESSION['user'])) $_SESSION['user'] = "Gosc";

//
// DODAWANIE EDYCTA STUDENTOW 
//
//// ADD Student
if (isset($_POST["StuAdd"])) {
	site_header(TRUE);	
			echo "<table border=\"0\" width=\"25%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Dodawanie Nowego Studenta </td></tr>",
    		 "<tr><td> Imie*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Imie\" >  </td></tr>",
    		 "<tr><td> Nazwisko*: </td><td > <input type=\"text\" name=\"Nazwisko\" >  </td></tr>",    		 
    		 "<tr><td> NrIndeksu*: </td><td > <input type=\"text\" name=\"NrIndeksu\" maxlength=\"10\">  </td></tr>",    		 
    		 "<tr><td> email*: </td><td > <input type=\"text\" name=\"email\" >  </td></tr>",    		 
    		 "<tr><td> telefon: </td><td > <input type=\"text\" name=\"telefon\" >  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"StuAddSend\" value=\"Dodaj\"> </form></td></tr></table>";
}
if (isset($_POST['StuAddSend'],$_POST['Imie'],$_POST['Nazwisko'],$_POST['NrIndeksu'],$_POST['email'])) {
	site_header(TRUE);	
	$sql = $pdo->query(" SELECT dbo.CzyMa10znakow('$_POST[NrIndeksu]')");
	$row = $sql->fetch();
	if ($row[0] == 0) { // 0 = nr indeksu ma 10 znakow, inna wartosc=zwraca ile podano
  		 // telefon moze byc null
   		 $telefon = trim($_POST['telefon']);
  		 if ( empty($telefon) ) $telefon=NULL; // puste pole
  		 else $telefon=intval($telefon); // w db typ int
        $stmt = $pdo->prepare("INSERT INTO dbo.Student (Imie,Nazwisko,NrIndeksu,email,telefon) VALUES (:imie,:nazwisko,:nrindeksu,:email,:telefon)");
        $stmt->bindParam(':imie', $_POST['Imie']);
        $stmt->bindParam(':nazwisko', $_POST['Nazwisko']);
        $stmt->bindParam(':nrindeksu', $_POST['NrIndeksu']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':telefon', $telefon);
        if ( $stmt->execute() ) echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane zostaly zapisane</font></h3></div> ";
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
  } 
  else echo "<div align=\"center\"><h3><font color=red>BLAD: Numer Indeksu musi skladac sie z 10 znakow, podano ".$row[0]." znakow !</font></h3></div> ";			
}
//// ADD Student - Koniec
//// Edit Student
if (isset($_POST["StuEdi"])) {
	site_header(TRUE);		
echo "<tr><td><h3>do symulacji, wybierz Studenta:</h3> <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TenStudent\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.Student.Imie+' '+dbo.Student.Nazwisko, dbo.Student.ID_Student FROM dbo.Student");
while(list($Student, $ID_Student) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Student\">$Student</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"StuEdiForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST["StuEdiForm"],$_POST['TenStudent'])) {
	site_header(TRUE);	
	// pobierz z db where id_student
 	$stmt = $pdo->prepare("SELECT 
dbo.Student.ID_Student, dbo.Student.Imie, dbo.Student.Nazwisko ,dbo.Student.NrIndeksu ,dbo.Student.email ,dbo.Student.telefon
FROM dbo.Student WHERE dbo.Student.ID_Student=?");
$stmt->execute([$_POST['TenStudent']]); 
	while ($row = $stmt->fetch()) {
//    echo $row['name']."<br />\n";
			echo "<table border=\"0\" width=\"25%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Edycja danych Studenta </td></tr>",
    		 "<tr><td> Imie*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Imie\" value=\"".$row['Imie']."\">  </td></tr>",
    		 "<tr><td> Nazwisko*: </td><td > <input type=\"text\" name=\"Nazwisko\" value=\"".$row['Nazwisko']."\">  </td></tr>",    		 
    		 "<tr><td> NrIndeksu*: </td><td > <input type=\"text\" name=\"NrIndeksu\" maxlength=\"10\" value=\"".$row['NrIndeksu']."\">  </td></tr>",    		 
    		 "<tr><td> email*: </td><td > <input type=\"text\" name=\"email\" value=\"".$row['email']."\">  </td></tr>",    		 
    		 "<tr><td> telefon: </td><td > <input type=\"text\" name=\"telefon\" value=\"".$row['telefon']."\">  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"hidden\" name=\"ID_Student\" value=\"".$row['ID_Student']."\"><input type=\"submit\" class=\"button\" name=\"StuEdiSend\" value=\"Zmien\"> </form></td></tr></table>";
	}					 
}
if (isset($_POST['StuEdiSend'],$_POST['ID_Student'],$_POST['Imie'],$_POST['Nazwisko'],$_POST['NrIndeksu'],$_POST['email'])) {
	site_header(TRUE);	
	$sql = $pdo->query(" SELECT dbo.CzyMa10znakow('$_POST[NrIndeksu]')");
	$row = $sql->fetch();
	if ($row[0] == 0) { // 0 = nr indeksu ma 10 znakow, inna wartosc=zwraca ile podano
  		 // telefon moze byc null
   		 $telefon = trim($_POST['telefon']);
  		 if ( empty($telefon) ) $telefon=NULL; // puste pole
  		 else $telefon=intval($telefon); // w db typ int
  		 $id=intval($_POST['ID_Student']);
        $stmt = $pdo->prepare("UPDATE dbo.Student SET Imie=:imie, Nazwisko=:nazwisko, NrIndeksu=:nrindeksu, email=:email, telefon=:telefon WHERE ID_Student=:id");
				$stmt->bindParam(':id', $id);
        $stmt->bindParam(':imie', $_POST['Imie']);
        $stmt->bindParam(':nazwisko', $_POST['Nazwisko']);
        $stmt->bindParam(':nrindeksu', $_POST['NrIndeksu']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':telefon', $telefon);
        if ( $stmt->execute() ) echo "<div align=\"center\"><h3><font color=green>Zmienione dane zostaly zapisane</font></h3></div> ";
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
  } 
  else echo "<div align=\"center\"><h3><font color=red>BLAD: Numer Indeksu musi skladac sie z 10 znakow, podano ".$row[0]." znakow !</font></h3></div> ";			
}
//// Edit Student - Koniec

//
// DODAWANIE EDYCTA PRACOWNIKOW
//
//// ADD Pracownik Naukowy
if (isset($_POST["NauAdd"])) {
	site_header(TRUE);	
			echo "<table border=\"0\" width=\"25%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Dodawanie Nowego Pracownika Naukowego </td></tr>",
    		 "<tr><td> Imie*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Imie\" >  </td></tr>",
    		 "<tr><td> Nazwisko*: </td><td > <input type=\"text\" name=\"Nazwisko\" >  </td></tr>",
    		 "<tr><td> Stopien Naukowy*: </td><td ><select name=\"StopienNaukowy\"><option value=\"\">Wybierz:</option>";
// Stopien Naukowy z tabeli slownikowej    	
$stmt = $pdo->prepare("SELECT dbo.StopienNauk.NazwaStopnia FROM dbo.StopienNauk");
				$stmt->execute(); 
				while ($row = $stmt->fetch()) {	  
		echo "<option value=\"".$row['NazwaStopnia']."\">".$row['NazwaStopnia']."</option>";
				}   		 
    echo "</select></td></tr>",
    		 "<tr><td> email*: </td><td > <input type=\"text\" name=\"email\" >  </td></tr>",    		 
    		 "<tr><td> telefon*: </td><td > <input type=\"text\" name=\"telefon\" >  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"NauAddSend\" value=\"Dodaj\"> </form></td></tr></table>";
}
if (isset($_POST['NauAddSend'],$_POST['Imie'],$_POST['Nazwisko'],$_POST['StopienNaukowy'],$_POST['email'],$_POST['telefon'])) {
	site_header(TRUE);	
   		  $telefon=intval($_POST['telefon']); // w db typ int
        $stmt = $pdo->prepare("INSERT INTO dbo.Pracownik (Imie,Nazwisko,StopienNaukowy,email,telefon) VALUES (:imie,:nazwisko,:stopiennaukowy,:email,:telefon)");
        $stmt->bindParam(':imie', $_POST['Imie']);
        $stmt->bindParam(':nazwisko', $_POST['Nazwisko']);
        $stmt->bindParam(':stopiennaukowy', $_POST['StopienNaukowy']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':telefon', $telefon);
        if ( $stmt->execute() ) echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane zostaly zapisane</font></h3></div> ";
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
}
//// ADD Pracownik Naukowy - Koniec
//// Edit Pracownik Naukowy
if (isset($_POST["NauEdi"])) {
	site_header(TRUE);		
echo "<tr><td>do symulacji, wybierz Pracownika Naukowego do Edycji <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TenPracownik\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Pracownik\">$Pracownik</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"NauEdiForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST["NauEdiForm"],$_POST['TenPracownik'])) {
	site_header(TRUE);	
	// pobierz z db where id_pracownik
 	$stmt = $pdo->prepare("SELECT 
dbo.Pracownik.ID_Pracownik, dbo.Pracownik.Imie, dbo.Pracownik.Nazwisko ,dbo.Pracownik.StopienNaukowy ,dbo.Pracownik.email ,dbo.Pracownik.telefon
FROM dbo.Pracownik WHERE dbo.Pracownik.ID_Pracownik=?");
$stmt->execute([$_POST['TenPracownik']]); 
	while ($row = $stmt->fetch()) {
//    echo $row['name']."<br />\n";
			echo "<table border=\"0\" width=\"25%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Edycja danych Pracownika Naukowego </td></tr>",
    		 "<tr><td> Imie*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Imie\" value=\"".$row['Imie']."\">  </td></tr>",
    		 "<tr><td> Nazwisko*: </td><td > <input type=\"text\" name=\"Nazwisko\" value=\"".$row['Nazwisko']."\">  </td></tr>",    		 
    		 "<tr><td> Stopien Naukowy*: </td><td ><select name=\"StopienNaukowy\"><option value=\"\">Wybierz:</option>";
// Stopien Naukowy z tabeli slownikowej   
					$stmt2 = $pdo->prepare("SELECT dbo.StopienNauk.NazwaStopnia FROM dbo.StopienNauk");
					$stmt2->execute(); 
					while ($row2 = $stmt2->fetch()) {	  
						if ( $row['StopienNaukowy'] === $row2['NazwaStopnia'] ) $addit=" selected=\"selected\" ";
			  		else $addit="";						
		echo "<option value=\"".$row2['NazwaStopnia']."\" ".$addit.">".$row2['NazwaStopnia']."</option>";
					}   		 
    echo "</select></td></tr>",
    		 "<tr><td> email*: </td><td > <input type=\"text\" name=\"email\" value=\"".$row['email']."\">  </td></tr>",    		 
    		 "<tr><td> telefon: </td><td > <input type=\"text\" name=\"telefon\" value=\"".$row['telefon']."\">  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"hidden\" name=\"ID_Pracownik\" value=\"".$row['ID_Pracownik']."\"><input type=\"submit\" class=\"button\" name=\"NauEdiSend\" value=\"Zmien\"> </form></td></tr></table>";
	}					 
}
if (isset($_POST['NauEdiSend'],$_POST['ID_Pracownik'],$_POST['Imie'],$_POST['Nazwisko'],$_POST['StopienNaukowy'],$_POST['email'],$_POST['telefon'])) {
	site_header(TRUE);	
  		 $telefon=intval($_POST['telefon']); // w db typ int
  		 $id=intval($_POST['ID_Pracownik']);
        $stmt = $pdo->prepare("UPDATE dbo.Pracownik SET Imie=:imie, Nazwisko=:nazwisko, StopienNaukowy=:stopiennaukowy, email=:email, telefon=:telefon WHERE ID_Pracownik=:id");
				$stmt->bindParam(':id', $id);
        $stmt->bindParam(':imie', $_POST['Imie']);
        $stmt->bindParam(':nazwisko', $_POST['Nazwisko']);
        $stmt->bindParam(':stopiennaukowy', $_POST['StopienNaukowy']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':telefon', $telefon);
        if ( $stmt->execute() ) echo "<div align=\"center\"><h3><font color=green>Zmienione dane zostaly zapisane</font></h3></div> ";
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
}
//// Edit Pracownik Naukowy - Koniec

//
// DODAWANIE EDYCTA Prac Dyplomowych
//
//// ADD Praca Dyplomowa
if (isset($_POST["PracaN"])) {
	site_header(TRUE);	
			echo "<table border=\"0\" width=\"50%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Dodawanie Nowego Tematu Pracy Dyplomowej </td></tr>",
    		 "<tr><td> Temat Pracy Dyplomowej*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Temat\" size=\"80\">  </td></tr>",
    		 "<tr><td> Etap Studiow*: </td><td ><select name=\"NazwaEtapStudiow\"><option value=\"\">Wybierz:</option>";
					$stmt = $pdo->prepare("SELECT dbo.EtapStudiow.NazwaEtapStudiow FROM dbo.EtapStudiow");
					$stmt->execute(); 
					while ($row = $stmt->fetch()) {	  
		echo "<option value=\"".$row['NazwaEtapStudiow']."\" >".$row['NazwaEtapStudiow']."</option>";
					}    		 
    echo "</select></td></tr>",    		 
    		 "<tr><td> Przypisany Promotor*: </td><td ><select name=\"ID_Promotor\"><option value=\"\">Wybierz:</option>";
					$stmt = $pdo->prepare("SELECT dbo.Pracownik.ID_Pracownik, (dbo.Pracownik.StopienNaukowy+''+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Pracownik FROM dbo.Pracownik WHERE dbo.Pracownik.StopienNaukowy LIKE '%dr %'");
					$stmt->execute(); 
					while ($row = $stmt->fetch()) {	  
		 echo "<option value=\"".$row['ID_Pracownik']."\">".$row['Pracownik']."</option>";
					}   		 
    echo "</select></td></tr>",
    		 "<tr><td> Slowa kluczowe (min. 1)*: </td><td >";
					$stmt = $pdo->prepare("SELECT dbo.Slowo.ID_Slowa, dbo.Slowo.NazwaSlowa FROM dbo.Slowo ORDER BY dbo.Slowo.NazwaSlowa ASC");
					$stmt->execute(); 
					while ($row = $stmt->fetch()) {	      		 
    echo "<input type=\"checkbox\" name=\"SlowaKluczowe[]\" value=\"".$row['ID_Slowa']."\" >".$row['NazwaSlowa']."<br>";
    			}
     echo "</td></tr>",   		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"NauAddSend\" value=\"Dodaj\"> </form></td></tr></table>";
}
if (isset($_POST['NauAddSend'],$_POST['Temat'],$_POST['NazwaEtapStudiow'],$_POST['ID_Promotor']) AND !empty($_POST['SlowaKluczowe'])) {
	site_header(TRUE);	
	 		  $idpromotor=intval($_POST['ID_Promotor']);  
        $stmt = $pdo->prepare("INSERT INTO dbo.PracaDyplomowa(Temat,NazwaEtapStudiow,ID_Promotor) OUTPUT INSERTED.ID_Pracy VALUES (:temat,:nazwaetapstudiow,:idpromotor)");
        $stmt->bindParam(':temat', $_POST['Temat']);
        $stmt->bindParam(':nazwaetapstudiow', $_POST['NazwaEtapStudiow']);
        $stmt->bindParam(':idpromotor', $idpromotor);
        if ( $stmt->execute() ) {
        	 echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane Pracy Dyplomowej zostaly zapisane</font></h3></div> ";
//obsluga slow kluczowych              	 
    			$idpracy = $stmt->fetch(PDO::FETCH_ASSOC);
        	$slowakluczowe = $_POST['SlowaKluczowe']; // array
        	for ($i=0; $i < count($slowakluczowe); $i++) {
        		$idslowa=intval($slowakluczowe[$i]);
          	$stmt = $pdo->prepare("INSERT INTO dbo.SlowaKluczowe (ID_Slowa,ID_Pracy) VALUES (:idslowa,:idpracy)");
        		$stmt->bindParam(':idslowa', $idslowa);
        		$stmt->bindParam(':idpracy', $idpracy['ID_Pracy']);  
        		if ( $stmt->execute() ) echo "<div align=\"center\"><h3><font color=green>Slowo kluczowe zostalo przypisane do Pracy Dyplomowej</font></h3></div> ";
        		else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis Slowa kluczowego [id=".$idslowa."] do Pracy Dyplomowej sie nie powiodl !</font></h3></div> ";        	      	
        	}        	 
        }
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych Pracy Dyplomowej sie nie powiodl !</font></h3></div> ";
}
elseif (isset($_POST['NauAddSend']) AND empty($_POST['SlowaKluczowe'])) echo "<div align=\"center\"><h3><font color=red>BLAD: Do Pracy musi byc przypisane przynajmniej 1 slowo kluczowe !</font></h3></div> ";

//// ADD Praca Dyplomowa  - Koniec
//// Edit Praca Dyplomowa

if (isset($_POST["PracaE"])) {
	site_header(TRUE);		
echo "<tr><td>do symulacji, wybierz Temat Pracy Dyplomowej do Edycji <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TaPraca\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.ID_Pracy FROM dbo.PracaDyplomowa ORDER BY dbo.PracaDyplomowa.Temat ASC");
while(list($Temat, $ID_Pracy) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Pracy\">$Temat</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"PracaEForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST["PracaEForm"],$_POST['TaPraca'])) {
	site_header(TRUE);	
	$idpracy=intval($_POST['TaPraca']);	
// zbierz przypisane do pracy z innych tabel, slowa kluczowe, recenzentow
	$stmt = $pdo->prepare("SELECT dbo.SlowaKluczowe.ID_Slowa AS ID_Slowa_checked FROM dbo.SlowaKluczowe WHERE dbo.SlowaKluczowe.ID_Pracy=?");
	$stmt->execute([$idpracy]); 
	$slowakluczowePracy = $stmt->fetchAll();
	$stmt = $pdo->prepare("SELECT dbo.Recenzenci.ID_Pracownik AS ID_Pracownik_checked FROM dbo.Recenzenci WHERE dbo.Recenzenci.ID_Pracy=?");
	$stmt->execute([$idpracy]); 
	$RecenzenciPracy = $stmt->fetchAll();	
	// pobierz z db where id_pracy
 	$stmt = $pdo->prepare("SELECT 
dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, dbo.PracaDyplomowa.ID_Promotor
FROM dbo.PracaDyplomowa WHERE dbo.PracaDyplomowa.ID_Pracy=?");
$stmt->execute([$idpracy]); 
	while ($row = $stmt->fetch()) {
		echo "<table border=\"0\" cellspacing=\"15\" width=\"50%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Edycja danych Pracy Dyplomowej </td></tr>",
    		 "<tr><td> Temat Pracy Dyplomowej*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Temat\" size=\"80\" value=\"".$row['Temat']."\">  </td></tr>",
    		 "<tr><td> Etap Studiow*: </td><td ><select name=\"NazwaEtapStudiow\"><option value=\"\">Wybierz:</option>";
					$stmt2 = $pdo->prepare("SELECT dbo.EtapStudiow.NazwaEtapStudiow FROM dbo.EtapStudiow ");
					$stmt2->execute(); 
					while ($row2 = $stmt2->fetch()) {	  
						if ( $row['NazwaEtapStudiow'] === $row2['NazwaEtapStudiow'] ) $addit=" selected=\"selected\" ";
			  		else $addit="";						
		echo "<option value=\"".$row2['NazwaEtapStudiow']."\" ".$addit.">".$row2['NazwaEtapStudiow']."</option>";
					}    		 
    echo "</select></td></tr>",    		 
    		 "<tr><td> Przypisany Promotor*: </td><td ><select name=\"ID_Promotor\"><option value=\"\">Wybierz:</option>";
					$stmt2 = $pdo->prepare("SELECT dbo.Pracownik.ID_Pracownik, (dbo.Pracownik.StopienNaukowy+''+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Pracownik FROM dbo.Pracownik WHERE dbo.Pracownik.StopienNaukowy LIKE '%dr %'");
					$stmt2->execute(); 
					while ($row2 = $stmt2->fetch()) {	  
						if ( $row['ID_Promotor'] === $row2['ID_Pracownik'] ) $addit=" selected=\"selected\" ";
			  		else $addit="";								
		 echo "<option value=\"".$row2['ID_Pracownik']."\" ".$addit.">".$row2['Pracownik']."</option>";
					}   		 
    echo "</select></td></tr>",
    		 "<tr><td> Slowa kluczowe (min. 1)*: </td><td >";
					$stmt2 = $pdo->prepare("SELECT dbo.Slowo.ID_Slowa, dbo.Slowo.NazwaSlowa FROM dbo.Slowo ORDER BY dbo.Slowo.NazwaSlowa ASC");
					if ($stmt2->execute() ) {
					 while ($row2 = $stmt2->fetch()) {
							foreach($slowakluczowePracy as $slowa) {
								if ( $row2['ID_Slowa'] === $slowa['ID_Slowa_checked'] ) { 
									$addit=" checked ";
									break; // znalezione, znajdz kolejne z tablicy
								}
			  				else $addit="";																
							}
						echo "<input type=\"checkbox\" name=\"SlowaKluczowe[]\" value=\"".$row2['ID_Slowa']."\" ".$addit.">".$row2['NazwaSlowa']."<br>";																     		 										  											
						}
    			}
     echo "</td></tr>";   
     		/// recenzenci ..
    echo "</select></td></tr>",
    		 "<tr><td> Recenzenci pracy: </td><td >";
					$stmt2 = $pdo->prepare("SELECT dbo.Pracownik.ID_Pracownik, (dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Pracownik FROM dbo.Pracownik");
					if ($stmt2->execute() ) {
					 while ($row2 = $stmt2->fetch()) {
							foreach($RecenzenciPracy as $Recenzent) {
								if ( $row2['ID_Pracownik'] === $Recenzent['ID_Pracownik_checked'] ) { 
									$addit=" checked ";
									break; // znalezione, znajdz kolejne z tablicy
								}
			  				else $addit="";																
							}
						echo "<input type=\"checkbox\" name=\"Recenzenci[]\" value=\"".$row2['ID_Pracownik']."\" ".$addit.">".$row2['Pracownik']."<br>";																     		 										  											
						}
    			}
     echo "</td></tr>";     		
     		/// 
		echo  "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"hidden\" name=\"ID_Pracy\" value=\"".$row['ID_Pracy']."\"><input type=\"submit\" class=\"button\" name=\"PracaESend\" value=\"Zapisz zmiany\"> </form></td></tr></table>";    		 
	}
}
	

if (isset($_POST['PracaESend'],$_POST['Temat'],$_POST['NazwaEtapStudiow'],$_POST['ID_Promotor'],$_POST['ID_Pracy']) AND !empty($_POST['SlowaKluczowe'])) 
{ 
	site_header(TRUE);	
	 		  $idpromotor=intval($_POST['ID_Promotor']); 
	 		  $idpracy=intval($_POST['ID_Pracy']); 
        $stmt = $pdo->prepare("UPDATE dbo.PracaDyplomowa SET Temat=:temat, NazwaEtapStudiow=:nazwaetapstudiow, ID_Promotor=:idpromotor WHERE ID_Pracy=:idpracy");        
        $stmt->bindParam(':temat', $_POST['Temat']);
        $stmt->bindParam(':nazwaetapstudiow', $_POST['NazwaEtapStudiow']);
        $stmt->bindParam(':idpromotor', $idpromotor);
        $stmt->bindParam(':idpracy', $idpracy);
			        if ( $stmt->execute() ) {
        	 echo "<div align=\"center\"><h3><font color=green>Zmiany w danych Pracy Dyplomowej zostaly zapisane</font></h3></div> ";
//obsluga slow kluczowych              	 
//usun wszystkie przypisane slowa dodaj, zaznaczone na nowo
					$stmt2 = $pdo->prepare("DELETE dbo.SlowaKluczowe WHERE dbo.SlowaKluczowe.ID_Pracy=:idpracy");
					$stmt2->bindParam(':idpracy', $idpracy);
		if (  $stmt2->execute() ) {
        	 $slowakluczowe = $_POST['SlowaKluczowe']; // array
        	 for ($i=0; $i < count($slowakluczowe); $i++) {
        		$idslowa=intval($slowakluczowe[$i]);
          	$stmt2 = $pdo->prepare("INSERT INTO dbo.SlowaKluczowe (ID_Slowa,ID_Pracy) VALUES (:idslowa,:idpracy)");
        		$stmt2->bindParam(':idslowa', $idslowa);
        		$stmt2->bindParam(':idpracy', $idpracy);  
        		if ( $stmt2->execute() ) echo "<div align=\"center\"><h3><font color=green>Slowo kluczowe zostalo przypisane do Pracy Dyplomowej</font></h3></div> ";
        		else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis Slowa kluczowego [id=".$idslowa."] do Pracy Dyplomowej sie nie powiodl !</font></h3></div> ";        	      	
        	 }
        	}
        	
        	 //recenzenci usun poprzednich, dodaj nowo wybranych, moze byc null        	
					$stmt2 = $pdo->prepare("DELETE dbo.Recenzenci WHERE dbo.Recenzenci.ID_Pracy=:idpracy");
					$stmt2->bindParam(':idpracy', $idpracy);
					$stmt2->execute();
     if (!empty($_POST['Recenzenci'])) {        	 
        	 $recenzenci = $_POST['Recenzenci']; // array
        	 for ($i=0; $i < count($recenzenci); $i++) {
        		$idpracownika=intval($recenzenci[$i]);
        		// sprawdz czy nie jest juz promotorem
						$sql = $pdo->query(" SELECT dbo.CzyMozeBycRecenzentem($idpracy,$idpracownika) ");
						$return = $sql->fetch();
						if ($return[0] == 1) { // moze dolaczyc        		
          	$stmt2 = $pdo->prepare("INSERT INTO dbo.Recenzenci (ID_Pracy,ID_Pracownik) VALUES (:idpracy,:idpracownika)");
        		$stmt2->bindParam(':idpracownika', $idpracownika);
        		$stmt2->bindParam(':idpracy', $idpracy);  
        		if ( $stmt2->execute() ) echo "<div align=\"center\"><h3><font color=green>Recenzent zostal przypisane do Pracy Dyplomowej</font></h3></div> ";
        		else echo "<div align=\"center\"><h3><font color=red>BLAD: Recenzent nie zostal dodany do Pracy Dyplomowej !</font></h3></div> ";        	      	
       		
        		}
						else echo "<div align=\"center\"><h3><font color=red>BLAD: Recenzent nie moze byc jednoczesnie Promotorem, Recenzent nie zostal dodany do Pracy Dyplomowej !</font></h3></div> ";        	      	
        	 }        	 	
     }
        }
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych Pracy Dyplomowej sie nie powiodl !</font></h3></div> ";

       
}
elseif (isset($_POST['NauAddSend']) AND empty($_POST['SlowaKluczowe'])) echo "<div align=\"center\"><h3><font color=red>BLAD: Do Pracy musi byc przypisane przynajmniej 1 slowo kluczowe !</font></h3></div> ";


//// Edit Praca Dyplomowa - Koniec

//
// DODAWANIE DATA OBRONY
//
//// ADD Data Obrony
if (isset($_POST["DatAdd"])) {
	site_header(TRUE);	
			echo "<table border=\"0\" cellspacing=\"15\" width=\"50%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Wyznaczanie Daty Obrony </td></tr>",
    		 "<tr><td> Temat Pracy Dyplomowej*: </td><td><form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TaPraca\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.ID_Pracy FROM dbo.PracaDyplomowa ORDER BY dbo.PracaDyplomowa.Temat ASC");
while(list($Temat, $ID_Pracy) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Pracy\">$Temat</option>\n";
}
echo "</select></td></tr>",    		 
    		 "<tr><td> Data obrony w formacie (RRRR-MM-DD) *: </td><td > <input type=\"text\" name=\"data\" >  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  </td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"DatAddSend\" value=\"Dodaj\"> </form></td></tr></table>";
}
if (isset($_POST['DatAddSend'],$_POST['TaPraca'],$_POST['data'])) {
	site_header(TRUE);	
	// sprwadz format daty RRRR-MM-DD
	$idpracy=intval($_POST['TaPraca']);
	$data=$_POST['data'];
	list($year, $month, $day) = explode('-', $data); 
	if ( strtotime($data) AND checkdate($month,$day,$year) ) {
	$data = strtotime($year.'-'.$month.'-'.$day);
	$data = date('Y-m-d H:i:s',$data);
	// dodac date obrony, ustawic zwrocony id obrony studenci where id_pracy
        $stmt = $pdo->prepare("INSERT INTO dbo.Obrona (Data) OUTPUT INSERTED.ID_Obrony VALUES (:data)");
        $stmt->bindParam(':data', $data);       	
        if ( $stmt->execute() ) {
        	echo "<div align=\"center\"><h3><font color=green>Wprowadzone Obrona zostala zapisane</font></h3></div> ";
    			$idobrony = $stmt->fetch(PDO::FETCH_ASSOC); 
    			$stmt = $pdo->prepare("UPDATE dbo.Studenci SET ID_Obrony=:idobrony WHERE dbo.Studenci.ID_Pracy = :idpracy");
        	$stmt->bindParam(':idobrony', $idobrony['ID_Obrony']);       	
        	$stmt->bindParam(':idpracy', $idpracy);
        	if ( $stmt->execute() ) 
        	echo "<div align=\"center\"><h3><font color=green>Wyznaczona Obrona zostala przypisana do Autora</font></h3></div> ";
        	else echo "<div align=\"center\"><h3><font color=red>BLAD: Wyznaczona Obrona NIE zostala przypisana do Autora!</font></h3></div> ";
        }
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl, Obrona NIE zapiasna !</font></h3></div> ";
  } 
  else echo "<div align=\"center\"><h3><font color=red>BLAD: Niewlasciwy format daty !</font></h3></div> ";			
}
//// ADD Data Obrony - Koniec
//// Edit Data Obrony
if (isset($_POST["DatEdi"])) {
	site_header(TRUE);		
echo "<tr><td>do symulacji, wybierz Temat Pracy Dyplomowej której chcesz zmienic date obrony<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TaPraca\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.ID_Pracy FROM dbo.PracaDyplomowa ORDER BY dbo.PracaDyplomowa.Temat ASC");
while(list($Temat, $ID_Pracy) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Pracy\">$Temat</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"DatEdiForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST["DatEdiForm"],$_POST['TaPraca'])) {
	site_header(TRUE);	
	// pobierz z db where id_pracy
	$tapraca=intval($_POST['TaPraca']);
 	$stmt = $pdo->prepare("SELECT DISTINCT
dbo.PracaDyplomowa.Temat, CONVERT(DATE, dbo.Obrona.Data) AS  Data, dbo.Obrona.ID_Obrony
FROM dbo.PracaDyplomowa 
RIGHT JOIN dbo.Studenci ON dbo.PracaDyplomowa.ID_Pracy = dbo.Studenci.ID_Pracy
RIGHT JOIN dbo.Obrona ON dbo.Studenci.ID_Obrony = dbo.Obrona.ID_Obrony
WHERE dbo.Studenci.ID_Pracy=?");
$stmt->execute([$tapraca]); 
	while ($row = $stmt->fetch()) {
//    echo $row['name']."<br />\n";
			echo "<table border=\"0\" width=\"25%\" align=\"center\">",
				 "<tr><td colspan=\"2\" align=\"center\"> Edycja daty obrony pracy dyplomowej</td></tr>",
    		 "<tr><td> Temat Pracy*: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > ".$row['Temat']." </td></tr>",
    		 "<tr><td> Data obrony w formacie (RRRR-MM-DD) *: </td><td > <input type=\"text\" name=\"Data\" value=\"".$row['Data']."\">  </td></tr>",    		 
    		 "<tr><td colspan=\"2\"> * - Wymagane  <input type=\"hidden\" name=\"ID_Obrony\" value=\"".$row['ID_Obrony']."\"></td></tr>",    		     		 
				 "<tr><td> </td><td> <input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"DatEdiSend\" value=\"Zmien\"> </form></td></tr></table>";
	}					 
}
if (isset($_POST['DatEdiSend'],$_POST['Data'],$_POST['ID_Obrony'])) {
	site_header(TRUE);	
	// sprwadz format daty RRRR-MM-DD
	$idobrony=intval($_POST['ID_Obrony']);
	$data=$_POST['Data'];
	list($year, $month, $day) = explode('-', $data); 
	if ( strtotime($data) AND checkdate($month,$day,$year) ) {
	$data = strtotime($year.'-'.$month.'-'.$day);
	$data = date('Y-m-d H:i:s',$data);
        $stmt = $pdo->prepare("UPDATE dbo.Obrona SET Data = :data WHERE ID_Obrony = :idobrony");
        $stmt->bindParam(':data', $data);       	
        $stmt->bindParam(':idobrony', $idobrony);       	
        if ( $stmt->execute() ) {
        	echo "<div align=\"center\"><h3><font color=green>Zmieniona Data Obrony zostala zapisane</font></h3></div> ";
        }
        else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl, Obrona NIE zapiasna !</font></h3></div> ";
  } 
  else echo "<div align=\"center\"><h3><font color=red>BLAD: Niewlasciwy format daty !</font></h3></div> ";			
}
//// Edit Data Obrony - Koniec
//
///// RAPORTY
//
// Raport - Prace Recenzowane przez pracownika uczelni
if (isset($_POST["RapPrac"])) {
	site_header(TRUE);		
echo "<tr><td> wybierz Pracownika Naukowego do wyswietlenia recenzowanych prac <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"TenPracownik\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT DISTINCT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik RIGHT JOIN dbo.Recenzenci ON dbo.Pracownik.ID_Pracownik = dbo.Recenzenci.ID_Pracownik");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Pracownik\">$Pracownik</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"RapPracForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST['RapPracForm'],$_POST['TenPracownik'])) {
	site_header(TRUE);	
echo "</table><br><br><div style=\"margin-left: 70px\"><h1> Raport - Prace Recenzowane przez pracownika uczelni </h1></div>",	
		 "<table border=\"1\" cellspacing=\"0\" cellpading=\"1\" width=\"90%\" align=\"center\">",
     "<tr><td> </td><td align=\"center\"> Temat </td><td align=\"center\"> Recenzent </td><td align=\"center\"> Recenzja </td><td align=\"center\"> Ocena Recenzenta </td><td align=\"center\"> Data Obrony </td><td align=\"center\"> Ocena Koncowa </td></tr>";
  $i=1;
	$tenpracownik=intval($_POST['TenPracownik']);
 	$stmt = $pdo->prepare("SELECT 
dbo.PracaDyplomowa.Temat,  (dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Recenzent,
dbo.Recenzenci.Recenzja, dbo.Recenzenci.Ocena, dbo.Studenci.Ocenakoncowa, CONVERT(DATE, dbo.Obrona.Data) AS  Data 
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Recenzenci ON dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.Pracownik.ID_Pracownik = dbo.Recenzenci.ID_Pracownik
WHERE dbo.Recenzenci.ID_Pracownik=?");
$stmt->execute([$tenpracownik]); 
	while ($row = $stmt->fetch()) {
    echo "<tr><td>$i</td><td >".$row['Temat']."</td><td align=\"center\">".$row['Recenzent']."</td><td >".$row['Recenzja']."</td><td align=\"center\">".$row['Ocena']."</td><td align=\"center\">".$row['Data']."</td><td align=\"center\">".$row['Ocenakoncowa']."</td></tr>";
   $i++;    
	}					 
}
//
// Raport - Prace obronione w dniu
//
if (isset($_POST["RapData"])) {
	site_header(TRUE);		
echo "<tr><td> Podaj Date do wskazania prac obronionych w danym dniu w formacie (RRRR-MM-DD) :<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
		 "<input type=\"text\" name=\"Data\"><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"RapDataForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST['RapDataForm'],$_POST['Data'])) {
	site_header(TRUE);	
  $i=1;
	// sprwadz format daty RRRR-MM-DD
	$data=$_POST['Data'];
	list($year, $month, $day) = explode('-', $data); 
	if ( strtotime($data) AND checkdate($month,$day,$year) ) {
echo "</table><br><br><div style=\"margin-left: 70px\"><h1> Raport - Prace obronione w dniu </h1></div>",	
		 "<table border=\"1\" cellspacing=\"0\" cellpading=\"1\" width=\"90%\" align=\"center\">",
     "<tr><td> </td><td align=\"center\"> Temat </td><td align=\"center\"> Recenzent </td><td align=\"center\"> Autor </td><td align=\"center\"> Recenzja </td><td align=\"center\"> Ocena Recenzenta </td><td align=\"center\"> Data Obrony </td><td align=\"center\"> Ocena Koncowa </td></tr>";	
		$data = strtotime($year.'-'.$month.'-'.$day);
		$data = date('Y-m-d H:i:s',$data);
//
 		$stmt = $pdo->prepare("SELECT 
dbo.PracaDyplomowa.Temat,  (dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Recenzent,
(dbo.Student.Imie+' '+dbo.Student.Nazwisko) AS Autor,
dbo.Recenzenci.Recenzja, dbo.Recenzenci.Ocena, dbo.Studenci.Ocenakoncowa, CONVERT(DATE, dbo.Obrona.Data) AS  Data 
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Recenzenci ON dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.Pracownik.ID_Pracownik = dbo.Recenzenci.ID_Pracownik
WHERE dbo.Obrona.Data = :data
ORDER BY Recenzent ASC ");
		$stmt->bindParam(':data', $data);       	
$stmt->execute(); 
		while ($row = $stmt->fetch()) {
    	echo "<tr><td>$i</td><td >".$row['Temat']."</td><td align=\"center\">".$row['Recenzent']."</td><td align=\"center\">".$row['Autor']."</td><td >".$row['Recenzja']."</td><td align=\"center\">".$row['Ocena']."</td><td align=\"center\">".$row['Data']."</td><td align=\"center\">".$row['Ocenakoncowa']."</td></tr>";
  	 $i++;    
		}
	echo "</table>";
	} 
  else echo "<div align=\"center\"><h3><font color=red>BLAD: Niewlasciwy format daty !</font></h3></div> ";								 
}
//
// Raport - Raport - Prace obronione na danym rodzaju studiow
//
if (isset($_POST["RapEtap"])) {
	site_header(TRUE);		
echo "<tr><td> wybierz Etap Studiow do wyswietlenia obronionych prac <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"Etap\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.EtapStudiow.NazwaEtapStudiow FROM dbo.EtapStudiow");
while(list($Etap) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$Etap\">$Etap</option>\n";
}
echo "</select><input type=\"hidden\" name=\"block\" value=\"block\"><input type=\"submit\" class=\"button\" name=\"RapEtapForm\" value=\"Wybierz\"> </form> </td></tr>";
}
if (isset($_POST['RapEtapForm'],$_POST['Etap'])) {
	site_header(TRUE);	
echo "</table><br><br><div style=\"margin-left: 70px\"><h1> Raport - Prace obronione na danym rodzaju studiow </h1></div>",	
		 "<table border=\"1\" cellspacing=\"0\" cellpading=\"1\" width=\"90%\" align=\"center\">",
     "<tr><td> </td><td align=\"center\"> Temat </td><td align=\"center\"> Rodzaj Studiow </td><td align=\"center\"> Data Obrony </td><td align=\"center\"> Ocena Koncowa </td></tr>";
  $i=1;
 	$stmt = $pdo->prepare("SELECT DISTINCT
dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, dbo.Studenci.Ocenakoncowa, CONVERT(DATE, dbo.Obrona.Data) AS  Data 
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
RIGHT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
WHERE dbo.PracaDyplomowa.NazwaEtapStudiow=?");
$stmt->execute([$_POST['Etap']]); 
	while ($row = $stmt->fetch()) {
    echo "<tr><td>$i</td><td >".$row['Temat']."</td><td align=\"center\">".$row['NazwaEtapStudiow']."</td><td align=\"center\">".$row['Data']."</td><td align=\"center\">".$row['Ocenakoncowa']."</td></tr>";
   $i++;    
	}					 
}
/// KONIEC RAPORTY 

//////// 
////////// Promotor - Dodaj Recenzje, Ocene Promotra
if ( isset($_POST['DodajRecPromotora'],$_POST['Wyb_ID_Pracy'],$_POST['Wyb_Temat'],$_SESSION['JakoPromotor']) ) {
	site_header(TRUE);		
	$sql = $pdo->query("SELECT dbo.PracaDyplomowa.Prom_Recenzja , dbo.PracaDyplomowa.Prom_Ocena FROM dbo.PracaDyplomowa WHERE dbo.PracaDyplomowa.ID_Promotor ='$_SESSION[JakoPromotor]' AND dbo.PracaDyplomowa.ID_Pracy = '$_POST[Wyb_ID_Pracy]'");
	$arr = $sql->fetch();	
		echo  "</table><br><br><div style=\"margin-left: 70px\"><h1> Promotor - Dodaj Recenzje, Ocene Promotra </h1></div>",	
		 			"<table border=\"1\" cellspacing=\"0\" cellpading=\"1\" width=\"90%\" align=\"center\">",
					"<tr><td colspan=\"2\"> Dodawanie Recenzji, Oceny Promotora do Pracy Dyplomowej: ".$_POST["Wyb_Temat"]." </td></tr>",
    		 "<tr><td> Recenzja: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Recenzja_New\" value=\"".$arr[0]."\">  </td></tr>",
    		 "<tr><td> Ocena: </td><td > <input type=\"text\" name=\"Ocena_New\" value=\"".$arr[1]."\">  </td></tr>",    		 
				 "<tr><td colspan=\"2\"><input type=\"hidden\" name=\"ID_Pracy\" value=\"".$_POST['Wyb_ID_Pracy']."\">",
				 "<input type=\"hidden\" name=\"ID_Promotor\" value=\"".$_SESSION['JakoPromotor']."\">",
				 "<input type=\"submit\" class=\"button\" name=\"RecOcenPromotor\" value=\"Dodaj Recenzje, Ocene\"> </form></td></tr>";
} 
if (isset($_POST['RecOcenPromotor'],$_POST['Recenzja_New'], $_POST['Ocena_New'], $_POST['ID_Pracy'], $_POST['ID_Promotor'])) {
	site_header(TRUE);		
	// Zmien recenzje/ocene do danej pracy , sprawdz ocene czy jest w skali o 2 do 5
	$sql = $pdo->query(" SELECT dbo.SprawdzOcene($_POST[Ocena_New])");
	$row = $sql->fetch();
	if ($row[0] == 1) { // ocena miesci sie w skali 2-5
		$ocena = trim($_POST['Ocena_New']);
		$recenzja = trim($_POST['Recenzja_New']);
    $sql = $pdo->prepare("UPDATE dbo.PracaDyplomowa SET dbo.PracaDyplomowa.Prom_Recenzja=:recenzja, dbo.PracaDyplomowa.Prom_Ocena=:ocena WHERE dbo.PracaDyplomowa.ID_Pracy=:idpracy AND dbo.PracaDyplomowa.ID_Promotor=:idpromotor");
    $sql->bindParam(':recenzja', $recenzja);
    $sql->bindParam(':ocena', $ocena);
    $sql->bindParam(':idpracy', $_POST['ID_Pracy']);
    $sql->bindParam(':idpromotor', $_POST['ID_Promotor']);
    if ( $sql->execute() ) echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane zostaly zapisane</font></h3></div> ";
		else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
	}
	else echo "<tr><td align=\"center\"><font color=red>BLAD: Ocena pracy musi byc w przedziale 2-5 !</font></td></tr> ";		
}


////////// RECENZENT - Dodaj Recenzje, Ocene
if ( isset($_POST['DodajRecOcen'],$_POST['Temat'],$_POST['ID_Pracy'],$_SESSION['JakoRecenzent']) ) {
//zczytaj sobie ocene, recenze
site_header(TRUE);
	$sql = $pdo->prepare("SELECT dbo.Recenzenci.Recenzja, dbo.Recenzenci.Ocena FROM dbo.Recenzenci WHERE dbo.Recenzenci.ID_Pracy =:idpracy AND dbo.Recenzenci.ID_Pracownik = :idrecenzent");
	$sql->bindParam(':idpracy', $_POST['ID_Pracy']);
  $sql->bindParam(':idrecenzent', $_SESSION['JakoRecenzent']);
  $sql->execute(); 
  while ($row = $sql->fetch()) {	
		echo "<tr><td colspan=\"2\"> Dodawanie Recenzji, Oceny do Pracy Dyplomowej: ".$_POST['Temat']." </td></tr>",
    		 "<tr><td> Recenzja: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"> </td><td > <input type=\"text\" name=\"Recenzja_New\" value=\"".$row['Recenzja']."\">  </td></tr>",
    		 "<tr><td> Ocena: </td><td > <input type=\"text\" name=\"Ocena_New\" value=\"".$row['Ocena']."\">  </td></tr>",    		 
				 "<tr><td colspan=\"2\"><input type=\"hidden\" name=\"ID_Pracy\" value=\"".$_POST['ID_Pracy']."\">",
				 "<input type=\"hidden\" name=\"ID_Recenzent\" value=\"".$_SESSION['JakoRecenzent']."\">",
				 "<input type=\"submit\" class=\"button\" name=\"ZmienRecOcen\" value=\"Dodaj Recenzje, Ocene\"> </form></td></tr>";
	}
} 
if (isset($_POST['ZmienRecOcen'],$_POST['Recenzja_New'], $_POST['Ocena_New'], $_POST['ID_Pracy'], $_POST['ID_Recenzent'])) {
	site_header(TRUE);
	// Zmien recenzje/ocene do danej pracy , sprawdz ocene czy jest w skali o 2 do 5
	$sql = $pdo->query(" SELECT dbo.SprawdzOcene($_POST[Ocena_New])");
	$row = $sql->fetch();
	if ($row[0] == 1) { // ocena miesci sie w skali 2-5
		$ocena = trim($_POST['Ocena_New']);
		$recenzja = trim($_POST['Recenzja_New']);		
    $sql = $pdo->prepare("UPDATE dbo.Recenzenci SET dbo.Recenzenci.Recenzja=:recenzja, dbo.Recenzenci.Ocena=:ocena WHERE dbo.Recenzenci.ID_Pracy=:idpracy AND dbo.Recenzenci.ID_Pracownik=:idrecenzent");
    $sql->bindParam(':recenzja', $recenzja);
    $sql->bindParam(':ocena', $ocena);
    $sql->bindParam(':idpracy', $_POST['ID_Pracy']);
    $sql->bindParam(':idrecenzent', $_POST['ID_Recenzent']);
    if ( $sql->execute() ) echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane zostaly zapisane</font></h3></div> ";
		else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
	}
	else echo "<tr><td align=\"center\"><font color=red>BLAD: Ocena pracy musi byc w przedziale 2-5 !</font></td></tr> ";		
}













////////// FUNKCJA SITE HEADER - NAGLOWEK WSZYSTKICH STRON
function site_header($switch) {
	if ($switch) { // :bool - switch do okreslenia czy ma swie wysweitlac przy danym formularzu 
?>
<html>
<head>
<?php
header('Content-Type: text/html; charset=WINDOWS-1250');
//mb_internal_encoding('WINDOWS-1250');
//mb_http_output('WINDOWS-1250');
//mb_http_input('WINDOWS-1250');
//mb_regex_encoding('WINDOWS-1250');
?>
   <title>Thesis</title>
<style>
<!--
body, table, tr, td, p, ul, li {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 13px;
}
.top {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 13px;
  font-weight: bold;
  text-align: center;
}
-->
</style>
</head>
<body hlink="black" alink="black" link="black" vlink="black" bgcolor="EFF6FB">
<table border="0" cellspacing="0" cellpading="1" width="90%" align="center" bgcolor="yellow">
  <tr><td align="center"> Zmieñ Uzytkownika: <form action="<?php $_SERVER['PHP_SELF'] ?>"  method="post">
 <input type="submit" class="button" name="Gosc" value="Gosc">
 <input type="submit" class="button" name="Student" value="Student">   
 <input type="submit" class="button" name="Recenzent" value="Recenzent">
 <input type="submit" class="button" name="Promotor" value="Promotor">
 <input type="submit" class="button" name="Dziekanat" value="Pracownik Dziekanatu"> 
 <input type="submit" class="button" name="Naukowy" value="Pracownik Naukowy"></form></td></tr>

<?php 
echo "<tr><td>";
				 
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
	if ( isset($_POST["Gosc"]) ) {
		session_unset();						
		$_SESSION['user'] = "Gosc";
		echo "<h3>Dostepne opcje dla uzytkownika ".$_SESSION['user']." , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych </a><br>",
				 "<li><a href=\"display.php\"> Przegl¹danie katalogu prac </a></ul><br>";		
	}
	elseif ( isset($_POST["Student"]) ) {
		session_unset();						
		$_SESSION['user'] = "Student";
		echo "<h3>Dostepne opcje dla uzytkownika ".$_SESSION['user']." , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych , dodatkowo mozliwosc wyboru tematu pracy</a><br>",
				 "<li>Wybor tematu pracy - zintegrowany powyzej",
				 "<li><a href=\"display.php\"> Przegladanie katalogu prac dyplomowych </a><br>",
				 "<li><a href=\"viewone.php\"> Podglad Szczegolowy Prac - Prace Dyplomowe Studenta </a></ul><br><br>";				 		
	}
	elseif ( isset($_POST["Recenzent"]) ) {
		session_unset();				
		$_SESSION['user'] = "Recenzent";
		echo "<h3>Dostepne opcje dla uzytkownika ".$_SESSION['user']." , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych , dodatkowo mozliwosc dodania recenzji/oceny</a><br>",
				 "<li>Dodanie recenzji/oceny - zintegrowany powyzej",
				 "<li><a href=\"display.php\"> Przegladanie katalogu prac dyplomowych </a><br>",
				 "<li><a href=\"viewone.php\"> Podglad Szczegolowy Prac - Prace Recenzowane przez danego Recenzenta, dodatkowo mozliwosc dodania recenzji/oceny </a></ul><br><br>";		
	}
	elseif ( isset($_POST["Promotor"]) ) {
		session_unset();		
		$_SESSION['user'] = "Promotor";
		echo "<h3>Dostepne opcje dla uzytkownika ".$_SESSION['user']." , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych , dodatkowo mozliwosc dodania recenzji/oceny</a><br>",
				 "<li>Dodanie recenzji/oceny Promotora - zintegrowany powyzej",
				 "<li><a href=\"display.php\"> Przegladanie katalogu prac dyplomowych </a><br>",
				 "<li><a href=\"viewone.php\"> Podglad Szczegolowy Prac - Prace pod opierka Promotora, dodatkowo mozliwosc dodania recenzji/oceny Promotora </a></ul><br><br>";		
	}	
	elseif ( isset($_POST["Dziekanat"]) ) {
		session_unset();						
		$_SESSION['user'] = "Dziekanat";
		echo "<h3>Dostepne opcje dla uzytkownika Pracownik ".$_SESSION['user']."u , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych , dodatkowo mozliwosc Podgladu szczegolowego Prac po nasicnieciu tematu </a><br>",
				 "<li><a href=\"viewone.php\"> Podglad Szczegolowy Prac - Poprzez klincie podglad wybranej pracy </a><br><br>",	
				 "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><input type=\"hidden\" name=\"block\" value=\"block\">",
				 "<li><input type=\"submit\" class=\"button\" name=\"StuAdd\" value=\"Dodaj studenta\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"StuEdi\" value=\"Edycja studenta\"><br><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"NauAdd\" value=\"Dodaj pracownika\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"NauEdi\" value=\"Edycja pracownika\"><br><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"PracaN\" value=\"Dodaj nowa prace dyplomowa\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"PracaE\" value=\"Edytuj dane pracy dyplomowej\"><br><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"DatAdd\" value=\"Dodaj date obrony\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"DatEdi\" value=\"Zmieñ datê obrony\"><br><br>",
				 "Raporty: <br>",				 
				 "<li><input type=\"submit\" class=\"button\" name=\"RapPrac\" value=\"Prace Recenzowane przez pracownika uczelni\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"RapData\" value=\"Prace obronione w dniu\"><br>",
				 "<li><input type=\"submit\" class=\"button\" name=\"RapEtap\" value=\"Prace obronione na danym rodzaju stiudiow\"></form><br>";
	}	
	elseif ( isset($_POST["Naukowy"]) ) {
		session_unset();						
		$_SESSION['user'] = "Naukowy";
				echo "<h3>Dostepne opcje dla uzytkownika Pracownik ".$_SESSION['user']." , w systemie: </h3><ul>",
				 "<li><a href=\"find.php\"> Wyszukiwanie prac dyplomowych , dodatkowo mozliwosc wybrania tematu pracy do Recenzowania</a><br>",
				  "<li>Dolaczenie Recenzenta - zintegrowany powyzej",
				 "<li><a href=\"display.php\"> Przegladanie katalogu prac dyplomowych </a><br>";
				 
	}			
}
else { 
	session_unset();					
	$_SESSION['user'] = "Gosc";
	echo " Przegladasz Witryne jako ".$_SESSION['user'];
}

echo "</td></tr>";
} // switch end
} // header end

function site_footer() {
?>
	<br><br>
	<hr>
</body>
</html>	
<?php
}
?>


