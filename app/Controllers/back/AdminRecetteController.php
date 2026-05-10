<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\AlimentModel;
use App\Models\RecetteModel;

class AdminRecetteController extends BaseController
{
    protected $regimeModel;
    protected $alimentModel;
    protected $recetteModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->alimentModel = new AlimentModel();
        $this->recetteModel = new RecetteModel();
    }

    public function index($regime_id)
    {
        $regime = $this->regimeModel->find($regime_id);

        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $recettes = $this->recetteModel->getByRegime($regime_id);

        return view('back/recettes/list', [
            'regime' => $regime,
            'recettes' => $recettes
        ]);
    }

    public function create($regime_id)
    {
        $regime = $this->regimeModel->find($regime_id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $aliments = $this->alimentModel->orderBy('nom', 'ASC')->findAll();

        return view('back/recettes/form', [
            'regime' => $regime,
            'aliments' => $aliments,
            'recette' => null
        ]);
    }

    public function store($regime_id)
    {
        $rules = [
            'aliment_id' => 'required|integer',
            'pourcentage' => 'required|numeric|greater_than[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $aliment_id = $this->request->getPost('aliment_id');
        $pourcentage = $this->request->getPost('pourcentage');

        if ($this->recetteModel->createRecette($regime_id, $aliment_id, $pourcentage)) {
            return redirect()->to("/admin/recettes/{$regime_id}")->with('success', 'Recette ajoutée avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout');
    }

    public function edit($regime_id, $recette_id)
    {
        $regime = $this->regimeModel->find($regime_id);
        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $recette = $this->recetteModel->getWithDetails($recette_id, $regime_id);

        if (!$recette) {
            return redirect()->to("/admin/recettes/{$regime_id}")->with('error', 'Recette non trouvée');
        }

        $aliments = $this->alimentModel->orderBy('nom', 'ASC')->findAll();

        return view('back/recettes/form', [
            'regime' => $regime,
            'aliments' => $aliments,
            'recette' => $recette
        ]);
    }

    public function update($regime_id, $recette_id)
    {
        $rules = [
            'aliment_id' => 'required|integer',
            'pourcentage' => 'required|numeric|greater_than[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'aliment_id' => $this->request->getPost('aliment_id'),
            'pourcentage' => $this->request->getPost('pourcentage'),
        ];

        if ($this->recetteModel->updateRecette($recette_id, $regime_id, $data)) {
            return redirect()->to("/admin/recettes/{$regime_id}")->with('success', 'Recette mise à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($regime_id, $recette_id)
    {
        try {
            if ($this->recetteModel->deleteRecette($recette_id, $regime_id)) {
                return redirect()->to("/admin/recettes/{$regime_id}")->with('success', 'Recette supprimée avec succès');
            }

            return redirect()->to("/admin/recettes/{$regime_id}")->with('error', 'Erreur lors de la suppression');
        } catch (\Exception $e) {
            return redirect()->to("/admin/recettes/{$regime_id}")->with('error', 'Erreur lors de la suppression');
        }
    }
}
