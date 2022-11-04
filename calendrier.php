<?php
require_once("commande.php");
  if(etudiantEstConnecter())
  {
      $id= $_SESSION['etudiant']['ID_Etu'];
      $sport= $_SESSION['etudiant']['Nom_Sport'];
       
    $resultat =executeRequete("SELECT DISTINCT Sport.Jour, Sport.Nom_Sport, Sport.Heure_Debut, Sport.Heure_Fin FROM `Sport`, `Etudiant` where Etudiant.Nom_Sport='$sport' AND Sport.Nom_Sport= '$sport' ORDER BY Jour DESC");

    $contenu .= '<h2> CALENDRIER </h2><br>';
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">Date</th>';
        $contenu .= '<th scope="col">Sport</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
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

  //------------------------------------------------------------------------------------------//
  elseif(internauteEstConnecterEtEstAdmin())
  {
   
       
    $resultat =executeRequete("SELECT DISTINCT Sport.Jour, Sport.Nom_Sport, Sport.Heure_Debut, Sport.Heure_Fin FROM `Sport`, `Etudiant` GROUP BY Sport.Nom_Sport ORDER BY Jour DESC");

    $contenu .= '<h2> CALENDRIER </h2><br>';
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
    $contenu .= '<tr>';
       // while($colonne= $resultat->fetch_field())
   // {
        $contenu .= '<th scope="col">Date</th>';
        $contenu .= '<th scope="col">Sport</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
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

  //------------------------------------------------------------------------------------------//

  else{header("location:connexion.php");}

  require_once("haider.php");
  echo $contenu;
  require_once("footer.php");
?>
