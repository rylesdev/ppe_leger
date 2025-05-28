<?php
	class Modele
    {
        private $unPdo;

        public function __construct()
        {
            try {
                $serveur = "localhost:8889";
                $bdd = "ppe_leger";
                $user = "root";
                $mdp = "root";
                $this->unPdo = new PDO("mysql:host=" . $serveur . ";dbname=" . $bdd, $user, $mdp);
            } catch (PDOException $exp) {
                echo "<br> Erreur de connexion à la BDD :" . $exp->getMessage();
            }
        }

        public function verifConnexion($emailUser, $mdpUser) {
            $requete = "select * 
                        from user u
                        where u.emailUser = ? and u.mdpUser = sha1(?);";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->BindValue(2, $mdpUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }



        /**************** SELECT ****************/
        public function selectParticulier($idUser) {
            $requete = "select u.emailUser, u.mdpUser, u.adresseUser, p.nomUser, p.prenomUser, p.dateNaissanceUser, p.sexeUser
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
            $requete = "select u.emailUser, u.mdpUser, u.adresseUser, e.siretUser, e.raisonSocialeUser, e.capitalSocialUser
                        from entreprise e
                        inner join user u  
                        on e.idUser = u.idUser
                        where u.idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectAdminPrincipal($idUser) {
            $requete = "select count(*) as isAdmin 
                        from admin 
                        where idUser = ? and niveauAdmin = 'principal';";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectLivre() {
            $requete = "select l.*, c.nomCategorie, m.nomMaisonEdition, p.nomPromotion  
				        from livre l
				        left join categorie c 
				        on l.idCategorie=c.idCategorie
				        left join maisonEdition m 
				        on l.idMaisonEdition=m.idMaisonEdition
				        left join promotion p
				        on l.idPromotion=p.idPromotion
				        where prixLivre != 0
				        group by l.idLivre;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectCategorie() {
            $requete = "select * from categorie;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectMaisonEdition() {
            $requete = "select * from maisonEdition;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectPromotion() {
            $requete =  "select *
                        from promotion  
                        group by idPromotion;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectIdCategorieByNom($nomCategorie) {
            $requete = "select idCategorie from categorie where nomCategorie = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $nomCategorie, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectIdMaisonEditionByNom($nomMaisonEdition) {
            $requete = "select idMaisonEdition from maisonEdition where nomMaisonEdition = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $nomMaisonEdition, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectIdPromotionByNom($nomPromotion) {
            $requete =  "select idPromotion from promotion where nomPromotion = ? 
                        order by idPromotion desc 
                        limit 1;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $nomPromotion, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectNomCategorieById($idCategorie) {
            $requete = "select nomCategorie from categorie where idCategorie = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idCategorie, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectNomMaisonEditionById($idMaisonEdition) {
            $requete = "select nomMaisonEdition from maisonEdition where idMaisonEdition = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idMaisonEdition, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectNomPromotionById($idPromotion) {
            $requete = "select nomPromotion from promotion where idPromotion = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idPromotion, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectNbLivreByPromotion($idPromotion) {
            $requete = "select count(*) as nbLivre
                        from livre
                        where idPromotion = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idPromotion, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectLivreById($idLivre) {
            $requete =  "select * from livre
                        where idLivre = ?";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idLivre, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectLikeLivre($filtre) {
            $requete = "SELECT l.*, c.nomCategorie, m.nomMaisonEdition
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
                        GROUP BY l.idLivre;";
            $exec = $this->unPdo->prepare($requete);
            $donnees = array(":filtre" => "%" . $filtre . "%");
            $exec->execute($donnees);
            return $exec->fetchAll();
        }

        public function selectLikePromotion($filtre) {
            $requete =  "SELECT p.idPromotion, p.nomPromotion, p.dateDebutPromotion, p.dateFinPromotion, p.reductionPromotion
                        FROM promotion p
                        WHERE (
                            p.nomPromotion LIKE :filtre OR
                            p.dateDebutPromotion LIKE :filtre OR
                            p.dateFinPromotion LIKE :filtre OR
                            p.reductionPromotion LIKE :filtre 
                        )
                        GROUP BY p.idPromotion, p.nomPromotion, p.dateDebutPromotion, p.dateFinPromotion, p.reductionPromotion;";
            $exec = $this->unPdo->prepare($requete);
            $donnees = array(":filtre" => "%" . $filtre . "%");
            $exec->execute($donnees);
            return $exec->fetchAll();
        }

        public function selectLikeMaisonEdition($filtre) {
            $requete = "SELECT m.idMaisonEdition, m.nomMaisonEdition
                        FROM maisonEdition m
                        WHERE (
                            m.idMaisonEdition LIKE :filtre OR
                            m.nomMaisonEdition LIKE :filtre  
                        )
                        GROUP BY m.idMaisonEdition, m.nomMaisonEdition;";
            $exec = $this->unPdo->prepare($requete);
            $donnees = array(":filtre" => "%" . $filtre . "%");
            $exec->execute($donnees);
            return $exec->fetchAll();
        }

        public function selectLikeCategorie($filtre) {
            $requete = "SELECT c.idCategorie, c.nomCategorie
                        FROM categorie c
                        WHERE (
                            c.idCategorie LIKE :filtre OR
                            c.nomCategorie LIKE :filtre  
                        )
                        GROUP BY c.idCategorie, c.nomCategorie;";
            $exec = $this->unPdo->prepare($requete);
            $donnees = array(":filtre" => "%" . $filtre . "%");
            $exec->execute($donnees);
            return $exec->fetchAll();
        }

        public function selectWhereLivre($idLivre) {
            $requete = "select * 
			            from livre l
			            inner join categorie c
			            on l.idCategorie=c.idCategorie
			            inner join maisonEdition m
			            on l.idMaisonEdition=m.idMaisonEdition
			            where idLivre = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idLivre, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectWherePromotion($idPromotion) {
            $requete =  "select p.*, l.idLivre, l.nomLivre
                        from promotion p
                        left join livre l on p.idPromotion = l.idPromotion
                        where p.idPromotion = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idPromotion, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectWhereMaisonEdition($idMaisonEdition) {
            $requete = "select m.idMaisonEdition, m.nomMaisonEdition
                        from maisonEdition m
                        left join livre l on m.idMaisonEdition=l.idMaisonEdition
                        where m.idMaisonEdition = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idMaisonEdition, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectWhereCategorie($idCategorie) {
            $requete = "select c.idCategorie, c.nomCategorie
                        from categorie c
                        left join livre l on c.idCategorie=l.idCategorie
                        where c.idCategorie = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idCategorie, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectFiltreLivreEnAttente($idUser, $filtre) {
            $filtre = "%".$filtre."%";
            $requete =  "SELECT l.idLivre, l.nomLivre, l.prixLivre, 
                        lc.idLigneCommande, lc.quantiteLigneCommande, 
                        c.idCommande, c.idUser
                        FROM livre l 
                        INNER JOIN lignecommande lc ON l.idLivre = lc.idLivre
                        INNER JOIN commande c ON lc.idCommande = c.idCommande
                        WHERE c.idUser = ? 
                        AND c.statutCommande = 'En attente'
                        AND l.nomLivre LIKE ?
                        ORDER BY l.nomLivre;";
            $select = $this->unPdo->prepare($requete);
            $select->BindValue(1, $idUser, PDO::PARAM_INT);
            $select->BindValue(2, $filtre, PDO::PARAM_STR);
            $select->execute();
            return $select->fetchAll();
        }

        public function selectAdresseUser($idUser) {
            $requete = "select adresseUser 
                        from user 
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectDateLivraisonCommande($idUser) {
            $requete =  "SELECT DATE_ADD(CURDATE(), INTERVAL 7 DAY) as dateLivraisonCommande 
                        FROM commande
                        WHERE idUser = ?
                        LIMIT 1;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectDateAbonnement($idUser) {
            $requete = "select floor(DATEDIFF(dateFinAbonnement, CURDATE())) AS jourRestant
                        from abonnement
                        where idUser = ?
                        order by idAbonnement desc
                        limit 1;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectPointAbonnement($idUser) {
            $requete = "select pointAbonnement
                        from abonnement
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetch();
        }

        public function selectNbLigneCommande($idCommande) {
            $requete = "select sum(l.quantiteLigneCommande) as nombreLigneCommande
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
            $requete =  "SELECT l.*, p.*, 
                        ROUND(l.prixLivre * (1 - p.reductionPromotion/100), 2) AS prixPromo
                        FROM livre l
                        JOIN promotion p ON l.idPromotion = p.idPromotion;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectOffrirLivre($idUser) {
            $requete = "select l.nomLivre
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

        public function selectCommandeEnAttente($idUser) {
            $requete = "select idCommande from commande 
                        where idUser = ? and statutCommande = 'en attente'
                        order by dateCommande desc limit 1;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectCommandeExpediee($idUser) {
            $requete = "select idCommande from commande 
                        where idUser = ? and statutCommande = 'expédiée'
                        order by dateCommande desc limit 1;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectCommandeByUser($idUser) {
            $requete = "SELECT * FROM commande 
                WHERE idUser = ? and statutCommande = 'expédiée'
                ORDER BY dateCommande DESC";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectCommandeByIdTri($idCommande, $tri = null) {
            try {
                $requete = "SELECT l.*, lc.quantiteLigneCommande, (l.prixLivre * lc.quantiteLigneCommande) AS totalPrix
                    FROM ligneCommande lc
                    JOIN livre l ON lc.idLivre = l.idLivre
                    WHERE lc.idCommande = ?";

                switch ($tri) {
                    case 'prixMin':
                        $requete .= " ORDER BY totalPrix ASC";
                        break;
                    case 'prixMax':
                        $requete .= " ORDER BY totalPrix DESC";
                        break;
                    case 'ordreCroissant':
                        $requete .= " ORDER BY l.nomLivre ASC";
                        break;
                    case 'ordreDecroissant':
                        $requete .= " ORDER BY l.nomLivre DESC";
                        break;
                }

                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idCommande, PDO::PARAM_INT);
                $exec->execute();

                return $exec->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log("Erreur dans selectCommandeByIdTri: " . $e->getMessage());
                return [];
            }
        }


        public function selectCommandeTri($idUser, $tri = null) {
            try {
                $requete = "SELECT l.*, lc.quantiteLigneCommande 
                    FROM ligneCommande lc
                    JOIN livre l ON lc.idLivre = l.idLivre
                    JOIN commande c ON lc.idCommande = c.idCommande
                    WHERE c.idUser = ?";

                // Ajout du tri
                switch ($tri) {
                    case 'prixMin':
                        $requete .= " ORDER BY l.prixLivre ASC";
                        break;
                    case 'prixMax':
                        $requete .= " ORDER BY l.prixLivre DESC";
                        break;
                    case 'ordreCroissant':
                        $requete .= " ORDER BY l.nomLivre ASC";
                        break;
                    case 'ordreDecroissant':
                        $requete .= " ORDER BY l.nomLivre DESC";
                        break;
                    default:
                        $requete .= " ORDER BY c.dateCommande DESC"; // Tri par défaut
                }

                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                return $exec->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log("Erreur dans selectCommandeTri: " . $e->getMessage());
                return [];
            }
        }

        public function countLigneCommande($idCommande) {
            $requete = "SELECT COUNT(*) as nbLigneCommande FROM ligneCommande WHERE idCommande = ?";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idCommande, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function selectEmail($emailUser) {
            $requete = "select * from user where emailuser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $emailUser, PDO::PARAM_STR);
            $exec->execute();
            return $exec->fetchAll();
        }



        /**************** DELETE ****************/
        public function deleteLivre($idLivre) {
            try {
                $requete =  "delete from livre 
			                where idLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idLivre, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function deletePromotion($idPromotion) {
            try {
                $requete =  "delete from promotion 
			                where idPromotion = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idPromotion, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function deleteMaisonEdition($idMaisonEdition) {
            try {
                $requete =  "delete from maisonEdition 
                            where idMaisonEdition = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idMaisonEdition, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function deleteCategorie($idCategorie) {
            try {
                $requete =  "delete from categorie 
                            where idCategorie = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idCategorie, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function deleteLigneCommande($idLigneCommande) {
            try {
                $requete =  "delete from ligneCommande 
                            where idLigneCommande = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idLigneCommande, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
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
                return false;
            }
        }



        /**************** INSERT ****************/
        public function insertPromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion) {
            try {
                $requete = "insert into promotion
                            values (null, ?, ?, ?, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $nomPromotion, PDO::PARAM_STR);
                $exec->bindValue(2, $dateDebutPromotion, PDO::PARAM_STR);
                $exec->bindValue(3, $dateFinPromotion, PDO::PARAM_STR);
                $exec->bindValue(4, $reductionPromotion, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertMaisonEdition($nomMaisonEdition) {
            try {
                $requete = "insert into maisonEdition
                            values (null, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $nomMaisonEdition, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertCategorie($nomCategorie) {
            try {
                $requete = "insert into categorie
                            values (null, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $nomCategorie, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete = "insert into user
                            values (null, ?, SHA1(?), ?, 'particulier')";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                $exec->execute();
                $idUser = $this->unPdo->lastInsertId();

                $requete = "insert into particulier
                            values (?, ?, ?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->bindValue(2, $nomUser, PDO::PARAM_STR);
                $exec->bindValue(3, $prenomUser, PDO::PARAM_STR);
                $exec->bindValue(4, $dateNaissanceUser, PDO::PARAM_STR);
                $exec->bindValue(5, $sexeUser, PDO::PARAM_STR);
                $exec->execute();

                $this->unPdo->commit();

                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                return false;
            }
        }

        public function insertEntreprise($emailUser, $mdpUser, $adresseUser, $siretUser, $raisonSocialeUser, $capitalSocialUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete = "insert into user 
                            values (null, ?, SHA1(?), ?, 'entreprise')";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $emailUser, PDO::PARAM_STR);
                $exec->bindValue(2, $mdpUser, PDO::PARAM_STR);
                $exec->bindValue(3, $adresseUser, PDO::PARAM_STR);
                $exec->execute();
                $idUser = $this->unPdo->lastInsertId();

                $requete = "insert into entreprise
                            values (?, ?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->bindValue(2, $siretUser, PDO::PARAM_STR);
                $exec->bindValue(3, $raisonSocialeUser, PDO::PARAM_STR);
                $exec->bindValue(4, $capitalSocialUser, PDO::PARAM_STR);
                $exec->execute();

                $this->unPdo->commit();

                return true;
            } catch (PDOException $exp) {
                $this->unPdo->rollBack();
                return false;
            }
        }

        public function insertCommande($idUser) {
            try {
                $this->unPdo->beginTransaction();

                $requete = "insert into commande 
                            values (null, null, 'en attente', null, ?);";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();

                $idCommande = $this->unPdo->lastInsertId();

                $this->unPdo->commit();

                return $idCommande;
            } catch (PDOException $e) {
                $this->unPdo->rollBack();
                return false;
            }
        }

        public function insertAbonnement1m($idUser) {
            try {
                $requete =  "insert into abonnement
						    values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 0);";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateAbonnement1m($idUser) {
            try {
                $requete = "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                        where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertAbonnement3m($idUser) {
            try {
                $requete = "insert into abonnement
						    values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0);";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateAbonnement3m($idUser) {
            try {
                $requete = "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                        where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertAbonnement1a($idUser) {
            try {
                $requete = "insert into abonnement
						values (null, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 0);";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateAbonnement1a($idUser) {
            try {
                $requete = "update abonnement 
                        set dateFinAbonnement = DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
                        where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateAbonnement0($idUser) {
            try {
                $requete =  "update abonnement 
                            set dateFinAbonnement = CURDATE()
                            where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function insertAvis($idLivre, $idUser, $commentaireAvis, $noteAvis) {
            try {
                $requete =  "insert into avis
                            values (null, ?, ?, ?, ?, curdate());";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idLivre, PDO::PARAM_INT);
                $exec->bindValue(2, $idUser, PDO::PARAM_INT);
                $exec->bindValue(3, $commentaireAvis, PDO::PARAM_STR);
                $exec->bindValue(4, $noteAvis, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOEXception $exp) {
                return false;
            }
        }



        /**************** UPDATE ****************/
        public function updateParticulier($emailUser, $mdpUser, $adresseUser, $nomUser, $prenomUser, $dateNaissanceUser, $sexeUser, $idUser) {
            try {
                $this->unPdo->beginTransaction();

                if ($mdpUser) {
                    $requete = "update user set
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

                $requete = "update particulier set
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
                    $requete = "update user set
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

                $requete = "update entreprise set
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

        public function updateLivre($nomLivre, $auteurLivre, $imageLivre, $prixLivre, $idCategorie, $idMaisonEdition, $idPromotion, $idLivre) {
            try {
                $requete =  "update livre 
			                set nomLivre = ?, 
			                auteurLivre = ?, 
			                imageLivre = ?, 
			                prixLivre = ?,
			                idcategorie = ?,
			                idMaisonEdition = ?,
			                idPromotion = ? 
			                where idLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $nomLivre, PDO::PARAM_STR);
                $exec->BindValue(2, $auteurLivre, PDO::PARAM_STR);
                $exec->BindValue(3, $imageLivre, PDO::PARAM_STR);
                $exec->BindValue(4, $prixLivre, PDO::PARAM_STR);
                $exec->BindValue(5, $idCategorie, PDO::PARAM_STR);
                $exec->BindValue(6, $idMaisonEdition, PDO::PARAM_STR);
                $exec->BindValue(7, $idPromotion, PDO::PARAM_STR);
                $exec->BindValue(8, $idLivre, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updatePromotion($nomPromotion, $dateDebutPromotion, $dateFinPromotion, $reductionPromotion, $idPromotion) {
            try {
                $requete =  "update promotion 
                            set nomPromotion = ?, 
                            dateDebutPromotion = ?, 
                            dateFinPromotion = ?, 
                            reductionPromotion = ?
                            where idPromotion = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $nomPromotion, PDO::PARAM_STR);
                $exec->BindValue(2, $dateDebutPromotion, PDO::PARAM_STR);
                $exec->BindValue(3, $dateFinPromotion, PDO::PARAM_STR);
                $exec->BindValue(4, $reductionPromotion, PDO::PARAM_STR);
                $exec->BindValue(5, $idPromotion, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateMaisonEdition($nomMaisonEdition, $idMaisonEdition) {
            try {
                $requete =  "update maisonEdition 
                            set nomMaisonEdition = ?
                            where idMaisonEdition = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $nomMaisonEdition, PDO::PARAM_STR);
                $exec->BindValue(2, $idMaisonEdition, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateCategorie($nomCategorie, $idCategorie) {
            try {
                $requete =  "update categorie 
                            set nomCategorie = ?
                            where idCategorie = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $nomCategorie, PDO::PARAM_STR);
                $exec->BindValue(2, $idCategorie, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updatePromotionLivre($idPromotion, $idLivre) {
            try {
                $requete =  "update livre 
                            set idPromotion = ?
                            where idLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idPromotion, PDO::PARAM_INT);
                $exec->BindValue(2, $idLivre, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateMaisonEditionLivre($idMaisonEdition, $idLivre) {
            try {
                $requete =  "update livre 
                            set idMaisonEdition = ?
                            where idLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idMaisonEdition, PDO::PARAM_INT);
                $exec->BindValue(2, $idLivre, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateCategorieLivre($idCategorie, $idLivre) {
            try {
                $requete =  "update livre 
                            set idCategorie = ?
                            where idLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idCategorie, PDO::PARAM_INT);
                $exec->BindValue(2, $idLivre, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateStockageLivre($exemplaireLivre, $nomLivre) {
            try {
                $requete = "update livre 
			            set exemplaireLivre = ? 
			            where nomLivre = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $exemplaireLivre, PDO::PARAM_INT);
                $exec->BindValue(2, $nomLivre, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateCommande($idCommande) {
            try {
                $requete =  "update commande
                            set dateCommande = curdate(),
                            statutCommande = 'expédiée', 
                            dateLivraisonCommande = DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                            where idCommande = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idCommande, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function updateLigneCommande($quantiteLigneCommande, $idLigneCommande) {
            try {
                $requete = "update ligneCommande
                        set quantiteLigneCommande = ?
                        where idLigneCommande = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $quantiteLigneCommande, PDO::PARAM_INT);
                $exec->BindValue(2, $idLigneCommande, PDO::PARAM_INT);
                $exec->execute();

                return true;

            } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                error_log("Erreur lors de l'insertion dans ligneCommande : " . $errorMessage);
                if ($e->getCode() == '45000') {
                    echo "<div class='alert alert-danger'>
                      Erreur : La quantité totale dépasse le nombre d'exemplaires disponibles pour ce livre.</div>";
                }
                return false;
            }
        }

        public function ajouterPointAbonnement($pointAbonnement, $idUser){
            try {
                $requete = "update abonnement 
                        set pointAbonnement = pointAbonnement + ?
                        where idUser = ?;";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $pointAbonnement, PDO::PARAM_INT);
                $exec->BindValue(2, $idUser, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $exp) {
                return false;
            }
        }

        public function enleverPointAbonnement($pointAbonnement, $idUser) {
            $requete = "update abonnement 
                        set pointAbonnement = pointAbonnement - ?
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $pointAbonnement, PDO::PARAM_INT);
            $exec->BindValue(2, $idUser, PDO::PARAM_INT);
            $exec->execute();
        }



        /**************** VIEW ****************/
        public function viewSelectTotalLivreEnAttente($idUser) {
            $requete = "select * 
                        from vTotalLivreEnAttente
                        where idUser = ?;";
            $exec = $this->unPdo->prepare($requete);
            $exec->bindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMinLivreEnAttente($idUser) {
            $requete = "select *
                        from vTotalLivreEnAttente
                        where idUser = ? 
                        order by totalLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNbMaxLivreEnAttente($idUser) {
            $requete = "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by totalLivre desc";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMinLivreEnAttente($idUser) {
            $requete = "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by nomLivre asc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectNomMaxLivreEnAttente($idUser) {
            $requete = "select *
                        from vTotalLivreEnAttente
                        where idUser = ?
                        order by nomLivre desc;";
            $exec = $this->unPdo->prepare($requete);
            $exec->BindValue(1, $idUser, PDO::PARAM_INT);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectCommandesEnAttente() {
            $requete = "select * 
                        from vCommandesEnAttente";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectMeilleuresVentes() {
            $requete = "select * 
                        from vMeilleuresVentes";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewSelectLivresEnStock() {
            $requete = "select * 
                        from vLivresEnStock";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewMeilleursAvis() {
            $requete = "select *
                        from vMeilleursAvis;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }

        public function viewNbLivreAcheteUser() {
            $requete = "select *
                        from vNbLivreAcheteUser;";
            $exec = $this->unPdo->prepare($requete);
            $exec->execute();
            return $exec->fetchAll();
        }



        /**************** PROCEDURE ****************/
        public function procedureInsertOrUpdateLigneCommande($idCommande, $idLivre, $quantiteLigneCommande) {
            $result = 0;
            try {
                $requete = "CALL pInsertOrUpdateLigneCommande(?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $idCommande, PDO::PARAM_INT);
                $exec->BindValue(2, $idLivre, PDO::PARAM_INT);
                $exec->BindValue(3, $quantiteLigneCommande, PDO::PARAM_INT);
                $exec->execute();
                return true;
                /*} catch (PDOException $e) {
                    $errorMessage = $e->getMessage();
                    error_log("Erreur lors de l'insertion dans ligneCommande : " . $errorMessage);
                    if ($e->getCode() == '45000') {
                        echo "<div class='alert alert-danger'>
                        Erreur : La quantité totale dépasse le nombre d'exemplaires disponibles pour ce livre.</div>";
                    }
                    return false;
                }*/
            } catch (PDOException $e) {
                if ($e->getCode() == '45000') {
                    $message = $e->getMessage();

                    if (strpos($message, 'TEXT 1') !== false) {
                        $result = 1;
                    } else if (strpos($message, 'TEXT 2') !== false) {
                        $result = 2;
                    }
                } else {
                    $result = -1;
                }
            }
            return $result;
        }

        public function procedureOffrirLivre($idUser, $chiffre) {
            try {
                $requete = "CALL pOffrirLivre(?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->bindValue(1, $idUser, PDO::PARAM_INT);
                $exec->bindValue(2, $chiffre, PDO::PARAM_INT);
                $exec->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        public function procedureInsertLivre($nomLivre, $auteurLivre, $imageLivre, $exemplaireLivre, $prixLivre, $nomCategorie, $nomMaisonEdition, $nomPromotion){
            try {
                $requete = "CALL pInsertLivre(?, ?, ?, ?, ?, ?, ?, ?)";
                $exec = $this->unPdo->prepare($requete);
                $exec->BindValue(1, $nomLivre, PDO::PARAM_STR);
                $exec->BindValue(2, $auteurLivre, PDO::PARAM_STR);
                $exec->BindValue(3, $imageLivre, PDO::PARAM_STR);
                $exec->BindValue(4, $exemplaireLivre, PDO::PARAM_STR);
                $exec->BindValue(5, $prixLivre, PDO::PARAM_STR);
                $exec->BindValue(6, $nomCategorie, PDO::PARAM_STR);
                $exec->BindValue(7, $nomMaisonEdition, PDO::PARAM_STR);
                $exec->BindValue(8, $nomPromotion, PDO::PARAM_STR);
                $exec->execute();
                return true;
            } catch(PDOException $exp) {
                return false;
            }
        }
    }
