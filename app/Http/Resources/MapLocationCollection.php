<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\MapLocation;
use App\Http\Controllers\ApiResponse;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\CompetitorProgram;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
class MapLocationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
    public function with($request)
    {
        return [
            "programs" => Program::get(),
            "competitors"=> CompetitorProgram::get(),
            "branches" => BranchResource::collection(Branch::get()),
            "icons" => Attachment::where('module_id',17)->get(),
            'can'=>[
                'create'=>Auth::user()->can('create',MapLocation::class),
                'import'=>Auth::user()->can('import',MapLocation::class),
                'export'=>Auth::user()->can('export',MapLocation::class),
            ]
        ];
    }
}
