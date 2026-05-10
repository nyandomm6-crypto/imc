<?php

namespace App\Libraries;

use App\Models\RegimeModel;
use App\Models\SportModel;

class SuggesterOffre
{
	private RegimeModel $regimeModel;
	private SportModel $sportModel;

	public function __construct()
	{
		$this->regimeModel = new RegimeModel();
		$this->sportModel = new SportModel();
	}

	/**
	 * Suggère un régime selon l'objectif
	 */
	public function suggerRegime(int $objectif_id): ?array
	{
		// Récupérer l'objectif
		$objectif = db_connect()->table('objectifs')
			->where('id', $objectif_id)
			->get()
			->getRow();

		if (!$objectif) {
			return null;
		}

		$libelle = strtolower($objectif->libelle ?? '');

		// Déterminer le régime selon l'objectif
		if (strpos($libelle, 'prise') !== false) {
			// Prise de masse → régime riche en calories
			return $this->regimeModel->orderBy('libelle', 'ASC')->get()->getRow();
		} elseif (strpos($libelle, 'perte') !== false) {
			// Perte de poids → premier régime
			return $this->regimeModel->orderBy('libelle', 'ASC')->get()->getRow();
		} else {
			// Maintien → régime équilibré
			return $this->regimeModel->orderBy('libelle', 'ASC')->get()->getRow();
		}
	}

	/**
	 * Suggère un sport selon l'objectif et l'IMC
	 */
	public function suggerSport(int $objectif_id, ?float $imc = null): ?array
	{
		// Récupérer l'objectif
		$objectif = db_connect()->table('objectifs')
			->where('id', $objectif_id)
			->get()
			->getRow();

		if (!$objectif) {
			return null;
		}

		$libelle = strtolower($objectif->libelle ?? '');

		// Déterminer le sport selon l'objectif et l'IMC
		if (strpos($libelle, 'prise') !== false) {
			// Prise de masse → activités de renforcement
			$result = $this->sportModel
				->where('nom', 'like', '%musculation%')
				->orWhere('nom', 'like', '%renforcement%')
				->get()
				->getRow();
			return $result ?? $this->sportModel->get()->getRow();
		} elseif (strpos($libelle, 'perte') !== false) {
			// Perte de poids → cardio (+ activités selon IMC)
			if ($imc !== null && $imc > 30) {
				// Si obèse → activités douces
				$result = $this->sportModel
					->where('nom', 'like', '%marche%')
					->orWhere('nom', 'like', '%natation%')
					->get()
					->getRow();
				return $result ?? $this->sportModel->get()->getRow();
			}
			// Sinon → cardio classique
			return $this->sportModel->get()->getRow();
		} else {
			// Maintien → activité mixte
			return $this->sportModel->get()->getRow();
		}
	}

	/**
	 * Suggère régime + sport selon l'objectif
	 */
	public function suggerRegimeEtSport(int $objectif_id, ?float $imc = null): array
	{
		return [
			'regime' => $this->suggerRegime($objectif_id),
			'sport' => $this->suggerSport($objectif_id, $imc),
		];
	}
}
