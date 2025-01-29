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
		public function selectUser($idUser) {
			return $this->unModele->selectUser($idUser);
		}

		public function selectAllLivres (){
			return $this->unModele->selectAllLivres();
		}

		public function selectLikeLivres ($filtre){
			return $this->unModele->selectLikeLivres($filtre);
		}

		public function selectAllCategories ($nomCategorie){
			return $this->unModele->selectAllCategories($nomCategorie);
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

		public function selectViewTotalCommandeEnAttente($idUser) {
			return $this->unModele->selectViewTotalCommandeEnAttente($idUser);
		}

		public function selectViewTotalCommandeExpediee($idUser) {
			return $this->unModele->selectViewTotalCommandeExpediee($idUser);
		}

		public function selectViewTotalLivreEnAttente($idUser) {
			return $this->unModele->selectViewTotalLivreEnAttente($idUser);
		}

		public function selectViewTotalLivreExpediee($idUser) {
			return $this->unModele->selectViewTotalLivreExpediee($idUser);
		}

		public function selectViewNbMinLivreEnAttente($idUser) {
			return $this->unModele->selectViewNbMinLivreEnAttente($idUser);
		}

		public function selectViewNbMaxLivreEnAttente($idUser) {
			return $this->unModele->selectViewNbMaxLivreEnAttente($idUser);
		}

		public function selectViewNomMinLivreEnAttente($idUser) {
			return $this->unModele->selectViewNomMinLivreEnAttente($idUser);
		}

		public function selectViewNomMaxLivreEnAttente($idUser) {
			return $this->unModele->selectViewNomMaxLivreEnAttente($idUser);
		}

		public function selectViewNbMinLivreExpediee($idUser) {
			return $this->unModele->selectViewNbMinLivreExpediee($idUser);
		}

		public function selectViewNbMaxLivreExpediee($idUser) {
			return $this->unModele->selectViewNbMaxLivreExpediee($idUser);
		}

		public function selectViewNomMinLivreExpediee($idUser) {
			return $this->unModele->selectViewNomMinLivreExpediee($idUser);
		}

		public function selectViewNomMaxLivreExpediee($idUser) {
			return $this->unModele->selectViewNomMaxLivreExpediee($idUser);
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

		public function selectDateAbonnement($idUser) {
			return $this->unModele->selectDateAbonnement($idUser);
		}

		public function selectDateLigneCommande($idUser) {
			return $this->unModele->selectDateLigneCommande($idUser);
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

		public function updateUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser, $idUser) {
			$this->unModele->updateUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser, $idUser);
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


		/**************** PROCEDURE ****************/
		public function procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser) {
			$this->unModele->procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser);
		}

		public function procedureInsertLivre($idUser, $chiffre) {
			$this->unModele->procedureInsertLivre($idUser, $chiffre);
		}
	}
?>





