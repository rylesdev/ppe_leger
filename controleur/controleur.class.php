<?php
	require_once ("modele/modele.class.php");
	class Controleur {
		private $unModele ;

		public function __construct (){
			$this->unModele = new Modele ();
		}

		public function verifConnexion ($emailUser, $mdpUser){
			return $this->unModele->verifConnexion($emailUser, $mdpUser);
		}

		/****************** SELECT  ******************/
        public function selectParticulier($idUser) {
            return $this->unModele->selectParticulier($idUser);
        }

        public function selectEntreprise($idUser) {
            return $this->unModele->selectEntreprise($idUser);
        }

        public function selectUser($idUser) {
            return $this->unModele->selectUser($idUser);
        }

		public function selectAdminPrincipal($idUser) {
			return $this->unModele->selectAdminPrincipal($idUser);
		}

		public function selectLivre(){
			return $this->unModele->selectLivre();
		}

		public function selectLikeLivre ($filtre){
			return $this->unModele->selectLikeLivre($filtre);
		}

		public function selectWhereLivre($idLivre){
			return $this->unModele->selectWhereLivre($idLivre);
		}

		public function selectAdresseUser ($idUser){
			return $this->unModele->selectAdresseUser($idUser);
		}

        public function selectLigneCommande ($idCommande){
            return $this->unModele->selectLigneCommande($idCommande);
        }

		public function selectDateLivraisonCommande($idUser) {
			return $this->unModele->selectDateLivraisonCommande($idUser);
		}

		public function viewSelectTotalCommandeEnAttente($idUser) {
			return $this->unModele->viewSelectTotalCommandeEnAttente($idUser);
		}

		public function viewSelectTotalCommandeExpediee($idUser) {
			return $this->unModele->viewSelectTotalCommandeExpediee($idUser);
		}

		public function viewSelectTotalLivreEnAttente($idUser) {
			return $this->unModele->viewSelectTotalLivreEnAttente($idUser);
		}

		public function viewSelectTotalLivreExpediee($idUser) {
			return $this->unModele->viewSelectTotalLivreExpediee($idUser);
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

		public function viewSelectNbMinLivreExpediee($idUser) {
			return $this->unModele->viewSelectNbMinLivreExpediee($idUser);
		}

		public function viewSelectNbMaxLivreExpediee($idUser) {
			return $this->unModele->viewSelectNbMaxLivreExpediee($idUser);
		}

		public function viewSelectNomMinLivreExpediee($idUser) {
			return $this->unModele->viewSelectNomMinLivreExpediee($idUser);
		}

		public function viewSelectNomMaxLivreExpediee($idUser) {
			return $this->unModele->viewSelectNomMaxLivreExpediee($idUser);
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

		public function selectDateAbonnement($idUser) {
			return $this->unModele->selectDateAbonnement($idUser);
		}

		public function selectPointAbonnement($idUser) {
			return $this->unModele->selectPointAbonnement($idUser);
		}

		public function selectDateLigneCommande($idUser) {
			return $this->unModele->selectDateLigneCommande($idUser);
		}

		public function selectNbLigneCommande($idCommande) {
			return $this->unModele->selectNbLigneCommande($idCommande);
		}

        public function selectLivrePromotion() {
            return $this->unModele->selectLivrePromotion();
        }

        public function selectUnLivrePromotion($idLivre) {
            return $this->unModele->selectUnLivrePromotion($idLivre);
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


		/**************** DELETE ****************/

		public function deleteLivre($idLivre){
			$this->unModele->deleteLivre($idLivre);
		}

		public function deleteLigneCommande($idLigneCommande){
			return $this->unModele->deleteLigneCommande($idLigneCommande);
		}

        public function archiverCommandeUtilisateur($idUser) {
            return $this->unModele->archiverCommandeUtilisateur($idUser);
        }

        public function deleteParticulier($idUser) {
            $this->unModele->deleteParticulier($idUser);
        }

        public function deleteEntreprise($idUser) {
            $this->unModele->deleteEntreprise($idUser);
        }

		/*public function deleteCommande($idLigneCommande){
			$this->unModele->deleteCommande($idLigneCommande);
		}*/


		/**************** INSERT ****************/
		/*public function triggerInsertParticulier ($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser) {
			$this->unModele->triggerInsertParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser);
		}*/

        public function insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser) {
            $this->unModele->insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser);
        }

        public function insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
            $this->unModele->insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
        }



        /*public function triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
            $this->unModele->triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
        }*/

		public function insertCommande($idUser){
			return $this->unModele->insertCommande($idUser);
		}

		public function insertLigneCommande($idCommande, $idLivre, $quantiteLigneCommande){
			return $this->unModele->insertLigneCommande($idCommande, $idLivre, $quantiteLigneCommande);
		}

		public function insertAbonnement1m($idUser){
			$this->unModele->insertAbonnement1m($idUser);
		}

		public function updateAbonnement1m($idUser) {
			return $this->unModele->updateAbonnement1m($idUser);
		}

		public function insertAbonnement3m($idUser) {
			$this->unModele->insertAbonnement3m($idUser);
		}

		public function updateAbonnement3m($idUser) {
			return $this->unModele->updateAbonnement3m($idUser);
		}

		public function insertAbonnement1a($idUser) {
			$this->unModele->insertAbonnement1a($idUser);
		}

		public function updateAbonnement1a($idUser) {
			return $this->unModele->updateAbonnement1a($idUser);
		}

		public function insertAvis($idLivre, $nomLivre, $idUser, $commentaireAvis, $noteAvis) {
			$this->unModele->insertAvis($idLivre, $nomLivre, $idUser, $commentaireAvis, $noteAvis);
		}


		/**************** UPDATE ****************/

		public function updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser) {
			$this->unModele->updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser);
		}

        public function updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
            $this->unModele->updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
        }

		public function updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre){
			$this->unModele->updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre);
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

		public function ajouterPointAbonnement($pointAbonnement, $idUser) {
			$this->unModele->ajouterPointAbonnement($pointAbonnement, $idUser);
		}

		public function enleverPointAbonnement($pointAbonnement, $idUser) {
			$this->unModele->enleverPointAbonnement($pointAbonnement, $idUser);
		}


		/**************** PROCEDURE ****************/
		public function procedureOffrirLivre($idUser, $chiffre) {
			return $this->unModele->procedureOffrirLivre($idUser, $chiffre);
		}

        public function procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition) {
            $this->unModele->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition);
        }

        public function procedureInsertOrUpdatePromotion($nomLivre, $reductionPromotion, $dateFinPromotion) {
            $this->unModele->procedureInsertOrUpdatePromotion($nomLivre, $reductionPromotion, $dateFinPromotion);
        }
	}
?>