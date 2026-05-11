<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\SuggestionModel;
use App\Models\UtilisateurModel;
use App\Models\CompteModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends BaseController
{
    private TransactionModel $transactionModel;
    private RegimeModel $regimeModel;
    private SportModel $sportModel;
    private SuggestionModel $suggestionModel;
    private UtilisateurModel $utilisateurModel;
    private CompteModel $compteModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->regimeModel = new RegimeModel();
        $this->sportModel = new SportModel();
        $this->suggestionModel = new SuggestionModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->compteModel = new CompteModel();
    }

    public function exportTransactions()
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer les données utilisateur
        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        if ($utilisateur === null) {
            $utilisateur = ['nom' => 'Utilisateur'];
        }

        // Récupérer les transactions de l'utilisateur via son compte
        $compte = $this->compteModel->getByUtilisateur($utilisateurId);
        $transactions = [];
        if ($compte !== null && isset($compte['id'])) {
            $transactions = $this->transactionModel->getByCompte((int) $compte['id']);
        }

        // Configuration DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        // Générer le HTML
        $html = view('pdf/transactions', [
            'transactions' => $transactions,
            'utilisateur' => $utilisateur
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Télécharger le PDF
        $dompdf->stream('historique-transactions.pdf', ['Attachment' => true]);
    }

    public function exportRegime($regimeId = null)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer les données utilisateur
        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        if ($utilisateur === null) {
            $utilisateur = ['nom' => 'Utilisateur'];
        }

        if ($regimeId) {
            // Exporter un régime spécifique
            $regime = $this->regimeModel->getById($regimeId);
            if (!$regime) {
                return redirect()->back()->with('error', 'Régime introuvable.');
            }
        } else {
            // Exporter la suggestion actuelle
            $regime = $this->suggestionModel->getSuggestionRegime();
        }

        // Configuration DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans'); // Police qui supporte mieux les caractères spéciaux

        $dompdf = new Dompdf($options);

        // Générer le HTML
        $html = view('pdf/regime', [
            'regime' => $regime,
            'utilisateur' => $utilisateur
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Télécharger le PDF
        $filename = $regimeId ? 'regime-' . $regimeId . '.pdf' : 'suggestion-regime.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    public function exportSport($sportId = null)
    {
        $utilisateurId = $this->getUtilisateurId();
        if ($utilisateurId === null) {
            return redirect()->to('/');
        }

        // Récupérer les données utilisateur
        $utilisateur = $this->utilisateurModel->getById($utilisateurId);
        if ($utilisateur === null) {
            $utilisateur = ['nom' => 'Utilisateur'];
        }

        if ($sportId) {
            // Exporter un sport spécifique
            $sport = $this->sportModel->getById($sportId);
            if (!$sport) {
                return redirect()->back()->with('error', 'Sport introuvable.');
            }
        } else {
            // Exporter la suggestion actuelle
            $sport = $this->suggestionModel->getSuggestionSport();
        }

        // Configuration DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans'); // Police qui supporte mieux les caractères spéciaux

        $dompdf = new Dompdf($options);

        // Générer le HTML
        $html = view('pdf/sport', [
            'sport' => $sport,
            'utilisateur' => $utilisateur
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Télécharger le PDF
        $filename = $sportId ? 'sport-' . $sportId . '.pdf' : 'suggestion-sport.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    private function getUtilisateurId(): ?int
    {
        $session = session();

        foreach (['utilisateur_id', 'user_id', 'id'] as $key) {
            $value = $session->get($key);

            if (is_numeric($value) && (int) $value > 0) {
                return (int) $value;
            }
        }

        return null;
    }
}