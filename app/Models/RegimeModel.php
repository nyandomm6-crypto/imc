<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
	protected $table = 'regimes';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = [
		'libelle',
	];

	public function getAll(): array
	{
		return $this->orderBy('libelle', 'ASC')->findAll();
	}

	public function getById($id)
	{
		$regime = $this->asArray()->find($id);

		if (! $regime) {
			return null;
		}

		$regime['aliments'] = $this->db->table('recettes r')
			->select('r.aliment_id, r.pourcentage, a.nom, a.calories_100g, a.proteines_100g, a.glucides_100g, a.lipides_100g')
			->join('aliments a', 'a.id = r.aliment_id', 'inner')
			->where('r.regime_id', $id)
			->orderBy('a.nom', 'ASC')
			->get()
			->getResultArray();

		return $regime;
	}

	public function getRegimesParObjectif($objectif_id): array
	{
		if ($this->hasColumn('objectifs_id') || $this->hasColumn('objectif_id')) {
			if ($this->hasColumn('objectif_id')) {
				$column = 'objectif_id';
			} else {
				$column = 'objectifs_id';
			}

			return $this->asArray()
				->where($column, $objectif_id)
				->orderBy('libelle', 'ASC')
				->findAll();
		}

		return $this->getAll();
	}

	public function getCompositionRegime($regime_id): array
	{
		$rows = $this->db->table('recettes r')
			->select('r.pourcentage, a.*')
			->join('aliments a', 'a.id = r.aliment_id', 'inner')
			->where('r.regime_id', $regime_id)
			->get()
			->getResultArray();

		$composition = [
			'total_pourcentage' => 0,
			'detail' => $rows,
		];

		$categories = ['viande', 'poisson', 'volaille'];
		$categoryColumns = $this->getAvailableColumns('aliments');
		$categoryColumn = null;

		foreach (['categorie', 'type', 'categorie_aliment'] as $candidate) {
			if (in_array($candidate, $categoryColumns, true)) {
				$categoryColumn = $candidate;
				break;
			}
		}

		if ($categoryColumn !== null) {
			$composition = array_fill_keys($categories, 0);
			$composition['total_pourcentage'] = 0;

			foreach ($rows as $row) {
				if (isset($row['pourcentage'])) {
					$pourcentage = (float) $row['pourcentage'];
				} else {
					$pourcentage = 0.0;
				}
				$composition['total_pourcentage'] += $pourcentage;

				if (isset($row[$categoryColumn])) {
					$categorie = strtolower((string) $row[$categoryColumn]);
				} else {
					$categorie = '';
				}
				if (in_array($categorie, $categories, true)) {
					$composition[$categorie] += $pourcentage;
				}
			}
		} else {
			foreach ($rows as $row) {
				if (isset($row['pourcentage'])) {
					$composition['total_pourcentage'] += (float) $row['pourcentage'];
				} else {
					$composition['total_pourcentage'] += 0.0;
				}
			}
		}

		return $composition;
	}

	public function create($data)
	{
		return $this->insert($data);
	}

	public function update($id, $data)
	{
		return parent::update($id, $data);
	}

	public function delete($id)
	{
		return parent::delete($id);
	}

	public function getPrixAvecDuree($regime_id, $duree_jours): float
	{
		$basePrice = 0.0;

		foreach (['prix_jour', 'prix', 'tarif_jour', 'tarif'] as $column) {
			if ($this->hasColumn($column)) {
				$row = $this->asArray()->select($column)->find($regime_id);
				if (isset($row[$column])) {
					$basePrice = (float) $row[$column];
				} else {
					$basePrice = 0.0;
				}
				break;
			}
		}

		return round($basePrice * max(1, (int) $duree_jours), 2);
	}

	public function getPrixAvecRemise($prix, $pourcentage): float
	{
		$prix = (float) $prix;
		$pourcentage = (float) $pourcentage;

		return round($prix - ($prix * $pourcentage / 100), 2);
	}

	public function suggestRegimeForUser(?int $genre_id, float $poids_kg, ?int $objectif_id, int $duree_jours = 30): array
	{
		$duree = max(1, (int) $duree_jours);

		$liste = [];
		if ($objectif_id !== null) {
			$liste = $this->getRegimesParObjectif($objectif_id);
		}

		if (empty($liste)) {
			$liste = $this->getAll();
		}

		$selected = null;
		$prix = 0.0;
		$usedEstimate = false;

		// Try to find a regime with an explicit price column
		foreach ($liste as $regime) {
			$p = $this->getPrixAvecDuree($regime['id'], $duree);
			if ($p > 0) {
				$selected = $regime;
				$prix = $p;
				break;
			}
		}

		if ($prix <= 0) {
			$usedEstimate = true;

			// Determine base rate by objectif label
			$baseRate = 2.0; // par jour par défaut
			if ($objectif_id !== null) {
				$row = $this->db->table('objectifs')->select('libelle')->where('id', $objectif_id)->get()->getRowArray();
				$libelle = isset($row['libelle']) ? strtolower($row['libelle']) : '';
				if (strpos($libelle, 'perte') !== false) {
					$baseRate = 2.0;
				} elseif (strpos($libelle, 'prise') !== false) {
					$baseRate = 2.5;
				} elseif (strpos($libelle, 'maint') !== false) {
					$baseRate = 1.5;
				}
			}

			$poidsRef = 70.0;
			$poidsDiff = max(-30, min(50, $poids_kg - $poidsRef));
			$poidsMultiplier = 1 + ($poidsDiff / 100.0); // entre ~0.7 et 1.5

			$genreMultiplier = 1.0;
			if ($genre_id !== null) {
				$g = $this->db->table('genres')->select('nom')->where('id', $genre_id)->get()->getRowArray();
				$genreNom = isset($g['nom']) ? strtolower($g['nom']) : '';
				if ($genreNom === 'homme') {
					$genreMultiplier = 1.05;
				}
			}

			$prix = round($baseRate * max(0.5, $poidsMultiplier) * $genreMultiplier * $duree, 2);

			if (isset($liste[0])) {
				$selected = $liste[0];
			} else {
				$selected = null;
			}
		}

		if ($usedEstimate) {
			$raison = 'Prix estimé (pas de tarif explicite). Calcul basé sur objectif, poids et genre.';
		} else {
			$raison = 'Prix issu du tarif du régime pour la durée donnée.';
		}

		return [
			'regime' => $selected,
			'prix' => (float) $prix,
			'estimation' => (bool) $usedEstimate,
			'raison' => $raison,
		];
	}

	public function ajouterAliment($regime_id, $aliment_id, $pourcentage)
	{
		return $this->db->table('recettes')->insert([
			'regime_id' => $regime_id,
			'aliment_id' => $aliment_id,
			'pourcentage' => $pourcentage,
		]);
	}

	public function retirerAliment($regime_id, $aliment_id)
	{
		return $this->db->table('recettes')
			->where('regime_id', $regime_id)
			->where('aliment_id', $aliment_id)
			->delete();
	}

	private function hasColumn(string $column): bool
	{
		return in_array($column, $this->getAvailableColumns($this->table), true);
	}

	private function getAvailableColumns(string $table): array
	{
		$columns = [];

		$rows = $this->db->query(
			'SELECT column_name FROM information_schema.columns WHERE table_schema = CURRENT_SCHEMA() AND table_name = ?',
			[$table]
		)->getResultArray();

		foreach ($rows as $row) {
			$columns[] = $row['column_name'];
		}

		return $columns;
	}
}

