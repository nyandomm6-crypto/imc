<?php

namespace App\Controllers\back;

use App\Controllers\BaseController;
use App\Models\SportModel;

class AdminSportController extends BaseController
{
    protected $sportModel;

    public function __construct()
    {
        $this->sportModel = new SportModel();
    }

    public function index()
    {
        $sports = $this->sportModel->orderBy('nom', 'ASC')->findAll();

        return view('back/sports/list', [
            'sports' => $sports
        ]);
    }

    public function create()
    {
        return view('back/sports/form', [
            'sport' => null
        ]);
    }

    public function store()
    {
        $rules = [
            'nom' => 'required|max_length[100]',
            'calories_par_heure' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'calories_par_heure']);

        if ($this->sportModel->insert($data)) {
            return redirect()->to('/admin/sports')->with('success', 'Sport créé avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
    }

    public function edit($id)
    {
        $sport = $this->sportModel->find($id);

        if (!$sport) {
            return redirect()->to('/admin/sports')->with('error', 'Sport non trouvé');
        }

        return view('back/sports/form', [
            'sport' => $sport
        ]);
    }

    public function update($id)
    {
        $sport = $this->sportModel->find($id);

        if (!$sport) {
            return redirect()->to('/admin/sports')->with('error', 'Sport non trouvé');
        }

        $rules = [
            'nom' => 'required|max_length[100]',
            'calories_par_heure' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['nom', 'calories_par_heure']);

        if ($this->sportModel->update($id, $data)) {
            return redirect()->to('/admin/sports')->with('success', 'Sport mis à jour avec succès');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour');
    }

    public function delete($id)
    {
        try {
            $this->sportModel->delete($id);
            return redirect()->to('/admin/sports')->with('success', 'Sport supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->to('/admin/sports')->with('error', 'Erreur lors de la suppression');
        }
    }
}
