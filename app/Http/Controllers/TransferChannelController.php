<?php

namespace App\Http\Controllers;

use App\Models\TransferChannel;
use App\Http\Requests\TransferChannelRequest;
use App\Http\Resources\transfer_channel\TransferChannelListResource;
use App\Http\Resources\transfer_channel\TransferChannelDetailResource;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TransferChannelDetailResource as GlobalTransferChannelDetailResource;

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
                'Transfer channels retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve transfer channels', 500, $e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TransferChannelRequest $request)
    {
        try {
            // $data = $request->validated();

            // // Upload QR image jika ada
            // if ($request->hasFile('qr_code_image')) {
            //     $data['qr_code_image_path'] = $request->file('qr_code_image')
            //         ->store('qr_code_images', 'public');
            // }

            // // Upload thumbnail jika ada
            // if ($request->hasFile('thumbnail_image')) {
            //     $data['thumbnail_image_path'] = $request->file('thumbnail_image')
            //         ->store('thumbnail_images', 'public');
            // }

            // // Create model
            // $transferChannel = TransferChannel::create($data);


            $data = $request->safe()->except(['qr_code_image', 'thumbnail_image']);

            if ($request->hasFile('qr_code_image')) {
                $data['qr_code_image_path'] = $request->file('qr_code_image')
                    ->store('transfer_channels/qr_code_images', 'public');
            }

            if ($request->hasFile('thumbnail_image')) {
                $data['thumbnail_image_path'] = $request->file('thumbnail_image')
                    ->store('transfer_channels/thumbnail_images', 'public');
            }

            $transferChannel = TransferChannel::create($data);

            return $this->successResponse(
                new TransferChannelDetailResource($transferChannel),
                'Transfer channel created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create transfer channel', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $transferChannel = TransferChannel::findOrFail($id);

            return $this->successResponse(
                new TransferChannelDetailResource($transferChannel),
                'Transfer channel retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Transfer channel not found');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TransferChannelRequest $request, int $id)
    {
        try {
            $transferChannel = TransferChannel::findOrFail($id);
            $transferChannel->update($request->validated());

            return $this->successResponse(
                new TransferChannelDetailResource($transferChannel),
                'Transfer channel updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update transfer channel', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransferChannel $transferChannel)
    {
        //
    }
}
