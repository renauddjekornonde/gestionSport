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
   
    $resultat= executeRequete("SELECT * FROM Notes WHERE ID_Note= $_GET[ID_Note]");
    $note_a_supprimer= $resultat->fetch_assoc();
    $chemin_note_a_supprimer= $_SERVER['DOCUMENT_ROOT'] . $note_a_supprimer['Note'];
    if(!empty($note_a_supprimer['Note']) && file_exists($chemin_note_a_supprimer)) unlink($chemin_note_a_supprimer);
    $contenu.= '<div class="validation">Supprission du note de l\'Etudiant ' . $_GET['ID_Note'] . '</div>';
    executeRequete("DELETE FROM Notes WHERE ID_Note= $_GET[ID_Note]");
    $_GET['action'] = 'affichage';
}

//--Modifier Note--//
if(!empty($_POST))
{
    $note_bdd= '';
    if(isset($_GET['action']) && $_GET['action']== 'modification')
    {
        $note_bdd= $_POST['note_actuelle'];
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("UPDATE Notes SET Note= '$_POST[Note]', ID_Etu= '$_POST[ID_Etu]', ID_Prof= '$_POST[ID_Prof]', Type_Evaluation= '$_POST[Type_Evaluation]', Nom_Etu= '$_POST[Nom_Etu]' WHERE ID_Note= $_GET[ID_Note]");
    
        $contenu .= '<div class="validation">Note Modifier</div>';
        $_GET['action']= 'affichage';
    }

    //--Enregistrer Notes--//
    elseif(isset($_GET['action']) && $_GET['action']== 'ajout')
    {
        //$note_bdd= $_POST['note_actuelle'];
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("INSERT INTO Notes (Note, ID_Etu, ID_Prof, Type_Evaluation, Nom_Etu) VALUES ('$_POST[Note]','$_POST[ID_Etu]','$_POST[ID_Prof]','$_POST[Type_Evaluation]', '$_POST[Nom_Etu]')");
    
        $contenu .= '<div class="validation">Note Ajouter</div>';
        $_GET['action']= 'affichage';
    }
   
   
}

//----Lien pour Editer Notes--//
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=affichage">Voir Note→</a></div><br><br>';
$contenu.= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=ajout">Ajouter Note→</a></div><br><br><hr><br>';

//----Affichage de la liste des Notes----//
if(professeurEstConnecter() && isset($_GET['action']) && $_GET['action']==  "affichage")
{
    $nom= $_SESSION['professeur']['Nom_Sport'];
    $resultat= executeRequete("SELECT DISTINCT Notes.ID_Note, Notes.Nom_Etu, Notes.Note, Notes.Type_Evaluation FROM `Notes`,`Etudiant`, `Professeur` WHERE Etudiant.Nom_Sport= '$nom' AND Etudiant.ID_Etu= Notes.ID_Etu");
  

    $contenu .= '<h2> Liste des Notes </h2>';
    $contenu .= 'Nombre d\'Evaluations :' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table table-dark table-bordered float-right" ><thead>';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">N° Etudiant</th>';
        $contenu .= '<th scope="col">Nom Etudiant</th>';
        $contenu .= '<th scope="col">Note</th>';
        $contenu .= '<th scope="col">Evaluation</th>';
       
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
                $contenu .= '<td><img src="' . $information . '"="90" height="90"></td>';
            }
           else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&ID_Note=' . $ligne['ID_Note'] .'"><img src="img/editer.png" width="20" height="20"></a></td>'; 
        $contenu .= '<td><a href="?action=suppression&ID_Note=' . $ligne['ID_Note'] .'" OnClick="return(confirm(\'Confirmer ?\'));"><img src="img/delete.jpg" width="20" height="20"></a></td>'; 
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
    $listEtudiant= executeRequete("SELECT DISTINCT Etudiant.Nom_Etu FROM `Etudiant`,`Professeur`, `Sport` WHERE Etudiant.Nom_Sport= '$nom' AND Sport.Nom_Sport= '$nom' ");

    $listId= executeRequete("SELECT DISTINCT Etudiant.ID_Etu FROM `Etudiant`,`Professeur`,`Sport` WHERE Etudiant.Nom_Sport= '$nom' AND Sport.Nom_Sport= '$nom' ");

echo $contenu;
if(isset($_GET['action']) && ($_GET['action']== 'ajout' || $_GET['action']== 'modification'))
{
    if(isset($_GET['ID_Note']))
    {
        $resultat= executeRequete("SELECT Note, ID_Etu, ID_prof, Type_Evaluation, Nom_Etu FROM Notes WHERE  ID_Note= $_GET[ID_Note]");
        $note_actuel= $resultat->fetch_assoc();
    }
    echo '

    <h1> Formulaire de Note </h1><br>
    <form method="POST" enctype="multipart/form-data" action="" class="login-email w-70">

        <div class="input-group">
        <input type="number" id="Note" class="form-control" placeholder="Note" name="Note" value="'; if(isset($note_actuel['Note'])) echo $note_actuel['Note']; echo'"></div><br><br>

        <div class="input-group">
        <select name="ID_Etu" class="form-select">';
        while ($row = mysqli_fetch_array($listId))
        {
            echo '
        <option value="';echo $row['ID_Etu']; echo ' " ';if(isset($note_actuel) && $note_actuel['ID_Etu']== $row['ID_Etu']) echo 'selected ';echo '>'; echo $row['ID_Etu']; echo '</option>';
        }
        echo '</select></div><br><br>

        <div class="input-group">
        <input type="number" id="ID_Prof" class="form-control" placeholder="Votre identifiant" name="ID_Prof" value="'; if(isset($note_actuel['ID_Prof'])) echo $note_actuel['ID_Prof']; echo'"> </div><br><br>

        <div class="input-group">
        <input type="text" id="Type_Evaluation" class="form-control" placeholder="Evaluation DE" name="Type_Evaluation" value="'; if(isset($note_actuel['Type_Evaluation'])) echo $note_actuel['Type_Evaluation']; echo'"> </div><br><br>

        <div class="input-group">
        <select name="Nom_Etu" class="form-select">';
        while ($row = mysqli_fetch_array($listEtudiant))
        {
            echo '
        <option value="';echo $row['Nom_Etu']; echo ' " ';if(isset($note_actuel) && $note_actuel['Nom_Etu']== $row['Nom_Etu']) echo 'selected ';echo '>'; echo $row['Nom_Etu']; echo '</option>';
        }
        echo '</select></div><br><br>';
           
      
      if(isset($note_actuel))
        {
            echo '<input type="hidden" name="note_actuelle" value="' . $note_actuel['Note'] . '"><br><br><br>';
        }
            echo '

            <div class="input-group">
        <input type="submit" class="btn btn-primary" value="'; echo ucfirst($_GET['action']) . ' de la Note"></div>
    </form>';
}
 require_once("footer.php"); ?>