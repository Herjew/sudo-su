<?php

namespace Herjew\SudoSu\Controllers;

use App\Models\BackendAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Herjew\SudoSu\SudoSu;
use Illuminate\Routing\Controller;

class SudoSuController extends Controller
{
    protected $sudoSu;

    public function __construct(SudoSu $sudoSu)
    {
        $this->sudoSu = $sudoSu;
    }

    public function loginAsUser(Request $request)
    {
        $admin = $this->sudoSu->loginAsUser($request->userId, $request->originalUserId);
        $this->authenticated($request, $admin);

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        $this->sudoSu->logout();

        return redirect()->back();
    }

    /**
     * 3 1 The user has been authenticated.
     * @param  \Illuminate\Http\Request  $request
     * @param  BackendAdmin  $admin
     * @return mixed
     */
    protected function authenticated(Request $request, $admin)
    {
        $admin->setAttribute('last_login_at', Carbon::now()->toDateTimeString());
        $admin->setAttribute('last_login_ip', $request->getClientIp());
        $admin->update();

        $admin->lastSession();
    }


}
