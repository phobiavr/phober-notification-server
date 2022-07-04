<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *    title="Phober - Notification Server",
 *    version="V 1.0.0",
 *    @OA\Contact(
 *      name="Hikmat",
 *      url="https://www.linkedin.com/in/abdukhaligov/",
 *      email="hikmat.pou@gmail.com"
 *    ),
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8888",
 *      description="localhost"
 * )
 */

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
