<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class ConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::check()) {
            try {
                $usuario = \Auth::guard()->user()->usuario;
                $key = 'user' . $usuario->id;

                $user_data = Redis::command('get', [$key]);

                if (!$user_data) {
                    $user_data = json_encode([
                        'id' => $usuario->id,
                        'name' => $usuario->get_full_name(),
                        'friends' => $usuario->amigos_ids()
                    ]);
                    Redis::command('set', [$key, $user_data]);
                    Redis::command('expire', [$key, 60 * 10]);  // Expire in 10 min
                }

                Redis::publish('user.connection', json_encode($user_data));

    
                // Start reforma
                // // Get Current Time
                // $now = time();
                // $min = date('i', $now);
                // // Generate the Key
                // $key = 'online:' . $min;
                // // Adding user to online users
                // Redis::command('sadd', [$key, $user_data]);
                // Redis::command('expire', [$key, 60 * 10]); // Expire in 10 minutes
    
    
                // $keys = array();
                // // Get Current Time
                // $min = date('i', time());
                // $count = 0;
                // $minutes_ago = 5;
                // while ($count < $minutes_ago) {
                //     $keys[] = 'online:' . $min;
                //     $count++;
                //     $min--;
                //     if($min < 0) { $min = 59; }
                // }
    
                // $online_ids = Redis::command('sunion', $keys);
                // end reforma
    
                // Redis::publish('user.connection', json_encode($online_ids));
            } catch (\Exception $e) {
                //
            }
        }
        return $next($request);
    }
}
