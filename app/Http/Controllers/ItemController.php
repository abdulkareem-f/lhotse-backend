<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
    }

    public function index(){
        $items = Item::paginate(15);

        return new JsonResponse($items);
    }

    public function store(ItemRequest $request){
        $data = $request->validated();
        if(!isset($data['reference']))
            $data['reference'] =  Str::uuid()->toString();

        $item = Item::create($data);

        return new JsonResponse([
            'data'      => ['item' => $item],
            'msg'       => 'Item created successfully'
        ]);
    }

    public function show(Item $item){
        return new JsonResponse([
            'data'      => ['item' => $item]
        ]);
    }

    public function update(ItemRequest $request, Item $item){
        $item->update($request->validated());

        return new JsonResponse([
            'data'      => ['item' => $item],
            'msg'       => 'Item updated successfully'
        ]);
    }

    public function destroy(Item $item){
        if($item->delete()){
            return new JsonResponse(['msg' => 'Item deleted successfully']);
        }

        return new JsonResponse(['msg' => 'Something went wrong, please try again'], 400);
    }
}
