<?php

namespace App\Http\Controllers\Komando;

use App\Http\Controllers\Controller;
use App\Models\Posko;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Cari data posko komando milik user
        $posko = null;
        if ($user->posko_id) {
            $posko = Posko::with(['children', 'bencana'])->find($user->posko_id);
        }

        // Fallback jika posko_id belum terikat di user tapi user memegang posko via user_id
        if (!$posko) {
            $posko = Posko::with(['children', 'bencana'])->where('user_id', $user->id)->first();
        }

        return view('dashboard.komando.index', compact('posko'));
    }
}