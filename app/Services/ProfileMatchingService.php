<?php

namespace App\Services;

class ProfileMatchingService
{
    /**
     * Default GAP to weight mapping (profile matching scale)
     * @var array<int,float>
     */
    protected array $gapToWeight = [
        0 => 5.0,
        1 => 4.5,
        2 => 4.0,
        3 => 3.5,
        4 => 3.0,
        5 => 2.5,
        6 => 2.0,
        7 => 1.5,
        8 => 1.0,
        9 => 0.5,
    ];

    /**
     * Calculate profile matching scores.
     *
     * @param array $alternatives Each alternative: ['id'=>..., 'values'=> [criteria_id => value,...]]
     * @param array $criteria Each criteria: ['id'=>..., 'weight'=>float, 'type'=>'CF'|'SF', 'ideal'=>float]
     * @param array|null $gapMap Optional custom gap->weight map
     * @return array Array of alternatives with 'score' and 'details'
     */
    public function calculate(array $alternatives, array $criteria, ?array $gapMap = null): array
    {
        if ($gapMap !== null) {
            $this->gapToWeight = $gapMap;
        }

        // index criteria by id for quick access
        $critById = [];
        foreach ($criteria as $c) {
            $critById[$c['id']] = $c;
        }

        $results = [];

        foreach ($alternatives as $alt) {
            $cfSum = 0.0;
            $sfSum = 0.0;
            $cfWeightTotal = 0.0;
            $sfWeightTotal = 0.0;
            $details = [];

            foreach ($alt['values'] as $critId => $value) {
                if (!isset($critById[$critId])) continue;
                $c = $critById[$critId];
                $ideal = $c['ideal'] ?? 5.0; // default ideal
                $weight = floatval($c['weight'] ?? 1.0);
                $type = $c['type'] ?? 'CF';

                $gap = $value - $ideal;
                $gapAbs = abs((int) round($gap));
                // map gap to weight, fallback to nearest defined
                $pmWeight = $this->gapToWeight[$gapAbs] ?? $this->nearestGapWeight($gapAbs);

                $score = $pmWeight * $weight;

                $details[] = [
                    'criteria_id' => $critId,
                    'value' => $value,
                    'ideal' => $ideal,
                    'gap' => $gap,
                    'pm_weight' => $pmWeight,
                    'weighted_score' => $score,
                    'type' => $type,
                ];

                if ($type === 'CF') {
                    $cfSum += $score;
                    $cfWeightTotal += $weight * 5.0; // max pm weight 5 -> normalize later
                } else {
                    $sfSum += $score;
                    $sfWeightTotal += $weight * 5.0;
                }
            }

            // normalize CF and SF to 0-100 scale then combine evenly (or based on config)
            $cfAvg = $cfWeightTotal > 0 ? ($cfSum / $cfWeightTotal) * 100.0 : 0.0;
            $sfAvg = $sfWeightTotal > 0 ? ($sfSum / $sfWeightTotal) * 100.0 : 0.0;

            // default: total score = 0.6*CF + 0.4*SF
            $total = 0.6 * $cfAvg + 0.4 * $sfAvg;

            $results[] = [
                'id' => $alt['id'] ?? null,
                'name' => $alt['name'] ?? null,
                'score' => round($total, 4),
                'cf' => round($cfAvg,4),
                'sf' => round($sfAvg,4),
                'details' => $details,
            ];
        }

        // sort descending by score
        usort($results, fn($a,$b)=> $b['score'] <=> $a['score']);

        return $results;
    }

    protected function nearestGapWeight(int $gap): float
    {
        // find nearest defined gap
        $keys = array_keys($this->gapToWeight);
        $nearest = $keys[0];
        $bestDiff = abs($gap - $nearest);
        foreach ($keys as $k) {
            $d = abs($gap - $k);
            if ($d < $bestDiff) {
                $nearest = $k; $bestDiff = $d;
            }
        }
        return $this->gapToWeight[$nearest];
    }
}
