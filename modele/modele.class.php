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
        public function selectUser($idUser) {
            $requete =  "select * 
                        from user
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

		public function selectAllLivres (){
			$requete =  "select l.*, c.nomCategorie
				        from livre l
				        inner join categorie c 
				        on l.idCategorie=c.idCategorie;";
			$exec = $this->unPdo->prepare ($requete);
			$exec->execute ();
			return $exec->fetchAll();
		}

		public function selectLikeLivres ($filtre){
			$requete =  "select l.*, c.nomCategorie
						from livre l   
						inner join categorie c 
						on l.idCategorie=c.idCategorie
						where l.idLivre like :filtre or 
						l.nomLivre like :filtre or 
						c.nomCategorie like :filtre or 
						l.auteurLivre like :filtre
						group by    l.idLivre, 
						            l.nomLivre, 
						            c.nomCategorie, 
						            l.auteurLivre, 
						            l.imageLivre, 
						            l.exemplaireLivre, 
						            l.prixLivre;";
			$exec = $this->unPdo->prepare ($requete);
			$donnees = array(":filtre"=>"%".$filtre."%");
			$exec->execute ($donnees);
			return $exec->fetchAll();
		}

        public function selectAllCategories($nomCategorie) {
            $requete =  "select c.nomCategorie 
                        from categorie c
                        inner join livre l
                        on c.idCategorie=l.idCategorie
                        where idCategorie = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $nomCategorie, PDO::PARAM_STR);
            $exec->execute();
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

        public function selectLigneCommande($idCommande) {
            $requete =  "select * 
                        from ligneCommande 
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idCommande, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectCommandeEnCours($idUser) {
            try {
                $requete =  "select idCommande 
                            from commande 
                            where idUser = ? and statutCommande = 'en attente'";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                $result = $exec->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    return $result['idCommande'];
                } else {
                    return null;
                }
            } catch (PDOException $exp) {
                echo $exp->getMessage();
                return null;
            }
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

        public function selectViewTotalLivre($idUser) {
            $requete =  "select * 
                        from vTotalLivre
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectViewTotalCommande($idUser) {
            $requete =  "select * 
                        from vTotalCommande   
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
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

        public function selectAbonnement($idUser) {
            $requete =  "select idUser
                        from abonnement 
                        where dateFinAbonnement > curdate() and idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec-> BindValue(1, $idUser, PDO::PARAM_INT);
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

        public function deleteLigneCommande($idCommande) {
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

		public function insertLivre($nomLivre, $auteurLivre, $imageLivre, $prixLivre, $nomCategorie){
			$requete =  "insert into livre (idLivre, nomLivre, auteurLivre, imageLivre, exemplaireLivre, prixLivre, idCategorie, idMaisonEdition)
			            values (null, ?, ?, ?, null, ?, '', '');
			            insert into categorie
			            values (null, ?);";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $nomLivre, PDO::PARAM_STR);
			$exec->BindValue (2, $auteurLivre, PDO::PARAM_STR);
			$exec->BindValue (3, $imageLivre, PDO::PARAM_STR);
			$exec->BindValue (4, $prixLivre, PDO::PARAM_STR);
            $exec->BindValue (5, $nomCategorie, PDO::PARAM_STR);
			$exec->execute ();
			return $exec->fetchAll();
		}

		public function insertCommande ($idUser) {
            try {
                $requete =  "insert into commande 
			                values (null, null, 'en attente', null, ?);";
			$exec = $this->unPdo->prepare ($requete);
			$exec->BindValue (1, $idUser, PDO::PARAM_INT);
			$exec->execute();
			return $this->unPdo->lastInsertId();
            } catch(PDOException $exp) {
                echo $exp->getMessage();
            }
            return $exec->fetchAll();
		}

        public function insertLigneCommande($idCommande, $idLivre, $quantiteLivre) {
            try {
                $requete = "insert into ligneCommande
                            values (null, ?, ?, ?);";
                $execInsert = $this->unPdo->prepare($requete);
                $execInsert->bindValue(1, $idCommande, PDO::PARAM_INT);
                $execInsert->bindValue(2, $idLivre, PDO::PARAM_INT);
                $execInsert->bindValue(3, $quantiteLivre, PDO::PARAM_INT);
                $execInsert->execute();
                return $execInsert->fetchAll();
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

        public function insertAbonnement1m($idUser) {
            $requete = 	"insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 0);";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function insertAbonnement3m($idUser) {
            $requete = 	"insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0);";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function insertAbonnement1a($idUser)
        {
            $requete = "insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 0);";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }



        /**************** UPDATE ****************/
        public function updateUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser, $idUser) {
            $requete =  "update user 
                        set nomUser = ?, 
                        prenomUser = ?, 
                        emailUser = ?, 
                        mdpUser = sha1(?), 
                        adresseUser = ? 
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $nomUser, PDO::PARAM_STR);
            $exec->BindValue(2, $prenomUser, PDO::PARAM_STR);
            $exec->BindValue(3, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(4, $mdpUser, PDO::PARAM_STR);
            $exec->BindValue(5, $adresseUser, PDO::PARAM_STR);
            $exec->BindValue(6, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

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

        public function updateCommande ($idCommande) {
            $requete =  "update commande
                        set dateCommande = curdate(),
                        statutCommande = 'expédiée', 
                        dateLivraisonCommande = DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $idCommande, PDO::PARAM_INT);
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

        public function updateLivreAbonnement($idUser) {
            $requete =  "update abonnement
                        set livreAchete = livreAchete + 1
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }


        /**************** PROCEDURE ****************/
        public function procedureInsertUser($nomUser, $prenomUser, $emailUser, $mdpUser, $adresseUser) {
            $exec = $this->unPdo->prepare("CALL pHashMdpUser(null, ?, ?, ?, ?, ?, 'client', curdate())");
            $exec->BindValue(1, $nomUser, PDO::PARAM_STR);
            $exec->BindValue(2, $prenomUser, PDO::PARAM_STR);
            $exec->BindValue(3, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(4, $mdpUser, PDO::PARAM_STR);
            $exec->BindValue(5, $adresseUser, PDO::PARAM_STR);
            $exec->execute();
        }

        public function procedureInsertLivre($idUser) {
            $exec = $this->unPdo->prepare("CALL pOffrirLivre(null, ?,)");
            $exec->BindValue(1, $idUser, PDO::PARAM_STR);
            $exec->execute();
        }
    }
?>