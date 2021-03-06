<?php

namespace App\Http\Resources\HumanResource\Kpi\KpiIndicator;

use App\Http\Resources\HumanResource\Kpi\KpiScore\KpiScoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class KpiIndicatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kpi_group_id' => $this->kpi_group_id,
            'name' => $this->name,
            'weight' => $this->weight,
            'target' => $this->target,
            'score' => $this->score,
            'notes' => $this->notes,
            'comment' => $this->comment,
            'score_percentage' => $this->score_percentage,
            'score_description' => $this->score_description,
            'automated_code' => $this->automated_code,
            'attachment' => $this->attachment,
            'scores' => KpiScoreResource::collection($this->scores->sortBy('score')),
        ];
    }
}
