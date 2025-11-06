<?php
namespace App\Modules\Settings\Services;

use App\Modules\Comptabilite\Models\OperationDivers;
use App\Modules\Settings\Models\Devise;
use App\Modules\Settings\Models\Divers;
use App\Modules\Settings\Resources\DiversResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DiversService
{
    /**
     * 🔹 Créer un nouvel enregistrement Divers
     */
    public function store(array $data)
    {
        try {
            $data['created_by'] = Auth::id();
            $divers             = Divers::create($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers créé avec succès.',
                'data'    => new DiversResource(
                    $divers->load(['createur', 'modificateur', 'operationsDivers'])
                ),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la création de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Mettre à jour un enregistrement Divers
     */
    public function update(int $id, array $data)
    {
        try {
            $divers             = Divers::findOrFail($id);
            $data['updated_by'] = Auth::id();
            $divers->update($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers mis à jour avec succès.',
                'data'    => new DiversResource(
                    $divers->load(['createur', 'modificateur', 'operationsDivers'])
                ),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la mise à jour de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Supprimer un enregistrement Divers
     */
    public function delete(int $id)
    {
        try {
            $divers = Divers::findOrFail($id);
            $divers->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Enregistrement Divers supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la suppression de l’enregistrement Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer tous les enregistrements Divers
     */
    public function getAll()
    {
        try {
            $divers = Divers::with(['createur', 'modificateur', 'operationsDivers'])
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'status'  => 200,
                'message' => 'Liste des enregistrements Divers récupérée avec succès.',
                'data'    => DiversResource::collection($divers),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Erreur lors de la récupération des enregistrements Divers.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 Récupérer un enregistrement Divers spécifique
     */
    public function getOne(int $id)
    {
        try {
            $divers = Divers::with(['createur', 'modificateur', 'operationsDivers'])
                ->findOrFail($id);

            return response()->json([
                'status'  => 200,
                'message' => 'Détails du Divers récupérés avec succès.',
                'data'    => new DiversResource($divers),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 404,
                'message' => 'Enregistrement Divers introuvable.',
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function calculerSoldeDivers(int $id_divers, int $cacheMinutes = 5): array
    {
        return Cache::remember("solde_divers_{$id_divers}", now()->addMinutes($cacheMinutes), function () use ($id_divers) {

            // 🔹 Récupérer toutes les devises actives
            $devises = Devise::select('id', 'symbole')->get();

            $soldes = [];
            $flux   = [];

            $operations = OperationDivers::with(['typeOperation', 'devise'])
                ->where('id_divers', $id_divers)
                ->get();

            // 🔸 Initialisation dynamique pour chaque devise
            foreach ($devises as $devise) {
                $symbole = strtolower($devise->symbole);

                $soldes[$symbole] = 0;
                $flux[$symbole]   = [
                    'entrees' => 0,
                    'sorties' => 0,
                ];
            }

            // 🔹 Parcours de toutes les opérations
            foreach ($operations as $op) {
                $devise  = strtolower($op->devise?->symbole ?? 'gnf');
                $nature  = $op->typeOperation?->nature ?? 1; // 1 = entrée, 0 = sortie
                $montant = (float) $op->montant;

                // 🔸 Si la devise n’existe pas encore (cas de devise ajoutée en cours)
                if (! isset($soldes[$devise])) {
                    $soldes[$devise] = 0;
                    $flux[$devise]   = [
                        'entrees' => 0,
                        'sorties' => 0,
                    ];
                }

                // 🔸 Traitement selon la nature
                if ($nature == 1) {
                    $flux[$devise]['entrees'] += $montant;
                    $soldes[$devise] += $montant;
                } else {
                    $flux[$devise]['sorties'] += $montant;
                    $soldes[$devise] -= $montant;
                }
            }

            // 🔹 Arrondir toutes les valeurs
            foreach ($soldes as $symbole => &$val) {
                $val = round($val, 2);
            }

            foreach ($flux as $symbole => &$item) {
                $item['entrees'] = round($item['entrees'], 2);
                $item['sorties'] = round($item['sorties'], 2);
            }

            // 🔹 Structure finale propre
            return [
                'soldes' => $soldes,
                'flux'   => $flux,
            ];
        });
    }

    public function getReleveDivers(int $id_divers): array
    {
        // 🔹 Charger le divers avec son compte, sa banque et sa devise
        $divers = Divers::with(['compte.banque', 'compte.devise'])->find($id_divers);

        if (! $divers || ! $divers->compte) {
            return [
                'status'        => 404,
                'message'       => "Divers introuvable ou sans compte associé.",
                'releve_divers' => [],
            ];
        }

        $compte          = $divers->compte;
        $banque          = $compte->banque?->libelle ?? null;
        $numero_compte   = $compte->numero_compte ?? null;
        $id_deviseCompte = $compte->devise_id;
        $symbole         = strtolower($compte->devise?->symbole ?? 'gnf');

        // 🔹 Récupération des opérations du divers (même devise)
        $operations = OperationDivers::with(['typeOperation', 'devise'])
            ->where('id_divers', $id_divers)
            ->where('id_devise', $id_deviseCompte)
            ->orderBy('date_operation')
            ->orderBy('created_at')
            ->get()
            ->map(function ($op) use ($banque, $numero_compte) {
                $nature = $op->typeOperation?->nature; // 1 = entrée, 0 = sortie

                return [
                    'date'           => $op->created_at?->format('Y-m-d H:i:s'),
                    'date_operation' => $op->date_operation,
                    'reference'      => $op->reference,
                    'type'           => 'operation_divers',
                    'libelle'        => $op->typeOperation?->libelle ?? 'Opération Divers',
                    'banque'         => $banque,
                    'numero_compte'  => $numero_compte,
                    'devise'         => strtolower($op->devise?->symbole ?? ''),
                    'debit'          => $nature == 0 ? (float) $op->montant : 0,
                    'credit'         => $nature == 1 ? (float) $op->montant : 0,
                ];
            });

        // 🔹 Initialisation des soldes par devise
        $soldes    = [];
        $resultats = [];

        $soldes[$symbole]    = 0;
        $resultats[$symbole] = [];

        // 🔹 Calcul du solde progressif
        foreach ($operations as $ligne) {
            $symb = $ligne['devise'] ?: $symbole;

            if (! isset($soldes[$symb])) {
                $soldes[$symb]    = 0;
                $resultats[$symb] = [];
            }

            $soldes[$symb] += $ligne['credit'] - $ligne['debit'];
            $ligne['solde_apres'] = round($soldes[$symb], 2);

            $resultats[$symb][] = $ligne;
        }

        // 🔹 Inverser les listes (plus récent en premier)
        foreach ($resultats as $symb => &$list) {
            $list = array_reverse($list);
        }

        // 🔹 Structure finale identique à getReleveClient()
        return [
            'status'        => 200,
            'message'       => 'Relevé du divers récupéré avec succès.',
            'releve_divers' => $resultats,
        ];
    }

    public function calculerSoldeGlobalDivers(): array
    {
        // 🔹 Initialisation globale
        $totaux = [
            'soldes' => [],
            'flux'   => [],
        ];

        // 🔹 Parcours de tous les divers
        foreach (Divers::all(['id']) as $divers) {
            $resultat = app(DiversService::class)->calculerSoldeDivers($divers->id);

            $soldes = $resultat['soldes'] ?? [];
            $flux   = $resultat['flux'] ?? [];

            // 🔹 Agrégation dynamique des soldes
            foreach ($soldes as $devise => $solde) {
                if (! isset($totaux['soldes'][$devise])) {
                    $totaux['soldes'][$devise] = 0;
                }
                $totaux['soldes'][$devise] += $solde;
            }

            // 🔹 Agrégation dynamique des flux
            foreach ($flux as $devise => $data) {
                if (! isset($totaux['flux'][$devise])) {
                    $totaux['flux'][$devise] = [
                        'entrees' => 0,
                        'sorties' => 0,
                    ];
                }

                $totaux['flux'][$devise]['entrees'] += $data['entrees'] ?? 0;
                $totaux['flux'][$devise]['sorties'] += $data['sorties'] ?? 0;
            }
        }

        // 🔹 Arrondir proprement toutes les valeurs
        foreach ($totaux['soldes'] as &$solde) {
            $solde = round($solde, 2);
        }

        foreach ($totaux['flux'] as &$fluxDevise) {
            $fluxDevise['entrees'] = round($fluxDevise['entrees'], 2);
            $fluxDevise['sorties'] = round($fluxDevise['sorties'], 2);
        }

        // ✅ Résultat final
        return [
            'status'  => 200,
            'message' => 'Solde global de tous les divers calculé avec succès.',
            'data'    => $totaux,
        ];
    }

}
