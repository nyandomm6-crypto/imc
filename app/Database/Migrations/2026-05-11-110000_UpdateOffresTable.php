<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOffresTable extends Migration
{
	public function up()
	{
		// Ajouter les colonnes à la table offres
		$this->forge->addColumn('offres', [
			'type' => [
				'type'       => 'VARCHAR',
				'constraint' => 20,
				'default'    => 'regime',
				'comment'    => 'regime, sport, regime_sport',
			],
			'description' => [
				'type' => 'TEXT',
				'null' => true,
			],
			'prix_gold' => [
				'type'       => 'NUMERIC',
				'constraint' => '10,2',
				'default'    => 0,
			],
			'date_creation' => [
				'type'    => 'TIMESTAMP',
				'default' => 'CURRENT_TIMESTAMP',
			],
		]);

		// Ajouter les colonnes à la table demandes_offres
		$this->forge->addColumn('demandes_offres', [
			'regime_id' => [
				'type'       => 'INT',
				'null'       => true,
				'constraint' => 20,
			],
			'sport_id' => [
				'type'       => 'INT',
				'null'       => true,
				'constraint' => 20,
			],
			'statut' => [
				'type'       => 'VARCHAR',
				'constraint' => 20,
				'default'    => 'demande',
				'comment'    => 'demande, acceptée, rejetée, complétée',
			],
			'prix_paye' => [
				'type'       => 'NUMERIC',
				'constraint' => '10,2',
				'null'       => true,
			],
			'date_acceptation' => [
				'type' => 'TIMESTAMP',
				'null' => true,
			],
		]);

		// Ajouter les foreign keys
		$this->db->disableForeignKeyChecks();
		$this->db->query('ALTER TABLE demandes_offres ADD CONSTRAINT fk_regime FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE SET NULL');
		$this->db->query('ALTER TABLE demandes_offres ADD CONSTRAINT fk_sport FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE SET NULL');
		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropColumn('offres', ['type', 'description', 'prix_gold', 'date_creation']);
		$this->forge->dropColumn('demandes_offres', ['regime_id', 'sport_id', 'statut', 'prix_paye', 'date_acceptation']);
	}
}
