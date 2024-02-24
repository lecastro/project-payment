<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use App\Components\Enums\StatusEnum;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Resources\ListPaymentResource;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function storeAction(CreatePaymentRequest $request): JsonResponse
    {
        try {
            $response = $this->paymentService->store($request->toArray());

            return response()->json(
                new PaymentResource($response),
                JsonResponse::HTTP_CREATED,
                [],
                JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'error',
                    'error'   => $th->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    public function indexAction(): JsonResponse
    {
        try {
            $response = $this->paymentService->index();

            return response()->json(
                new ListPaymentResource($response),
                JsonResponse::HTTP_OK,
                [],
                JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'error',
                    'error'   => $th->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    public function showAction(string $id): JsonResponse
    {
        try {
            $response = $this->paymentService->show($id);

            return response()->json(
                new ListPaymentResource($response),
                JsonResponse::HTTP_OK,
                [],
                JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'error',
                    'error'   => $th->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    public function confirmAction(string $id): JsonResponse
    {
        try {
            $response = $this->paymentService->updateStatus($id, StatusEnum::PAID->value);

            return response()->json(
                $response,
                JsonResponse::HTTP_OK,
                [],
                JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'error',
                    'error'   => $th->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }

    public function cancelAction(string $id): JsonResponse
    {
        try {
            $response = $this->paymentService->updateStatus($id, StatusEnum::CANCELED->value);

            return response()->json(
                $response,
                JsonResponse::HTTP_OK,
                [],
                JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'error',
                    'error'   => $th->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }
}
