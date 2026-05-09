<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
	protected $table = 'objectifs';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = [
		'libelle',
	];

	public function getAll(): array
	{
		return $this->orderBy('libelle', 'ASC')->findAll();
	}

	public function getObjectifsUtilisateur(int $utilisateur_id): array
	{
		return $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.utilisateur_id', $utilisateur_id)
			->orderBy('o.libelle', 'ASC')
			->get()
			->getResultArray();
	}

	public function setObjectifs(int $utilisateur_id, array $objectif_ids, array $valeurs_cibles = []): bool
	{
		$objectif_ids = array_values(array_filter($objectif_ids, static function ($value) {
			return $value !== null && $value !== '';
		}));

		if (count($objectif_ids) === 0 || count($objectif_ids) > 3) {
			return false;
		}

		$this->db->transStart();

		$this->db->table('utilisateur_objectifs')
			->where('utilisateur_id', $utilisateur_id)
			->delete();

		$rows = [];
		foreach ($objectif_ids as $index => $objectif_id) {
			$rows[] = [
				'utilisateur_id' => $utilisateur_id,
				'objectif_id' => (int) $objectif_id,
				'valeur_cible' => $valeurs_cibles[$index] ?? null,
			];
		}

		if ($rows !== []) {
			$this->db->table('utilisateur_objectifs')->insertBatch($rows);
		}

		$this->db->transComplete();

		return $this->db->transStatus();
	}

	public function deleteObjectifsUtilisateur(int $utilisateur_id): bool
	{
		return (bool) $this->db->table('utilisateur_objectifs')
			->where('utilisateur_id', $utilisateur_id)
			->delete();
	}

	public function repartitionObjectifs()
	{
		return $this->select('objectifs.libelle, COUNT(utilisateur_objectifs.id) as total')
			->join('utilisateur_objectifs', 'utilisateur_objectifs.objectif_id = objectifs.id')
			->groupBy('objectifs.libelle')
			->findAll();
	}
}

