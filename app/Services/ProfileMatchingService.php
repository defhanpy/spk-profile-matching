<?php

namespace App\Services;

class ProfileMatchingService
{
    /**
     * Calculate profile matching scores for given alternatives and criteria.
     * This is a skeleton to be implemented and unit-tested.
     *
     * @param array $alternatives Array of alternatives with criteria values
     * @param array $criteria Array of criteria definitions (weights, type CF/SF)
     * @return array Rankings with scores
     */
    public function calculate(array $alternatives, array $criteria): array
    {
        // TODO: implement GAP calculation, CF/SF aggregation and final score
        // Return placeholder structure
        return array_map(function($alt){
            return ['id' => $alt['id'] ?? null, 'score' => 0];
        }, $alternatives);
    }
}
