<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\CompteModel;
use App\Models\ImcModel;
use App\Models\MesureModel;
use App\Models\ObjectifModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\UtilisateurModel;
use App\Models\CodePromoModel;

class AdminDashboardController extends BaseController
{
    private UtilisateurModel $utilisateurModel;
    private MesureModel $mesureModel;
    private ImcModel $imcModel;
    private ObjectifModel $objectifModel;
    private RegimeModel $regimeModel;
    private SportModel $sportModel;
    private CompteModel $compteModel;
    private CodePromoModel $codePromoModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->mesureModel = new MesureModel();
        $this->imcModel = new ImcModel();
        $this->objectifModel = new ObjectifModel();
        $this->regimeModel = new RegimeModel();
        $this->sportModel = new SportModel();
        $this->compteModel = new CompteModel();
        $this->codePromoModel = new CodePromoModel();
    }

    public function index()
    {
        $data['total_users'] = $this->utilisateurModel->countByRole(2);
        $data['total_regimes'] = $this->regimeModel->countAllRegimes();
        $data['total_codes_actifs'] = $this->codePromoModel->countByStatus("active");

        // Graph inscriptions par mois
        $inscriptions = $this->utilisateurModel->inscriptionsParMois();

        $labels = [];
        $values = [];

        foreach ($inscriptions as $row) {
            $labels[] = $row['mois'];
            $values[] = $row['total'];
        }

        $data['chart_labels'] = $labels;
        $data['chart_data'] = $values;

        // camember
        $objectifs = $this->objectifModel->repartitionObjectifs();

        $labelsObj = [];
        $dataObj = [];

        foreach ($objectifs as $row) {
            $labelsObj[] = $row['libelle'];
            $dataObj[] = $row['total'];
        }

        $data['objectif_labels'] = $labelsObj;
        $data['objectif_data'] = $dataObj;

        // top 5 utilisateurs par solde 
        $topUsers = $this->compteModel->topUsersBySolde(5);

        $data['top_users'] = $topUsers;

        $codesUtilises = $this->codePromoModel->codesRecemmentUtilises(10);

        $data['codes_recents'] = $codesUtilises;

        
        return view('back/dashboard/index', $data);
    }
}
