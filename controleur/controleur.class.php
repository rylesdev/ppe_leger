<?php
require_once ("modele/modele.class.php");
class Controleur {
    private $unModele ;

    public function __construct (){
        $this->unModele = new Modele ();
    }



    /****************** SELECT  ******************/
    public function verifConnexion ($emailUser, $mdpUser) {
        return $this->unModele->verifConnexion($emailUser, $mdpUser);
    }

    public function selectParticulier($idUser) {
        return $this->unModele->selectParticulier($idUser);
    }

    public function selectEntreprise($idUser) {
        return $this->unModele->selectEntreprise($idUser);
    }

    public function selectAdmin($idUser) {
        return $this->unModele->selectAdmin($idUser);
    }

    public function selectLivre() {
        return $this->unModele->selectLivre();
    }
    
    public function selectCategorie() {
        return $this->unModele->selectCategorie();
    }

    public function selectMaisonEdition() {
        return $this->unModele->selectMaisonEdition();
    }

    public function selectPromotion() {
        return $this->unModele->selectPromotion();
    }

    public function selectIdCategorieByNom($nomCategorie) {
        return $this->unModele->selectIdCategorieByNom($nomCategorie);
    }

    public function selectIdMaisonEditionByNom($nomMaisonEdition) {
        return $this->unModele->selectIdMaisonEditionByNom($nomMaisonEdition);
    }

    public function selectIdPromotionByNom($nomPromotion) {
        return $this->unModele->selectIdPromotionByNom($nomPromotion);
    }

    public function selectNomCategorieById($idCategorie) {
        return $this->unModele->selectNomCategorieById($idCategorie);
    }

    public function selectNomMaisonEditionById($idMaisonEdition) {
        return $this->unModele->selectNomMaisonEditionById($idMaisonEdition);
    }

    public function selectNomPromotionById($idPromotion) {
        return $this->unModele->selectNomPromotionById($idPromotion);
    }

    public function selectNbLivreByPromotion($idPromotion) {
        return $this->unModele->selectNbLivreByPromotion($idPromotion);
    }

    public function selectLivreById($idLivre) {
        return $this->unModele->selectLivreById($idLivre);
    }

    public function selectLikeLivre ($filtre) {
        return $this->unModele->selectLikeLivre($filtre);
    }

    public function selectLikeMaisonEdition($filtre) {
        return $this->unModele->selectLikeMaisonEdition($filtre);
    }

    public function selectLikeCategorie($filtre) {
        return $this->unModele->selectLikeCategorie($filtre);
    }

    public function selectLikePromotion ($filtre) {
        return $this->unModele->selectLikePromotion($filtre);
    }

    public function selectWhereLivre($idLivre) {
        return $this->unModele->selectWhereLivre($idLivre);
    }

    public function selectWherePromotion($idPromotion) {
        return $this->unModele->selectWherePromotion($idPromotion);
    }

    public function selectWhereMaisonEdition($idMaisonEdition) {
        return $this->unModele->selectWhereMaisonEdition($idMaisonEdition);
    }

    public function selectWhereCategorie($idCategorie) {
        return $this->unModele->selectWhereCategorie($idCategorie);
    }

    public function selectFiltreLivreEnAttente($idUser, $filtre) {
        return $this->unModele->selectFiltreLivreEnAttente($idUser, $filtre);
    }

    public function selectAdresseUser($idUser) {
        return $this->unModele->selectAdresseUser($idUser);
    }

    public function selectDateLivraisonCommande($idUser) {
        return $this->unModele->selectDateLivraisonCommande($idUser);
    }

    public function selectDateAbonnement($idUser) {
        return $this->unModele->selectDateAbonnement($idUser);
    }

    public function selectPointAbonnement($idUser) {
        return $this->unModele->selectPointAbonnement($idUser);
    }

    public function selectNbLigneCommande($idCommande) {
        return $this->unModele->selectNbLigneCommande($idCommande);
    }

    public function selectLivrePromotion() {
        return $this->unModele->selectLivrePromotion();
    }

    public function selectOffrirLivre($idUser) {
        return $this->unModele->selectOffrirLivre($idUser);
    }

    public function selectCommandeEnAttente($idUser) {
        return $this->unModele->selectCommandeEnAttente($idUser);
    }

    public function selectCommandeExpediee($idUser) {
        return $this->unModele->selectCommandeExpediee($idUser);
    }

    public function selectCommandeByUser($idUser) {
        return $this->unModele->selectCommandeByUser($idUser);
    }

    public function selectCommandeByIdTri($idCommande, $tri = null) {
        return $this->unModele->selectCommandeByIdTri($idCommande, $tri);
    }

    public function selectCommandeTri($idUser, $tri = null) {
        return $this->unModele->selectCommandeTri($idUser, $tri);
    }

    public function countLigneCommande($idCommande) {
        return $this->unModele->countLigneCommande($idCommande);
    }

    public function executerRequete($requete) {
        return $this->unModele->executerRequete($requete);
    }

    public function selectUserByEmail ($emailUser) {
        return $this->unModele->selectUserByEmail ($emailUser);
    }



    /**************** DELETE ****************/
    public function deleteLivre($idLivre){
         return $this->unModele->deleteLivre($idLivre);
    }

    public function deleteMaisonEdition($idMaisonEdition) {
        return $this->unModele->deleteMaisonEdition($idMaisonEdition);
    }

    public function deleteCategorie($idCategorie) {
        return $this->unModele->deleteCategorie($idCategorie);
    }

    public function deletePromotion($idPromotion) {
        return $this->unModele->deletePromotion($idPromotion);
    }

    public function deleteLigneCommande($idLigneCommande) {
        return $this->unModele->deleteLigneCommande($idLigneCommande);
    }

    public function deleteParticulier($idUser) {
        return $this->unModele->deleteParticulier($idUser);
    }

    public function deleteEntreprise($idUser) {
        return $this->unModele->deleteEntreprise($idUser);
    }



    /**************** INSERT ****************/
    public function insertMaisonEdition($nomMaisonEdition) {
        return $this->unModele->insertMaisonEdition($nomMaisonEdition);
    }

    public function insertCategorie($nomCategorie) {
        return $this->unModele->insertCategorie($nomCategorie);
    }

    public function insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion) {
        return $this->unModele->insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion);
    }

    public function insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser) {
        return $this->unModele->insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);
    }

    public function insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
        return $this->unModele->insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
    }

    public function insertCommande($idUser) {
        return $this->unModele->insertCommande($idUser);
    }

    public function insertAbonnement1m($idUser) {
        return $this->unModele->insertAbonnement1m($idUser);
    }

    public function updateAbonnement1m($idUser) {
        return $this->unModele->updateAbonnement1m($idUser);
    }

    public function insertAbonnement3m($idUser) {
        return $this->unModele->insertAbonnement3m($idUser);
    }

    public function updateAbonnement3m($idUser) {
        return $this->unModele->updateAbonnement3m($idUser);
    }

    public function insertAbonnement1a($idUser) {
        return $this->unModele->insertAbonnement1a($idUser);
    }

    public function updateAbonnement1a($idUser) {
        return $this->unModele->updateAbonnement1a($idUser);
    }

    public function updateAbonnement0($idUser) {
        return $this->unModele->updateAbonnement0($idUser);
    }

    public function insertAvis($idLivre, $idUser, $commentaireAvis, $noteAvis) {
        return $this->unModele->insertAvis($idLivre, $idUser, $commentaireAvis, $noteAvis);
    }



    /**************** UPDATE ****************/
    public function updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser) {
        return $this->unModele->updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser);
    }

    public function updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
        return $this->unModele->updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
    }

    public function  updateLivre($nomLivre, $auteurLivre, $imageLivre, $prixLivre, $idCategorie, $idMaisonEdition, $idPromotion, $idLivre) {
        return $this->unModele->updateLivre($nomLivre, $auteurLivre, $imageLivre, $prixLivre, $idCategorie, $idMaisonEdition, $idPromotion, $idLivre);
    }

    public function updateMaisonEdition($nomMaisonEdition, $idMaisonEdition) {
        return $this->unModele->updateMaisonEdition($nomMaisonEdition, $idMaisonEdition);
    }

    public function updateCategorie($nomCategorie, $idCategorie) {
        return $this->unModele->updateCategorie($nomCategorie, $idCategorie);
    }

    public function updatePromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotion) {
        return $this->unModele->updatePromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotion);
    }

    public function updateMaisonEditionLivre($idMaisonEdition, $idLivre) {
        return $this->unModele->updateMaisonEditionLivre($idMaisonEdition, $idLivre);
    }

    public function updateCategorieLivre($idCategorie, $idLivre) {
        return $this->unModele->updateCategorieLivre($idCategorie, $idLivre);
    }

    public function updatePromotionLivre($idPromotion, $idLivre) {
        return $this->unModele->updatePromotionLivre($idPromotion, $idLivre);
    }

    public function updateStockageLivre($exemplaireLivre, $nomLivre) {
        return $this->unModele->updateStockageLivre($exemplaireLivre, $nomLivre);
    }

    public function updateCommande($idCommande) {
        return $this->unModele->updateCommande($idCommande);
    }

    public function updateLigneCommande ($quantiteLigneCommande, $idLigneCommande) {
        return $this->unModele->updateLigneCommande ($quantiteLigneCommande, $idLigneCommande);
    }

    public function ajouterPointAbonnement($pointAbonnement, $idUser){
        return $this->unModele->ajouterPointAbonnement($pointAbonnement, $idUser);
    }

    public function enleverPointAbonnement($pointAbonnement, $idUser) {
        $this->unModele->enleverPointAbonnement($pointAbonnement, $idUser);
    }



    /**************** VIEW ****************/
    public function viewSelectTotalLivreEnAttente($idUser) {
        return $this->unModele->viewSelectTotalLivreEnAttente($idUser);
    }

    public function viewSelectNbMinLivreEnAttente($idUser) {
        return $this->unModele->viewSelectNbMinLivreEnAttente($idUser);
    }

    public function viewSelectNbMaxLivreEnAttente($idUser) {
        return $this->unModele->viewSelectNbMaxLivreEnAttente($idUser);
    }

    public function viewSelectNomMinLivreEnAttente($idUser) {
        return $this->unModele->viewSelectNomMinLivreEnAttente($idUser);
    }

    public function viewSelectNomMaxLivreEnAttente($idUser) {
        return $this->unModele->viewSelectNomMaxLivreEnAttente($idUser);
    }

    public function viewSelectCommandesEnAttente() {
        return $this->unModele->viewSelectCommandesEnAttente();
    }

    public function viewSelectMeilleuresVentes() {
        return $this->unModele->viewSelectMeilleuresVentes();
    }

    public function viewSelectLivresEnStock() {
        return $this->unModele->viewSelectLivresEnStock();
    }

    public function viewMeilleursAvis() {
        return $this->unModele->viewMeilleursAvis();
    }

    public function viewNbLivreAcheteUser() {
        return $this->unModele->viewNbLivreAcheteUser();
    }



    /**************** PROCEDURE ****************/
    public function procedureInsertOrUpdateLigneCommande($idCommande, $idLivre, $quantiteLigneCommande) {
        return $this->unModele->procedureInsertOrUpdateLigneCommande($idCommande, $idLivre, $quantiteLigneCommande);
    }

    public function procedureOffrirLivre($idUser, $chiffre) {
        return $this->unModele->procedureOffrirLivre($idUser, $chiffre);
    }

    public function procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition, $nomPromotion) {
        return $this->unModele->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition, $nomPromotion);
    }
}