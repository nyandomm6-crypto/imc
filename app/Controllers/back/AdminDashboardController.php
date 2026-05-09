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
    private MesureModel      $mesureModel;
    private ImcModel         $imcModel;
    private ObjectifModel    $objectifModel;
    private RegimeModel      $regimeModel;
    private SportModel       $sportModel;
    private CompteModel      $compteModel;
    private CodePromoModel   $codePromoModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->mesureModel      = new MesureModel();
        $this->imcModel         = new ImcModel();
        $this->objectifModel    = new ObjectifModel();
        $this->regimeModel      = new RegimeModel();
        $this->sportModel       = new SportModel();
        $this->compteModel      = new CompteModel();
        $this->codePromoModel   = new CodePromoModel();
    }

    public function index()
{
    $data = [];

    // ── Scalaires (int uniquement)
    $data['total_users']        = (int) ($this->utilisateurModel->countByRole(2)          ?? 0);
    $data['total_regimes']      = (int) ($this->regimeModel->countAllRegimes()             ?? 0);
    $data['total_codes_actifs'] = (int) ($this->codePromoModel->countByStatus('active')   ?? 0);

    // ── Charts : JSON strings (pas des tableaux)
    $inscriptions = (array) ($this->utilisateurModel->inscriptionsParMois() ?? []);
    $data['chart_labels'] = json_encode(array_column($inscriptions, 'mois'))  ?: '[]';
    $data['chart_data']   = json_encode(array_column($inscriptions, 'total')) ?: '[]';

    $objectifs = (array) ($this->objectifModel->repartitionObjectifs() ?? []);
    $data['objectif_labels'] = json_encode(array_column($objectifs, 'libelle')) ?: '[]';
    $data['objectif_data']   = json_encode(array_column($objectifs, 'total'))   ?: '[]';

    // ── Top users : JSON string
    $topUsers = (array) ($this->compteModel->topUsersBySolde(5) ?? []);
    $data['top_users_json'] = json_encode(array_map(fn($u) => [
        'nom'    => (string) ($u['nom']    ?? ''),
        'solde'  => (float)  ($u['solde']  ?? 0),
        'status' => (string) ($u['status'] ?? 'inactive'),
    ], $topUsers)) ?: '[]';

    // ── Codes récents : JSON string
    $codes = (array) ($this->codePromoModel->codesRecemmentUtilises(10) ?? []);
    $data['codes_recents_json'] = json_encode(array_map(fn($c) => [
        'code'             => (string) ($c['code']             ?? ''),
        'nom'              => (string) ($c['nom']              ?? ''),
        'date_utilisation' => (string) ($c['date_utilisation'] ?? ''),
        'status'           => (string) ($c['status']           ?? ''),
    ], $codes)) ?: '[]';

    return view('back/dashboard/index', $data);
}
}