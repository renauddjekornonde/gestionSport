<?php
require_once("commande.php");
  if(etudiantEstConnecter())
  {
      $id= $_SESSION['etudiant']['ID_Etu'];
      $nom= $_SESSION['etudiant']['Nom_Sport'];
       
      $resultat= executeRequete("SELECT DISTINCT Programme.ID_Programme, Programme.Nom_Etu, Programme.ID_Etu, Programme.Motif, Programme.Heure_Debut, Programme.Heure_Fin, Programme.Date_Programme FROM `Programme`,`Etudiant`,`Professeur` WHERE Etudiant.Nom_Sport= '$nom' AND Etudiant.ID_Etu= Programme.ID_Etu AND Programme.ID_Etu= $id ORDER BY Date_Programme DESC ");

    $contenu .= '<h2> Absences </h2>';
    $contenu .= 'Nombre d\'absence :' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
    $contenu .= '<tr>';
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Nom Complet</th>';
        $contenu .= '<th scope="col">Identifiant</th>';
        $contenu .= '<th scope="col">Motif</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
        $contenu .= '<th scope="col">Date</th>';
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

  //-------------------------------------------------------------------------------------//
  elseif(internauteEstConnecterEtEstAdmin())
  {
       
      $resultat= executeRequete("SELECT DISTINCT Programme.ID_Programme, Programme.Nom_Etu, Programme.ID_Etu, Programme.Motif, Programme.Heure_Debut, Programme.Heure_Fin, Programme.Date_Programme, Departement.Nom_Departement FROM `Programme`,`Etudiant`,`Professeur`, `Departement`, `Sport` GROUP BY Programme.ID_Programme ORDER BY Date_Programme DESC ");

    $contenu .= '<h2> Absences </h2>';
    $contenu .= 'Nombre d\'absence :' . $resultat->num_rows;
    $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
    $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
    $contenu .= '<tr>';
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Nom Complet</th>';
        $contenu .= '<th scope="col">Identifiant</th>';
        $contenu .= '<th scope="col">Motif</th>';
        $contenu .= '<th scope="col">Debut</th>';
        $contenu .= '<th scope="col">Fin</th>';
        $contenu .= '<th scope="col">Date</th>';
        $contenu .= '<th scope="col">Departement</th>';
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
  //-------------------------------------------------------------------------------------//

  else{header("location:connexion.php");}

  require_once("haider.php");
  echo $contenu;
  require_once("footer.php");
?>
