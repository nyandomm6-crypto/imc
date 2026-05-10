<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\RegimeModel;

class AdminRegimeController extends BaseController
{
    protected $regimeModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
    }

    public function index()
    {
        $regimes = $this->regimeModel->orderBy('libelle', 'ASC')->findAll();

        return view('back/regimes/list', [
            'regimes' => $regimes
        ]);
    }

    public function create()
    {
        return view('back/regimes/form', [
            'regime' => null
        ]);
    }

    public function store()
    {
        $rules = [
            'libelle' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['libelle']);

        if ($this->regimeModel->insert($data)) {
            return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
    }

    public function edit($id)
    {
        $regime = $this->regimeModel->find($id);

        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        return view('back/regimes/form', [
            'regime' => $regime
        ]);
    }

    public function update($id)
    {
        $regime = $this->regimeModel->find($id);

        if (!$regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Régime non trouvé');
        }

        $rules = [
            'libelle' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['libelle']);

        if ($this->regimeModel->update($id, $data)) {
            return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
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
