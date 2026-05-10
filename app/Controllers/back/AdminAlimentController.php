<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\AlimentModel;

class AdminAlimentController extends BaseController
{
    protected $alimentModel;

    public function __construct()
    {
        $this->alimentModel = new AlimentModel();
    }

    public function index()
    {
        $aliments = $this->alimentModel->orderBy('nom', 'ASC')->findAll();

        return view('back/aliments/list', [
            'aliments' => $aliments
        ]);
    }

    public function create()
    {
        return view('back/aliments/form', [
            'aliment' => null
        ]);
    }

    public function store()
    {
        $rules = [
            'nom' => 'required|max_length[100]',
            'calories_100g' => 'required|numeric',
            'proteines_100g' => 'numeric',
            'glucides_100g' => 'numeric',
            'lipides_100g' => 'numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'calories_100g', 'proteines_100g', 'glucides_100g', 'lipides_100g']);

        if ($this->alimentModel->insert($data)) {
            return redirect()->to('/admin/aliments')->with('success', 'Aliment créé avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
    }

    public function edit($id)
    {
        $aliment = $this->alimentModel->find($id);

        if (!$aliment) {
            return redirect()->to('/admin/aliments')->with('error', 'Aliment non trouvé');
        }

        return view('back/aliments/form', [
            'aliment' => $aliment
        ]);
    }

    public function update($id)
    {
        $aliment = $this->alimentModel->find($id);

        if (!$aliment) {
            return redirect()->to('/admin/aliments')->with('error', 'Aliment non trouvé');
        }

        $rules = [
            'nom' => 'required|max_length[100]',
            'calories_100g' => 'required|numeric',
            'proteines_100g' => 'numeric',
            'glucides_100g' => 'numeric',
            'lipides_100g' => 'numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'calories_100g', 'proteines_100g', 'glucides_100g', 'lipides_100g']);

        if ($this->alimentModel->update($id, $data)) {
            return redirect()->to('/admin/aliments')->with('success', 'Aliment mis à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        try {
            $this->alimentModel->delete($id);
            return redirect()->to('/admin/aliments')->with('success', 'Aliment supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->to('/admin/aliments')->with('error', 'Erreur lors de la suppression');
        }
    }
}
