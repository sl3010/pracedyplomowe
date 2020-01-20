<?php
// DODAC 
// Dodanie recenzji / oceny
// Witryna zapamiêtuje wpisan¹ recenzjê / ocenê pracownika naukowego pe³ni¹cych funkcjê Recenzenta, Promotora
    		 // IF Recenzent daj przycisk do dodania recenzji / oceny
    		 // IF Promotor  daj przycisk do dodania recenzji / oceny
require_once('template.php');
//site_header(); // template top
if (isset($_POST['block'])) site_header(FALSE);
else site_header(TRUE);
//////////////// STUDENT
if ( $_SESSION["user"] == "Student" ) {
echo "<tr><td><h3>do symulacji, wybierz Studenta:</h3> <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoStudent\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT dbo.Student.Imie+' '+dbo.Student.Nazwisko, dbo.Student.ID_Student FROM dbo.Student;");
while(list($Student, $ID_Student) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Student."\">".$Student."</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzStudent\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoStudent']) ) {
	$_SESSION['JakoStudent'] = $_POST['JakoStudent'];
}

//////////////// Recenzent, Promotor
if ( ($_SESSION['user'] == "Promotor") AND !isset($_POST['block'])) {
echo "<tr><td>do symulacji, wybierz Promotora <form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\"><select name=\"JakoPromotor\"><option value=\"\">Wybierz:</option>";
$sql = $pdo->query("SELECT DISTINCT dbo.Pracownik.StopienNaukowy+' '+Pracownik.Imie+' '+dbo.Pracownik.Nazwisko, dbo.Pracownik.ID_Pracownik FROM dbo.Pracownik JOIN dbo.PracaDyplomowa ON dbo.PracaDyplomowa.ID_Promotor = dbo.Pracownik.ID_Pracownik;");
while(list($Pracownik, $ID_Pracownik) = $sql->fetch(PDO::FETCH_NUM)) {
 echo "<option value=\"".$ID_Pracownik."\">".$Pracownik."</option>\n";
}
echo "</select><input type=\"submit\" class=\"button\" name=\"WybierzPromotora\" value=\"Wybierz\"> </form> </td></tr>";
}
if ( !empty($_POST['JakoPromotor']) ) {
	$_SESSION['JakoPromotor'] = $_POST['JakoPromotor'];
}

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

echo "</table>";
if (!isset($_POST['block'])) {
?>
<div style="margin-left: 70px"><h1> Podglad Szczegolowy prac dyplomowych </h1></div>;
 <table border="0" cellspacing="5" cellpading="0" width="90%" align="center">
<?php 
}
if (isset($_POST['WybierzStudent']))	  {
$query = "SELECT DISTINCT
dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, 
(dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, 
dbo.PracaDyplomowa.Prom_Recenzja, dbo.PracaDyplomowa.Prom_Ocena,
STUFF((SELECT ';; '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko+' | Recenzja: '+(ISNULL(dbo.Recenzenci.Recenzja, ' '))+' | Ocena:  '+(ISNULL(CONVERT(varchar,dbo.Recenzenci.Ocena), ' ')) FROM dbo.Pracownik RIGHT JOIN dbo.Recenzenci  ON dbo.Recenzenci.ID_Pracownik = dbo.Pracownik.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,'') AS [Recenzenci, Opinie, Oceny ], 
dbo.Student.NrIndeksu, dbo.Student.Imie+' '+dbo.Student.Nazwisko, CONVERT(DATE, dbo.Obrona.Data) AS  Data , dbo.Studenci.Ocenakoncowa
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Recenzenci ON  dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.PracaDyplomowa.ID_Promotor = dbo.Pracownik.ID_Pracownik
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
WHERE dbo.Student.ID_Student='$_SESSION[JakoStudent]';";
}
if (isset($_POST['WybierzPromotora']))	  {
$query = "SELECT DISTINCT
dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, 
(dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, 
dbo.PracaDyplomowa.Prom_Recenzja, dbo.PracaDyplomowa.Prom_Ocena,
STUFF((SELECT ';; '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko+' | Recenzja: '+(ISNULL(dbo.Recenzenci.Recenzja, ' '))+' | Ocena:  '+(ISNULL(CONVERT(varchar,dbo.Recenzenci.Ocena), ' ')) FROM dbo.Pracownik RIGHT JOIN dbo.Recenzenci  ON dbo.Recenzenci.ID_Pracownik = dbo.Pracownik.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,'') AS [Recenzenci, Opinie, Oceny ], 
dbo.Student.NrIndeksu, dbo.Student.Imie+' '+dbo.Student.Nazwisko, CONVERT(DATE, dbo.Obrona.Data) AS  Data , dbo.Studenci.Ocenakoncowa
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Recenzenci ON  dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.PracaDyplomowa.ID_Promotor = dbo.Pracownik.ID_Pracownik
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
WHERE dbo.PracaDyplomowa.ID_Promotor='$_SESSION[JakoPromotor]'";
}
if (isset($_POST['WybierzRecenzenta']))	  {
$query = "SELECT DISTINCT
dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, 
(dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, 
dbo.PracaDyplomowa.Prom_Recenzja, dbo.PracaDyplomowa.Prom_Ocena,
STUFF((SELECT ';; '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko+' | Recenzja: '+(ISNULL(dbo.Recenzenci.Recenzja, ' '))+' | Ocena:  '+(ISNULL(CONVERT(varchar,dbo.Recenzenci.Ocena), ' '))+' | '+CONVERT(varchar,dbo.Pracownik.ID_Pracownik) FROM dbo.Pracownik RIGHT JOIN dbo.Recenzenci  ON dbo.Recenzenci.ID_Pracownik = dbo.Pracownik.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,'') AS [Recenzenci, Opinie, Oceny ],  
dbo.Student.NrIndeksu, dbo.Student.Imie+' '+dbo.Student.Nazwisko, CONVERT(DATE, dbo.Obrona.Data) AS  Data , dbo.Studenci.Ocenakoncowa
FROM dbo.PracaDyplomowa
LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Recenzenci ON  dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.PracaDyplomowa.ID_Promotor = dbo.Pracownik.ID_Pracownik
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
WHERE dbo.Recenzenci.ID_Pracownik='$_SESSION[JakoRecenzent]'";
}

if (isset($_GET['id'])) {
	if ( ctype_digit($_GET['id']) )
		$id_pracy=(int)$_GET['id'];
	else die();
	$query = "SELECT DISTINCT dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, (dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, dbo.PracaDyplomowa.Prom_Recenzja, dbo.PracaDyplomowa.Prom_Ocena,STUFF((SELECT ';; '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko+' | Recenzja: '+(ISNULL(dbo.Recenzenci.Recenzja, ' '))+' | Ocena:  '+(ISNULL(CONVERT(varchar,dbo.Recenzenci.Ocena), ' ')) FROM dbo.Pracownik RIGHT JOIN dbo.Recenzenci  ON dbo.Recenzenci.ID_Pracownik = dbo.Pracownik.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,'') AS Recenzenci, dbo.Student.NrIndeksu, (dbo.Student.Imie+' '+dbo.Student.Nazwisko) AS Student, CONVERT(DATE, dbo.Obrona.Data) AS  Data , dbo.Studenci.Ocenakoncowa FROM dbo.PracaDyplomowa LEFT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy LEFT JOIN dbo.Recenzenci ON  dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony LEFT JOIN dbo.Pracownik ON dbo.PracaDyplomowa.ID_Promotor = dbo.Pracownik.ID_Pracownik LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student WHERE dbo.PracaDyplomowa.ID_Pracy=$id_pracy";
	$result = $pdo->query($query);
while(list($ID_Pracy, $Temat, $NazwaEtapStudiow, $Promotor, $Prom_Rec, $Prom_Ocen, $Recenzenci, $NrIndeksu, $Student, $Data, $Ocenakoncowa) = $result->fetch(PDO::FETCH_NUM)) {
	$arrRecenzenci=explode(";; ",$Recenzenci);
    echo "<tr><td> Temat Pracy Dyplomowej </td><td >$Temat</td></tr>",
    		 "<tr><td> Etap Stidiow</td><td >$NazwaEtapStudiow</td></tr>",
    		 "<tr><td> Numer Indeksu Autora</td><td >$NrIndeksu</td></tr>",
    		 "<tr><td> Imie i Nazwisko Autora</td><td >$Student</td></tr>",    		 
    		 "<tr><td> Promotor Pracy</td><td >$Promotor</td></td></tr>",
    		 "<tr><td> Recenzja Promotora</td><td >$Prom_Rec";
   echo "</td></td></tr>",
    		 "<tr><td> Ocena Promotora</td><td >$Prom_Ocen</td></td></tr>",
    		 "<tr><td> Recenzenci</td><td><table border=\"0\" width=\"100%\">";
    		 
    		 if ( !empty($Recenzenci)) {
    		 foreach($arrRecenzenci as $Recenzent) {
    		 	$Rec_podzial=explode(" | ",$Recenzent);
    		 	//$Rec_podzial[ ]  0 -stopien Imie nazwisko , 1-Recenzja, 2-Ocena, 3-ID_Recenzent (ID Tylko przy Recenzencie)
    		 echo "<tr><td>".$Rec_podzial[0]."</td><td>".$Rec_podzial[1]."</td><td>".$Rec_podzial[2]." ";
    		 $Rec_podzial[1]=str_replace("Recenzja:  ", "", $Rec_podzial[1]);
    		 $Rec_podzial[2]=str_replace("Ocena:  ", "", $Rec_podzial[2]);
    echo "</td></tr>";
    		 }
    		}    		
    echo "</table></td></tr>",
    		 "<tr><td> Data Obrony</td><td >$Data</td></tr>",
    		 "<tr><td> Ocena koncowa</td><td >$Ocenakoncowa</td></tr>",
    		 "<tr><td colspan=\"2\"> <hr> </td></tr>";
}
}
if ( isset($_POST['WybierzStudent']) OR isset($_POST['WybierzPromotora']) OR isset($_POST['WybierzRecenzenta'])  ) {
$result = $pdo->query($query);
while(list($ID_Pracy, $Temat, $NazwaEtapStudiow, $Promotor, $Prom_Rec, $Prom_Ocen, $Recenzenci, $NrIndeksu, $Student, $Data, $Ocenakoncowa) = $result->fetch(PDO::FETCH_NUM)) {
	$arrRecenzenci=explode(";; ",$Recenzenci);
    echo "<tr><td> Temat Pracy Dyplomowej </td><td >$Temat</td></tr>",
    		 "<tr><td> Etap Stidiow</td><td >$NazwaEtapStudiow</td></tr>",
    		 "<tr><td> Numer Indeksu Autora</td><td >$NrIndeksu</td></tr>",
    		 "<tr><td> Imie i Nazwisko Autora</td><td >$Student</td></tr>",    		 
    		 "<tr><td> Promotor Pracy</td><td >$Promotor</td></td></tr>",
    		 "<tr><td> Recenzja Promotora</td><td >$Prom_Rec";
	  		if (isset($_SESSION["JakoPromotor"])) {    		     		 
 		echo "<form action=\"".$_SERVER['PHP_SELF']."\"  method=\"post\">",
 				 "<input type=\"hidden\" name=\"Wyb_ID_Pracy\" value=\"$ID_Pracy\">",
 			   "<input type=\"hidden\" name=\"Wyb_Temat\" value=\"$Temat\">",
 			   "<input type=\"hidden\" name=\"block\" value=\"block\">",
    		 "<input type=\"submit\" class=\"button\" name=\"DodajRecPromotora\" value=\"Zmiana Recenzji, Oceny Promotora\"> </form>";
    		 }
   echo "</td></td></tr>",
    		 "<tr><td> Ocena Promotora</td><td >$Prom_Ocen</td></td></tr>",
    		 "<tr><td> Recenzenci</td><td><table border=\"0\" width=\"100%\">";
    		 
    		 if ( !empty($Recenzenci)) {
    		 foreach($arrRecenzenci as $Recenzent) {
    		 	$Rec_podzial=explode(" | ",$Recenzent);
    		 	//$Rec_podzial[ ]  0 -stopien Imie nazwisko , 1-Recenzja, 2-Ocena, 3-ID_Recenzent (ID Tylko przy Recenzencie)
    		 echo "<tr><td>".$Rec_podzial[0]."</td><td>".$Rec_podzial[1]."</td><td>".$Rec_podzial[2]." ";
    		 $Rec_podzial[1]=str_replace("Recenzja:  ", "", $Rec_podzial[1]);
    		 $Rec_podzial[2]=str_replace("Ocena:  ", "", $Rec_podzial[2]);

				 if ( isset($Rec_podzial[3]) AND isset($_SESSION["JakoRecenzent"]) ) {
				 	 $Rec_podzial[3]=intval($Rec_podzial[3]);       		   	
    		   if ( (empty($Rec_podzial[1]) OR empty($Rec_podzial[2])) AND ($Rec_podzial[3] == $_SESSION["JakoRecenzent"]) ) {
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
    echo "</table></td></tr>",
    		 "<tr><td> Data Obrony</td><td >$Data</td></tr>",
    		 "<tr><td> Ocena koncowa</td><td >$Ocenakoncowa</td></tr>",
    		 "<tr><td colspan=\"2\"> <hr> </td></tr>";

}
}

?>
</table>

<?php
site_footer(); //template bottom
?>