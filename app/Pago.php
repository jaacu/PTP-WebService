<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payer' => 'object',
        'payment' => 'object',
        'status' => 'object',
        'check' => 'boolean',
        'created_at' => 'datetime:d-m-Y'
    ];

    public function isChecked()
    {
        return $this->check;
    }

    public function getRouteKey()
    {
        return $this->reference;
    }

    public function isPending()
    {
        return (optional($this->status)->status ?? false) == 'PENDING';
    }

    public function getStatusColor()
    {
        switch (optional($this->status)->status) {
            default:
            case 'PENDING':
            return 'warning';
            break;
            case 'REJECTED':
            return 'danger';
            break;
            case 'APPROVED':
            return 'success';
            break;
        }
    }
}
