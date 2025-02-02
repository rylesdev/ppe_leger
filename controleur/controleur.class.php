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

		public function selectAdminPrincipal($idUser) {
			return $this->unModele->selectAdminPrincipal($idUser);
		}

		public function selectAllLivres (){
			return $this->unModele->selectAllLivres();
		}

		public function selectLikeLivres ($filtre){
			return $this->unModele->selectLikeLivres($filtre);
		}

		public function selectWhereLivre($idLivre){
			return $this->unModele->selectWhereLivre($idLivre);
		}

		public function selectAdresseUser ($idUser){
			return $this->unModele->selectAdresseUser($idUser);
		}

		public function selectCommandeEnCours($idUser) {
			return $this->unModele->selectCommandeEnCours($idUser);
		}

		public function selectDateLivraisonCommande($idUser) {
			return $this->unModele->selectDateLivraisonCommande($idUser);
		}

		public function viewSelectTotalCommandeEnAttente($idUser) {
			return $this->unModele->viewSelectTotalCommandeEnAttente($idUser);
		}

		public function viewSelectTotalCommandeEnAttentePoint($idUser) {
			return $this->unModele->viewSelectTotalCommandeEnAttentePoint($idUser);
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

        public function selectOffrirLivre($idUser) {
            return $this->unModele->selectOffrirLivre($idUser);
        }


		/**************** DELETE ****************/

		public function deleteLivre($idLivre){
			$this->unModele->deleteLivre($idLivre);
		}

		public function deleteLigneCommande($idCommande){
			return $this->unModele->deleteLigneCommande($idCommande);
		}

		public function deleteCommande($idCommande){
			$this->unModele->deleteCommande($idCommande);
		}


		/**************** INSERT ****************/
		public function triggerInsertParticulier ($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser) {
			$this->unModele->triggerInsertParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser);
		}

		public function triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
			$this->unModele->triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser);
		}

		public function insertCommande($idUser){
			return $this->unModele->insertCommande($idUser);
		}

		public function insertLigneCommande($idCommande, $idLivre, $quantiteLivre){
			return $this->unModele->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);
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

		public function updateParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser, $idUser) {
			$this->unModele->updateParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser, $idUser);
		}

        public function updateEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
            $this->unModele->updateEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser);
        }

		public function updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre){
			$this->unModele->updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre);
		}

		public function updateStockageLivre($exemplaireLivre, $nomLivre) {
			$this->unModele->updateStockageLivre($exemplaireLivre, $nomLivre);
		}

		public function updateCommande($idCommande) {
			return $this->unModele->updateCommande($idCommande);
		}

		public function updateLigneCommande ($quantiteLigneCommande, $idCommande) {
			$this->unModele->updateLigneCommande ($quantiteLigneCommande, $idCommande);
		}

		public function ajouterPointAbonnement($pointAbonnement, $idUser) {
			$this->unModele->ajouterPointAbonnement($pointAbonnement, $idUser);
		}

		public function enleverPointAbonnement($pointAbonnement, $idUser) {
			$this->unModele->enleverPointAbonnement($pointAbonnement, $idUser);
		}


		/**************** PROCEDURE ****************/
		public function procedureOffrirLivre($idUser, $chiffre) {
			$this->unModele->procedureOffrirLivre($idUser, $chiffre);
		}

        public function procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition) {
            $this->unModele->procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition);
        }

        public function procedureInsertOrUpdatePromotion($nomLivre, $prixPromotion, $dateFinPromotion) {
            $this->unModele->procedureInsertOrUpdatePromotion($nomLivre, $prixPromotion, $dateFinPromotion);
        }
	}
?>