<?php

namespace App\Http\Controllers;

use App\Models\SearchProfile;
use Illuminate\Http\Request;
use App\Models\Property;

class SearchController extends Controller
{
    protected $standardDeviation = 0.25;

    public function getMatched($property_id)
    {
        $property = Property::findOrFail($property_id);

        $profiles = SearchProfile::wherePropertyType($property->id)->get();

        $data = [];

        foreach ($profiles as $key => $profile) {
            
            $strict_match_count = 0;
            $loose_match_count = 0;

            foreach ($profile->search_fields as $key => $field) {

                if($profile->isADirectMatchField($key)) {
                    if($field == $property->fields[$key]) {
                        $strict_match_count += 1;
                    }
                } 
                else {
                    if($this->checkDirectMatch($field, $property->fields[$key])) {
                        $strict_match_count += 1;
                    } 
                    else if($this->checkLooseMatch($field, $property->fields[$key])) {
                        $loose_match_count += 1;
                    }
                }

            }

            if($strict_match_count || $loose_match_count) {
                array_push($data, [
                    "searchProfileId" => $profile->id,
                    "score" => $this->calculateScore($strict_match_count, $loose_match_count),
                    "strictMatchesCount" => $strict_match_count,
                    "looseMatchesCount" => $loose_match_count,
                ]);
            }
        }

        usort($data, function ($item1, $item2) {
            return $item2['score'] <=> $item1['score'];
        });

        return $data;
    }

    protected function checkDirectMatch($field_range, $value)
    {
        [$min, $max] = [...$field_range];

        if($min && $value < $min) return false;

        if($max && $value > $max) return false;

        return true;
    }

    protected function checkLooseMatch($field_range, $value)
    {
        [$min, $max] = [...$field_range];

        $min = $min - ($min * $this->standardDeviation);

        $max = $max + ($max * $this->standardDeviation);

        return $this->checkDirectMatch([$min, $max], $value);
    }

    public function calculateScore($strict_match_count, $loose_match_count)
    {
        //let's give a weightage of strict match; here twice that of loose match.
        return (2 * $strict_match_count) + $loose_match_count;
    }
}
