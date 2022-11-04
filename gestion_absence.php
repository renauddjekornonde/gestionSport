<?php
require_once("commande.php");

//---Verification Admin--//
if(!professeurEstConnecter())
{
    header("location:connexion.php");
    exit();
}

//--Traitement php pour supprimer Notes--//
if(isset($_GET['action']) && $_GET['action']== "suppression")
{
   
    $resultat= executeRequete("SELECT * FROM Programme WHERE ID_Programme= $_GET[ID_Programme]");
    $programme_a_supprimer= $resultat->fetch_assoc();
    $chemin_programme_a_supprimer= $_SERVER['DOCUMENT_ROOT'] . $programme_a_supprimer['Photo'];
    if(!empty($programme_a_supprimer['Photo']) && file_exists($programme_note_a_supprimer)) unlink($programme_note_a_supprimer);
    $contenu.= '<div class="validation">Supprission Absence du Programme ' . $_GET['ID_Programme'] . '</div>';
    executeRequete("DELETE FROM Programme WHERE ID_Programme= $_GET[ID_Programme]");
    $_GET['action'] = 'affichage';
}

//--Modifacation Note--//
if(!empty($_POST))
{
    $programme_bdd= "";
    if(isset($_GET['action']) && $_GET['action']== 'modification')
    {
        
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("UPDATE Programme SET Nom_Etu= '$_POST[Nom_Etu]', ID_Etu= '$_POST[ID_Etu]', ID_Prof= '$_POST[ID_Prof]', Motif= '$_POST[Motif]', Heure_Debut= '$_POST[Heure_Debut]', Heure_Fin= '$_POST[Heure_Fin]', Date_Programme= '$_POST[Date_Programme]' WHERE ID_Programme= $_GET[ID_Programme]");
    
        $contenu .= '<div class="validation">Modification reussite</div>';
        $_GET['action']= 'affichage';
    }

    //--Enregistre absence--//
    elseif(isset($_GET['action']) && $_GET['action']== 'ajout')
    {
        
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("INSERT INTO Programme (Nom_Etu, ID_Etu, ID_Prof, Motif, Heure_Debut, Heure_Fin, Date_Programme) VALUES ('$_POST[Nom_Etu]','$_POST[ID_Etu]','$_POST[ID_Prof]','$_POST[Motif]', '$_POST[Heure_Debut]', '$_POST[Heure_Fin]', '$_POST[Date_Programme]')");
    
        $contenu .= '<div class="validation">Programme Remplit</div>';
        $_GET['action']= 'affichage';
    }
   
}

//----Lien pour Editer Notes--//
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=affichage">Voir Absence→</a></div><br><br>';
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=ajout">Remplir le Programme d\'Absence→</a></div><br><br><hr><br>';

//----Affichage de la liste des Notes----//
if(isset($_GET['action']) && $_GET['action']==  "affichage")
{
    if(professeurEstConnecter())
    {
       $nom= $_SESSION['professeur']['Nom_Sport'];
    }
    
    $resultat= executeRequete("SELECT DISTINCT Programme.ID_Programme, Programme.Nom_Etu, Programme.ID_Etu, Programme.Motif, Programme.Heure_Debut, Programme.Heure_Fin, Programme.Date_Programme FROM `Programme`,`Etudiant`,`Professeur` WHERE Etudiant.Nom_Sport= '$nom' AND Etudiant.ID_Etu= Programme.ID_Etu ORDER BY Date_Programme DESC ");
   

    $contenu .= '<h2> Liste des Absents </h2>';
    $contenu .= 'Nombre d\'Absents:' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table table-dark table-bordered float-right" ><thead>';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">N° Programme</th>';
        $contenu .= '<th scope="col">Nom Etudiant</th>';
        $contenu .= '<th scope="col">N° Etudiant</th>';
        $contenu .= '<th scope="col">Motif</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
        $contenu .= '<th scope="col">Date</th>';
    //}
    $contenu .= '<th scope="col"> Modifier </th>';
    $contenu .= '<th scope="col"> Supprimer </th>';
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
        $contenu .= '<td><a href="?action=modification&ID_Programme=' . $ligne['ID_Programme'] .'"><img src="img/editer.png" width="20" height="20"></a></td>'; 
        $contenu .= '<td><a href="?action=suppression&ID_Programme=' . $ligne['ID_Programme'] .'" OnClick="return(confirm(\'Confirmer ?\'));"><img src="img/delete.jpg" width="20" height="20"></a></td>'; 
        $contenu .= '</tr>';
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
$listEtudiant= executeRequete("SELECT DISTINCT Etudiant.Nom_Etu FROM `Etudiant`,`Professeur`, `Notes` WHERE Etudiant.Nom_Sport= '$nom' ");

$listId= executeRequete("SELECT DISTINCT Etudiant.ID_Etu FROM `Etudiant`,`Professeur` WHERE Etudiant.Nom_Sport= '$nom' ");

echo $contenu;
if(isset($_GET['action']) && ($_GET['action']== 'ajout' || $_GET['action']== 'modification'))
{
    if(isset($_GET['ID_Programme']))
    {
        $resultat= executeRequete("SELECT * FROM Programme WHERE ID_Programme= $_GET[ID_Programme]");
        $programme_actuel= $resultat->fetch_assoc();
    }
    echo '

    <h1> Formulaire d\'Absence </h1>
    <form method="POST" enctype="multipart/form-data" action="" class="w-70">
    <div class="input-group">
        <input type="hidden" id="ID_Etu" name="ID_Etu" value="'; if(isset($programme_actuel['ID_Etu'])) echo $programme_actuel['ID_Etu']; echo'"></div>

        <div class="input-group">
        <select name="Nom_Etu" class="form-select">';
        while ($row = mysqli_fetch_array($listEtudiant))
        {
            echo '
        <option value="';echo $row['Nom_Etu']; echo ' " ';if(isset($programme_actuel) && $programme_actuel['Nom_Etu']== $row['Nom_Etu']) echo 'selected ';echo '>'; echo $row['Nom_Etu']; echo '</option>';
        }
        echo '</select></div><br><br>

        <div class="input-group">
        <select name="ID_Etu" class="form-select">';
        while ($row = mysqli_fetch_array($listId))
        {
            echo '
        <option value="';echo $row['ID_Etu']; echo ' " ';if(isset($programme_actuel) && $programme_actuel['ID_Etu']== $row['ID_Etu']) echo 'selected ';echo '>'; echo $row['ID_Etu']; echo '</option>';
        }
        echo '</select></div><br><br>

        <div class="input-group">
        <input type="number" class="form-control" placeholder="Votre Identifiant" id="ID_Prof" name="ID_Prof" value="'; if(isset($programme_actuel['ID_Prof'])) echo $programme_actuel['ID_Prof']; echo'"></div> <br><br>

        <div class="input-group">
        <input type="text" id="Motif" class="form-control" placeholder="Motif" name="Motif" value="'; if(isset($programme_actuel['Motif'])) echo $programme_actuel['Motif']; echo'"> </div><br><br>

        <div class="input-group">
        <input type="time" class="form-control" placeholder="Debut" id="Heure_Debut" name="Heure_Debut" value="'; if(isset($programme_actuel['Heure_Debut'])) echo $programme_actuel['Heure_Debut']; echo'"></div> <br><br>

        <div class="input-group">
        <input type="time" class="form-control" placeholder="Fin" id="Heure_Fin" name="Heure_Fin" value="'; if(isset($programme_actuel['Heure_Fin'])) echo $programme_actuel['Heure_Fin']; echo'"> </div><br><br>

        <div class="input-group">
        <input type="date" class="form-control" placeholder="Date" id="Date_Programme" name="Date_Programme" value="'; if(isset($programme_actuel['Date_Programme'])) echo $programme_actuel['Date_Programme']; echo'"></div> <br><br>';

            echo '
            <div class="input-group">
        <input type="submit" class="btn btn-primary"  value="'; echo ucfirst($_GET['action']) . ' De l\'Absent"></div>
    </form>';
}
 require_once("footer.php"); ?>