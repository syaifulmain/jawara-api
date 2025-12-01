<?php

namespace App\Http\Controllers;

use App\Models\TransferChannel;
use App\Http\Requests\TransferChannelRequest;
use App\Http\Resources\transfer_channel\TransferChannelListResource;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferChannelController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = TransferChannel::query();

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('account_number', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $transferChannels = $query->orderBy('name')
                ->paginate($request->get('per_page', 15));

            // return $this->successResponse(
            //     $transferChannels,
            //     'Transfer channels retrieved successfully'
            // );
             return $this->successResponse(
                TransferChannelListResource::collection($transferChannels),
                'Residents retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve transfer channels', 500, $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransferChannelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransferChannel $transferChannel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransferChannel $transferChannel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransferChannelRequest $request, TransferChannel $transferChannel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransferChannel $transferChannel)
    {
        //
    }
}
