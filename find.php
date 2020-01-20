<?php
require_once('template.php');
//site_header(); // template top
if (isset($_POST['block'])) site_header(FALSE);
else site_header(TRUE);

//////////////// STUDENT - Wybiera prace do pisania
if ( $_SESSION['user'] == "Student" ) {
echo "<tr><td><h3>do symulacji, wyboru tematu pracy w roli Studenta, wybierz Studenta:</h3> <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoStudent\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.Student.Imie+' '+dbo.Student.Nazwisko, dbo.Student.ID_Student FROM dbo.Student;");
while(list($Student, $ID_Student) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"$ID_Student\">$Student</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzTegoStudenta\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoStudent']) ) {
	$_SESSION['JakoStudent'] = $_POST['JakoStudent'];
}
//////////////// PROMOTOR - Opiekuje sie swoimi pracami
if ( ($_SESSION['user'] == "Promotor") AND !isset($_POST['block'])) {
echo "<tr><td>do symulacji, wybierz Promotora <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoPromotor\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT DISTINCT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik RIGHT JOIN dbo.PracaDyplomowa ON dbo.Pracownik.ID_Pracownik = dbo.PracaDyplomowa.ID_Promotor");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Pracownik."\">".$Pracownik."</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzPromotora\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoPromotor']) ) {
	$_SESSION['JakoPromotor'] = $_POST['JakoPromotor'];
}

//////////////// RECENZET - Dodaje opinie do swoich prac
if (( $_SESSION["user"] == "Recenzent")AND !isset($_POST['block'])) {
echo "<tr><td>do symulacji, wybierz Recenzenta <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoRecenzent\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT DISTINCT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik JOIN dbo.Recenzenci ON dbo.Recenzenci.ID_Pracownik = dbo.Pracownik.ID_Pracownik;");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Pracownik."\">".$Pracownik."</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzRecenzenta\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoRecenzent']) ) {
	$_SESSION['JakoRecenzent'] = $_POST['JakoRecenzent'];
}

//////////////// PRACOWNIK NAUKOWY - Wybiera prace do recenzi
if ( $_SESSION['user'] == "Naukowy" ) {
echo "<tr><td>do symulacji, wyboru pracy do Recenzji w roli Pracownika Naukowego: <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoNaukowy\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik ;");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Pracownik."\">".$Pracownik."</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzTegoNaukowego\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoNaukowy']) ) {
	$_SESSION['JakoNaukowy'] = $_POST['JakoNaukowy'];
}


// zostaw to table
echo "</table>";
if (!isset($_POST['block'])) {
//
/////////////////////// Kryteria wyszukiwania
//
?>		
<div style="margin-left: 70px"><h1> Wyszukiwanie Prac Dyplomowych </h1></div>
<table border="0"  " cellspacing="5" cellpading="0" width="90%" align="center">
<tr><td width="25%"><form action="<?php $_SERVER['PHP_SELF'] ?>"  method="post"> Wyszukanie po promotorze </td><td><select name="szuPromotora"><option value="">Wybierz:</option>
<?php 
$results = $pdo->query("SELECT DISTINCT(dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, dbo.PracaDyplomowa.ID_Promotor 
FROM dbo.Pracownik 
INNER JOIN dbo.PracaDyplomowa ON dbo.Pracownik.ID_Pracownik=dbo.PracaDyplomowa.ID_Promotor;");
while(list($Promotor, $ID_Promotor) = $results->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Promotor."\">".$Promotor."</option>\n";
}
?></select></td></tr>
<tr><td> Wyszukanie po slowie kluczowym </td><td><select name="szuSlowa"><option value="">Wybierz:</option>
<?php 
$results = $pdo->query("SELECT DISTINCT dbo.Slowo.NazwaSlowa, dbo.Slowo.ID_Slowa FROM dbo.Slowo 
INNER JOIN dbo.SlowaKluczowe ON dbo.Slowo.ID_Slowa = dbo.SlowaKluczowe.ID_Slowa
INNER JOIN dbo.PracaDyplomowa ON dbo.SlowaKluczowe.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy;");
while(list($Slowo, $ID_Slowa) = $results->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Slowa."\">".$Slowo."</option>\n";
}
?></select></td></tr>
<tr><td> </td><td><input type="submit" class="button" name="Szukaj" value="Szukaj"> </td></tr>
</table>
</form>
<?php 
}
/////////////////////// Kryteria wyszukiwania - Koniec

/////////////////////// Kwerenda sql 
if (!empty($_POST['szuPromotora']) OR !empty($_POST['szuSlowa'])  ) {
$query="SELECT DISTINCT
dbo.PracaDyplomowa.ID_Pracy, dbo.Student.ID_Student, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, 
(dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, 
Recenzenci = STUFF((SELECT ';; '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko+' | '+CONVERT(varchar,dbo.Pracownik.ID_Pracownik) FROM dbo.Pracownik INNER JOIN dbo.Recenzenci  ON dbo.Pracownik.ID_Pracownik = dbo.Recenzenci.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,''),
dbo.Student.NrIndeksu, dbo.Studenci.Ocenakoncowa, CONVERT(DATE, dbo.Obrona.Data) AS  Data 
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
LEFT JOIN dbo.Pracownik ON dbo.Pracownik.ID_Pracownik = dbo.PracaDyplomowa.ID_Promotor
LEFT JOIN dbo.SlowaKluczowe ON dbo.SlowaKluczowe.ID_Pracy  = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony ";

if ( !empty($_POST['szuPromotora']) AND !empty($_POST['szuSlowa']) ) {
$query.=" WHERE dbo.PracaDyplomowa.ID_Promotor = '$_POST[szuPromotora]' AND dbo.SlowaKluczowe.ID_Slowa = '$_POST[szuSlowa]' ";
}
elseif ( !empty($_POST["szuPromotora"]) ) { 
$query.=" WHERE dbo.PracaDyplomowa.ID_Promotor = '$_POST[szuPromotora]' ";
}
elseif ( !empty($_POST["szuSlowa"]) ) { 
$query.=" WHERE dbo.SlowaKluczowe.ID_Slowa = '$_POST[szuSlowa]' ";
}
} else $query="";
/////////////////////// Kwerenda sql - KONIEC


?>

<table border="1" cellspacing="0" cellpading="1" width="90%" align="center">
 <?php
////////// STUDENT
// Student - Dolacz do wybranej pracy dyplomowej
if ( isset($_POST['WybierzStudent'],$_POST['Wyb_Temat'],$_POST['Wyb_ID_Pracy']) ) { 
echo "<tr><td colspan=\"2\"> Czy napewno chcesz dolaczyc do Autorow piszacych prace dyplomowa na temat, ".$_POST['Wyb_Temat']." ?	",
		 "<tr><td align=\"center\"><form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">", 
		 "<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"".$_POST['Wyb_ID_Pracy']."\">",
     "<input type=\"submit\" class=\"button\" name=\"StuWybierzTak\" value=\"TAK\">",
		 "</td><td align=\"center\"><input type=\"submit\" class=\"button\" name=\"WybierzNie\" value=\"NIE\"></form></td></tr>";
}
if (isset($_POST['StuWybierzTak'],$_POST['Wyb_ID_Pracy'],$_SESSION['JakoStudent']))	  {
	// dodaj studenta do pracy dyplomowej, jak jest wolne miejsce (1-3)  CzyMozedolaczyc(ID_Pracy) 1-Tak, 0-Nie
	$sql = $pdo->query(" SELECT dbo.CzyMozedolaczyc($_POST[Wyb_ID_Pracy])");
	$row = $sql->fetch();
	if ($row[0] == 1) { // 1-moze dolaczyc  0-juz jest 3
		$sql = $pdo->prepare("INSERT INTO dbo.Studenci (ID_Pracy,ID_Student) VALUES (:idpracy,:idstudent)");
    $sql->bindParam(':idpracy', $_POST['Wyb_ID_Pracy']);
    $sql->bindParam(':idstudent', $_SESSION['JakoStudent']);
    if ( $sql->execute() ) echo "<div align=\"center\"><h3><font color=green>Student zostal przypisany do Pracy Dyplomowej</font></h3></div> ";
        		else echo "<div align=\"center\"><h3><font color=red>BLAD: Student NIE zostal przypisany do Pracy Dyplomowej !</font></h3></div> ";  
	}
	else echo "<div align=\"center\"><h3><font color=red>BLAD: Grupa piszaca wybrany temat Pracy Dyplomowej posiada juz maksymalna liczebnosc!</font></h3></div> ";		
}

////////// PRACOWNIK NAUKOWY 
if ( isset($_POST['WybierzdoRecenzji'],$_POST['Wyb_ID_Pracy']) ) { 
echo "<tr><td colspan=\"2\"> Czy napewno chcesz dolaczyc do Recenzentow prac dyplomowej na temat, ".$_POST['Wyb_Temat']." ?	",
		 "<tr><td align=\"center\"><form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">", 
		 "<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"".$_POST['Wyb_ID_Pracy']."\">",
     "<input type=\"submit\" class=\"button\" name=\"NaukWybierzTak\" value=\"TAK\">",
		 "</td><td align=\"center\"><input type=\"submit\" class=\"button\" name=\"WybierzNie\" value=\"NIE\"></form></td></tr>";
}
if (isset($_POST['NaukWybierzTak'],$_POST['Wyb_ID_Pracy'],$_SESSION['JakoNaukowy']))	  {
	// dodaj recenzenta  pracy dyplomowej, dbo.CzyMozeBycRecenzentem (ID_Pracy, ID_Pracownik) 1-Tak, 0-Nie
	$sql = $pdo->query(" SELECT dbo.CzyMozeBycRecenzentem($_POST[Wyb_ID_Pracy],$_SESSION[JakoNaukowy]) ");
	$row = $sql->fetch();
	// echo "fetch= ".$row[0];
	if ($row[0] == 1) { // moze dolaczyc	
		$sql = $pdo->prepare("INSERT INTO dbo.Recenzenci (ID_Pracy,ID_Pracownik) VALUES(:idpracy,:idpracownik)");
    $sql->bindParam(':idpracy', $_POST['Wyb_ID_Pracy']);
    $sql->bindParam(':idpracownik', $_SESSION['JakoNaukowy']);
    if ( $sql->execute() ) echo "<div align=\"center\"><h3><font color=green>Wprowadzone dane zostaly zapisane</font></h3></div> ";
		else echo "<div align=\"center\"><h3><font color=red>BLAD: Zapis danych sie nie powiodl !</font></h3></div> ";
	}
	else echo "<div align=\"center\"><h3><font color=red>BLAD: Pracownik Naukowy pelni juz funkcje Promotora wybranej pracy, nie moze byc jej Recenzentem!</font></h3></div> ";				
}


/////////////////////// Wybory / Opcje dla Uzytkownikow  - KONIEC

/////////////////////// Wyniki wyszukiwania
if ( !empty($query) AND !isset($_POST["WybierzdoRecenzji"]) AND !isset($_POST["WybierzStudent"]) AND !isset($_POST["StuWybierzTak"]) AND !isset($_POST["DodajRecOcen"]) AND !isset($_POST["DodajRecPromotora"])) {
echo "<tr><td> </td><td align=\"center\"> Temat </td><td align=\"center\"> Etap Studiow </td><td align=\"center\"> Promotor </td><td align=\"center\"> Recenzenci </td><td align=\"center\"> Numer Indeksu </td><td align=\"center\"> Ocena Koncowa </td><td align=\"center\"> Data Obrony </td><td> </td></tr>	";
$i=1;
$result = $pdo->query($query);
while(list($ID_Pracy, $ID_Student, $Temat, $NazwaEtapStudiow, $Promotor, $Recenzenci, $NrIndeksu, $Ocenakoncowa, $Data) = $result->fetch(PDO::FETCH_NUM)) {
    echo "<tr><td>$i</td><td >";
    if ($_SESSION['user'] == "Dziekanat") {
    	echo "<a href=\"viewone.php?id=".$ID_Pracy."\"> ".$Temat." </a>";
    	}
    else echo $Temat;
    echo "</td><td align=\"center\">$NazwaEtapStudiow</td><td >$Promotor</td><td align=\"center\"><table border=\"0\" width=\"100%\">";
    		 if ( !empty($Recenzenci)) {
    		 $arrRecenzenci=explode(";; ",$Recenzenci);
    		 foreach($arrRecenzenci as $Recenzent) {
    		 	$Rec_podzial=explode(" | ",$Recenzent);
    		 	//$Rec_podzial[ ]  0 -stopien Imie nazwisko , 1-ID_Recenzent
    		 if (empty($Rec_podzial[0])) $Rec_podzial[0]=" ";
    		 echo "<tr><td>".$Rec_podzial[0]." ";
    		 $Rec_podzial[1]=intval($Rec_podzial[1]);
				 if ( isset($Rec_podzial[1]) AND isset($_SESSION["JakoRecenzent"]) ) {
    		   if ($Rec_podzial[1] == $_SESSION["JakoRecenzent"]) {
    echo   "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
    			 "<input type=\"hidden\" name=\"Temat\" value=\"".$Temat."\">",
    			 "<input type=\"hidden\" name=\"ID_Pracy\" value=\"".$ID_Pracy."\">",
    			 "<input type=\"hidden\" name=\"block\" value=\"block\">",
    		   "<input type=\"submit\" class=\"button\" name=\"DodajRecOcen\" value=\"Dodaj Recenzje, Ocene\"> </form>";
    		 	 }  
    		 	}
    echo "</td></tr>";	
    		 }
    		}    		
    echo "</table></td><td align=\"center\">$NrIndeksu</td><td align=\"center\">$Ocenakoncowa</td><td align=\"center\">$Data</td><td>"; 
    // jezeli student , wybor pracy	; jak pracownik naukowy mozliwosc recenzowania pracy (sprawdzic czy nei jest juz promotorem)
    if ($_SESSION['user'] == "Student") {
 echo "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
 			"<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"$ID_Pracy\">",
 			"<input type=\"hidden\" name=\"Wyb_Temat\" value=\"$Temat\">",
    	"<input type=\"submit\" class=\"button\" name=\"WybierzStudent\" value=\"Wybierz do pisania\">",
    	"</form>";
    }
    elseif ($_SESSION['user'] == "Naukowy") {
 echo "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
 			"<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"$ID_Pracy\">",
 			"<input type=\"hidden\" name=\"Wyb_Temat\" value=\"$Temat\">",
    	"<input type=\"submit\" class=\"button\" name=\"WybierzdoRecenzji\" value=\"Wybierz do Recenzowania\">",
    	"</form>";
    }   
    elseif ($_SESSION['user'] == "Promotor") {
 echo "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
 			"<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"$ID_Pracy\">",
 			"<input type=\"hidden\" name=\"Wyb_Temat\" value=\"$Temat\">",
 			"<input type=\"hidden\" name=\"block\" value=\"block\">",
    	"<input type=\"submit\" class=\"button\" name=\"DodajRecPromotora\" value=\"Dodaj Recenzje,Ocene Promotora\">",
    	"</form>";
    }     
    echo" </td></tr>";
    $i++;
}
}
/////////////////////// Wyniki wyszukiwania - KONIEC
?>
</table>

<?php
site_footer(); //template bottom
?>