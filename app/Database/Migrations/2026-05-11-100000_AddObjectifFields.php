<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddObjectifFields extends Migration
{
	public function up()
	{
		$this->forge->addColumn('utilisateur_objectifs', [
			'statut' => [
				'type'       => 'VARCHAR',
				'constraint' => 20,
				'default'    => 'en_cours',
				'comment'    => 'en_cours, atteint, abandonné',
			],
			'date_debut' => [
				'type'    => 'TIMESTAMP',
				'default' => 'CURRENT_TIMESTAMP',
			],
			'date_fin' => [
				'type'    => 'TIMESTAMP',
				'null'    => true,
			],
		]);
	}

	public function down()
	{
		$this->forge->dropColumn('utilisateur_objectifs', ['statut', 'date_debut', 'date_fin']);
	}
}
