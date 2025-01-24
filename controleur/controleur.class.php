<?php
	require_once ("modele/modele.class.php"); 
	class Controleur {
		private $unModele ; 

		public function __construct (){
			$this->unModele = new Modele ();
		}

		public function verifConnexion ($emailUser, $mdpUser){
			return $this->unModele->verifConnexion($emailUser, sha1($mdpUser));
		}

		/****************** SELECT  ******************/

		public function selectAllLivres ($idUser){
			return $this->unModele->selectAllLivres($idUser);
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

		public function selectLigneCommande ($idCommande){
			return $this->unModele->selectLigneCommande($idCommande);
		}

		public function selectCommandeEnCours($idUser) {
			return $this->unModele->selectCommandeEnCours($idUser);
		}

		public function selectDateLivraisonCommande($idUser) {
			return $this->unModele->selectDateLivraisonCommande($idUser);
		}

		public function selectViewTotalLivre($idUser) {
			return $this->unModele->selectViewTotalLivre($idUser);
		}

		public function selectViewTotalCommande($idUser) {
			return $this->unModele->selectViewTotalCommande($idUser);
		}

		public function selectViewNbMinLivre() {
			return $this->unModele->selectViewNbMinLivre();
		}

		public function selectViewNbMaxLivre() {
			return $this->unModele->selectViewNbMaxLivre();
		}

		public function selectViewNomMinLivre() {
			return $this->unModele->selectViewNomMinLivre();
		}

		public function selectViewNomMaxLivre() {
			return $this->unModele->selectViewNomMaxLivre();
		}

		public function selectViewCommandesEnAttente() {
			return $this->unModele->selectViewCommandesEnAttente();
		}

		public function selectViewMeilleuresVentes() {
			return $this->unModele->selectViewMeilleuresVentes();
		}

		public function selectViewLivresEnStock() {
			return $this->unModele->selectViewLivresEnStock();
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

		public function insertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser){
			$this->unModele->insertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser);
		}

		public function insertLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $prixLivre){
			$this->unModele->insertLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $prixLivre);
		}

		public function insertCommande($idUser){
			return $this->unModele->insertCommande($idUser);
		}

		public function insertLigneCommande($idCommande, $idLivre, $quantiteLivre){
			return $this->unModele->insertLigneCommande($idCommande, $idLivre, $quantiteLivre);
		}


		/**************** UPDATE ****************/

		public function updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre){
			$this->unModele->updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre);
		}

		public function updateStockageLivre($exemplaireLivre, $nomLivre) {
			$this->unModele->updateStockageLivre($exemplaireLivre, $nomLivre);
		}

		public function updateLigneCommande ($quantiteLigneCommande, $idCommande) {
			$this->unModele->updateLigneCommande ($quantiteLigneCommande, $idCommande);
		}

		public function procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser) {
			$this->unModele->procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser);
		}
	}
?>





