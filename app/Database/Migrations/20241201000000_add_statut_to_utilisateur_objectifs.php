<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatutToUtilisateurObjectifs extends Migration
{
    public function up()
    {
        $columns = $this->db->getFieldNames('utilisateur_objectifs');

        if (!in_array('statut', $columns)) {
            $this->forge->addColumn('utilisateur_objectifs', [
                'statut' => [
                    'type' => 'ENUM',
                    'constraint' => ['en_cours', 'termine'],
                    'default' => 'en_cours',
                    'after' => 'valeur_cible',
                ],
            ]);
        }

        if (!in_array('date_creation', $columns)) {
            $this->forge->addColumn('utilisateur_objectifs', [
                'date_creation' => [
                    'type' => 'TIMESTAMP',
                    'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                    'after' => 'statut',
                ],
            ]);
        }
    }

    public function down()
    {
        $columns = $this->db->getFieldNames('utilisateur_objectifs');

        if (in_array('date_creation', $columns)) {
            $this->forge->dropColumn('utilisateur_objectifs', 'date_creation');
        }

        if (in_array('statut', $columns)) {
            $this->forge->dropColumn('utilisateur_objectifs', 'statut');
        }
    }
}