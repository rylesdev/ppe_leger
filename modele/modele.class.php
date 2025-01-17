<?php
	class Modele {
		private $unPdo ; 

		public function __construct(){
			try{
				$serveur = "localhost:8889"; 
				$bdd     = "ppe";
				$user    = "root"; 
				$mdp     = "root"; 
				$this->unPdo = new PDO("mysql:host=".$serveur.";dbname=".$bdd, $user, $mdp);
			}
			catch(PDOException $exp){
				echo "<br> Erreur de connexion à la BDD :".$exp->getMessage();
			}
		}

        public function verifConnexion($emailUser, $mdpUser) {
            $requete =  "select * 
                        from user 
                        where emailUser = ? and mdpUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(2, $mdpUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }


		/**************** SELECT ****************/

		public function selectAllLivres (){
			$requete =  "select l.*
				        from livre l;";
			$exec = $this->unPdo->prepare ($requete);
			$exec->execute (); 
			return $exec->fetchAll(); 
		}

		public function selectLikeLivres ($filtre){
			$requete =  "select l.*
						from livre l   
						where l.idLivre like :filtre or 
						l.nomLivre like :filtre or 
						l.categorieLivre like :filtre or 
						l.auteurLivre like :filtre
						group by    l.idLivre, 
						            l.nomLivre, 
						            l.categorieLivre, 
						            l.auteurLivre, 
						            l.imageLivre, 
						            l.exemplaireLivre, 
						            l.prixLivre;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(":filtre"=>"%".$filtre."%");
			$exec->execute ($donnees); 
			return $exec->fetchAll(); 
		}

		public function selectWhereLivre ($idLivre){
			$requete =  "select * 
			            from livre 
			            where idLivre = ?;";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $idLivre, PDO::PARAM_STR);
			$exec->execute ();
			return $exec->fetch();
		}

        public function selectAdresseUser($idUser) {
            $requete =  "select adresseUser 
                        from user 
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectDateLivraisonCommande($idUser) {
            $requete =  "select dateLivraisonCommande 
                        from commande
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectViewTotalLivre() {
            $requete =  "select * 
                        from vTotalLivre;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewTotalCommande($idUser) {
            $requete =  "select * 
                        from vTotalCommande   
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectViewNbMinLivre() {
            $requete =  "select * 
                        from vNbMinLivre";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewNbMaxLivre() {
        $requete =  "select * 
                    from vNbMaxLivre;";
        $exec = $this->unPdo->prepare($requete);
        $exec->execute();
        return $exec->fetchAll();
        }

        public function selectViewNomMinLivre() {
            $requete =  "select * 
                        from vNomMinLivre";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewNomMaxLivre() {
            $requete =  "select * 
                        from vNomMaxLivre";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewCommandesEnAttente() {
            $requete =  "select * 
                        from vCommandesEnAttente";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewMeilleuresVentes() {
            $requete =  "select * 
                        from vMeilleuresVentes";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewLivresEnStock() {
            $requete =  "select * 
                        from vLivresEnStock";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }


        /**************** DELETE ****************/

		public function deleteLivre($idLivre){
			$requete =  "delete 
			            from livre 
			            where idLivre = ?;";
			$exec = $this->unPdo->prepare ($requete); 
			$exec->BindValue (1, $idLivre, PDO::PARAM_STR);
			$exec->execute();
		}

        public function deleteligneCommande($idCommande) {
            $requete =  "delete from 
                        ligneCommande 
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idCommande, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function deleteCommande($idCommande) {
            $requete =  "delete 
                        from commande 
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idCommande, PDO::PARAM_INT);
            $exec->execute();
        }


		/**************** INSERT ****************/

		public function insertUser ($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser){
			$requete =  "insert into user 
			            values (?, ?, ?, ?, ?, ?, 'client', curdate()); ";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $nomUser, PDO::PARAM_STR);
			$exec->BindValue (2, $prenomUser, PDO::PARAM_STR);
			$exec->BindValue (3, $emailUser, PDO::PARAM_STR);
			$exec->BindValue (4, $mdpUser, PDO::PARAM_STR);
			$exec->BindValue (5, $adresseUser, PDO::PARAM_STR);
			$exec->execute();
		}

		public function insertLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $prixLivre){
			$requete =  "insert into livre 
			            values (null, ?, ?, ?, ?, null, ?);";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $nomLivre, PDO::PARAM_STR);
			$exec->BindValue (2, $categorieLivre, PDO::PARAM_STR);
			$exec->BindValue (3, $auteurLivre, PDO::PARAM_STR);
			$exec->BindValue (4, $imageLivre, PDO::PARAM_STR);
			$exec->BindValue (5, $prixLivre, PDO::PARAM_STR);
			$exec->execute ();
			return $exec->fetchAll();
		}

		public function insertCommande ($idUser) {
            try {
                $requete =  "insert into commande 
			                values (null, curdate(), 'en attente', DATE_ADD(CURDATE(), INTERVAL 7 DAY), ?);";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $idUser, PDO::PARAM_INT);
			$exec->execute();
			return $this->unPdo->lastInsertId();
            } catch(PDOException $exp) {
                $messageErreur = $exp->getMessage();
            }
            return $exec->fetchAll();
		}

        public function insertLigneCommande($idCommande, $idLivre, $quantiteLivre)
        {
            try {
                $requete = "insert into ligneCommande
                            values (?, ?, ?);";
                $execInsert = $this->unPdo->prepare($requete);
                $execInsert->bindValue(1, $idCommande, PDO::PARAM_INT);
                $execInsert->bindValue(2, $idLivre, PDO::PARAM_INT);
                $execInsert->bindValue(3, $quantiteLivre, PDO::PARAM_INT);
                $execInsert->execute();
            } catch (PDOException $exp) {
                if ($exp->getCode() === '45000') {
                    $messageErreur = $exp->getMessage();
                    if (strpos($messageErreur, 'Stock insuffisant pour le livre') !== false) {
                        echo "<h3 style='color: red;'>Erreur : Stock insuffisant pour le livre. <br> Veuillez réduire la quantité ou choisir un autre livre. </h3>";
                    } elseif (strpos($messageErreur, 'Livre déjà dans le panier') !== false) {
                        echo "<h3 style='color: red;'>Erreur : Livre déjà dans le panier. <br> Veuillez modifier le nombre d'exemplaire. </h3>";
                    } else {
                        echo "<h3 style='color: red;'>Erreur : Un problème inattendu s'est produit.</h3>";
                    }
                }
            }
        }



        /**************** UPDATE ****************/

		public function updateLivre($nomLivre, $categorieLivre, $auteurLivre, $imageLivre, $idLivre, $prixLivre) {
			$requete =  "update livre 
			            set nomLivre = ?, 
			            categorieLivre = ?, 
			            auteurLivre = ?, 
			            imageLivre = ?, 
			            prixLivre = ? 
			            where idLivre = ?;";
			$exec = $this->unPdo->prepare($requete);
			$exec->BindValue(1, $nomLivre, PDO::PARAM_STR);
			$exec->BindValue(2, $categorieLivre, PDO::PARAM_STR);
			$exec->BindValue(3, $auteurLivre, PDO::PARAM_STR);
			$exec->BindValue(4, $imageLivre, PDO::PARAM_STR);
			$exec->BindValue(5, $prixLivre, PDO::PARAM_STR);
			$exec->BindValue(6, $idLivre, PDO::PARAM_INT);
			$exec->execute();
		}

		public function updateStockageLivre($exemplaireLivre, $nomLivre) {
			$requete =  "update livre 
			            set exemplaireLivre = ? 
			            where nomLivre = ?;";
			$exec = $this->unPdo->prepare($requete);
			$exec->BindValue(1,$exemplaireLivre, PDO::PARAM_INT);
			$exec->BindValue(2, $nomLivre, PDO::PARAM_STR);
			$exec->execute();
		}

        public function updateLigneCommande ($quantiteLigneCommande, $idCommande) {
            $requete =  "update ligneCommande
                        set quantiteLigneCommande = ?
                        where idCommande = ?;";;
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $quantiteLigneCommande, PDO::PARAM_INT);
            $exec->BindValue (2, $idCommande, PDO::PARAM_INT);
            $exec->execute();
            return $this->unPdo->lastInsertId();
        }


        /**************** PROCEDURE ****************/

        public function procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser) {
            $exec = $this->unPdo->prepare("CALL pHashMdpUser(null, ?, ?, ?, ?, ?, 'client', curdate())");
            $exec->BindValue (1, $nomUser, PDO::PARAM_STR);
            $exec->BindValue (2, $prenomUser, PDO::PARAM_STR);
            $exec->BindValue (3, $emailUser, PDO::PARAM_STR);
            $exec->BindValue (4, $mdpUser, PDO::PARAM_STR);
            $exec->BindValue (5, $adresseUser, PDO::PARAM_STR);
            $exec->execute();
        }
	}
?>