<?php

namespace YazanStash\LaravelDebounce\Tests;

use Orchestra\Testbench\TestCase;
use YazanStash\LaravelDebounce\DebounceReport;

class DebounceReportTest extends TestCase
{
    /** @test */
    public function hydrates_an_array_into_a_report_instance()
    {
        $report = new DebounceReport($this->fakeReportData());

        $this->assertInstanceOf(DebounceReport::class, $report);
    }

    /** @test */
    public function test_email_method()
    {
        $report = new DebounceReport($this->fakeReportData());

        $this->assertEquals('stashyazan@gmail.com', $report->email());
    }

    /** @test */
    public function test_isRole_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['role' => 'false']]));
        $this->assertFalse($report->isRole());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['role' => 'true']]));
        $this->assertTrue($report->isRole());
    }

    /** @test */
    public function test_isFreeEmail_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['free_email' => 'false']]));
        $this->assertFalse($report->isFreeEmail());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['free_email' => 'true']]));
        $this->assertTrue($report->isFreeEmail());
    }

    /** @test */
    public function test_correctedAddress_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['did_you_mean' => '']]));
        $this->assertNull($report->correctedAddress());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['did_you_mean' => 'stash.yazan@gmail.com']]));
        $this->assertEquals('stash.yazan@gmail.com', $report->correctedAddress());
    }

    /** @test */
    public function test_code_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '3']]));
        $this->assertEquals(3, $report->code());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '5']]));
        $this->assertEquals('5', $report->code());
    }

    /** @test */
    public function test_result_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['result' => 'Safe to Send']]));
        $this->assertEquals('Safe to Send', $report->result());
    }

    /** @test */
    public function test_reason_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['reason' => 'Deliverable']]));
        $this->assertEquals('Deliverable', $report->reason());
    }

    /** @test */
    public function test_isTransactionable_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['send_transactional' => '0']]));
        $this->assertFalse($report->isTransactionable());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['send_transactional' => '1']]));
        $this->assertTrue($report->isTransactionable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_syntax()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '1']]));
        $this->assertFalse($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_spam_trap()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '2']]));
        $this->assertFalse($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_disposable()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '3']]));
        $this->assertFalse($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_accept_all()
    {
        app('config')->set('debounce.marketing.send_to_accept_all', false);
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '4']]));
        $this->assertFalse($report->isMarketable());

        app('config')->set('debounce.marketing.send_to_accept_all', true);
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '4']]));
        $this->assertTrue($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_deliverable()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '5']]));
        $this->assertTrue($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_invalid()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '6']]));
        $this->assertFalse($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_unknown()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '7']]));
        $this->assertFalse($report->isMarketable());
    }

    /** @test */
    public function test_isMarketable_method__when_status_is_role()
    {
        app('config')->set('debounce.marketing.send_to_unknown', false);
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '8']]));
        $this->assertFalse($report->isMarketable());

        app('config')->set('debounce.marketing.send_to_unknown', true);
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['code' => '8']]));
        $this->assertTrue($report->isMarketable());
    }

    protected function fakeReportData($overrides = [])
    {
        return array_merge([
            "debounce" => [
                "email" => "stashyazan@gmail.com",
                "code" => "5",
                "role" => "false",
                "free_email" => "true",
                "result" => "Safe to Send",
                "reason" => "Deliverable",
                "send_transactional" => "1",
                "did_you_mean" => "",
            ],
            "success" => "1",
            "balance" => "99"
        ], $overrides);
    }
}
