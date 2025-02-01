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
                        from user u
                        where u.emailUser = ? and u.mdpUser = sha1(?);";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(2, $mdpUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectParticulier($idUser) {
            $requete =  "select * 
                        from particulier
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectEntreprise($idUser) {
            $requete =  "select * 
                        from entreprise
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
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

        public function selectAdminPrincipal($idUser) {
            $requete =  "select count(*) as isAdmin 
                        from admin 
                        where idUser = ? and niveauAdmin = 'principal';";
            $exec =$this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

		public function selectAllLivres (){
			$requete =  "select l.*, m.nomMaisonEdition, c.nomCategorie
				        from livre l
				        inner join categorie c 
				        on l.idCategorie=c.idCategorie
				        inner join maisonEdition m 
				        on l.idMaisonEdition=m.idMaisonEdition
				        where prixLivre != 0;";
			$exec = $this->unPdo->prepare ($requete);
			$exec->execute ();
			return $exec->fetchAll();
		}

        // La méthode "selectLikeLivres" ne marche pas.
		public function selectLikeLivres ($filtre){
			$requete =  "select l.*, c.nomCategorie
						from livre l   
						inner join categorie c 
						on l.idCategorie=c.idCategorie
						where prixLivre !=  and l.idLivre like :filtre or 
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
                            where idUser = ? and statutCommande = 'en attente';";
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

        public function viewSelectTotalCommandeEnAttente($idUser) {
            $requete =  "select *
                        from vTotalCommandeEnAttente
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function viewSelectTotalCommandeEnAttentePoint($idUser) {
            $requete =  "select idUser, totalCommande * 10 as totalCommandeMultiplie
                        from vTotalCommandeEnAttente
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function viewSelectTotalCommandeExpediee($idUser) {
            $requete =  "select * 
                        from vTotalCommandeExpediee   
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function viewSelectTotalLivreEnAttente($idUser) {
            $requete =  "select * 
                        from vTotalLivreEnAttente
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectTotalLivreExpediee($idUser) {
            $requete =  "select * 
                        from vTotalLivreExpediee
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMinLivreEnAttente($idUser) {
            $requete =  "select *
                        from vTotalLivreEnAttente
                        where idUser = ? 
                        order by totalLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMaxLivreEnAttente($idUser) {
            $requete =  "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by totalLivre desc";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMinLivreEnAttente($idUser) {
            $requete =  "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by nomLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMaxLivreEnAttente($idUser) {
            $requete =  "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by nomLivre desc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMinLivreExpediee($idUser) {
            $requete =  "select *
                        from vTotalLivreExpediee
                        where idUser = ? 
                        order by totalLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMaxLivreExpediee($idUser) {
            $requete =  "select *
                        from vTotalLivreExpediee
                        where idUser = ?
                        order by totalLivre desc";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMinLivreExpediee($idUser) {
            $requete =  "select *
                        from vTotalLivreExpediee
                        where idUser = ?
                        order by nomLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMaxLivreExpediee($idUser) {
            $requete =  "select *
                        from vTotalLivreExpediee
                        where idUser = ?
                        order by nomLivre desc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectCommandesEnAttente() {
            $requete =  "select * 
                        from vCommandesEnAttente";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectMeilleuresVentes() {
            $requete =  "select * 
                        from vMeilleuresVentes";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectLivresEnStock() {
            $requete =  "select * 
                        from vLivresEnStock";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectDateAbonnement($idUser) {
            $requete =  "select floor(DATEDIFF(dateFinAbonnement, CURDATE())) AS jourRestant
                        from abonnement
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectPointAbonnement($idUser) {
            $requete =  "select pointAbonnement
                        from abonnement
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectDateLigneCommande($idUser) {
            $requete = "select li.idLigneCommande, floor(DATEDIFF(c.dateLivraisonCommande, CURDATE())) AS jourRestant
                        from ligneCommande li
                        inner join commande c 
                        on li.idCommande=c.idCommande
                        where c.idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectNbLigneCommande($idCommande) {
            $requete =  "select sum(l.quantiteLigneCommande) as nombreLigneCommande
                        from ligneCommande l
                        where l.idCommande = ? and l.idCommande in(
                            select c.idCommande
                            from commande c
                            where c.statutCommande = 'expédiée');";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idCommande, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
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
        public function triggerInsertParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser) {
            try {
                $requete =  "insert into particulier (idUser, emailUser, mdpUser, nomUser, prenomUser, adresseUser, dateNaissanceUser, sexeUser)
                            values (null, ?, sha1(?), ?, ?, ?, ?, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $nomUser, PDO::PARAM_STR);
                $exec->bindValue(4, $prenomUser, PDO::PARAM_STR);
                $exec->bindValue(5, $adresseUser, PDO::PARAM_STR);
                $exec->bindValue(6, $dateNaissanceUser, PDO::PARAM_STR);
                $exec->bindValue(7, $sexeUser, PDO::PARAM_STR);
                $exec->execute();

                return $this->unPdo->lastInsertId();
            } catch (PDOException $exp) {
                echo $exp->getMessage();
            }
        }


        public function triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
            try {
                $requete =  "insert into entreprise (idUser, emailUser, mdpUser, siretUser, raisonSocialeUser, capitalSocialUser)
                            values (null, ?, sha1(?), ?, ?, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $siretUser, PDO::PARAM_STR);
                $exec->bindValue(4, $raisonSocialeUser, PDO::PARAM_STR);
                $exec->bindValue(5, $capitalSocialUser, PDO::PARAM_STR);
                $exec->execute();

                return $this->unPdo->lastInsertId();
            } catch (PDOException $exp) {
                echo $exp->getMessage();
            }
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
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH));";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function updateAbonnement1m($idUser) {
            $requete =  "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function insertAbonnement3m($idUser) {
            $requete = 	"insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH));";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function updateAbonnement3m($idUser) {
            $requete =  "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function insertAbonnement1a($idUser)
        {
            $requete = "insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR));";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function updateAbonnement1a($idUser) {
            $requete =  "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function insertAvis($idLivre, $nomLivre, $idUser, $commentaireAvis, $noteAvis) {
            $requete =  "insert into avis
                        values (null, ?, ?, ?, ?, ?, curdate());";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idLivre, PDO::PARAM_INT);
            $exec->bindValue(2, $nomLivre, PDO::PARAM_STR);
            $exec->bindValue(3, $idUser, PDO::PARAM_INT);
            $exec->bindValue(4, $commentaireAvis, PDO::PARAM_STR);
            $exec->bindValue(5, $noteAvis, PDO::PARAM_INT);
            $exec->execute();
        }


        /**************** UPDATE ****************/
        public function updateParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser, $idUser) {
            $requete =  "update particulier 
                        set emailUser = ?, 
                        mdpUser = sha1(?), 
                        nomUser = ?, 
                        prenomUser = ?,
                        adresseUser = ?,
                        dateNaissanceUser = ?,
                        sexeUser = ? 
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(2, $mdpUser, PDO::PARAM_STR);
            $exec->BindValue(3, $nomUser, PDO::PARAM_STR);
            $exec->BindValue(4, $prenomUser, PDO::PARAM_STR);
            $exec->BindValue(5, $adresseUser, PDO::PARAM_STR);
            $exec->BindValue(6, $dateNaissanceUser, PDO::PARAM_STR);
            $exec->BindValue(7, $sexeUser, PDO::PARAM_STR);
            $exec->BindValue(8, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }

        public function updateEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
            $requete =  "update entreprise 
                        set emailUser = ?, 
                        mdpUser = sha1(?), 
                        siretUser = ?, 
                        raisonSocialeUser = ?,
                        capitalSocialUser = ?
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(2, $mdpUser, PDO::PARAM_STR);
            $exec->BindValue(3, $siretUser, PDO::PARAM_STR);
            $exec->BindValue(4, $raisonSocialeUser, PDO::PARAM_STR);
            $exec->BindValue(5, $capitalSocialUser, PDO::PARAM_STR);
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
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idCommande, PDO::PARAM_INT);
                $exec->execute();
            return $exec->fetchAll();
        }

        public function updateLigneCommande ($quantiteLigneCommande, $idCommande) {
            $requete =  "update ligneCommande
                        set quantiteLigneCommande = ?
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $quantiteLigneCommande, PDO::PARAM_INT);
            $exec->BindValue (2, $idCommande, PDO::PARAM_INT);
            $exec->execute();
            return $this->unPdo->lastInsertId();
        }

        public function ajouterPointAbonnement($pointAbonnement, $idUser) {
            $requete =  "update abonnement 
                        set pointAbonnement = pointAbonnement + ?
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $pointAbonnement, PDO::PARAM_INT);
            $exec->BindValue(2, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function enleverPointAbonnement($pointAbonnement, $idUser) {
            $requete =  "update abonnement 
                        set pointAbonnement = pointAbonnement - ?
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $pointAbonnement, PDO::PARAM_INT);
            $exec->BindValue(2, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }



        /**************** PROCEDURE ****************/
        public function procedureOffrirLivre($idUser, $chiffre) {
            $requete = "CALL pOffrirLivre(?, ?)";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->BindValue(2, $chiffre, PDO::PARAM_INT);
            $exec->execute();
        }

        public function procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition) {
            $requete =  "CALL pInsertLivre(?, ?, ?, ?, ?, ?, ?)";
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $nomLivre, PDO::PARAM_STR);
            $exec->BindValue (2, $auteurLivre, PDO::PARAM_STR);
            $exec->BindValue (3, $imageLivre, PDO::PARAM_STR);
            $exec->BindValue (4, $exemplaireLivre, PDO::PARAM_STR);
            $exec->BindValue (5, $prixLivre, PDO::PARAM_STR);
            $exec->BindValue (6, $nomCategorie, PDO::PARAM_STR);
            $exec->BindValue (7, $nomMaisonEdition, PDO::PARAM_STR);
            $exec->execute ();
            return $exec->fetchAll();
        }
    }
?>