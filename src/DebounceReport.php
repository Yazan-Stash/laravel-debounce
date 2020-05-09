<?php

namespace YazanStash\LaravelDebounce;

class DebounceReport
{
    protected $report;

    protected $email;
    protected $is_role;
    protected $is_free_email;

    function __construct(array $report)
    {
        $this->report = $report;

        $this->email = data_get($report, 'debounce.email');

        $this->is_role = filter_var(data_get($report, 'debounce.role'), FILTER_VALIDATE_BOOLEAN);

        $this->is_free_email = filter_var(data_get($report, 'debounce.free_email'), FILTER_VALIDATE_BOOLEAN);

        $this->is_transactionable = filter_var(data_get($report, 'debounce.send_transactional'), FILTER_VALIDATE_BOOLEAN);

        $this->corrected_address = data_get($report, 'debounce.did_you_mean');
        $this->code = data_get($report, 'debounce.code');
        $this->result = data_get($report, 'debounce.result');
        $this->reason = data_get($report, 'debounce.reason');
    }

    public function email()
    {
        return $this->email;
    }

    public function isRole()
    {
        return $this->is_role;
    }

    public function isFreeEmail()
    {
        return $this->is_free_email;
    }

    public function correctedAddress()
    {
        return ! empty($this->corrected_address)
            ? $this->corrected_address : null;
    }

    public function code()
    {
        return $this->code;
    }

    public function result()
    {
        return $this->result;
    }

    public function reason()
    {
        return $this->reason;
    }

    public function isTransactionable()
    {
        return $this->is_transactionable;
    }

    public function isMarketable()
    {
        if (config('debounce.marketing.send_to_accept_all') && $this->code == 4) {
            return true;
        }

        if (config('debounce.marketing.send_to_unknown') && $this->code == 8) {
            return true;
        }

        return $this->code == 5;
    }
}
