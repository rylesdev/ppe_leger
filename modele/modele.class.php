<?php
	class Modele {
		private $unPdo ; 

		public function __construct(){
			try{
				$serveur = "localhost:8889"; 
				$bdd     = "ppe_leger";
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

        /*public function selectEmailUser($emailUser) {
            public function emailExists($emailUser) {
                $requete = "SELECT COUNT(*) as count FROM user WHERE emailUser = ?";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->execute();
                return $exec->fetchAll();

                $requeteParticulier = "SELECT COUNT(*) as count FROM particulier WHERE emailUser = ?";
                $execParticulier = $this->unPdo->prepare($requeteParticulier);
                $execParticulier->bindValue(1, $emailUser, PDO::PARAM_STR);
                $execParticulier->execute();
                return $execParticulier->fetchAll();

                $requeteEntreprise = "SELECT COUNT(*) as count FROM entreprise WHERE emailUser = ?";
                $execEntreprise = $this->unPdo->prepare($sqlEntreprise);
                $execEntreprise->bindValue(1, $emailUser, PDO::PARAM_STR);
                $execEntreprise->execute();
                return $execEntreprise->fetchAll();

                // Si l'email existe dans au moins une des tables, retourner true
                return ($exec['count'] > 0 || $execParticulier['count'] > 0 || $execEntreprise['count'] > 0);
            }
        }*/

        public function selectParticulier($idUser) {
            $requete =  "select u.emailUser, u.mdpUser, u.adresseUser, p.nomUser, p.prenomUser, p.dateNaissanceUser, p.sexeUser
                        from particulier p
                        inner join user u 
                        on p.idUser = u.idUser
                        where u.idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectEntreprise($idUser) {
            $requete =  "select u.emailUser, u.mdpUser, u.adresseUser, e.siretUser, e.raisonSocialeUser, e.capitalSocialUser
                        from entreprise e
                        inner join user u  
                        on e.idUser = u.idUser
                        where u.idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        /*public function selectEntreprise($idUser) {
            $requete =  "select * 
                        from entreprise
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }*/



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

		public function selectLikeLivres ($filtre){
			$requete =  "SELECT l.*, c.nomCategorie, m.nomMaisonEdition
                        FROM livre l   
                        INNER JOIN categorie c 
                        ON l.idCategorie = c.idCategorie
                        INNER JOIN maisonEdition m 
                        ON l.idMaisonEdition = m.idMaisonEdition
                        WHERE prixLivre != 0 
                        AND (
                            l.nomLivre LIKE :filtre OR 
                            c.nomCategorie LIKE :filtre OR 
                            l.auteurLivre LIKE :filtre OR
                            m.nomMaisonEdition LIKE :filtre 
                        )
                        GROUP BY l.idLivre, 
                                 l.nomLivre, 
                                 c.nomCategorie, 
                                 l.auteurLivre, 
                                 m.nomMaisonEdition,
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
			            from livre l
			            inner join categorie c
			            on l.idCategorie=c.idCategorie
			            inner join maisonEdition m
			            on l.idMaisonEdition=m.idMaisonEdition
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

        public function viewMeilleursAvis() {
            $requete =  "select *
                        from vMeilleursAvis;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewNbLivreAcheteUser() {
            $requete =  "select *
                        from vNbLivreAcheteUser;";
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

        public function selectLivrePromotion() {
            $requete =  "select l.*, p.reductionPromotion 
                        from livre l
                        inner join promotion p
                        on l.idPromotion=p.idPromotion;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectUnLivrePromotion($idLivre) {
            $requete =  "select prixPromotion 
                        from promotion 
                        where idLivre = ? 
                        and dateDebut <= curdate() 
                        and dateFin >= curdate()";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idLivre, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectOffrirLivre($idUser) {
            $requete =  "select l.nomLivre
                        from ligneCommande li
                        inner join commande c 
                        on li.idCommande=c.idCommande
                        inner join livre l
                        on li.idLivre=l.idLivre
                        where li.idLivre between 9 and 12 
                        and c.idUser = ? 
                        and c.dateLivraisonCommande > curdate();";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
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

        public function deleteLigneCommande($idLigneCommande) {
            $requete =  "delete from 
                        ligneCommande 
                        where idLigneCommande = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idLigneCommande, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        /*public function deleteCommande($idCommande) {
            $requete =  "delete 
                        from commande 
                        where idCommande = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue (1, $idCommande, PDO::PARAM_INT);
            $exec->execute();
        }*/

        public function archiverCommandeUtilisateur($idUser) {
            try {
                $this->unPdo->beginTransaction();

                // 1. Archiver les commandes
                $exec = $this->unPdo->prepare("
            INSERT INTO archiveCommande 
            (idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser, date_archivage)
            SELECT 
                idCommande, dateCommande, statutCommande, dateLivraisonCommande, idUser, NOW()
            FROM commande 
            WHERE idUser = :idUser
        ");
                $exec->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                $exec = $exec->execute();
                if (!$exec) {
                    throw new PDOException("Échec de l'archivage des commandes");
                }

                // 2. Archiver les lignes de commande associées
                $exec = $this->unPdo->prepare("
            INSERT INTO archiveLigneCommande
            (idLigneCommande, idCommande, idLivre, quantiteLigneCommande, date_archivage)
            SELECT 
                lc.idLigneCommande, lc.idCommande, lc.idLivre, lc.quantiteLigneCommande, NOW()
            FROM ligneCommande lc
            JOIN commande c ON lc.idCommande = c.idCommande
            WHERE c.idUser = :idUser
        ");
                $exec->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                $exec = $exec->execute();
                if (!$exec) {
                    throw new PDOException("Échec de l'archivage des lignes de commande");
                }

                // 3. Suppression dans l'ordre inverse des dépendances
                $exec = $this->unPdo->prepare("
            DELETE FROM ligneCommande 
            WHERE idCommande IN (
                SELECT idCommande FROM commande WHERE idUser = :idUser
            )
        ");
                $exec->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                $exec = $exec->execute();
                if (!$exec) {
                    throw new PDOException("Échec de la suppression des lignes de commande");
                }

                $exec = $this->unPdo->prepare("
            DELETE FROM commande WHERE idUser = :idUser
        ");
                $exec->bindValue(':idUser', $idUser, PDO::PARAM_INT);
                $exec = $exec->execute();
                if (!$exec) {
                    throw new PDOException("Échec de la suppression des commandes");
                }

                $this->unPdo->commit();
                return true;

            } catch (PDOException $e) {
                $this->unPdo->rollBack();
                error_log("Erreur archivage commandes: " . $e->getMessage());
                return false;
            }
        }

        public function deleteParticulier($idUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete = "delete from particulier where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $requete = "delete from user where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $this->unPdo->commit();
                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur suppression particulier: " . $exp->getMessage());
                return false;
            }
        }

        public function deleteEntreprise($idUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete = "delete from entreprise where idUser = ?";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $requete = "delete from user where idUser = ?";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $this->unPdo->commit();
                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur suppression entreprise: " . $exp->getMessage());
                return false;
            }
        }


		/**************** INSERT ****************/
        /*public function triggerInsertParticulier($emailUser, $mdpUser, $nomUser, $prenomUser, $adresseUser, $dateNaissanceUser, $sexeUser) {
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
        }*/

        public function insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete =  "insert into user
                            values (null, ?, SHA1(?), ?, 'particulier')";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                $exec->execute();
                $idUser = $this->unPdo->lastInsertId();

                $requete =  "insert into particulier
                            values (?, ?, ?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->bindValue(2, $nomUser, PDO::PARAM_STR);
                $exec->bindValue(3, $prenomUser, PDO::PARAM_STR);
                $exec->bindValue(4, $dateNaissanceUser, PDO::PARAM_STR);
                $exec->bindValue(5, $sexeUser, PDO::PARAM_STR);
                $exec->execute();

                $this->unPdo->commit();

                return $idUser;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur insertion particulier: " . $exp->getMessage());
                return false;
            }
        }

        /*public function triggerInsertEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
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
        }*/

        public function insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete =  "insert into user 
                            values (null, ?, SHA1(?), ?, 'entreprise')";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                $exec->execute();
                $idUser = $this->unPdo->lastInsertId();

                $requete =  "insert into entreprise
                            values (?, ?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->bindValue(2, $siretUser, PDO::PARAM_STR);
                $exec->bindValue(3, $raisonSocialeUser, PDO::PARAM_STR);
                $exec->bindValue(4, $capitalSocialUser, PDO::PARAM_STR);
                $exec->execute();

                $this->unPdo->commit();

                return $idUser;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur insertion entreprise: " . $exp->getMessage());
                return false;
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
                        echo "<h3 style='color: green;'>Livre ajouté dans le panier. </h3>";
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
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH)n 0);";
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
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 0);";
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
        public function updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser) {
            try {
                $this->unPdo->beginTransaction();

                if ($mdpUser) {
                    $requete =  "update user set
                                emailUser = ?,
                                mdpUser = SHA1(?),
                                adresseUser = ?
                                where idUser = ?";
                    $exec = $this->unPdo->prepare($requete);
                    $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                    $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                    $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                    $exec->bindValue(4, $idUser, PDO::PARAM_INT);
                    $exec->execute();
                }

                $requete =  "update particulier set
                            nomUser = ?,
                            prenomUser = ?,
                            dateNaissanceUser = ?,
                            sexeUser = ?
                            where idUser = ?";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $nomUser, PDO::PARAM_STR);
                $exec->bindValue(2, $prenomUser, PDO::PARAM_STR);
                $exec->bindValue(3, $dateNaissanceUser, PDO::PARAM_STR);
                $exec->bindValue(4, $sexeUser, PDO::PARAM_STR);
                $exec->bindValue(5, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $this->unPdo->commit();
                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur mise à jour particulier: " . $exp->getMessage());
                return false;
            }
        }

        public function updateEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
            try {
                $this->unPdo->beginTransaction();

                if ($mdpUser) {
                    $requete =  "update user set
                                emailUser = ?,
                                mdpUser = SHA1(?),
                                adresseUser = ?
                                where idUser = ?;";
                    $exec = $this->unPdo->prepare($requete);
                    $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                    $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                    $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                    $exec->bindValue(4, $idUser, PDO::PARAM_INT);
                    $exec->execute();
                }

                $requete =  "update entreprise set
                            siretUser = ?,
                            raisonSocialeUser = ?,
                            capitalSocialUser = ?
                            where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $siretUser, PDO::PARAM_STR);
                $exec->bindValue(2, $raisonSocialeUser, PDO::PARAM_STR);
                $exec->bindValue(3, $capitalSocialUser, PDO::PARAM_STR);
                $exec->bindValue(4, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $this->unPdo->commit();
                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                error_log("Erreur mise à jour entreprise: " . $exp->getMessage());
                return false;
            }
        }

        /*public function updateEntreprise($emailUser, $mdpUser, $siretUser, $raisonSocialeUser, $capitalSocialUser, $idUser) {
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
        }*/

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

        public function updateLigneCommande ($quantiteLigneCommande, $idLigneCommande) {
            $requete =  "update ligneCommande
                        set quantiteLigneCommande = ?
                        where idLigneCommande = ?;";
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $quantiteLigneCommande, PDO::PARAM_INT);
            $exec->BindValue (2, $idLigneCommande, PDO::PARAM_INT);
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

        public function enleverPointAbonnement($pointAbonnement, $idUser)
        {
            $requete = "update abonnement 
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

        public function procedureInsertOrUpdatePromotion($nomLivre, $prixPromotion, $dateFinPromotion) {
            $requete = "CALL pInsertOrUpdatePromotion(?, ?, ?)";
            $exec = $this->unPdo->prepare ($requete);
            $exec->BindValue (1, $nomLivre, PDO::PARAM_STR);
            $exec->BindValue (2, $prixPromotion, PDO::PARAM_STR);
            $exec->BindValue (3, $dateFinPromotion, PDO::PARAM_STR);
            $exec->execute ();
            return $exec->fetchAll();
        }
    }
?>