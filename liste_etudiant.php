<?php
require_once("commande.php");
  if(professeurEstConnecter())
  {
      $id= $_SESSION['professeur']['ID_Prof'];
      $sport= $_SESSION['professeur']['Nom_Sport'];
       
     $resultat= executeRequete("SELECT DISTINCT Etudiant.ID_Etu, Etudiant.Nom_Etu, Etudiant.Nom_Departement, Etudiant.Date_Naissance FROM `Etudiant`,`Professeur`, `Sport` WHERE Etudiant.Nom_Sport= '$sport' AND Sport.Nom_Sport= '$sport'");

    $contenu .= '<h2> Liste de la Classe </h2>';
    $contenu .= '<h6>Nombre des etudiants : ' . $resultat->num_rows .'</h6><br><hr>';
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">Identifiant</th>';
        $contenu .= '<th scope="col">Nom Etudiant</th>';
        $contenu .= '<th scope="col">Departement</th>';
        $contenu .= '<th scope="col">Date Naissance</th>';
    //}
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
        $contenu.= '</tr>';
        $contenu .= '</tbody>';
    }
        $contenu .= '</table></div><br><br>';
  }
  else{header("location:connexion.php");}

  require_once("haider.php");
  echo $contenu;
  require_once("footer.php");
?>
