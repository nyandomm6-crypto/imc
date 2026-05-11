# TODO List des fonctions par modèle

## UtilisateurModel.php
- [ ] getAll()
- [ ] getById($id)
- [ ] getByEmail($email)
- [ ] inscrire($data)
- [ ] update($id, $data)
- [ ] delete($id)

## MesureModel.php
- [ ] getLastMesure($utilisateur_id)
- [ ] getHistorique($utilisateur_id)
- [ ] ajouterMesure($utilisateur_id, $poids, $taille)

## ImcModel.php
- [ ] calculerIMC($poids, $taille)
- [ ] getCategorie($imc)
- [ ] sauvegarderIMC($utilisateur_id, $valeur_imc)
- [ ] getHistoriqueIMC($utilisateur_id)
- [ ] getImcIdeal($genre)
- [ ] calculerPoidsIdeal($taille, $genre)

## ObjectifModel.php
- [ ] getAll()
- [ ] getObjectifsUtilisateur($utilisateur_id)
- [ ] setObjectifs($utilisateur_id, $objectif_ids, $valeurs_cibles)
- [ ] deleteObjectifsUtilisateur($utilisateur_id)

## RegimeModel.php
- [ ] getAll()
- [ ] getById($id)
- [ ] getRegimesParObjectif($objectif_id)
- [ ] getCompositionRegime($regime_id)
- [ ] create($data)
- [ ] update($id, $data)
- [ ] delete($id)
- [ ] getPrixAvecDuree($regime_id, $duree_jours)
- [ ] getPrixAvecRemise($prix, $pourcentage)
- [ ] ajouterAliment($regime_id, $aliment_id, $pourcentage)
- [ ] retirerAliment($regime_id, $aliment_id)

## AlimentModel.php
- [ ] getAll()
- [ ] getById($id)
- [ ] create($data)
- [ ] update($id, $data)
- [ ] delete($id)
- [ ] getAlimentsByCategorie($categorie)

## SportModel.php
- [ ] getAll()
- [ ] getById($id)
- [ ] create($data)
- [ ] update($id, $data)
- [ ] delete($id)
- [ ] calculerCaloriesBrulees($sport_id, $duree_heures)
- [ ] getSportsRecommandes($objectif_id, $imc)

## CodePromoModel.php
- [ ] getAll()
- [ ] getByCode($code)
- [ ] isValid($code)
- [ ] create($data)
- [ ] utiliserCode($code, $utilisateur_id)
- [ ] getMontantCode($code)
- [ ] expirer($id)
- [ ] validerCode($id)

## CompteModel.php
- [ ] getByUtilisateur($utilisateur_id)
- [ ] creerCompte($utilisateur_id)
- [ ] getSolde($utilisateur_id)
- [ ] crediter($utilisateur_id, $montant)
- [ ] debiter($utilisateur_id, $montant)
- [ ] suspendre($utilisateur_id)

## TransactionModel.php
- [ ] getByCompte($compte_id)
- [ ] ajouterTransaction($compte_id, $type, $montant, $description)
- [ ] getTotalIncome($compte_id)
- [ ] getTotalExpense($compte_id)

## AbonnementModel.php
- [ ] getOptions()
- [ ] getAbonnementActif($utilisateur_id)
- [ ] souscrire($utilisateur_id, $option_id)
- [ ] hasGold($utilisateur_id)
- [ ] createOption($data)
- [ ] updateOption($id, $data)

## OffreModel.php
- [ ] getAll()
- [ ] demanderOffre($utilisateur_id, $offre_id)
- [ ] create($data)
- [ ] delete($id)





# TODO List des fonctions par Controller

## front
## UtilisateurController.php
- [ ] index() — UtilisateurModel
- [ ] show($id) — UtilisateurModel
- [ ] store($data) — UtilisateurModel
- [ ] update($id, $data) — UtilisateurModel
- [ ] destroy($id) — UtilisateurModel
- [ ] login($email, $mot_de_passe) — UtilisateurModel

## MesureController.php
- [ ] ajouterMesure($utilisateur_id, $poids, $taille) — MesureModel
- [ ] historique($utilisateur_id) — MesureModel

## ImcController.php
- [ ] calculer($utilisateur_id) — ImcModel, MesureModel
- [ ] historique($utilisateur_id) — ImcModel

## ObjectifController.php
- [ ] index() — ObjectifModel
- [ ] setObjectifs($utilisateur_id, $objectif_ids, $valeurs_cibles) — ObjectifModel
- [ ] deleteObjectifs($utilisateur_id) — ObjectifModel

## RegimeController.php
- [ ] index() — RegimeModel
- [ ] show($id) — RegimeModel
- [ ] regimesParObjectif($objectif_id) — RegimeModel
- [ ] composition($regime_id) — RegimeModel, AlimentModel

## AlimentController.php
- [ ] index() — AlimentModel
- [ ] show($id) — AlimentModel
- [ ] alimentsParCategorie($categorie) — AlimentModel

## SportController.php
- [ ] index() — SportModel
- [ ] show($id) — SportModel
- [ ] sportsRecommandes($objectif_id, $imc) — SportModel

## CodePromoController.php
- [ ] index() — CodePromoModel
- [ ] utiliser($code, $utilisateur_id) — CodePromoModel

## CompteController.php
- [ ] show($utilisateur_id) — CompteModel
- [ ] crediter($utilisateur_id, $montant) — CompteModel
- [ ] debiter($utilisateur_id, $montant) — CompteModel

## TransactionController.php
- [ ] historique($compte_id) — TransactionModel

## AbonnementController.php
- [ ] options() — AbonnementModel
- [ ] souscrire($utilisateur_id, $option_id) — AbonnementModel

## OffreController.php
- [ ] index() — OffreModel
- [ ] demander($utilisateur_id, $offre_id) — OffreModel


## back 
### AuthController.php
- [ ] login($email, $mot_de_passe) — UtilisateurModel
- [ ] logout() — UtilisateurModel

### AdminController.php
- [ ] dashboard() — UtilisateurModel, CompteModel, TransactionModel
- [ ] manageUsers() — UtilisateurModel
- [ ] manageRegimes() — RegimeModel
- [ ] manageOffres() — OffreModel
- [ ] manageCodesPromo() — CodePromoModel

### DashboardController.php
- [ ] index() — UtilisateurModel, ImcModel, CompteModel

### StatistiqueController.php
- [ ] imcStats() — ImcModel
- [ ] financeStats() — CompteModel, TransactionModel

### NotificationController.php
- [ ] sendNotification($utilisateur_id, $message) — UtilisateurModel
- [ ] getNotifications($utilisateur_id) — UtilisateurModel

