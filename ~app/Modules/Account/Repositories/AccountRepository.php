<?php

namespace Modules\Account\Repositories;

class AccountRepository extends \Modules\User\Repositories\UserRepository
{
  public function redirectToList()
  {
    return redirect()->route('admin.account.index');
  }
}
