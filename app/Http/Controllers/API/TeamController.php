<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\TeamRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $teamQuery = Team::query();

        // Get single data
        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponseFormatter::success($team, 'Team found');
            }

            return ResponseFormatter::error('Team not found', 404);
        }

        // Get multiple data
        $teams = $teamQuery->where('company_id', $request->company_id);

        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Teams found'
        );
    }

    public function create(TeamRequest $request)
    {
        try {
            //upload icon
            if($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }

            //Create Team
            $team = Team::create([  
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if(!$team)
            {
                throw new Exception("Failed to create team");
            }

                return ResponseFormatter::success($team, 'Team created successfully');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(TeamUpdateRequest $request, $id)
    {
        try {
           //Get Team
           $team = Team::find($id);
           
           if(!$team)
           {
            throw new Exception("Team not found");
           }

           //Upload Icon
           if($request->hasFile('icon')) {
            $path = $request->file('icon')->store('public/icons');
           }

           //update Team
           $team->update(
                [
                    'name' => $request->name,
                    'icon' => isset($path) ? $path : $team->icon,
                    'company_id' => $request->company_id,
                ]);
                return ResponseFormatter::success($team, 'Team updated successfully');   
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
             //Get Team
           $team = Team::find($id);
           
           if(!$team)
           {
            throw new Exception("Team not found");
           }

           $team->delete();
           return ResponseFormatter::success($team, 'Team Deleted');   
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
