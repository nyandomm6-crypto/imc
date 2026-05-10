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

class AdminRegimeController extends BaseController
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

    // public function index()
    // {
    //     return "OK REGIMES";
    // }

    public function index()
    {
        $data['regimes'] = $this->regimeModel->getRegimesAvecStats();
        return view('back/regime/liste', $data);
    }

    public function liste()
    {
        $regimes = $this->regimeModel->getRegimesAvecStats();

        return view('back/regime/liste', [
            'regimes' => $regimes
        ]);
    }

    public function create()
    {
        $alimentModel = new \App\Models\AlimentModel();

        return view('back/regime/create', [
            'aliments' => $alimentModel->findAll()
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $db->transStart();

        // 1. Regime
        $regimeId = $this->regimeModel->insert([
            'libelle' => $this->request->getPost('libelle'),
            'variation_poids' => $this->request->getPost('variation_poids')
        ]);

        // 2. Recettes (composition)
        $recettes = $this->request->getPost('recettes');

        foreach ($recettes as $r) {
            if ($r['pourcentage'] > 0) {
                $this->db->table('recettes')->insert([
                    'regime_id' => $regimeId,
                    'aliment_id' => $r['aliment_id'],
                    'pourcentage' => $r['pourcentage']
                ]);
            }
        }

        // 3. Prix
        $prix = $this->request->getPost('prix');

        foreach ($prix as $p) {
            $this->db->table('regime_prix')->insert([
                'regime_id' => $regimeId,
                'duree_jours' => $p['duree_jours'],
                'prix' => $p['prix']
            ]);
        }

        $db->transComplete();

        return redirect()->to('/admin/regimes');
    }  

    public function edit($id)
    {
        $alimentModel = new \App\Models\AlimentModel();

        $data['regime'] = $this->regimeModel->find($id);
        $data['aliments'] = $alimentModel->findAll();

        // Recettes groupées
        $recettes = $this->regimeModel->getRecettesByRegime($id);

        $data['recettes'] = [];
        foreach ($recettes as $r) {
            $data['recettes'][$r['aliment_id']] = $r['pourcentage'];
        }

        // Prix
        $data['prixList'] = $this->regimeModel->getPrixByRegime($id);

        return view('back/regime/edit', $data);
    }


    public function update($id)
    {
        $db = \Config\Database::connect();

        $db->transStart();

        // update regime
        $this->regimeModel->update($id, [
            'libelle' => $this->request->getPost('libelle'),
            'variation_poids' => $this->request->getPost('variation_poids')
        ]);

        // reset recettes
        $db->table('recettes')->where('regime_id', $id)->delete();

        $recettes = $this->request->getPost('recettes');
        foreach ($recettes as $r) {
            $db->table('recettes')->insert([
                'regime_id' => $id,
                'aliment_id' => $r['aliment_id'],
                'pourcentage' => $r['pourcentage']
            ]);
        }

        // reset prix
        $db->table('regime_prix')->where('regime_id', $id)->delete();

        $prix = $this->request->getPost('prix');
        foreach ($prix as $p) {
            $db->table('regime_prix')->insert([
                'regime_id' => $id,
                'duree_jours' => $p['duree_jours'],
                'prix' => $p['prix']
            ]);
        }

        $db->transComplete();

        return redirect()->to('/admin/regimes');
    }
}
