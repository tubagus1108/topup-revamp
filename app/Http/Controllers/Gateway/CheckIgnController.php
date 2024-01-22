<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiGames as CheckIngGame;
use Carbon\Carbon;

class CheckIgnController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->filled(['game', 'user_id'])) {
            $input_post = [
                'game' => trim($request->input('game')),
                'user_id' => trim($request->input('user_id')),
                'other_id' => trim($request->input('other_id'))
            ];

            $ischeck = false;
            $isnew = false;

            $checkAccount = DB::connection('db_read')
                ->table('check_ign')
                ->select('*')
                ->where('code', $input_post['game'])
                ->where('user_id', $input_post['user_id'])
                ->where('other_id', $input_post['other_id'])
                ->first();

            if ($checkAccount) {
                $dataAccount = (array) $checkAccount;

                if ($dataAccount['expired_at'] > now()->timestamp) {
                    $result = ['response' => true, 'data' => ['status' => 200, 'nickname' => $dataAccount['nickname']]];
                } else {
                    $ischeck = true;
                }
            } else {
                $ischeck = true;
                $isnew = true;
            }

            if ($ischeck) {
                $apiGames = new CheckIngGame;

                if (!method_exists($apiGames, $input_post['game'])) {
                    $result = ['response' => false, 'data' => ['msg' => 'Game not found (' . $input_post['game'] . ')']];
                } else {
                    $result = json_decode($apiGames->{$input_post['game']}($input_post['user_id'], $input_post['other_id']), true);

                    if ($result['status'] == 200) {
                        if ($isnew) {
                            DB::connection('db_read')
                                ->table('check_ign')
                                ->insert([
                                    'code' => $input_post['game'],
                                    'user_id' => $input_post['user_id'],
                                    'other_id' => $input_post['other_id'],
                                    'nickname' => $result['nickname'],
                                    'expired_at' => Carbon::now()->addDays(7)->toDateTimeString(),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                        } else {
                            DB::connection('db_read')
                                ->table('check_ign')
                                ->where('id', $dataAccount['id'])
                                ->update([
                                    'nickname' => $result['nickname'],
                                    'expired_at' => Carbon::now()->addDays(7)->toDateTimeString(),
                                    'updated_at' => now(),
                                ]);
                        }

                        $result = ['response' => true, 'data' => $result];
                    } else {
                        $result = ['response' => false, 'data' => $result];
                    }
                }
            }

            return response()->json($result, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['response' => false, 'data' => ['msg' => 'Invalid Request']], 400, [], JSON_PRETTY_PRINT);
        }
    }
}
