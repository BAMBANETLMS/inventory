<?php

namespace Encore\PHPInfo\Actions;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;


class RestoreDeleted extends BatchAction
{
    public $name = 'Restore';

    public function handle(Collection $collection)
    {
        // $request ...
        $collection->each->restore();
        return $this->response()->success('Recovered')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to restore?');
    }
    
}
