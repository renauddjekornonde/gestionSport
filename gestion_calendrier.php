<?php
require_once("commande.php");

//---Verification Admin--//
if(!professeurEstConnecter())
{
    header("location:connexion.php");
    exit();
}

//--Modifacation Note--//
if(!empty($_POST))
{
    $sport_bdd= "";
    if(isset($_GET['action']) && $_GET['action']== 'modification')
    {
        
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("UPDATE Sport SET Nom_Sport= '$_POST[Nom_Sport]', Heure_Debut= '$_POST[Heure_Debut]', Heure_Fin= '$_POST[Heure_Fin]', Jour= '$_POST[Jour]' WHERE Nom_Sport= '$_GET[Nom_Sport]'");
    
        $contenu .= '<div class="table-success">Modification reussite</div>';
        $_GET['action']= 'affichage';
    }

    //--Enregistre absence--//
    elseif(isset($_GET['action']) && $_GET['action']== 'ajout')
    {
        
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("INSERT INTO Sport (Nom_Sport, Heure_Debut, Heure_Fin, Jour) VALUES ('$_POST[Nom_Sport]', '$_POST[Heure_Debut]', '$_POST[Heure_Fin]', '$_POST[Jour]')");
    
        $contenu .= '<div class="table-success">Calendrier Remplit</div>';
        $_GET['action']= 'affichage';
    }
   
}

//----Lien pour Editer Notes--//
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=affichage">Voir Calendrier→</a></div><br><br>';
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=ajout">Remplir Calendrier→</a></div><br><br><hr><br>';

//----Affichage de la liste des Notes----//
if(isset($_GET['action']) && $_GET['action']==  "affichage")
{
    if(professeurEstConnecter())
    {
       $nom= $_SESSION['professeur']['Nom_Sport'];
    }
    
    $resultat= executeRequete("SELECT DISTINCT Sport.Nom_Sport, Sport.Heure_Debut, Sport.Heure_Fin, Sport.Jour FROM `Sport`,`Etudiant`,`Professeur` WHERE Etudiant.Nom_Sport= '$nom' AND Sport.Nom_Sport= '$nom' AND Professeur.Nom_Sport= '$nom' ORDER BY Jour DESC ");
   

    $contenu .= '<h2> CALENDRIER </h2>';
    $contenu .= 'Nombre de seances:' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table table-dark table-bordered float-right" ><thead>';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">Sport</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
        $contenu .= '<th scope="col">Jour</th>';
    //}
    $contenu .= '<th scope="col"> Modifier </th>';
    $contenu .= '</tr>';
    $contenu .='</thead>';
    while($ligne = $resultat->fetch_assoc())
    {
        $contenu .= '<tbody>';
        $contenu .= '<tr>';
       foreach($ligne as $indice => $information)
        {
            if($indice == "Photo")
            {
                $contenu .= '<td><img src="' . $information . '"="70" height="70"></td>';
            }
           else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&Nom_Sport=' . $ligne['Nom_Sport'] .'"><img src="img/editer.png" width="20" height="20"></a></td>'; 
        $contenu.= '</tr>';
        $contenu .= '</tbody>';
    }
        $contenu .= '</table></div><br><br>';

}

//---html---//
require_once("haider.php");
if(professeurEstConnecter())
{
   $nom= $_SESSION['professeur']['Nom_Sport'];
}
$listSport= executeRequete("SELECT DISTINCT Sport.Nom_Sport FROM `Professeur`, `Sport` WHERE Sport.Nom_Sport= '$nom' ");

echo $contenu;
if(isset($_GET['action']) && ($_GET['action']== 'ajout' || $_GET['action']== 'modification'))
{
    if(isset($_GET['Nom_Sport']))
    {
        $resultat= executeRequete("SELECT * FROM Sport WHERE Nom_Sport= '$_GET[Nom_Sport]'");
        $calendrier_actuel= $resultat->fetch_assoc();
    }
    echo '

    <h1> Formulaire du calendrier </h1>
    <form method="POST" enctype="multipart/form-data" action="" class="w-70">
    <div class="input-group">
    <select name="Nom_Sport" class="form-select">';
    while ($row = mysqli_fetch_array($listSport))
    {
        echo '
    <option value="';echo $row['Nom_Sport']; echo ' " ';if(isset($note_actuel) && $note_actuel['Nom_Sport']== $row['Nom_Sport']) echo 'selected ';echo '>'; echo $row['Nom_Sport']; echo '</option>';
    }
    echo '</select></div><br><br>

        <div class="input-group">
        <input type="date" id="Jour" class="form-control" placeholder="Date" name="Jour" value="'; if(isset($calendrier_actuel['Jour'])) echo $calendrier_actuel['Jour']; echo'"></div> <br><br>

        <div class="input-group">
        <input type="time" id="Heure_Debut" class="form-control" placeholder="Debut" name="Heure_Debut" value="'; if(isset($calendrier_actuel['Heure_Debut'])) echo $calendrier_actuel['Heure_Debut']; echo'"></div> <br><br>

        <div class="input-group">
        <input type="time" id="Heure_Fin" class="form-control" placeholder="Fin" name="Heure_Fin" value="'; if(isset($calendrier_actuel['Heure_Fin'])) echo $calendrier_actuel['Heure_Fin']; echo'"> </div><br><br>';

            echo '
            <div class="input-group">
        <input type="submit" class="btn btn-primary" value="'; echo ucfirst($_GET['action']) . ' Du Calendrier "></div>
    </form>';
}
 require_once("footer.php"); ?>