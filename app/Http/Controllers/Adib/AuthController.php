<?php

namespace App\Http\Controllers\Adib;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ClosedCash;
use App\Models\Stock\StockTemp;
use App\Models\Transaksi\PenjualanTemp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * display login view
     * @return \Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('metronics.login');
    }

    /**
     * @param LoginRequest $request
     * @return TRUE|json
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $idUser = Auth::user()->id;
        $request->session()->put('ClosedCash', $this->ClosedCash($idUser));
        $this->penjualanTemp($idUser);
        $this->StockTemp($idUser);

        return json_encode(['status' => TRUE]);
    }

    /**
     * Handle Penjualan Temp Terakhir
     * @param integer User ID
     */
    private function penjualanTemp($id)
    {
        $tempPenjualan = PenjualanTemp::where('idSales', $id)->where('jenisTemp', 'Penjualan')->latest()->first();
        if ($tempPenjualan) {
            session(['penjualan'=>$tempPenjualan->id]);
        }
    }

    private function StockTemp($id)
    {
        $tempStock = StockTemp::where('idUser', $id)->where('jenisTemp', 'StockMasuk')->latest()->first();
        if ($tempStock) {
            session(['stockMasuk'=>$tempStock->id]);
        }
    }

    /**
     * Handle Closed Cashed after login to session
     * @param number ID USER
     * @return string Active Closed id
     */
    public function ClosedCash($idUser)
    {
        $data = ClosedCash::whereNull('closed')->latest()->first();
        if ($data) {
            // jika null maka buat data
            return $data->active;
        }
        $generateClosedCash = md5(now());
        $isi = [
            'active' => $generateClosedCash,
            'idUser' => $idUser,
        ];
        $createData = ClosedCash::create($isi);
        return $generateClosedCash;

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'guest', // default guest
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $idUser = Auth::user()->id;
        $this->StockTemp($idUser);
        $this->penjualanTemp($idUser);
        $request->session()->put('ClosedCash', $this->ClosedCash($idUser));

        return json_encode(['status' => TRUE]);
    }
}
