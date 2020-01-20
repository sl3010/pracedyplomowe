<?php
require_once('template.php');
//site_header(); // template top
if (isset($_POST['block'])) site_header(FALSE);
else site_header(TRUE);
echo "</table>";
?>
	<div style="margin-left: 70px"><h1> Przegladanie Katalogu Prac Dyplomowych </h1></div>
<table border="1"   " cellspacing="0" cellpading="1" width="90%" align="center">
    <tr><td> </td><td align="center"> Temat </td><td align="center"> Etap Studiow </td><td align="center"> Promotor </td><td align="center"> Recenzenci </td><td align="center"> Numer Indeksu </td><td align="center"> Data Obrony </td><td align="center"> Ocena Koncowa </td></tr>
<?php
$query = "SELECT 
dbo.PracaDyplomowa.ID_Pracy, dbo.PracaDyplomowa.Temat, dbo.PracaDyplomowa.NazwaEtapStudiow, (dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko) AS Promotor, 
Recenzenci = STUFF((SELECT ', '+dbo.Pracownik.StopienNaukowy+' '+dbo.Pracownik.Imie+' '+dbo.Pracownik.Nazwisko FROM dbo.Pracownik INNER JOIN dbo.Recenzenci  ON dbo.Pracownik.ID_Pracownik = dbo.Recenzenci.ID_Pracownik AND dbo.Recenzenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy FOR XML PATH ('')),1,2,''), 
dbo.Student.NrIndeksu, dbo.Studenci.Ocenakoncowa, CONVERT(DATE, dbo.Obrona.Data) AS  Data 
FROM dbo.PracaDyplomowa
RIGHT JOIN dbo.Studenci ON dbo.Studenci.ID_Pracy = dbo.PracaDyplomowa.ID_Pracy 
LEFT JOIN dbo.Obrona  ON dbo.Obrona.ID_Obrony = dbo.Studenci.ID_Obrony
LEFT JOIN dbo.Pracownik ON dbo.Pracownik.ID_Pracownik = dbo.PracaDyplomowa.ID_Promotor
LEFT JOIN dbo.Student ON dbo.Student.ID_Student = dbo.Studenci.ID_Student
ORDER BY
   CASE
        WHEN dbo.Obrona.Data IS NULL THEN 0
        ELSE 1
   END, dbo.Obrona.Data DESC";
$i=1;
$result = $pdo->query($query);
while(list($ID_Pracy, $Temat, $NazwaEtapStudiow, $Promotor, $Recenzenci, $NrIndeksu, $Data, $Ocenakoncowa) = $result->fetch(PDO::FETCH_NUM)) {
    echo "<tr><td>$i</td><td >$Temat</td><td align=\"center\">$NazwaEtapStudiow</td><td >$Promotor</td><td align=\"center\">$Recenzenci</td><td align=\"center\">$NrIndeksu</td><td align=\"center\">$Data</td><td align=\"center\">$Ocenakoncowa</td></tr>";
    $i++;
}

?>
</table>

<?php
site_footer(); //template bottom
?>