<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\AlimentModel;
use App\Models\OffreModel;
use App\Models\RegimeModel;
use Config\Database;

class AdminRegimeController extends BaseController
{
    protected $regimeModel;
    protected $alimentModel;
    protected $offreModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->alimentModel = new AlimentModel();
        $this->offreModel = new OffreModel();
    }

    public function index()
    {
        $regimes = $this->regimeModel->orderBy('libelle', 'ASC')->findAll();

        return view('back/regime/liste', [
            'regimes' => $regimes
        ]);
    }

    public function create()
    {
        $aliments = $this->alimentModel->orderBy('nom', 'ASC')->findAll();
        $offres = $this->offreModel->orderBy('nom', 'ASC')->findAll();

        return view('back/regime/form', [
            'regime' => null,
            'aliments' => $aliments,
            'offres' => $offres,
        ]);
    }

    public function store()
    {
        $rules = [
            'libelle' => 'required|max_length[100]',
            'offre_id' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['libelle', 'offre_id']);
        $db = Database::connect();
        $db->transStart();

        $regimeId = $this->regimeModel->insert($data);
        if ($regimeId === false) {
            $db->transComplete();
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du régime');
        }

        $alimentIds = $this->request->getPost('aliment_id') ?? [];
        $pourcentages = $this->request->getPost('pourcentage') ?? [];

        foreach ($alimentIds as $index => $alimentId) {
            $p = $pourcentages[$index] ?? null;
            if ($alimentId && $p !== null && $p !== '') {
                $this->regimeModel->ajouterAliment((int) $regimeId, (int) $alimentId, (float) $p);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du régime');
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès');
    }

    public function edit($id)
    {
        $regime = $this->regimeModel->find($id);

        if (! $regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $aliments = $this->alimentModel->orderBy('nom', 'ASC')->findAll();
        $offres = $this->offreModel->orderBy('nom', 'ASC')->findAll();
        $regimeWithAliments = $this->regimeModel->getById($id);

        return view('back/regime/form', [
            'regime' => $regimeWithAliments,
            'aliments' => $aliments,
            'offres' => $offres,
        ]);
    }

    public function update($id)
    {
        $regime = $this->regimeModel->find($id);

        if (! $regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $rules = [
            'libelle' => 'required|max_length[100]',
            'offre_id' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['libelle', 'offre_id']);
        $db = Database::connect();
        $db->transStart();

        if (! $this->regimeModel->update($id, $data)) {
            $db->transComplete();
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du régime');
        }

        $db->table('recettes')->where('regime_id', $id)->delete();

        $alimentIds = $this->request->getPost('aliment_id') ?? [];
        $pourcentages = $this->request->getPost('pourcentage') ?? [];

        foreach ($alimentIds as $index => $alimentId) {
            $p = $pourcentages[$index] ?? null;
            if ($alimentId && $p !== null && $p !== '') {
                $this->regimeModel->ajouterAliment((int) $id, (int) $alimentId, (float) $p);
            }
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du régime');
        }

        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour avec succès');
    }

    public function delete($id)
    {
        try {
            $this->regimeModel->delete($id);
            return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->to('/admin/regimes')->with('error', 'Erreur lors de la suppression');
        }
    }
}
