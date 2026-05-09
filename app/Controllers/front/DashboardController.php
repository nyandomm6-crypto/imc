<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\CompteModel;
use App\Models\ImcModel;
use App\Models\MesureModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\TransactionModel;
use App\Models\UtilisateurModel;

class DashboardController extends BaseController
{
	private UtilisateurModel $utilisateurModel;
	private MesureModel $mesureModel;
	private ImcModel $imcModel;
	private ObjectifModel $objectifModel;
	private RegimeModel $regimeModel;
	private SportModel $sportModel;
	private CompteModel $compteModel;
	private TransactionModel $transactionModel;

	public function __construct()
	{
		$this->utilisateurModel = new UtilisateurModel();
		$this->mesureModel = new MesureModel();
		$this->imcModel = new ImcModel();
		$this->objectifModel = new ObjectifModel();
		$this->regimeModel = new RegimeModel();
		$this->sportModel = new SportModel();
		$this->compteModel = new CompteModel();
		$this->transactionModel = new TransactionModel();
	}

	public function index()
	{
		$utilisateurId = $this->getUtilisateurId();

		if ($utilisateurId === null) {
			return redirect()->to('/');
		}
		$data = $this->buildDashboardData($utilisateurId);

		return view('front/dashboard/index', $data);
	}

	private function getUtilisateurId(): ?int
	{
		$session = session();

		foreach (['utilisateur_id', 'user_id', 'id'] as $key) {
			$value = $session->get($key);

			if (is_numeric($value) && (int) $value > 0) {
				return (int) $value;
			}
		}

		return null;
	}

	private function buildDashboardData(int $utilisateurId): array
	{
		$utilisateur = $this->utilisateurModel->getById($utilisateurId);

		if ($utilisateur === null) {
			return $this->defaultData();
		}

		$mesure = $this->mesureModel->getLastMesure($utilisateurId);
		$imc = null;
		$categorieImc = 'Inconnue';
		$imcProgression = 0;

		if ($mesure !== null) {
			$poids = (float) ($mesure['poids_kg'] ?? 0);
			$taille = (float) ($mesure['taille_m'] ?? 0);
			$imc = $this->imcModel->calculerIMC($poids, $taille);
			$categorie = $this->imcModel->getCategorie($imc);
			if ($categorie !== null) {
				$categorieImc = $categorie;
			} else {
				$categorieImc = 'Inconnue';
			}
			$imcProgression = $this->calculateImcProgression($imc);
		}

		$objectifs = $this->objectifModel->getObjectifsUtilisateur($utilisateurId);
		$objectifId = null;
		if (!empty($objectifs) && isset($objectifs[0]['objectif_id'])) {
			$objectifId = (int) $objectifs[0]['objectif_id'];
		}

		$regimes = [];
		if ($objectifId !== null) {
			$listeRegimes = $this->regimeModel->getRegimesParObjectif($objectifId);
		} else {
			$listeRegimes = $this->regimeModel->getAll();
		}

		$compteurRegimes = 0;
		foreach ($listeRegimes as $regime) {
			if ($compteurRegimes >= 3) {
				break;
			}

			$regimes[] = $regime;
			$compteurRegimes++;
		}

		$sports = $this->sportModel->getSportsRecommandes($objectifId, $imc);
		$compte = $this->compteModel->getByUtilisateur($utilisateurId);
		$statutCompte = 'inactive';
		$transactions = [];
		if ($compte !== null && isset($compte['status'])) {
			$statutCompte = $compte['status'];
		}
		if ($compte !== null && isset($compte['id'])) {
			$transactions = $this->transactionModel->getLatestByCompte((int) $compte['id'], 5);
		}

		return [
			'utilisateur' => $utilisateur,
			'imc' => $imc,
			'categorieImc' => $categorieImc,
			'imcProgression' => $imcProgression,
			'mesure' => $mesure,
			'objectifs' => $objectifs,
			'regimes' => $regimes,
			'sports' => $sports,
			'soldeCompte' => $this->compteModel->getSolde($utilisateurId),
			'compteStatut' => $statutCompte,
			'transactions' => $transactions,
		];
	}

	private function calculateImcProgression(float $imc): int
	{
		if ($imc <= 0) {
			return 0;
		}

		return (int) max(0, min(100, round((($imc - 12) / 28) * 100)));
	}

	private function defaultData(): array
	{
		return [
			'utilisateur' => [
				'nom' => 'Utilisateur',
				'email' => '',
			],
			'imc' => null,
			'categorieImc' => 'Inconnue',
			'imcProgression' => 0,
			'mesure' => null,
			'objectifs' => [],
			'regimes' => [],
			'sports' => [],
			'soldeCompte' => 0.0,
			'compteStatut' => 'inactive',
			'transactions' => [],
		];
	}
}
