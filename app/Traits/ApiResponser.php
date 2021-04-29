<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

trait ApiResponser{

    /**
     * @param $personalAccessToken
     * @param null $message
     * @param int $code
     * @return JsonResponse
     */
	protected function token($personalAccessToken, $message = null, $code = 200)
	{
		$tokenData = [
			'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString()
		];

		return $this->success($tokenData, $message, $code);
	}

    /**
     * @param $data
     * @param null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data, $message = null, $code = 200)
	{
		return response()->json([
			'success'=> true,
			'message' => $message,
			'data' => $data
		], $code);
	}

    /**
     * @param null $message
     * @param $code
     * @return JsonResponse
     */
	protected function error($message = null, $code)
	{
		return response()->json([
			'success'=> false,
			'message' => $message,
		], $code);
	}

}
