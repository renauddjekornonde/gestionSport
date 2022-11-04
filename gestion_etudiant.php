<?php
require_once("commande.php");

//---Verification Admin--//
if(!internauteEstConnecterEtEstAdmin())
{
    header("location:connexion.php");
    exit();
}

//--Traitement php pour supprimer Etudiant--//
if(isset($_GET['action']) && $_GET['action']== "suppression")
{
    $resultat= executeRequete("SELECT * FROM Etudiant WHERE ID_Etu= $_GET[ID_Etu]");
    $etudiant_a_supprimer= $resultat->fetch_assoc();
    $chemin_photo_a_supprimer= $_SERVER['DOCUMENT_ROOT'] . $etudiant_a_supprimer['Photo'];
    if(!empty($etudiant_a_supprimer['Photo']) && file_exists($chemin_photo_a_supprimer)) unlink($chemin_photo_a_supprimer);
    $contenu.= '<div class="validation">Supprission Du Compte de l\'etudiant ' . $_GET['ID_Etu'] . '</div>';
    executeRequete("DELETE FROM Etudiant WHERE ID_Etu=$_GET[ID_Etu]");
    $_GET['action'] = 'affichage';
}

//--Modification Etudiant--//
if(!empty($_POST))
{
    $photo_bdd= "";
    if(isset($_GET['action']) && $_GET['action']== 'modification')
    {
        $photo_bdd= $_POST['Photo'];
        if(!empty($_FILES['Photo']['name']))
        {
            $nom_photo= $_POST['Nom_Etu'] . '_' .$_FILES['Photo']['name'];
            $photo_bdd= RACINE_SITE . "Photo/$nom_photo";
            $photo_dossier= $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . "Photo/$nom_photo";
            copy($_FILES['Photo']['tmp_name'], $photo_dossier);
        }
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("UPDATE Etudiant SET Mail= '$_POST[Mail]', Date_Naissance= '$_POST[Date_Naissance]', Password= '$_POST[Password]', Photo= '$photo_bdd', Nom_Departement= '$_POST[Nom_Departement]', Nom_Sport= '$_POST[Nom_Sport]', Nom_Etu= '$_POST[Nom_Etu]' WHERE ID_Etu= $_GET[ID_Etu]");
    
        $contenu .= '<div class="validation">Modification réussite</div>';
        $_GET['action']= 'affichage';
    }

    //--Enregistrer Etudiant--//
    elseif(isset($_GET['action']) && $_GET['action']== 'ajout')
    {
      $photo_bdd= $_FILES['Photo'];
        if(!empty($_FILES['Photo']['name']))
        {
            $nom_photo= $_POST['Nom_Etu'] . '_' .$_FILES['Photo']['name'];
            $photo_bdd= RACINE_SITE . "Photo/$nom_photo";
            $photo_dossier= $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . "Photo/$nom_photo";
            copy($_FILES['Photo']['tmp_name'], $photo_dossier);
        }
        foreach($_POST as $indice => $valeur)
        {
            $_POST[$indice]= htmlEntities(addSlashes($valeur));
        }
        executeRequete("INSERT INTO Etudiant (Mail, Date_Naissance, Password, Photo, Nom_Departement, Nom_Sport, Nom_Etu) VALUES ('$_POST[Mail]', '$_POST[Date_Naissance]', '$_POST[Password]', '$photo_bdd', '$_POST[Nom_Departement]', '$_POST[Nom_Sport]', '$_POST[Nom_Etu]')");
    
        $contenu .= '<div class="validation">Inscription Réussite</div>';
        $_GET['action']= 'affichage';
    }
   
}

//----Lien pour Editer Etudiant--//
$contenu .= '<div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=affichage">Liste des Etudiants→</a></div><br><br>';
$contenu .= ' <div class="d-flex justify-content-left mb-4"><a class="btn btn-primary text-uppercase" href="?action=ajout">Inscrire Etudiants→</a></div><br><br><hr><br>';

//----Affichage de la liste des etudiants----//
if(isset($_GET['action']) && $_GET['action']==  "affichage")
{
    $resultat= executeRequete("SELECT * FROM Etudiant");

    $contenu .= '<h2> Liste des Etudiants </h2>';
    $contenu .= 'Nombre des Etudiants :' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table table-dark table-bordered" ><thead>';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Mail</th>';
        $contenu .= '<th scope="col">Date Naissance</th>';
        $contenu .= '<th scope="col">Password</th>';
        $contenu .= '<th scope="col">Photo</th>';
        $contenu .= '<th scope="col">Departement</th>';
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
                $contenu .= '<td><img src="' . $information . '"="50" height="50"></td>';
            }
            else
            {
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&ID_Etu=' . $ligne['ID_Etu'] .'"><img src="img/editer.png" width="20" height="20"></a></td>'; 
        $contenu .= '<td><a href="?action=suppression&ID_Etu=' . $ligne['ID_Etu'] .'" OnClick="return(confirm(\'Confirmer ?\'));"><img src="img/delete.jpg" width="20" height="20"></a></td>'; 
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
    if(isset($_GET['ID_Etu']))
    {
        $resultat= executeRequete("SELECT Nom_Etu, Mail, Date_Naissance, Password, Nom_Departement, Nom_Sport, Photo FROM Etudiant WHERE ID_Etu= $_GET[ID_Etu]");
        $etudiant_actuel= $resultat->fetch_assoc();
    }
    echo '

    <h1> Formulaire inscription </h1>
    <form method="POST" enctype="multipart/form-data" action="" class="w-70">
    <div class="input-group">
        <input type="hidden" id="ID_Etu" name="ID_Etu" value="'; if(isset($etudiant_actuel['ID_Etu'])) echo $etudiant_actuel['ID_Etu']; echo'"></div>

        <div class="input-group">
        <input type="text" id="Nom_Etu" class="form-control" name="Nom_Etu" placeholder="Nom Complet" value="'; if(isset($etudiant_actuel['Nom_Etu'])) echo $etudiant_actuel['Nom_Etu']; echo'"></div><br><br>

        <div class="input-group">
        <input type="email" id="Mail" class="form-control" name="Mail" placeholder="exemple@supdeco.edu.sn" value="'; if(isset($etudiant_actuel['Mail'])) echo $etudiant_actuel['Mail']; echo'"></div><br><br>

        <div class="input-group">
        <input type="date" class="form-control" id="Date_Naissance" placeholder="Naissance" name="Date_Naissance"value="'; if(isset($etudiant_actuel['Date_Naissance'])) echo $etudiant_actuel['Date_Naissance']; echo'"> </div><br><br>

        <div class="input-group">
        <input type="Password" class="form-control" placeholder="Mot de passe" id="Password" name="Password" value="'; if(isset($etudiant_actuel['Password'])) echo $etudiant_actuel['Password']; echo'"></div> <br><br>

        <div class="input-group">
            <select name="Nom_Departement" class="form-select">
            <option value="ESITEC"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Departement']== 'ESITEC') echo 'selected'; echo '>ESITEC</option>

            <option value="IMTN"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Departement']== 'IMTN') echo 'selected'; echo '>IMTN</option>

            <option value="MERCURE"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Departement']== 'MERCURE') echo 'selected'; echo '>MERCURE</option>

            <option value="EPITA"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Departement']== 'EPITA') echo 'selected'; echo '>EPITA</option>
            </select>
            </div><br><br>

            <div class="input-group">
            <select name="Nom_Sport" class="form-select">
            <option value="FITNESS"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Sport']== 'FITNESS') echo 'selected'; echo '>FITNESS</option>

            <option value="FOOTBALL"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Sport']== 'FOOTBALL') echo 'selected'; echo '>FOOTBALL</option>

            <option value="BASKET"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Sport']== 'BASKET') echo 'selected'; echo '>BASKET</option>

            <option value="TENNIS"';if(isset($etudiant_actuel) && $etudiant_actuel['Nom_Sport']== 'TENNIS') echo 'selected'; echo '>TENNIS</option>
            </select>
            </div><br><br>

            <div class="input-group">
            <input type="file" class="form-control" placeholder="Photo" id="Photo" name="Photo"></div><br> <br>';
            if(isset($etudiant_actuel))
            {
                echo '<i> Ancienne Photo</i><br>';
                echo '<img src="' .$etudiant_actuel['Photo'] .'" ="600"  height="60"><br>';
                echo '<input type="hidden" name="Photo" value="' . $etudiant_actuel['Photo'] . '"><br>';
            }
            echo '
            <div class="input-group">
        <input type="submit" class="btn btn-primary" value="'; echo ucfirst($_GET['action']) . ' de Etudiant"></div>
    </form>';
}
 require_once("footer.php"); ?>