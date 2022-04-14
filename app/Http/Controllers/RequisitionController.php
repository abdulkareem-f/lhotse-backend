<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequisitionRequest;
use App\Jobs\SendRequisitionSubmitted;
use App\Models\Requisition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete', 'submitRequisition']);
    }

    public function index(){
        $requisitions = Requisition::paginate(15);

        return new JsonResponse($requisitions);
    }

    public function store(RequisitionRequest $request){
        $data = $request->validated();
        if(!isset($data['reference']))
            $data['reference'] =  Str::uuid()->toString();

        $requisition = Requisition::create($data);

        return new JsonResponse([
            'data'      => ['requisition' => $requisition->refresh()],
            'msg'       => 'Requisition created successfully'
        ]);
    }

    public function show(Requisition $requisition){
        return new JsonResponse([
            'data'      => ['requisition' => $requisition]
        ]);
    }

    public function update(RequisitionRequest $request, Requisition $requisition){
        $requisition->update($request->validated());

        return new JsonResponse([
            'data'      => ['requisition' => $requisition],
            'msg'       => 'Requisition updated successfully'
        ]);
    }

    public function destroy(Requisition $requisition){
        if($requisition->items->isNotEmpty()){
            return new JsonResponse(['msg' => "You can not delete requisition ({$requisition->name}), it has items"], 422);
        }
        if($requisition->delete()){
            return new JsonResponse(['msg' => 'Requisition deleted successfully']);
        }

        return new JsonResponse(['msg' => 'Something went wrong, please try again'], 400);
    }

    public function submitRequisition($id){
        $requisition = Requisition::find($id);
        if(!$requisition){
            return new JsonResponse(['msg' => 'Requisition is not found'], 404);
        }
        if($requisition->status == 'submitted'){
            return new JsonResponse(['msg' => "Requisition ({$requisition->name}) is already has been submitted"], 422);
        }

        $requisition->status = 'submitted';
        $requisition->save();

        SendRequisitionSubmitted::dispatch($requisition);

        return new JsonResponse([
            'data'  =>  ['requisition' => $requisition],
            'msg'   =>  'Requisition has been submitted successfully'
        ]);
    }
}
