<?php
require_once("commande.php");

//---Verification Admin--//
if(!internauteEstConnecterEtEstAdmin())
{
    header("location:connexion.php");
    exit();
}

//--Traitement php pour supprimer Professeur--//
if(isset($_GET['action']) && $_GET['action']== "suppression")
{
    $resultat= executeRequete("SELECT * FROM Professeur WHERE ID_Prof= $_GET[ID_Prof]");
    $professeur_a_supprimer= $resultat->fetch_assoc();
    $chemin_photo_a_supprimer= $_SERVER['DOCUMENT_ROOT'] . $professeur_a_supprimer['Photo'];
    if(!empty($professeur_a_supprimer['Photo']) && file_exists($chemin_photo_a_supprimer)) unlink($chemin_photo_a_supprimer);
    $contenu .= '<div class="validation">Supprission Du Compte Du Professeur' . $_GET['ID_Prof'] . '</div>';
    executeRequete("DELETE FROM Professeur WHERE ID_Prof=$_GET[ID_Prof]");
    $_GET['action'] = 'affichage';
}

//--Modification Professeur--//
if(!empty($_POST))
{
    $photo_bdd= "";
    if(isset($_GET['action']) && $_GET['action']== 'modification')
    {
        $photo_bdd= $_POST['photo_actuelle'];

        if(!empty($_FILES['Photo']['name']))
        {
            $nom_photo= $_POST['Nom_Prof'] . '_' . $_FILES['Photo']['name'];
            $photo_bdd= RACINE_SITE . "Photo/$nom_photo";
            $photo_dossier= $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . "Photo/$nom_photo";
            copy($_FILES['Photo']['tmp_name'], $photo_dossier);
        }
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("UPDATE Professeur SET  Mail= '$_POST[Mail]', Password ='$_POST[Password]', Photo= '$photo_bdd', Nom_Sport= '$_POST[Nom_Sport]', Nom_Prof= '$_POST[Nom_Prof]' WHERE ID_Prof= $_GET[ID_Prof]");
    
        $contenu .= '<div class="validation">Modification réussite</div>';
        $_GET['action']= 'affichage';
    }
    //--ENregistrer Professeur--//
    elseif(isset($_GET['action']) && $_GET['action']== 'ajout')
    {
        $photo_bdd= $_FILES['Photo'];
        if(!empty($_FILES['Photo']['name']))
        {
            $nom_photo= $_POST['Nom_Prof'] . '_' . $_FILES['Photo']['name'];
            $photo_bdd= RACINE_SITE . "Photo/$nom_photo";
            $photo_dossier= $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . "Photo/$nom_photo";
            copy($_FILES['Photo']['tmp_name'], $photo_dossier);
        }
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("INSERT INTO Professeur (Mail, Password, Photo, Nom_Sport, Nom_Prof) VALUES ('$_POST[Mail]', '$_POST[Password]', '$photo_bdd', '$_POST[Nom_Sport]', '$_POST[Nom_Prof]')");
    
        $contenu .= '<div class="validation">Inscription réussite</div>';
        $_GET['action']= 'affichage';
    }
   
}

//----Lien pour Editer Professeur--//
$contenu .= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=affichage">Liste des Professeurs→</a></div><br><br>';
$contenu .= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=ajout">Inscrire Professeur→</a></div><br><br><hr><br>';

//----Affichage de la liste des Professeur----//
if(isset($_GET['action']) && $_GET['action']=="affichage")
{
    $resultat= executeRequete("SELECT * FROM Professeur");

    $contenu .= '<h2> Liste des Professeur </h2>';
    $contenu .= 'Nombre des Professeur :'. $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table table-dark table-bordered float-right" ><thead>';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Mail</th>';
        $contenu .= '<th scope="col">Password</th>';
        $contenu .= '<th scope="col">Photo</th>';
        $contenu .= '<th scope="col">Sport</th>';
        $contenu .= '<th scope="col">Nom complet</th>';
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
                $contenu .= '<td><img src="'. $information .'"="70" height="70"></td>';
            }
            else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&ID_Prof=' . $ligne['ID_Prof'] .'"><img src="img/editer.png" width="20" height="20"></a></td>'; 
        $contenu .= '<td><a href="?action=suppression&ID_Prof=' . $ligne['ID_Prof'] .'" OnClick="return(confirm(\'Confirmer ?\'));"><img src="img/delete.jpg" width="20" height="20"></a></td>'; 
        $contenu.= '</tr>';
        $contenu.= '</tbody>';
    }
        $contenu .= '</table></div><br><br>';
}

//---html---//
require_once("haider.php");
echo $contenu;
if(isset($_GET['action']) && ($_GET['action']== 'ajout' || $_GET['action']== 'modification'))
{
    if(isset($_GET['ID_Prof']))
    {
        $resultat= executeRequete("SELECT Nom_Prof, Mail, Password, Nom_Sport, Photo FROM Professeur WHERE ID_Prof= $_GET[ID_Prof]");
        $professeur_actuel= $resultat->fetch_assoc();
    }
    echo '

    <h1> Formulaire inscription </h1>
    <form method="POST" enctype="multipart/form-data" action="" class="w-70">

    <div class="input-group">
        <input type="hidden" id="ID_Prof" name="ID_Prof" value="'; if(isset($professeur_actuel['ID_Prof'])) echo $professeur_actuel['ID_Prof']; echo'"></div>

        <div class="input-group">
        <input type="text" class="form-control" placeholder="Nom Complet" id="Nom_Prof" name="Nom_Prof" value="'; if(isset($professeur_actuel['Nom_Prof'])) echo $professeur_actuel['Nom_Prof']; echo'"></div><br><br>

        <div class="input-group">
        <input type="email" id="Mail" name="Mail" class="form-control" placeholder="exemple@supdeco.edu.sn" value="'; if(isset($professeur_actuel['Mail'])) echo $professeur_actuel['Mail']; echo'"></div><br><br>

        <div class="input-group">
        <input type="Password" id="Password" class="form-control" placeholder="Mot de passe" name="Password" value="'; if(isset($professeur_actuel['Password'])) echo $professeur_actuel['Password']; echo'"></div> <br><br>

        <div class="input-group">
            <select name="Nom_Sport" class="form-select">
            <option value="FITNESS"';if(isset($professeur_actuel) && $professeur_actuel['Nom_Sport']== 'FITNESS') echo 'selected'; echo '>FITNESS</option>

            <option value="FOOTBALL"';if(isset($professeur_actuel) && $professeur_actuel['Nom_Sport']== 'FOOTBALL') echo 'selected'; echo '>FOOTBALL</option>

            <option value="BASKET"';if(isset($professeur_actuel) && $professeur_actuel['Nom_Sport']== 'BASKET') echo 'selected'; echo '>BASKET</option>

            <option value="TENNIS"';if(isset($professeur_actuel) && $professeur_actuel['Nom_Sport']== 'TENNIS') echo 'selected'; echo '>TENNIS</option>
            </select>
           </div> <br><br>

           <div class="input-group">
            <input type="file" id="Photo" class="form-control" placeholder="Photo" name="Photo"></div><br><br>';
            if(isset($professeur_actuel))
            {
                echo '<i> Ancienne Photo</i><br>';
                echo '<img src="' .$professeur_actuel['Photo'] .'" ="60" height="60"><br>';
                echo '<input type="hidden" name="photo_actuelle" value="' . $professeur_actuel['Photo'] . '"><br>';
            }
            echo '
            <div class="input-group">
        <input type="submit" class="btn btn-primary" value="'; echo ucfirst($_GET['action']) . ' du professeur"></div>
    </form>';
}
 require_once("footer.php"); ?>