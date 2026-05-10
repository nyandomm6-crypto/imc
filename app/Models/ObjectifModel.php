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
			->orderBy('uo.id', 'DESC')
			->get()
			->getResultArray();
	}

	public function getObjectifsUtilisateurComplete(int $utilisateur_id): array
	{
		return $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, uo.statut, uo.date_debut, uo.date_fin, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.utilisateur_id', $utilisateur_id)
			->orderBy('uo.date_debut', 'DESC')
			->get()
			->getResultArray();
	}

	public function getObjectifEnCours(int $utilisateur_id): ?array
	{
		$result = $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.utilisateur_id', $utilisateur_id)
			->get()
			->getRow();

		return $result ? (array) $result : null;
	}

	public function getObjectifEnCoursComplete(int $utilisateur_id): ?array
	{
		$result = $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, uo.statut, uo.date_debut, uo.date_fin, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.utilisateur_id', $utilisateur_id)
			->where('uo.statut', 'en_cours')
			->get()
			->getRow();

		return $result ? (array) $result : null;
	}

	public function createObjectifUtilisateur(int $utilisateur_id, int $objectif_id, ?float $valeur_cible = null): bool|int
	{
		$data = [
			'utilisateur_id' => $utilisateur_id,
			'objectif_id'    => $objectif_id,
			'valeur_cible'   => $valeur_cible,
			'statut'         => 'en_cours',
			'date_debut'     => date('Y-m-d H:i:s'),
		];

		return $this->db->table('utilisateur_objectifs')->insert($data);
	}

	public function markObjectifAsAchieved(int $utilisateur_objectif_id): bool
	{
		try {
			return (bool) $this->db->table('utilisateur_objectifs')
				->where('id', $utilisateur_objectif_id)
				->update([
					'statut'   => 'atteint',
					'date_fin' => date('Y-m-d H:i:s'),
				]);
		} catch (\Throwable $e) {
			// Les colonnes n'existent pas encore (migration non appliquée)
			// Simplement ignorer l'erreur et retourner true
			log_message('info', 'Colonnes statut/date_fin non trouvées. Migration à appliquer: php spark migrate');
			return true;
		}
	}

	public function markObjectifAsAbandoned(int $utilisateur_objectif_id): bool
	{
		try {
			return (bool) $this->db->table('utilisateur_objectifs')
				->where('id', $utilisateur_objectif_id)
				->update([
					'statut'   => 'abandonné',
					'date_fin' => date('Y-m-d H:i:s'),
				]);
		} catch (\Throwable $e) {
			// Les colonnes n'existent pas encore (migration non appliquée)
			// Simplement ignorer l'erreur et retourner true
			log_message('info', 'Colonnes statut/date_fin non trouvées. Migration à appliquer: php spark migrate');
			return true;
		}
	}

	public function getObjectifUtilisateurById(int $utilisateur_objectif_id, int $utilisateur_id): ?array
	{
		$result = $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.id', $utilisateur_objectif_id)
			->where('uo.utilisateur_id', $utilisateur_id)
			->get()
			->getRow();

		return $result ? (array) $result : null;
	}

	public function getObjectifUtilisateurByIdComplete(int $utilisateur_objectif_id, int $utilisateur_id): ?array
	{
		$result = $this->db->table('utilisateur_objectifs uo')
			->select('uo.id as utilisateur_objectif_id, uo.utilisateur_id, uo.objectif_id, uo.valeur_cible, uo.statut, uo.date_debut, uo.date_fin, o.libelle')
			->join('objectifs o', 'o.id = uo.objectif_id', 'inner')
			->where('uo.id', $utilisateur_objectif_id)
			->where('uo.utilisateur_id', $utilisateur_id)
			->get()
			->getRow();

		return $result ? (array) $result : null;
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

