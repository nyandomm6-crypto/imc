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
			$column = $this->hasColumn('objectif_id') ? 'objectif_id' : 'objectifs_id';

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
				$pourcentage = (float) ($row['pourcentage'] ?? 0);
				$composition['total_pourcentage'] += $pourcentage;

				$categorie = strtolower((string) ($row[$categoryColumn] ?? ''));
				if (in_array($categorie, $categories, true)) {
					$composition[$categorie] += $pourcentage;
				}
			}
		} else {
			foreach ($rows as $row) {
				$composition['total_pourcentage'] += (float) ($row['pourcentage'] ?? 0);
			}
		}

		return $composition;
	}

	public function create($data)
	{
		return $this->insert($data);
	}

	public function update($id = null, $row = null): bool
	{
		return parent::update($id, $row);
	}

	public function delete($id = null, bool $purge = false): bool
	{
		return parent::delete($id, $purge);
	}

	public function getPrixAvecDuree($regime_id, $duree_jours): float
	{
		$basePrice = 0.0;

		foreach (['prix_jour', 'prix', 'tarif_jour', 'tarif'] as $column) {
			if ($this->hasColumn($column)) {
				$row = $this->asArray()->select($column)->find($regime_id);
				$basePrice = (float) ($row[$column] ?? 0);
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

